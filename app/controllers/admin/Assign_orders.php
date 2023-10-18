<?php defined('BASEPATH') OR exit('No direct script access allowed'); //Write by Ismail FSD
<<<<<<< HEAD
class Assign_orders extends MY_Controller
{
    public function __construct()
    {
=======
class Assign_orders extends MY_Controller{
    public function __construct(){
        error_reporting(0);
>>>>>>> fd0cf6eaae594d04ad6f8ee45a0fa0cd095b456c
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
        if ($this->Customer) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->load->library('form_validation');
        $this->load->admin_model('general_model');
    }
    function index($user_id){

        $this->data['user_id'] = $user_id;

        $this->db->select('
            delivery_date,
            GROUP_CONCAT(status) AS status,
        ');
        $this->db->from('sma_sales_dispatch');
        $this->db->where('dispatcher_id',$user_id);
        $this->db->group_by('delivery_date');
        $this->data['rows'] = $this->db->get()->result();

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Assign Orders')));
        $meta = array('page_title' => lang('Assign Orders'), 'bc' => $bc);
        $this->data['user_id'] = $user_id;
        $this->data['routes'] = $this->db->get('sma_routes_tb')->result();
        $this->page_construct2('assign_orders/index', $meta, $this->data);
    }
    function add($user_id){
        $this->data['user_id'] = $user_id;
        $this->data['routes'] = $this->general_model->GetAllRoutes();
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Assign Orders')));
        $meta = array('page_title' => lang('Assign Orders'), 'bc' => $bc);
        $this->page_construct2('assign_orders/add', $meta, $this->data);
    }
    public function fatch_orders(){
        $dispatcher_id = $this->input->get('dispatcher_id');
        $date = $this->input->get('date');
        $route = $this->input->get('route');


        $this->db->select('
            s.id as order_id,
            s.date,
            c.name as shop_name,
            s.reference_no,
            (
                SELECT COUNT(si.id) FROM sma_sale_items as si WHERE si.sale_id = s.id
            ) as items,
            s.grand_total,
            (
                SELECT
                    COUNT(ds.id)
                FROM
                    sma_sales_dispatch as ds 
                WHERE
                    ds.sale_id = s.id AND 
                    ds.status = "pending" AND 
                    ds.dispatcher_id = '.$dispatcher_id.'

            ) as delivery_status,
        ');
        $this->db->from('sma_sales as s');
        $this->db->join('sma_companies as c','c.id = s.customer_id','left');
        $this->db->where('s.delivery_status','pending');
        $this->db->where('c.route_id',$route);
        $q = $this->db->get();
        $rows = $q->result();
        echo json_encode($rows);
    }
    public function submit_assign(){
        $sendvalue['status'] = false;
        $dispacher_id =  $this->input->post('dispacher_id');
        $date =  $this->input->post('date');
        $routeVal =  $this->input->post('routeVal');
        $orders =  $this->input->post('order');
        if(count($orders) == 0){
            $sendvalue['message'] = "Please select order";
        }
        else{
            foreach($orders as $key => $order){
                $insert['dispatcher_id']  = $dispacher_id;
                $insert['sale_id']  = $order;
                $insert['delivery_date']  = $date;
                $this->db->insert('sma_sales_dispatch',$insert);
            }
            $this->db->set('delivery_status','pending');
            $this->db->where('id',$order);
            $this->db->update('sma_sales');
            $sendvalue['status'] = true;
            $sendvalue['message'] = "Order assign successfully";
        }
        echo json_encode($sendvalue);
    }
    public function detail(){
        $date = $this->input->get('date');
        $dispacher_id = $this->input->get('dispacher_id');
        $this->data['user_id'] = $dispacher_id;
        $this->db->select('
            sd.*,
            s.id as order_id,
            s.date as sale_date,
            c.name as shop_name,
            s.reference_no,
            (
                SELECT COUNT(si.id) FROM sma_sale_items as si WHERE si.sale_id = s.id
            ) as items,
            s.grand_total,
        ');
        $this->db->from('sma_sales_dispatch as sd');
        $this->db->join('sma_sales as s','s.id = sd.sale_id','left');
        $this->db->join('sma_companies as c','c.id = s.customer_id','left');
        $this->db->where('sd.delivery_date',$date);
        $this->db->where('sd.dispatcher_id',$dispacher_id);
        $this->data['rows'] = $this->db->get()->result();
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Assign Orders')));
        $meta = array('page_title' => lang('Assign Orders'), 'bc' => $bc);
        $this->page_construct2('assign_orders/detail', $meta, $this->data);


    }
    public function delect_assign(){
        $sendvalue['status'] = false;
        $dispacher_id =  $this->input->post('dispacher_id');
        $ids =  $this->input->post('ids');
        foreach($ids as $key => $id){
            $this->db->delete('sma_sales_dispatch', array('id' => $id));
        }
        $sendvalue['status'] = true;
        $sendvalue['message'] = "Delete assigned order successfully";
        echo json_encode($sendvalue);
    }
    public function submit_assign_single(){
        $sendvalue['status'] = false;
        $sale_id =  $this->input->post('sale_id');
        $dispacher_id =  $this->input->post('dispatcher');
        $date =  $this->input->post('date');
        $insert['dispatcher_id']  = $dispacher_id;
        $insert['sale_id']  = $sale_id;
        $insert['delivery_date']  = $date;
        $this->db->insert('sma_sales_dispatch',$insert);
        $this->db->set('delivery_status','pending');
        $this->db->where('id',$sale_id);
        $this->db->update('sma_sales');
        $sendvalue['status'] = true;
        $sendvalue['message'] = "Assigned order successfully";
        echo json_encode($sendvalue);
    }
    public function delect_assign_single(){
        $id =  $this->input->get('id');
        $this->db->delete('sma_sales_dispatch', array('id' => $id));
        $sendvalue['status'] = true;
        $sendvalue['message'] = "Delete assigned order successfully";
        echo json_encode($sendvalue);
    }


}