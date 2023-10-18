<?php defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function __construct()
    {
        error_reporting(0);

        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            admin_redirect('login');
        }

        if ($this->Customer || $this->Supplier) {
            redirect('/');
        }

        $this->load->library('form_validation');
        $this->load->admin_model('db_model');
        $this->load->admin_model('stores_model');
    }

    public function index()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            admin_redirect('sync');
        }

        $user_id = $this->session->userdata('user_id');


        $this->data['user_details'] = $this->db_model->getUsersDetails($user_id);
      
        $user_details = $this->data['user_details'][0]->biller_id;


        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['sales'] = $this->db_model->getLatestSales($user_details);
        $this->data['quotes'] = $this->db_model->getLastestQuotes();
        $this->data['purchases'] = $this->db_model->getLatestPurchases($user_details);
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        $this->data['chatData'] = $this->db_model->getChartData($user_details);
        $this->data['stock'] = $this->db_model->getStockValue();
        $this->data['bs'] = $this->db_model->getBestSeller($user_details);
        $this->data['brandwiseseller'] = $this->db_model->getBrandwiseSeller($user_details);
        $this->data['etailerwiseseller'] = $this->db_model->getBestSellerByEtailer($user_details);
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($user_details, $lmsdate, $lmedate);


        $this->data['current_status'] = $this->currentStatus();
   
        // print_r($this->data['current_status']);
        // exit;

        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);

        $this->page_construct2('dashboard', $meta, $this->data);

    }
    public function currentStatus()
    {
        $user_id = $this->session->userdata('user_id');
        $user_details = $this->db_model->getUsersDetails($user_id);
        
        $sendvalue['today_sale'] = $this->getSalesTotal('today', $user_details);
        $sendvalue['yesterday_sale'] = $this->getSalesTotal('yesterday', $user_details);
        $sendvalue['current_month_sale'] = $this->getSalesTotal('current_month', $user_details);
        $sendvalue['last_month_sale'] = $this->getSalesTotal('last_month', $user_details);
    
        return $sendvalue;
    }
    
    private function getSalesTotal($time_period, $user_details)
    {
        $start_date = '';
        $end_date = '';
        
        switch ($time_period) {
            case 'today':
                $start_date = date('Y-m-d 00:00:00');
                $end_date = date('Y-m-d 23:59:59');
                break;
            case 'yesterday':
                $start_date = date('Y-m-d 00:00:00', strtotime('yesterday'));
                $end_date = date('Y-m-d 23:59:59', strtotime('yesterday'));
                break;
            case 'current_month':
                $start_date = date('Y-m-01 00:00:00');
                $end_date = date('Y-m-t 23:59:59');
                break;
            case 'last_month':
                $start_date = date('Y-m-01 00:00:00', strtotime('first day of last month'));
                $end_date = date('Y-m-t 23:59:59', strtotime('last day of last month'));
                break;
            default:
                break;
        }
        
        $supplier_ids = array_filter([
            $user_details[0]->biller_id,
            $user_details[0]->biller2,
            $user_details[0]->biller3,
            $user_details[0]->biller4,
            $user_details[0]->biller5,
        ]);
    
     
    
        $this->db->select('FORMAT(ROUND(SUM(grand_total)), 2) as grand_total');
        $this->db->from('sales');
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        if (!empty($supplier_ids)) {
            $this->db->where_in('supplier_id', $supplier_ids);
        }
    
        $query = $this->db->get();
        $result = $query->row();
    
        return isset($result->grand_total) ? $result->grand_total : 0;
    }
    

    function promotions()
    {
        $this->load->view($this->theme . 'promotions', $this->data);
    }

    function image_upload()
    {
        if (DEMO) {
            $error = array('error' => $this->lang->line('disabled_in_demo'));
            $this->sma->send_json($error);
            exit;
        }
        $this->security->csrf_verify();
        if (isset($_FILES['file'])) {
            $this->load->library('upload');
            $config['upload_path'] = 'assets/uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '500';
            $config['max_width'] = $this->Settings->iwidth;
            $config['max_height'] = $this->Settings->iheight;
            $config['encrypt_name'] = TRUE;
            $config['overwrite'] = FALSE;
            $config['max_filename'] = 25;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file')) {
                $error = $this->upload->display_errors();
                $error = array('error' => $error);
                $this->sma->send_json($error);
                exit;
            }
            $photo = $this->upload->file_name;
            $array = array(
                'filelink' => base_url() . 'assets/uploads/images/' . $photo
            );
            echo stripslashes(json_encode($array));
            exit;

        } else {
            $error = array('error' => 'No file selected to upload!');
            $this->sma->send_json($error);
            exit;
        }
    }

    function set_data($ud, $value)
    {
        $this->session->set_userdata($ud, $value);
        echo true;
    }

    function hideNotification($id = NULL)
    {
        $this->session->set_userdata('hidden' . $id, 1);
        echo true;
    }

    function language($lang = false)
    {
        if ($this->input->get('lang')) {
            $lang = $this->input->get('lang');
        }
        //$this->load->helper('cookie');
        $folder = 'app/language/';
        $languagefiles = scandir($folder);
        if (in_array($lang, $languagefiles)) {
            $cookie = array(
                'name' => 'language',
                'value' => $lang,
                'expire' => '31536000',
                'prefix' => 'sma_',
                'secure' => false
            );
            $this->input->set_cookie($cookie);
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

    // public function salesreport($req = null)
    // {
    //     $this->db->select('
    //         DATE(sma_sales.date) AS sale_date,
    //         COUNT(DISTINCT sma_sales.reference_no) AS reference_count,
    //         SUM(sma_sales.grand_total) AS total_sales
    //     ');
    //     $this->db->from('sma_sales');
    //     $this->db->group_by('sale_date');
    //     $this->db->order_by('sale_date', 'asc'); 

    //     $query = $this->db->get();
    //     $result = $query->result_array();


    // }

    //     public function salesreport($req = null)
// {
//     if ($this->Settings->version == '2.3') {
//         $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
//         admin_redirect('sync');
//     }

    //     $user_id = $this->session->userdata('user_id');

    //     $this->data['user_details'] = $this->db_model->getUsersDetails($user_id);
//     $user_details = $this->data['user_details'][0]->biller_id;

    //     $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
//     $this->data['sales'] = $this->db_model->getLatestSales($user_details);
//     $this->data['quotes'] = $this->db_model->getLastestQuotes();
//     $this->data['purchases'] = $this->db_model->getLatestPurchases($user_details);
//     $this->data['transfers'] = $this->db_model->getLatestTransfers();
//     $this->data['customers'] = $this->db_model->getLatestCustomers();
//     $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
//     $this->data['chatData'] = $this->db_model->getChartData($user_details);
//     $this->data['stock'] = $this->db_model->getStockValue();
//     $this->data['bs'] = $this->db_model->getBestSeller($user_details);

    //     $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
//     $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
//     $this->data['lmbs'] = $this->db_model->getBestSeller($user_details, $lmsdate, $lmedate);
//     $this->data['current_status'] = $this->currentStatus();

    //     $this->db->select('
//         DATE(sma_sales.date) AS sale_date,
//         COUNT(DISTINCT sma_sales.reference_no) AS reference_count,
//         SUM(sma_sales.grand_total) AS total_sales
//     ');
//     $this->db->from('sma_sales');
//     $this->db->group_by('sale_date');
//     $this->db->order_by('sale_date', 'asc'); 

    //     $query = $this->db->get();
//     $this->data['sales_data'] = $query->result();
// //   echo "<pre>";
// //     print_r( $this->data['sales_data']);
// //     die();

    //     // $this->load->view('dashboard', $this->data);


    //     // print_r($this->data);
//     // die();

    //     $bc = array(array('link' => '#', 'page' => lang('dashboard')));
//     $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
//     // $this->data['sales_data_json'] = json_encode($this->data['sales_data']);

    //     $this->page_construct2('dashboard', $meta, $this->data);
// }

    // public function salesreport()
// {
//     $senddata['status'] = false;
//     $senddata['message'] = 'Try again!';
//     $id = $this->input->post('id');
//     $reason = $this->input->post('reason');
//     if ($this->data['Owner']) {
//         if ($reason != '') {
//             $this->db->delete('suspended_items', ['suspend_id ' => $id]);
//             $this->db->delete('suspended_bills', ['id' => $id]);
//             $senddata['status'] = true;
//             $senddata['message'] = 'Open sale delete successfully!';
//         } else {
//             $senddata['message'] = 'Enter Reason!';
//         }
//     } else {
//         $senddata['message'] = 'Permission Denied!';
//     }
//     echo json_encode($senddata);
// }



    // public function salesreport()
// {


    // $this->db->select('
//            DATE(sma_sales.date) AS sale_date,
//          COUNT(DISTINCT sma_sales.reference_no) AS reference_count,
//             SUM(sma_sales.grand_total) AS total_sales
//          ');
//         $this->db->from('sma_sales');
//         $this->db->group_by('sale_date');
//         $this->db->order_by('sale_date', 'asc'); 

    //         $query = $this->db->get();
//         $result = $query->result_array();
//     echo json_encode($result);
// }


    public function salesreport()
    {
        $dateThirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

        $this->db->select('
        DATE(sma_sales.date) AS sale_date,
        COUNT(DISTINCT sma_sales.reference_no) AS reference_count,
        SUM(sma_sales.grand_total) AS total_sales
    ');
        $this->db->from('sma_sales');
        $this->db->where('sma_sales.date >=', $dateThirtyDaysAgo);
        $this->db->group_by('sale_date');
        $this->db->order_by('sale_date', 'asc');

        $query = $this->db->get();
        $result = $query->result_array();
        echo json_encode($result);
    }

    public function purchasereport()
    {
        $dateThirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

        $this->db->select('
        DATE(sma_purchases.date) AS sale_date,
        COUNT(DISTINCT sma_purchases.reference_no) AS reference_count,
        SUM(sma_purchases.grand_total) AS total_sales
    ');
        $this->db->from('sma_purchases');
        $this->db->where('sma_purchases.date >=', $dateThirtyDaysAgo);
        $this->db->group_by('sale_date');
        $this->db->order_by('sale_date', 'asc');

        $query = $this->db->get();
        $result = $query->result_array();
        echo json_encode($result);
    }


    public function yearlySalereport()
    {

        $this->db->select('MONTH(date) as month, YEAR(date) as year, SUM(total) as total_sales')
            ->from('sma_sales')
            ->where('date >=', date('Y-m-d', strtotime('-12 months')))
            ->group_by('YEAR(date), MONTH(date)')
            ->order_by('YEAR(date), MONTH(date)');

        $query = $this->db->get();
        $result = $query->result_array();
        echo json_encode($result);
    }


    public function yearlyPurchasereport()
    {

        $this->db->select('MONTH(date) as month, YEAR(date) as year, SUM(total) as total_sales')
            ->from('sma_purchases')
            ->where('date >=', date('Y-m-d', strtotime('-12 months')))
            ->group_by('YEAR(date), MONTH(date)')
            ->order_by('YEAR(date), MONTH(date)');

        $query = $this->db->get();
        $result = $query->result_array();
        echo json_encode($result);
    }


    public function productsale()
    {
        $this->db->select('p.name AS product_name, COUNT(si.product_id) AS sale_count, SUM(s.total) AS total_sum');
        $this->db->from('sma_sales AS s');
        $this->db->join('sma_sale_items AS si', 's.id = si.sale_id');
        $this->db->join('sma_products AS p', 'p.id = si.product_id');
        $this->db->group_by('p.id, p.name');
        $this->db->order_by('total_sum', 'DESC');
        $this->db->limit(25);

        $query = $this->db->get();
        $result = $query->result_array();
        echo json_encode($result);

    }


    public function productpurchase()
    {
        $this->db->select('p.name AS product_name, COUNT(si.product_id) AS sale_count, SUM(pur.total) AS total_sum');
        $this->db->from('sma_purchases AS pur');
        $this->db->join('sma_sale_items AS si', 'pur.id = si.sale_id');
        $this->db->join('sma_products AS p', 'p.id = si.product_id');
        $this->db->group_by('p.id, p.name');
        $this->db->order_by('total_sum', 'DESC');
        $this->db->limit(25);

        $query = $this->db->get();
        $result = $query->result_array();
        echo json_encode($result);

    }


    public function supplierPurchase()
    {
        $this->db->select('COUNT(pi.product_id) AS product_count, p.supplier_id, s.name, SUM(pi.price) AS total_amount');
        $this->db->from('sma_purchase_items AS pi');
        $this->db->join('sma_purchases p', 'pi.purchase_id = p.id');
        $this->db->join('sma_companies s', 'p.supplier_id = s.id');
        $this->db->group_by('p.supplier_id, s.name');
        $this->db->order_by('total_amount', 'DESC');
        $this->db->limit(5);

        $query = $this->db->get();
        $result = $query->result_array();
        echo json_encode($result);

    }



    // SELECT DATE(`date`) AS sale_date, 
//        COUNT(DISTINCT `reference_no`) AS reference_count,
//        SUM(`grand_total`) AS total_sales
// FROM `sma_sales`
// GROUP BY sale_date
// ORDER BY sale_date


    //         SELECT DATE(`date`) AS sale_date, 
//        COUNT(DISTINCT `reference_no`) AS reference_count,
//        SUM(`grand_total`) AS total_sales

    // GROUP BY sale_date
// ORDER BY sale_date
    // }



    function toggle_rtl()
    {
        $cookie = array(
            'name' => 'rtl_support',
            'value' => $this->Settings->user_rtl == 1 ? 0 : 1,
            'expire' => '31536000',
            'prefix' => 'sma_',
            'secure' => false
        );
        $this->input->set_cookie($cookie);
        redirect($_SERVER["HTTP_REFERER"]);
    }

    function download($file)
    {
        if (file_exists('./files/' . $file)) {
            $this->load->helper('download');
            force_download('./files/' . $file, NULL);
            exit();
        }
        $this->session->set_flashdata('error', lang('file_x_exist'));
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function slug()
    {
        echo $this->sma->slug($this->input->get('title', TRUE), $this->input->get('type', TRUE));
        exit();
    }

}