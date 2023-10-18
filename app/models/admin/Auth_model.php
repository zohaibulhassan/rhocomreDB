<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public $tables = array();
    public $activation_code;
    public $forgotten_password_code;
    public $new_password;
    public $identity;
    public $_ion_where = array();
    public $_ion_select = array();
    public $_ion_like = array();
    public $_ion_limit = NULL;
    public $_ion_offset = NULL;
    public $_ion_order_by = NULL;
    public $_ion_order = NULL;
    protected $_ion_hooks;
    protected $response = NULL;
    protected $messages;
    protected $errors;
    protected $error_start_delimiter;
    protected $error_end_delimiter;
    public $_cache_user_in_group = array();
    protected $_cache_groups = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->config('ion_auth', TRUE);

        //initialize db tables data
        $this->tables = $this->config->item('tables', 'ion_auth');

        //initialize data
        $this->identity_column = $this->config->item('identity', 'ion_auth');
        $this->store_salt = $this->config->item('store_salt', 'ion_auth');
        $this->salt_length = $this->config->item('salt_length', 'ion_auth');
        $this->join = $this->config->item('join', 'ion_auth');


        //initialize hash method options (Bcrypt)
        $this->hash_method = $this->config->item('hash_method', 'ion_auth');
        $this->default_rounds = $this->config->item('default_rounds', 'ion_auth');
        $this->random_rounds = $this->config->item('random_rounds', 'ion_auth');
        $this->min_rounds = $this->config->item('min_rounds', 'ion_auth');
        $this->max_rounds = $this->config->item('max_rounds', 'ion_auth');


        //initialize messages and error
        $this->messages = array();
        $this->errors = array();
        $delimiters_source = $this->config->item('delimiters_source', 'ion_auth');

        //load the error delimeters either from the config file or use what's been supplied to form validation
        if ($delimiters_source === 'form_validation') {
            //load in delimiters from form_validation
            //to keep this simple we'll load the value using reflection since these properties are protected
            $this->load->library('form_validation');
            $form_validation_class = new ReflectionClass("CI_Form_validation");

            $error_prefix = $form_validation_class->getProperty("_error_prefix");
            $error_prefix->setAccessible(TRUE); 
            $this->error_start_delimiter = $error_prefix->getValue($this->form_validation);
            $this->message_start_delimiter = $this->error_start_delimiter;

            $error_suffix = $form_validation_class->getProperty("_error_suffix");
            $error_suffix->setAccessible(TRUE);
            $this->error_end_delimiter = $error_suffix->getValue($this->form_validation);
            $this->message_end_delimiter = $this->error_end_delimiter;
        } else {
            //use delimiters from config
            $this->message_start_delimiter = $this->config->item('message_start_delimiter', 'ion_auth');
            $this->message_end_delimiter = $this->config->item('message_end_delimiter', 'ion_auth');
            $this->error_start_delimiter = $this->config->item('error_start_delimiter', 'ion_auth');
            $this->error_end_delimiter = $this->config->item('error_end_delimiter', 'ion_auth');
        }


        //initialize our hooks object
        $this->_ion_hooks = new stdClass;

        //load the bcrypt class if needed
        if ($this->hash_method == 'bcrypt') {
            if ($this->random_rounds) {
                $rand = rand($this->min_rounds, $this->max_rounds);
                $rounds = array('rounds' => $rand);
            } else {
                $rounds = array('rounds' => $this->default_rounds);
            }

            $this->load->library('bcrypt', $rounds);
        }

        $this->trigger_events('model_constructor');
    }

    public function hash_password($password, $salt = false, $use_sha1_override = FALSE)
    {
        if (empty($password)) {
            return FALSE;
        }

        //bcrypt
        if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
            return $this->bcrypt->hash($password);
        }


        if ($this->store_salt && $salt) {
            return sha1($password . $salt);
        } else {
            $salt = $this->salt();
            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    public function hash_password_db($id, $password, $use_sha1_override = FALSE)
    {
        if (empty($id) || empty($password)) {
            return FALSE;
        }

        $this->trigger_events('extra_where');

        $query = $this->db->select('password, salt')
            ->where('id', $id)
            ->limit(1)
            ->get($this->tables['users']);

        $hash_password_db = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }

        // bcrypt
        if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
            if ($this->bcrypt->verify($password, $hash_password_db->password)) {
                return TRUE;
            }
            
            return FALSE;
        }

        // sha1
        if ($this->store_salt) {
            $db_password = sha1($password . $hash_password_db->salt);
        } else {
            $salt = substr($hash_password_db->password, 0, $this->salt_length);

            $db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }

        if ($db_password == $hash_password_db->password) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function hash_code($password)
    {
        return $this->hash_password($password, FALSE, TRUE);
    }

    public function salt()
    {
        return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
    }

    public function activate($id, $code = false)
    {
        $this->trigger_events('pre_activate');

        if ($code !== FALSE) {
            $query = $this->db->select($this->identity_column)
                ->where('activation_code', $code)
                ->limit(1)
                ->get($this->tables['users']);

            $result = $query->row();

            if ($query->num_rows() !== 1) {
                $this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
                $this->set_error('activate_unsuccessful');
                return FALSE;
            }

            $identity = $result->{$this->identity_column};
            $data = ['activation_code' => NULL, 'active' => '1'];

            $this->trigger_events('extra_where');
            if ($this->db->update($this->tables['users'], $data, array($this->identity_column => $identity))) {
                $this->trigger_events(array('post_activate', 'post_activate_successful'));
                $this->set_message('activate_successful');
                return TRUE;
            } else {
                $this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
                $this->set_error('activate_unsuccessful');
                return FALSE;
            }

        } else {
            $user = $this->user($id)->row();
            $data = ['activation_code' => NULL, 'active' => '1'];
            $this->trigger_events('extra_where');
            return $this->db->update($this->tables['users'], $data, array('id' => $id));
        }

        return FALSE;
    }

    public function deactivate($id = NULL)
    {
        $this->trigger_events('deactivate');

        if (!isset($id)) {
            $this->set_error('deactivate_unsuccessful');
            return FALSE;
        }

        $activation_code = sha1(md5(microtime()));
        $this->activation_code = $activation_code;

        $data = array(
            'activation_code' => $activation_code,
            'active' => 0
        );

        $this->trigger_events('extra_where');
        $this->db->update($this->tables['users'], $data, array('id' => $id));

        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->set_message('deactivate_successful');
        } else {
            $this->set_error('deactivate_unsuccessful');
        }
        return $return;
    }

    public function clear_forgotten_password_code($code)
    {

        if (empty($code)) {
            return FALSE;
        }

        $this->db->where('forgotten_password_code', $code);

        if ($this->db->count_all_results($this->tables['users']) > 0) {
            $data = array(
                'forgotten_password_code' => NULL,
                'forgotten_password_time' => NULL
            );

            $this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

            return TRUE;
        }

        return FALSE;
    }

    public function reset_password($identity, $new)
    {
        $this->trigger_events('pre_change_password');

        if (!$this->identity_check($identity)) {
            $this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
            return FALSE;
        }

        $this->trigger_events('extra_where');

        $query = $this->db->select('id, password, salt')
            ->where($this->identity_column, $identity)
            ->limit(1)
            ->get($this->tables['users']);

        if ($query->num_rows() !== 1) {
            $this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $result = $query->row();

        $new = $this->hash_password($new, $result->salt);

        // store the new password and reset the remember code so all remembered instances have to re-login
        // also clear the forgotten password code
        $data = array(
            'password' => $new,
            'remember_code' => NULL,
            'forgotten_password_code' => NULL,
            'forgotten_password_time' => NULL,
        );

        $this->trigger_events('extra_where');
        $this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));

        $return = $this->db->affected_rows() == 1;
        if ($return) {
            $this->trigger_events(array('post_change_password', 'post_change_password_successful'));
            $this->set_message('password_change_successful');
        } else {
            $this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
            $this->set_error('password_change_unsuccessful');
        }

        return $return;
    }

    public function change_password($identity, $old, $new)
    {
        $this->trigger_events('pre_change_password');

        $this->trigger_events('extra_where');

        $query = $this->db->select('id, password, salt')
            ->where($this->identity_column, $identity)
            ->limit(1)
            ->get($this->tables['users']);

        if ($query->num_rows() !== 1) {
            $this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $user = $query->row();

        $old_password_matches = $this->hash_password_db($user->id, $old);

        if ($old_password_matches === TRUE) {
            //store the new password and reset the remember code so all remembered instances have to re-login
            $hashed_new_password = $this->hash_password($new, $user->salt);
            $data = array(
                'password' => $hashed_new_password,
                'remember_code' => NULL,
            );

            $this->trigger_events('extra_where');

            $successfully_changed_password_in_db = $this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));
            if ($successfully_changed_password_in_db) {
                $this->trigger_events(array('post_change_password', 'post_change_password_successful'));
                $this->set_message('password_change_successful');
            } else {
                $this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
                $this->set_error('password_change_unsuccessful');
            }

            return $successfully_changed_password_in_db;
        } else {
            $this->set_error('old_password_wrong');
        }

        $this->set_error('password_change_unsuccessful');
        return FALSE;
    }

    public function username_check($username = '')
    {
        $this->trigger_events('username_check');

        if (empty($username)) {
            return FALSE;
        }

        $this->trigger_events('extra_where');

        return $this->db->where('username', $username)
            ->count_all_results($this->tables['users']) > 0;
    }

    public function email_check($email = '')
    {
        $this->trigger_events('email_check');

        if (empty($email)) {
            return FALSE;
        }

        $this->trigger_events('extra_where');

        return $this->db->where('email', $email)
            ->count_all_results($this->tables['users']) > 0;
    }

    public function identity_check($identity = '')
    {
        $this->trigger_events('identity_check');

        if (empty($identity)) {
            return FALSE;
        }

        return $this->db->where($this->identity_column, $identity)
            ->count_all_results($this->tables['users']) > 0;
    }

    public function forgotten_password($identity)
    {
        if (empty($identity)) {
            $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
            return FALSE;
        }

        //All some more randomness
        $activation_code_part = "";
        if (function_exists("openssl_random_pseudo_bytes")) {
            $activation_code_part = openssl_random_pseudo_bytes(128);
        }

        for ($i = 0; $i < 1024; $i++) {
            $activation_code_part = sha1($activation_code_part . mt_rand() . microtime());
        }

        $key = $this->hash_code($activation_code_part . $identity);

        $this->forgotten_password_code = $key;

        $this->trigger_events('extra_where');

        $update = array(
            'forgotten_password_code' => $key,
            'forgotten_password_time' => time()
        );

        $this->db->update($this->tables['users'], $update, array($this->identity_column => $identity));

        $return = $this->db->affected_rows() == 1;

        if ($return)
            $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
        else
            $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));

        return $return;
    }

    public function forgotten_password_complete($code, $salt = FALSE)
    {
        $this->trigger_events('pre_forgotten_password_complete');

        if (empty($code)) {
            $this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
            return FALSE;
        }

        $profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

        if ($profile) {

            if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
                //Make sure it isn't expired
                $expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
                if (time() - $profile->forgotten_password_time > $expiration) {
                    //it has expired
                    $this->set_error('forgot_password_expired');
                    $this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
                    return FALSE;
                }
            }

            $password = $this->salt();

            $data = array(
                'password' => $this->hash_password($password, $salt),
                'forgotten_password_code' => NULL,
                'active' => 1,
            );

            $this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

            $this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_successful'));
            return $password;
        }

        $this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
        return FALSE;
    }

    public function register($username, $password, $email, $additional_data = array(), $active = FALSE)
    {
        $this->trigger_events('pre_register');

        $manual_activation = $this->config->item('manual_activation', 'ion_auth');

        if ($this->identity_column == 'email' && $this->email_check($email)) {
            $this->set_error('account_creation_duplicate_email');
            return FALSE;
        } elseif ($this->identity_column == 'username' && $this->username_check($username)) {
            $this->set_error('account_creation_duplicate_username');
            return FALSE;
        }

        // If username is taken, use username1 or username2, etc.
        if ($this->identity_column != 'username') {
            $original_username = $username;
            for ($i = 0; $this->username_check($username); $i++) {
                if ($i > 0) {
                    $username = $original_username . $i;
                }
            }
        }

        // IP Address
        $ip_address = $this->_prepare_ip($this->input->ip_address());
        $salt = $this->store_salt ? $this->salt() : FALSE;
        $password = $this->hash_password($password, $salt);

        // Users table.
        $data = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'ip_address' => $ip_address,
            'created_on' => time(),
            'last_login' => time(),
            'active' => ($active ? 1 : (($manual_activation === false) ? 1 : 0))
        );

        if ($this->store_salt) {
            $data['salt'] = $salt;
        }

        if (!isset($additional_data['group_id'])) {
            $additional_data['group_id'] = 3;
        }
        //filter out any data passed that doesnt have a matching column in the users table
        //and merge the set user data and the additional data
        $user_data = array_merge($this->_filter_data($this->tables['users'], $additional_data), $data);

        $this->trigger_events('extra_set');

        $this->db->insert($this->tables['users'], $user_data);

        $id = $this->db->insert_id();

        /*if(!empty($groups)) {
            //add to groups
            foreach ($groups as $group) {
                $this->add_to_group($group, $id);
            }
        }

        //add to default group if not already set
        $default_group = $this->where('name', $this->config->item('default_group', 'ion_auth'))->group()->row();
        if((isset($default_group->id) && !isset($groups)) || (empty($groups) && !in_array($default_group->id, $groups))) {
            $this->add_to_group($default_group->id, $id);
        }*/

        $this->trigger_events('post_register');

        return (isset($id)) ? $id : FALSE;
    }

    public function login($identity, $password, $remember = FALSE)
    {
        $this->trigger_events('pre_login');

        if (empty($identity) || empty($password)) {
            $this->set_error('login_unsuccessful');
            return FALSE;
        }

        $this->trigger_events('extra_where');
        $this->load->helper('email');
        $this->identity_column = valid_email($identity) ? 'email' : 'username';
        $query = $this->db->select($this->identity_column . ', username, email, id, password, active, last_login, last_ip_address, avatar, gender, group_id, warehouse_id, biller_id, company_id, view_right, edit_right, allow_discount, show_cost, show_price')
            ->where($this->identity_column, $this->db->escape_str($identity))
            ->limit(1)
            ->get($this->tables['users']);

        if ($this->is_time_locked_out($identity)) {
            //Hash something anyway, just to take up time
            $this->hash_password($password);

            $this->trigger_events('post_login_unsuccessful');
            $this->set_error('login_timeout');

            return FALSE;
        }

        if ($query->num_rows() === 1) {
            $user = $query->row();

            $password = $this->hash_password_db($user->id, $password);

            if ($password === TRUE) {

                if ($user->active != 1) {
                    $this->trigger_events('post_login_unsuccessful');
                    $this->set_error('login_unsuccessful_not_active');
                    return FALSE;
                }

                $this->set_session($user);

                $this->update_last_login($user->id);
                $this->update_last_login_ip($user->id);
                $ldata = array('user_id' => $user->id, 'ip_address' => $this->input->ip_address(), 'login' => $identity);
                $this->db->insert('user_logins', $ldata);
                $this->clear_login_attempts($identity);

                if ($remember && $this->config->item('remember_users', 'ion_auth')) {
                    $this->remember_user($user->id);
                }

                $this->trigger_events(array('post_login', 'post_login_successful'));
                $this->set_message('login_successful');

                return TRUE;
            }
        }

        //Hash something anyway, just to take up time
        $this->hash_password($password);

        $this->increase_login_attempts($identity);

        $this->trigger_events('post_login_unsuccessful');
        $this->set_error('login_unsuccessful');

        return FALSE;
    }

    public function is_max_login_attempts_exceeded($identity)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $max_attempts = $this->config->item('maximum_login_attempts', 'ion_auth');
            if ($max_attempts > 0) {
                $attempts = $this->get_attempts_num($identity);
                return $attempts >= $max_attempts;
            }
        }
        return FALSE;
    }

    function get_attempts_num($identity)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $ip_address = $this->_prepare_ip($this->input->ip_address());
            $this->db->select('1', FALSE);
            if ($this->config->item('track_login_ip_address', 'ion_auth'))
                $this->db->where('ip_address', $ip_address);
            else if (strlen($identity) > 0)
                $this->db->or_where('login', $identity);
            $qres = $this->db->get($this->tables['login_attempts']);
            return $qres->num_rows();
        }
        return 0;
    }

    public function is_time_locked_out($identity)
    {

        return $this->is_max_login_attempts_exceeded($identity) && $this->get_last_attempt_time($identity) > time() - $this->config->item('lockout_time', 'ion_auth');
    }

    public function get_last_attempt_time($identity)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $ip_address = $this->_prepare_ip($this->input->ip_address());

            $this->db->select_max('time');
            if ($this->config->item('track_login_ip_address', 'ion_auth'))
                $this->db->where('ip_address', $ip_address);
            else if (strlen($identity) > 0)
                $this->db->or_where('login', $identity);
            $qres = $this->db->get($this->tables['login_attempts'], 1);

            if ($qres->num_rows() > 0) {
                return $qres->row()->time;
            }
        }

        return 0;
    }

    public function increase_login_attempts($identity)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $ip_address = $this->_prepare_ip($this->input->ip_address());
            return $this->db->insert($this->tables['login_attempts'], array('ip_address' => $ip_address, 'login' => $identity, 'time' => time()));
        }
        return FALSE;
    }

    public function clear_login_attempts($identity, $expire_period = 86400)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $ip_address = $this->_prepare_ip($this->input->ip_address());

            $this->db->where(array('ip_address' => $ip_address, 'login' => $identity));
            // Purge obsolete login attempts
            $this->db->or_where('time <', time() - $expire_period, FALSE);

            return $this->db->delete($this->tables['login_attempts']);
        }
        return FALSE;
    }

    public function limit($limit)
    {
        $this->trigger_events('limit');
        $this->_ion_limit = $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->trigger_events('offset');
        $this->_ion_offset = $offset;

        return $this;
    }

    public function where($where, $value = NULL)
    {
        $this->trigger_events('where');

        if (!is_array($where)) {
            $where = array($where => $value);
        }

        array_push($this->_ion_where, $where);

        return $this;
    }

    public function like($like, $value = NULL, $position = 'both')
    {
        $this->trigger_events('like');

        if (!is_array($like)) {
            $like = array($like => array(
                'value' => $value,
                'position' => $position,
            ));
        }

        array_push($this->_ion_like, $like);

        return $this;
    }

    public function select($select)
    {
        $this->trigger_events('select');

        $this->_ion_select[] = $select;

        return $this;
    }

    public function order_by($by, $order = 'desc')
    {
        $this->trigger_events('order_by');

        $this->_ion_order_by = $by;
        $this->_ion_order = $order;

        return $this;
    }

    public function row()
    {
        $this->trigger_events('row');

        $row = $this->response->row();
        $this->response->free_result();

        return $row;
    }

    public function row_array()
    {
        $this->trigger_events(array('row', 'row_array'));

        $row = $this->response->row_array();
        $this->response->free_result();

        return $row;
    }

    public function result()
    {
        $this->trigger_events('result');

        $result = $this->response->result();
        $this->response->free_result();

        return $result;
    }

    public function result_array()
    {
        $this->trigger_events(array('result', 'result_array'));

        $result = $this->response->result_array();
        $this->response->free_result();

        return $result;
    }

    public function num_rows()
    {
        $this->trigger_events(array('num_rows'));

        $result = $this->response->num_rows();
        $this->response->free_result();

        return $result;
    }

    public function users($groups = NULL)
    {
        $this->trigger_events('users');

        if (isset($this->_ion_select) && !empty($this->_ion_select)) {
            foreach ($this->_ion_select as $select) {
                $this->db->select($select);
            }

            $this->_ion_select = array();
        } else {
            //default selects
            $this->db->select(array(
                $this->tables['users'] . '.*',
                $this->tables['users'] . '.id as id',
                $this->tables['users'] . '.id as user_id'
            ));
        }

        //filter by group id(s) if passed
        /*if(isset($groups)) {
            //build an array if only one group was passed
            if(is_numeric($groups)) {
                $groups = Array($groups);
            }

            //join and then run a where_in against the group ids
            if(isset($groups) && !empty($groups)) {
                $this->db->distinct();
                $this->db->join(
                        $this->tables['users_groups'], $this->tables['users_groups'] . '.' . $this->join['users'] . '=' . $this->tables['users'] . '.id', 'inner'
                );

                $this->db->where_in($this->tables['users_groups'] . '.' . $this->join['groups'], $groups);
            }
        }*/

        $this->trigger_events('extra_where');

        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }

            $this->_ion_where = array();
        }

        if (isset($this->_ion_like) && !empty($this->_ion_like)) {
            foreach ($this->_ion_like as $like) {
                $this->db->or_like($like);
            }

            $this->_ion_like = array();
        }

        if (isset($this->_ion_limit) && isset($this->_ion_offset)) {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);

            $this->_ion_limit = NULL;
            $this->_ion_offset = NULL;
        } else if (isset($this->_ion_limit)) {
            $this->db->limit($this->_ion_limit);

            $this->_ion_limit = NULL;
        }

        //set the order
        if (isset($this->_ion_order_by) && isset($this->_ion_order)) {
            $this->db->order_by($this->_ion_order_by, $this->_ion_order);

            $this->_ion_order = NULL;
            $this->_ion_order_by = NULL;
        }

        $this->response = $this->db->get($this->tables['users']);

        return $this;
    }

    public function user($id = NULL)
    {
        $this->trigger_events('user');

        //if no id was passed use the current users id
        $id || $id = $this->session->userdata('user_id');

        $this->limit(1);
        $this->where($this->tables['users'] . '.id', $id);

        $this->users();

        return $this;
    }

    /**
     * get_users_groups
     *
     * @return array
     * @author Ben Edmunds
     * */
    public function get_users_group($id = FALSE)
    {
        $this->trigger_events('get_users_group');

        //if no id was passed use the current users id
        $id || $id = $this->session->userdata('user_id');

        return $this->db->select($this->tables['groups'] . '.id as id, ' . $this->tables['groups'] . '.name, ' . $this->tables['groups'] . '.description')
            ->where($this->tables['groups'] . '.id', $id)
            //->join($this->tables['groups'], $this->tables['users_groups'] . '.' . $this->join['groups'] . '=' . $this->tables['groups'] . '.id')
            ->get($this->tables['groups']);
    }


    /**
     * groups
     *
     * @return object
     * @author Ben Edmunds
     * */
    public function groups()
    {
        $this->trigger_events('groups');

        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }

        if (isset($this->_ion_limit) && isset($this->_ion_offset)) {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);

            $this->_ion_limit = NULL;
            $this->_ion_offset = NULL;
        } else if (isset($this->_ion_limit)) {
            $this->db->limit($this->_ion_limit);

            $this->_ion_limit = NULL;
        }

        //set the order
        if (isset($this->_ion_order_by) && isset($this->_ion_order)) {
            $this->db->order_by($this->_ion_order_by, $this->_ion_order);
        }

        $this->response = $this->db->get($this->tables['groups']);

        return $this;
    }

    public function group($id = NULL)
    {
        $this->trigger_events('group');

        if (isset($id)) {
            $this->db->where($this->tables['groups'] . '.id', $id);
        }

        $this->limit(1);

        return $this->groups();
    }

    public function update($id, array $data, $upgs = array())
    {
        $this->trigger_events('pre_update_user');

        $user = $this->user($id)->row();

        $this->db->trans_begin();

        if (array_key_exists($this->identity_column, $data) && $this->identity_check($data[$this->identity_column]) && $user->{$this->identity_column} !== $data[$this->identity_column]) {
            $this->db->trans_rollback();
            $this->set_error('account_creation_duplicate_' . $this->identity_column);

            $this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
            $this->set_error('update_unsuccessful');

            return FALSE;
        }

        // Filter the data passed
        $data = $this->_filter_data($this->tables['users'], $data);

        if (array_key_exists('username', $data) || array_key_exists('password', $data) || array_key_exists('email', $data)) {
            if (array_key_exists('password', $data)) {
                if (!empty($data['password'])) {
                    $data['password'] = $this->hash_password($data['password'], $user->salt);
                } else {
                    // unset password so it doesn't effect database entry if no password passed
                    unset($data['password']);
                }
            }
        }

        $this->trigger_events('extra_where');
        $this->db->update($this->tables['users'], $data, array('id' => $user->id));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            $this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
            $this->set_error('update_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->trigger_events(array('post_update_user', 'post_update_user_successful'));
        $this->set_message('update_successful');
        return TRUE;
    }

    public function delete_user($id)
    {
        $this->trigger_events('pre_delete_user');

        $this->db->trans_begin();

        // remove user from groups
        //$this->remove_from_group(NULL, $id);

        // delete user from users table should be placed after remove from group
        $this->db->delete($this->tables['users'], array('id' => $id));

        // if user does not exist in database then it returns FALSE else removes the user from groups
        if ($this->db->affected_rows() == 0) {
            return FALSE;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->trigger_events(array('post_delete_user', 'post_delete_user_unsuccessful'));
            $this->set_error('delete_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->trigger_events(array('post_delete_user', 'post_delete_user_successful'));
        $this->set_message('delete_successful');
        return TRUE;
    }

    public function update_last_login($id)
    {
        $this->trigger_events('update_last_login');

        $this->load->helper('date');

        $this->trigger_events('extra_where');

        $this->db->update($this->tables['users'], array('last_login' => time()), array('id' => $id));

        return $this->db->affected_rows() == 1;
    }

    public function update_last_login_ip($id)
    {
        $this->trigger_events('update_last_login_ip');

        $this->trigger_events('extra_where');

        $this->db->update($this->tables['users'], array('last_ip_address' => $this->input->ip_address()), array('id' => $id));

        return $this->db->affected_rows() == 1;
    }

    public function set_lang($lang = 'en')
    {
        $this->trigger_events('set_lang');

        // if the user_expire is set to zero we'll set the expiration two years from now.
        if ($this->config->item('user_expire', 'ion_auth') === 0) {
            $expire = (60 * 60 * 24 * 365 * 2);
        } // otherwise use what is set
        else {
            $expire = $this->config->item('user_expire', 'ion_auth');
        }

        set_cookie(array(
            'name' => 'lang_code',
            'value' => $lang,
            'expire' => $expire
        ));

        return TRUE;
    }

    public function set_session($user)
    {

        $this->trigger_events('pre_set_session');

        $session_data = array(
            'identity' => $user->{$this->identity_column},
            'username' => $user->username,
            'email' => $user->email,
            'user_id' => $user->id, //everyone likes to overwrite id so we'll use user_id
            'old_last_login' => $user->last_login,
            'last_ip' => $user->last_ip_address,
            'avatar' => $user->avatar,
            'gender' => $user->gender,
            'group_id' => $user->group_id,
            'warehouse_id' => $user->warehouse_id,
            'view_right' => $user->view_right,
            'edit_right' => $user->edit_right,
            'allow_discount' => $user->allow_discount,
            'biller_id' => $user->biller_id,
            'company_id' => $user->company_id,
            'show_cost' => $user->show_cost,
            'show_price' => $user->show_price,
        );

        $this->session->set_userdata($session_data);

        $this->trigger_events('post_set_session');

        return TRUE;
    }

    public function remember_user($id)
    {
        $this->trigger_events('pre_remember_user');

        if (!$id) {
            return FALSE;
        }

        $user = $this->user($id)->row();

        $salt = sha1($user->password);

        $this->db->update($this->tables['users'], array('remember_code' => $salt), array('id' => $id));

        if ($this->db->affected_rows() > -1) {
            // if the user_expire is set to zero we'll set the expiration two years from now.
            if ($this->config->item('user_expire', 'ion_auth') === 0) {
                $expire = (60 * 60 * 24 * 365 * 2);
            } // otherwise use what is set
            else {
                $expire = $this->config->item('user_expire', 'ion_auth');
            }

            set_cookie(array(
                'name' => 'identity',
                'value' => $user->{$this->identity_column},
                'expire' => $expire
            ));

            set_cookie(array(
                'name' => 'remember_code',
                'value' => $salt,
                'expire' => $expire
            ));

            $this->trigger_events(array('post_remember_user', 'remember_user_successful'));
            return TRUE;
        }

        $this->trigger_events(array('post_remember_user', 'remember_user_unsuccessful'));
        return FALSE;
    }

    public function login_remembered_user()
    {
        $this->trigger_events('pre_login_remembered_user');

        //check for valid data
        if (!get_cookie('identity') || !get_cookie('remember_code') || !$this->identity_check(get_cookie('identity'))) {
            $this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
            return FALSE;
        }

        //get the user
        $this->trigger_events('extra_where');
        $query = $this->db->select($this->identity_column . ', id, username, email, last_login, last_ip_address, avatar, gender, group_id, warehouse_id, biller_id, company_id, view_right, allow_discount, edit_right, show_cost, show_price')
            ->where($this->identity_column, get_cookie('identity'))
            ->where('remember_code', get_cookie('remember_code'))
            ->limit(1)
            ->get($this->tables['users']);

        //if the user was found, sign them in
        if ($query->num_rows() == 1) {
            $user = $query->row();

            $this->update_last_login($user->id);
            $this->update_last_login_ip($user->id);

            $this->set_session($user);

            //extend the users cookies if the option is enabled
            if ($this->config->item('user_extend_on_login', 'ion_auth')) {
                $this->remember_user($user->id);
            }

            $this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
            return TRUE;
        }

        $this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
        return FALSE;
    }

    public function create_group($group_name = FALSE, $group_description = '', $additional_data = array())
    {
        // bail if the group name was not passed
        if (!$group_name) {
            $this->set_error('group_name_required');
            return FALSE;
        }

        // bail if the group name already exists
        $existing_group = $this->db->get_where($this->tables['groups'], array('name' => $group_name))->num_rows();
        if ($existing_group !== 0) {
            $this->set_error('group_already_exists');
            return FALSE;
        }

        $data = array('name' => $group_name, 'description' => $group_description);

        //filter out any data passed that doesnt have a matching column in the groups table
        //and merge the set group data and the additional data
        if (!empty($additional_data))
            $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);

        $this->trigger_events('extra_group_set');

        // insert the new group
        $this->db->insert($this->tables['groups'], $data);
        $group_id = $this->db->insert_id();

        // report success
        $this->set_message('group_creation_successful');
        // return the brand new group id
        return $group_id;
    }

    public function update_group($group_id = FALSE, $group_name = FALSE, $additional_data = array())
    {
        if (empty($group_id))
            return FALSE;

        $data = array();

        if (!empty($group_name)) {
            // we are changing the name, so do some checks
            // bail if the group name already exists
            $existing_group = $this->db->get_where($this->tables['groups'], array('name' => $group_name))->row();
            if (isset($existing_group->id) && $existing_group->id != $group_id) {
                $this->set_error('group_already_exists');
                return FALSE;
            }

            $data['name'] = $group_name;
        }


        // IMPORTANT!! Third parameter was string type $description; this following code is to maintain backward compatibility
        // New projects should work with 3rd param as array
        if (is_string($additional_data))
            $additional_data = array('description' => $additional_data);


        //filter out any data passed that doesnt have a matching column in the groups table
        //and merge the set group data and the additional data
        if (!empty($additional_data))
            $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);


        $this->db->update($this->tables['groups'], $data, array('id' => $group_id));

        $this->set_message('group_update_successful');

        return TRUE;
    }

    public function delete_group($group_id = FALSE)
    {
        // bail if mandatory param not set
        if (!$group_id || empty($group_id)) {
            return FALSE;
        }

        $this->trigger_events('pre_delete_group');

        $this->db->trans_begin();

        // remove all users from this group
        //$this->db->delete($this->tables['users_groups'], array($this->join['groups'] => $group_id));
        // remove the group itself
        $this->db->delete($this->tables['groups'], array('id' => $group_id));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->trigger_events(array('post_delete_group', 'post_delete_group_unsuccessful'));
            $this->set_error('group_delete_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->trigger_events(array('post_delete_group', 'post_delete_group_successful'));
        $this->set_message('group_delete_successful');
        return TRUE;
    }

    public function set_hook($event, $name, $class, $method, $arguments)
    {
        $this->_ion_hooks->{$event}[$name] = new stdClass;
        $this->_ion_hooks->{$event}[$name]->class = $class;
        $this->_ion_hooks->{$event}[$name]->method = $method;
        $this->_ion_hooks->{$event}[$name]->arguments = $arguments;
    }

    public function remove_hook($event, $name)
    {
        if (isset($this->_ion_hooks->{$event}[$name])) {
            unset($this->_ion_hooks->{$event}[$name]);
        }
    }

    public function remove_hooks($event)
    {
        if (isset($this->_ion_hooks->$event)) {
            unset($this->_ion_hooks->$event);
        }
    }

    protected function _call_hook($event, $name)
    {
        if (isset($this->_ion_hooks->{$event}[$name]) && method_exists($this->_ion_hooks->{$event}[$name]->class, $this->_ion_hooks->{$event}[$name]->method)) {
            $hook = $this->_ion_hooks->{$event}[$name];

            return call_user_func_array(array($hook->class, $hook->method), $hook->arguments);
        }

        return FALSE;
    }

    public function trigger_events($events)
    {
        if (is_array($events) && !empty($events)) {
            foreach ($events as $event) {
                $this->trigger_events($event);
            }
        } else {
            if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events)) {
                foreach ($this->_ion_hooks->$events as $name => $hook) {
                    $this->_call_hook($events, $name);
                }
            }
        }
    }

    public function set_message_delimiters($start_delimiter, $end_delimiter)
    {
        $this->message_start_delimiter = $start_delimiter;
        $this->message_end_delimiter = $end_delimiter;

        return TRUE;
    }

    public function set_error_delimiters($start_delimiter, $end_delimiter)
    {
        $this->error_start_delimiter = $start_delimiter;
        $this->error_end_delimiter = $end_delimiter;

        return TRUE;
    }

    public function set_message($message)
    {
        $this->messages[] = $message;

        return $message;
    }

    public function messages()
    {
        $_output = '';
        foreach ($this->messages as $message) {
            $messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
            $_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
        }

        return $_output;
    }

    public function messages_array($langify = TRUE)
    {
        if ($langify) {
            $_output = array();
            foreach ($this->messages as $message) {
                $messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
                $_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
            }
            return $_output;
        } else {
            return $this->messages;
        }
    }

    public function set_error($error)
    {
        $this->errors[] = $error;

        return $error;
    }

    public function errors()
    {
        $_output = '';
        foreach ($this->errors as $error) {
            $errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
            $_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
        }

        return $_output;
    }

    public function errors_array($langify = TRUE)
    {
        if ($langify) {
            $_output = array();
            foreach ($this->errors as $error) {
                $errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
                $_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
            }
            return $_output;
        } else {
            return $this->errors;
        }
    }

    protected function _filter_data($table, $data)
    {
        $filtered_data = array();
        $columns = $this->db->list_fields($table);

        if (is_array($data)) {
            foreach ($columns as $column) {
                if (array_key_exists($column, $data))
                    $filtered_data[$column] = $data[$column];
            }
        }

        return $filtered_data;
    }

    protected function _prepare_ip($ip_address)
    {
        if ($this->db->platform() === 'postgre' || $this->db->platform() === 'sqlsrv' || $this->db->platform() === 'mssql' || $this->db->platform() === 'mysqli' || $this->db->platform() === 'mysql') {
            return $ip_address;
        } else {
            return inet_pton($ip_address);
        }
    }

    public function updateAvatar($id, $avatar)
    {
        if ($this->db->update($this->tables['users'], array('avatar' => $avatar), array('id' => $id))) {
            $this->set_message('avatar_updated');
            return TRUE;
        }
        return FALSE;
    }

    public function getBillingAddress($id)
    {

        $q = $this->db->get_where("addresses", array('user_id' => $id, 'type' => 'billing'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getDeliveryAddress($id)
    {

        $q = $this->db->get_where("addresses", array('user_id' => $id, 'type' => 'delivery'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function updateUser($id, $admin)
    {
        if ($this->db->update("users", array('admin_id' => $admin), array('id' => $id))) {
            $this->db->update("orders", array('admin_id' => $admin), array('customer_id' => $id));
            $this->db->update("drafts", array('admin_id' => $admin), array('customer_id' => $id));
            return true;
        } else {
            return false;
        }
    }


}
