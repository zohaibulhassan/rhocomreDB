<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Stores extends MY_Controller
{
    public function __construct(){
        error_reporting(0);
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
        if ($this->Customer) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        $this->load->model('admin/wordpresswoocommerce_model', 'wp');
        $this->load->admin_model('daraz_model');
        $this->load->admin_model('darazAPI_model');
        $this->load->library('form_validation');
        $this->load->admin_model('stores_model');
    }

    public function index(){
        $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => lang('Stores')]];
        $meta = ['page_title' => lang('Stores'), 'bc' => $bc];
        $this->page_construct2('store/index', $meta, $this->data);
    }

    public function get_stores(){


        // Count Total Rows
        $this->db->from('stores_tb');
        $totalq = $this->db->get();
        $this->runquery_store('yes');
        $query = $this->db->get();
        $recordsFiltered = $query->num_rows();
        $this->runquery_store();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        $rows = $query->result();

        $data = [];
        foreach ($rows as $row) {

            $store_id = $row->id;
            // style="z-index: 1;"
            $button = '';
            $button .= '
            
            <div class="uk-button-dropdown" data-uk-dropdown="{mode:';$button .= "'click'";$button .= '}" aria-haspopup="true" aria-expanded="false">
                <button class="md-btn md-btn-primary md-btn-mini">Action <i class="material-icons"></i></button>
                <div   class="uk-dropdown uk-dropdown-bottom" aria-hidden="true" style="min-width: 200px; top: 35px; left: 0px;" tabindex="">
                    <ul class="uk-nav uk-nav-dropdown">
                    '; 
                    if ($row->types == 'Shopify') {
                        $button .= '<li><a href="' . base_url('admin/stores/orders?id=' . $row->id) . '">Orders List</a></li>';
                    }
                    $button .= '<li><a href="' . base_url('admin/stores/products?id=' . $row->id) . '">Link Products</a></li>';
                    $button .= '<li><a href="" class="bulk_workbtn" data-store-id="' . $store_id. '">Add Product in Bulk</a></li>';
                    $button .= '<li><a href="' . base_url('admin/stores/edit?id=' . $row->id) . '">Edit</a></li>';
                    if ($row->products == 0) {
                        $button .= '<li><a href="#" class="deletebtn" >Delete</a></li>';
                    }
                    if($this->data['Owner'] || $this->data['GP']['store_product_integration_report']){
                        $button .= '<li><a href="' . base_url('admin/stores/system_products_report?sid=' . $row->id) . '">Check Rhocom360 Products</a></li>';
                        if($row->types == "Daraz"){
                            $button .= '<li><a href="' . base_url('admin/stores/daraaz_products_report?sid=' . $row->id) . '">Check Daraz Occupy Stock Products</a></li>';
                        }
                        $button .= '<li><a href="' . base_url('admin/stores/store_products_report?sid=' . $row->id) . '">Check Store Products</a></li>';
                    }
                    if($this->data['Owner']){
                        // $button .= '<li><a href="' . base_url('admin/stores/createstorewebhook?id=' . $row->id) . '">Create Webhook in Store</a></li>';
                    }
                    $button .= '
                    </ul>
                </div>
            </div>            
            ';

            $auto_batch_selete = 'No';
            if ($row->auto_batch_selete == 1) {
                $auto_batch_selete = 'Yes';
            }
            $auto_invoice = 'No';
            if ($row->auto_invoice == 1) {
                $auto_invoice = 'Yes';
            }
            $data[] = [
                $row->id,
                $row->name,
                $row->types,
                $row->stock_margin,
                ucwords($row->update_qty_in),
                $row->update_price,
                ucwords($row->auto_so),
                $auto_batch_selete,
                $auto_invoice,
                $row->created_at,
                $row->status,
                $button,
            ];
        }
        $output = [
            'draw' => $_POST['draw'],
            'recordsTotal' => $totalq->num_rows(),
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];
        // Output to JSON format
        
        echo json_encode($output);
    }

    
    public function addbulk_submit()
    {

        $store_id = $this->input->get('storeid');
        $sendvalue['codestatus'] = "default msg";
    
        $extratext = "";

        if($store_id != 0)
        {
            $store = $this->stores_model->detail($store_id);
   
            if($store)
            {
                // $sendvalue['codestatus'] = $name;

                if ($_FILES["csv_file"]["error"] === UPLOAD_ERR_OK) 
                {
                    if ($_FILES["csv_file"]['type'] == 'text/csv') 
                    {
                        // $sendvalue['codestatus'] = 'CSV Selected.';
                        $unique_identifier = time();
                        $file_info = $_FILES['csv_file'];
                        $original_file_name = $file_info['name'];
                        $new_file_name = $unique_identifier . '_' . $original_file_name;

                        $this->load->library('upload');
                        $config['upload_path'] = 'files/';
                        $config['allowed_types'] = '*';
                        $config['file_name'] = $new_file_name;
                        $config['overwrite'] = true;

                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('csv_file')) 
                        {
                            $csv = $this->upload->file_name;
                            $arrResult = array();
                            $handle = fopen('files/'. $csv, "r");
                            $extratext = "files/".$csv;
                            
                            if ($handle) {
                                while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                                    $arrResult[] = $row;                                   
                                }
                     
                                fclose($handle);
                            }

                            // echo "<pre>";
                            // print_r(array_map("myfunction",array(1,7,3,4,5)));
                            // echo "</pre>";
                                                                            
                            $titles = array_shift($arrResult);
                            $keys = array('product_id', 'name','store_product_id','update_type','qty_type','price_type','warehouse_id','discount','supplier_id');
                            $finals = array();
                            $productIds = array();

                            foreach ($arrResult as $key => $value)
                            {
                                $productIds[] = $value[0];
                                $finals[] = array_combine($keys, $value);
                            }
   
                            // $sendvalue['codestatus'] = $productIds;  
                           
                            $this->db->from('sma_store_products_tb');
                            $this->db->where('store_id', $store_id);
                            $this->db->where_in('product_id', $productIds);
                            $query = $this->db->get();
            
                            if ($query->num_rows() > 0) 
                            {
                                $sendvalue['codestatus'] = "product already exist";
                            }
                            else
                            {       
                                foreach($finals as $final)
                                {                                           
                                    $productdetail = $this->calPrice($final['product_id'], $final['price_type'], $final['discount'], $final['qty_type'], $final['warehouse_id']);
                            
                                    if($productdetail['codestatus'] == "ok")
                                    {
                                        $wodata['store_url'] = $store->store_url;
                                        $wodata['wordpress_wocommerce_consumer_key'] = $store->wordpress_wocommerce_consumer_key;
                                        $wodata['wordpress_wocommerce_consumer_secret'] = $store->wordpress_wocommerce_consumer_secret;
                                                                        
                                        if($final['name'] == "")
                                        {
                                            $wodata['product']['name'] = $productdetail['product_name'];
                                            $insertdata['product_name'] = $productdetail['product_name'];
                                        }
                                        else{
                                            $wodata['product']['name'] = $final['name'];
                                            $insertdata['product_name'] = $final['name'];
                                        }

                                        $wodata['product']['type'] = 'simple';
                                        $wodata['product']['short_description'] = $productdetail['product_details'] ?? '';
                                        
                                        if($productdetail['mrp'] == $productdetail['total']){
                                            $wodata['product']['regular_price'] = (string)$productdetail['mrp'];
                                            $wodata['product']['sale_price'] = '';
                                        }
                                        else{
                                            $wodata['product']['regular_price'] = (string)$productdetail['mrp'];
                                            $wodata['product']['sale_price'] = (string)$productdetail['total'];
                                        }

                                        if($final['update_type'] == "qty" || $final['update_type'] == "priceqty" || $final['update_type'] == "detailnqty" || $final['update_type'] == "full")
                                        {                              

                                            $wodata['product']['manage_stock'] = true;
                                            if($productdetail['stock'] > 0){
                                                $wodata['product']['stock_status'] = 'instock';
                                            }
                                            else{
                                                $wodata['product']['stock_status'] = 'outofstock';
                                            }
                                            $wodata['product']['stock_quantity'] = (int)$productdetail['stock'];
                                        }
                                        else{
                                            $wodata['product']['manage_stock'] = false;
                                            $wodata['product']['stock_status'] = 'instock';
                                        }
                                    
                                        if($final['store_product_id'] == "")
                                        {
                                            $returndata = $this->stores_model->newProduct($wodata);
                                            $insertdata['store_product_id'] = $returndata['productdata']->id;
                                        }
                                        else
                                        {       
                                            $insertdata['store_product_id'] = $final['store_product_id'];
                                            $returndata = $this->stores_model->updateProductDetail($wodata, $final['store_product_id']);
                                        }
                                         
                                        // $sendvalue['codestatus'] = $final['update_type']; 
                                        // break;


                                        $insertdata['store_id'] = $store_id;
                                        $insertdata['update_in'] = $final['update_type'];
                                        $insertdata['product_id'] = $final['product_id'];
                                        $insertdata['update_qty_in'] = $final['qty_type'];  //$final['qty_type']; //$final['update_type']
                                        $insertdata['price_type'] = 'mrp'; //$final['price_type'];
                                        $insertdata['discount'] = $final['discount'];
                                        $insertdata['warehouse_id'] = $final['warehouse_id'];
                                        
                                        if($final['supplier_id'] == "" || $final['supplier_id'] == "0")
                                        {
                                            $insertdata['supplier_id'] = $productdetail['supplier1'];
                                        }
                                        else
                                        {
                                            $supplierdetail = $this->getSupplierName($final['supplier_id']);
                                            if($supplierdetail == "Invalid Supplier"){
                                                $insertdata['supplier_id'] = $productdetail['supplier1'];
                                            }
                                            else{
                                                $insertdata['supplier_id'] = $final['supplier_id'];
                                            }
                                        }
                                        $insertdata['created_by'] = $this->session->userdata('user_id');
                                        $insertdata['status'] = 'active';
                                
                                        $this->db->insert('sma_store_products_tb',$insertdata);

                                        // $sendvalue['codestatus'] = 'updated first';
                                        // break;
                                

                                        // $insertdata['store_product_id'] = 22;
                                        // $insertdata['store_product_id'] = 123;
                                        // $insertdata['product_name'] = $final['name'];;
                                        // $insertdata['store_id'] = $store_id;
                                        // $insertdata['update_in'] = $final['update_type'];
                                        // $insertdata['product_id'] = $final['product_id'];
                                        // $insertdata['update_qty_in'] = $final['Quantity Type'] ?? 'Not Found';
                                        // $insertdata['price_type'] = $final['price_type'];
                                        // $insertdata['discount'] = $final['discount'];
                                        // $insertdata['warehouse_id'] = $final['warehouse_id'];
                                        // $insertdata['supplier_id'] = 22;
                                        // $insertdata['supplier_id'] = 23;
                                        // $insertdata['supplier_id'] = $final['supplier_id'];
                                        // $insertdata['created_by'] = $this->session->userdata('user_id');
                                        // $insertdata['status'] = 'active';
                                        // $this->db->insert('sma_store_products_tb',$insertdata);
                                        
                                        // $sendvalue['codestatus'] = 'updated';
                                        // break;
                                        
                                    }
                                }
                                $sendvalue['codestatus'] = 'Product Integrate Successfully';
                            }                         
                        }
                        else
                        {
                            // $error = array('error' => $this->upload->display_errors());
                            // $sendvalue['codestatus'] = 'Upoading Faild';
                            // $sendvalue['error'] = $error;
                            $sendvalue['codestatus'] = $this->upload->display_errors(); //error_msg
                            // $sendvalue['codestatus'] = 'file uload error'; //error_msg
                        }
                    }
                    else
                    {
                        $sendvalue['codestatus'] = 'Upload only CSV Excel File. This file type'.$_FILES["csv_file"]['type'];
                    }
                }
                else
                {
                    $sendvalue['codestatus'] = 'Please Select File.';
                }
            }
            else{
                $sendvalue['codestatus'] = "Invalid Store";
            }
        }
        else
        {
            $sendvalue['codestatus'] = "Invalid Store";
        }


        $this->output
                ->set_content_type('application/json') 
                ->set_output(json_encode($sendvalue)); 

    }
    public function addbulk_submit22()
    {
        $sendvalue['message'] = "default msg";
        $store_id = $this->input->get('storeid');
        // $formData = $_POST['formData'];
        $uploadedFile = $_FILES['csv_file'];
        $sendvalue['message'] = $uploadedFile['name'];

        // $storeid = $_POST['storeid'];
        // $storeid = $this->input->post('storeid');

        $this->output
                ->set_content_type('application/json') // Set content type to JSON
                ->set_output(json_encode($sendvalue)); // Encode the array as JSON

        exit();

        // $extratext = "";
        // if($storeid != "")
        // {
            // $store = $this->stores_model->detail($storeid);
            // if($store)
            // {
                // if ($_FILES["csv_file"]["error"] === UPLOAD_ERR_OK) 
                // {
                   
                //     // if (isset($_FILES["csv_file"]) && $_FILES["csv_file"] != "") 
                //     // {
                   
                //     // $sendvalue['message'] = 'File selected.';
                //     if ($_FILES["csv_file"]['type'] == 'text/csv') 
                //     {
                    //         $unique_identifier = time();
                    //         $file_info = $_FILES['csv_file'];
                    //         $original_file_name = $file_info['name'];
                    //         $new_file_name = $unique_identifier . '_' . $original_file_name;

                    //         $this->load->library('upload');
                    //         $config['upload_path'] = 'files/';
                    //         $config['allowed_types'] = '*';
                    //         $config['file_name'] = $new_file_name;
                    //         $config['overwrite'] = true;
                    //         $this->upload->initialize($config);

                    //         if ($this->upload->do_upload('csv_file')) 
                    //         {
                    //             //  $sendvalue['message'] = 'uploaded file at files/ directory';
                    //             $csv = $this->upload->file_name;
                    //             $arrResult = array();
                    //             $handle = fopen('files/'. $csv, "r");
                    //             $extratext = "files/".$csv; 
                            
                    //             if ($handle) 
                    //             {
                    //                 while (($row = fgetcsv($handle, 1000, ",")) !== false) 
                    //                 {
                    //                     $arrResult[] = $row;
                    //                 }
                    //                 fclose($handle);
                    //             }
                    //             // $sendvalue['message'] = $row; break; //name, Integration Type, stock_margin, Default Quantity Unit, Default Price Type, Auto SO, Auto Batch Selection, Auto Invoice

                    //             $titles = array_shift($arrResult);
                    //             $keys = array('name','Integration Type','stock_margin','Default Quantity Unit','Default Price Type','Auto SO','Auto Batch Selection','Auto Invoice');
                    //             $finals = array();
                    
                    //             foreach ($arrResult as $key => $value) {
                    //                 $finals[] = array_combine($keys, $value);   //error here
                    //             }
                            
                                
                    //             foreach($finals as $final)
                    //             {
                    //                 // $productdetail = $this->calPrice(
                    //                 //     $final['product_id'],
                    //                 //     $final['price_type'],
                    //                 //     $final['discount'],
                    //                 //     $final['qty_type'],
                    //                 //     $final['warehouse_id']
                    //                 // );
                    //                 // const columnNames = "company_id, name, Integration Type, stock_margin, Default Quantity Unit, Default Price Type, Auto SO, Auto Batch Selection, Auto Invoice";
                    //                 // $sendvalue['message'] = $final['Auto Invoice'];     break; 
                    //                 // $sendvalue['message'] = $this->session->userdata('user_id');     break; 

                    //                 $insertdata['name'] = $final['name'];
                    //                 // $insertdata['integration_type'] = $final['Integration Type'];
                    //                 $insertdata['types'] = $final['Integration Type'];
                    //                 $insertdata['stock_margin'] = $final['stock_margin'];
                    //                 $insertdata['update_qty_in'] = $final['Default Quantity Unit'];
                    //                 $insertdata['update_price'] = $final['Default Price Type'];
                    //                 $insertdata['auto_so'] = $final['Auto SO'];
                    //                 $insertdata['auto_batch_selete'] = $final['Auto Batch Selection']; //
                    //                 $insertdata['auto_invoice'] = $final['Auto Invoice'];
                    //                 $insertdata['created_by'] = $this->session->userdata('user_id');
                    //                 $insertdata['status'] = 'active';
                    //                 $this->db->insert('sma_stores_tb',$insertdata);

                        

                    //                         // if($productdetail['codestatus'] == "ok")
                    //                         // {
                    //                         //     $wodata['store_url'] = $store->store_url;
                    //                         //     $wodata['wordpress_wocommerce_consumer_key'] = $store->wordpress_wocommerce_consumer_key;
                    //                         //     $wodata['wordpress_wocommerce_consumer_secret'] = $store->wordpress_wocommerce_consumer_secret;
                    //                         //     if($final['name'] == "")
                    //                         //     {
                    //                         //         $wodata['product']['name'] = $productdetail['product_name'];
                    //                         //         $insertdata['product_name'] = $productdetail['product_name'];
                    //                         //     }
                    //                         //     else
                    //                         //     {
                    //                         //         $wodata['product']['name'] = $final['name'];
                    //                         //         $insertdata['product_name'] = $final['name'];
                    //                         //     }
                    //                         //     $wodata['product']['type'] = 'simple';
                    //                         //     $wodata['product']['short_description'] = $productdetail['product_details'];
                    //                         //     // Porduct Detail
                    //                         //     if($productdetail['mrp'] == $productdetail['total']){
                    //                         //         $wodata['product']['regular_price'] = (string)$productdetail['mrp'];
                    //                         //         $wodata['product']['sale_price'] = '';
                    //                         //     }
                    //                         //     else{
                    //                         //         $wodata['product']['regular_price'] = (string)$productdetail['mrp'];
                    //                         //         $wodata['product']['sale_price'] = (string)$productdetail['total'];
                    //                         //     }
                    //                         //     if($final['update_type'] == "qty" || $final['update_type'] == "priceqty" || $final['update_type'] == "detailnqty" || $final['update_type'] == "full"){
                    //                         //         $wodata['product']['manage_stock'] = true;
                    //                         //         if($productdetail['stock'] > 0){
                    //                         //             $wodata['product']['stock_status'] = 'instock';
                    //                         //         }
                    //                         //         else{
                    //                         //             $wodata['product']['stock_status'] = 'outofstock';
                    //                         //         }
                    //                         //         $wodata['product']['stock_quantity'] = (int)$productdetail['stock'];
                    //                         //     }
                    //                         //     else{
                    //                         //         $wodata['product']['manage_stock'] = false;
                    //                         //         $wodata['product']['stock_status'] = 'instock';
                    //                         //     }
                    //                         //     if($final['store_product_id'] == ""){
                    //                         //         $returndata = $this->stores_model->newProduct($wodata);
                    //                         //         $insertdata['store_product_id'] = $returndata['productdata']->id;
                    //                         //     }
                    //                         //     else{
                    //                         //         $insertdata['store_product_id'] = $final['store_product_id'];
                    //                         //         $returndata = $this->stores_model->updateProductDetail($wodata,$final['store_product_id']);
                    //                         //     }
                                        
                    //                         //     $insertdata['store_id'] = $storeid;
                    //                         //     $insertdata['update_in'] = $final['update_type'];
                    //                         //     $insertdata['product_id'] = $final['product_id'];
                    //                         //     $insertdata['update_qty_in'] = $final['qty_type'];
                    //                         //     $insertdata['price_type'] = $final['price_type'];
                    //                         //     $insertdata['discount'] = $final['discount'];
                    //                         //     $insertdata['warehouse_id'] = $final['warehouse_id'];
                                                
                    //                         //     if($final['supplier_id'] == "" || $final['supplier_id'] == "0")
                    //                         //     {
                    //                         //         $insertdata['supplier_id'] = $productdetail['supplier1'];
                    //                         //     }
                    //                         //     else{
                    //                         //         $supplierdetail = $this->getSupplierName($final['supplier_id']);
                    //                         //         if($supplierdetail == "Invalid Supplier"){
                    //                         //             $insertdata['supplier_id'] = $productdetail['supplier1'];
                    //                         //         }
                    //                         //         else{
                    //                         //             $insertdata['supplier_id'] = $final['supplier_id'];
                    //                         //         }
                    //                         //     }
                    //                     // $insertdata['created_by'] = $this->session->userdata('user_id');
                    //                     // $insertdata['status'] = 'active';
                    //                     // $this->db->insert('sma_store_products_tb',$insertdata);
                    //                 // }
                    //             }
                    //             $sendvalue['message'] = 'Companies Integrated Successfully';
                    //         }
                    //         else
                    //         {
                    //             // $error = array('error' => $this->upload->display_errors());
                    //             // $sendvalue['codestatus'] = 'Upoading Faild';
                    //             // $sendvalue['error'] = $error;
                    //             $sendvalue['message'] = $this->upload->display_errors(); //error_msg
                    //         }
                //     }
                //     else
                //     {
                //         $sendvalue['message'] = 'Upload only CSV Excel File. This file type'.$_FILES["csv_file"]['type'];
                //     }
                // }
                // else{
                //     $sendvalue['message'] = 'Please Select File.';
                // }
            // }
            // else{
            //     $sendvalue['codestatus'] = "Invalid Store";
            // }
        // }
        // else{
        //     $sendvalue['codestatus'] = "Invalid Store";
        // }
        // $this->useractivities_model->add([
        //     'note'=>$sendvalue['codestatus'].'File Location: '.$extratext,
        //     'location'=>'Stores->Add Bulk->Submit',
        //     'store_id'=>$storeid,
        //     'action_by'=>$this->session->userdata('user_id')
        // ]);
        // echo json_encode($sendvalue);

   

        $this->output
            ->set_content_type('application/json') // Set content type to JSON
            ->set_output(json_encode($sendvalue)); // Encode the array as JSON
    }
    public function zohaib_bhai_method()
    {
       
        $sendvalue['codestatus'] = "no";
        $storeid = $this->input->post('storeid');
        $extratext = "";

        if($storeid != "")
        {
            $store = $this->stores_model->detail($storeid);

            if($store){
                if (isset($_FILES["products"]) && $_FILES["products"] != "") {
                   
                    if ($_FILES["products"]['type'] == 'text/csv') {
                       
                        $this->load->library('upload');
                        $config['upload_path'] = 'files/';
                        $config['allowed_types'] = '*';
                        $config['overwrite'] = true;
                        $this->upload->initialize($config);
                       
                        if ($this->upload->do_upload('products')) {
                            $csv = $this->upload->file_name;
                            $arrResult = array();
                            $handle = fopen('files/'. $csv, "r");
                            $extratext = "files/".$csv;

                            if ($handle) {
                                while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                                    $arrResult[] = $row;
                                }
                                fclose($handle);
                            }
                            $titles = array_shift($arrResult);
                            $keys = array('product_id', 'name','store_product_id','update_type','qty_type','price_type','warehouse_id','discount','supplier_id');
                            $finals = array();
                            foreach ($arrResult as $key => $value) {
                                $finals[] = array_combine($keys, $value);
                            }
                            foreach($finals as $final){
                                $productdetail = $this->calPrice($final['product_id'], $final['price_type'], $final['discount'], $final['qty_type'], $final['warehouse_id']);
                                if($productdetail['codestatus'] == "ok"){
                                    $wodata['store_url'] = $store->store_url;
                                    $wodata['wordpress_wocommerce_consumer_key'] = $store->wordpress_wocommerce_consumer_key;
                                    $wodata['wordpress_wocommerce_consumer_secret'] = $store->wordpress_wocommerce_consumer_secret;
                                    if($final['name'] == ""){
                                        $wodata['product']['name'] = $productdetail['product_name'];
                                        $insertdata['product_name'] = $productdetail['product_name'];
                                    }
                                    else{
                                        $wodata['product']['name'] = $final['name'];
                                        $insertdata['product_name'] = $final['name'];
                                    }
                                    $wodata['product']['type'] = 'simple';
                                    $wodata['product']['short_description'] = $productdetail['product_details'];
                                    // Porduct Detail
                                    if($productdetail['mrp'] == $productdetail['total']){
                                        $wodata['product']['regular_price'] = (string)$productdetail['mrp'];
                                        $wodata['product']['sale_price'] = '';
                                    }
                                    else{
                                        $wodata['product']['regular_price'] = (string)$productdetail['mrp'];
                                        $wodata['product']['sale_price'] = (string)$productdetail['total'];
                                    }
                                    if($final['update_type'] == "qty" || $final['update_type'] == "priceqty" || $final['update_type'] == "detailnqty" || $final['update_type'] == "full"){
                                        $wodata['product']['manage_stock'] = true;
                                        if($productdetail['stock'] > 0){
                                            $wodata['product']['stock_status'] = 'instock';
                                        }
                                        else{
                                            $wodata['product']['stock_status'] = 'outofstock';
                                        }
                                        $wodata['product']['stock_quantity'] = (int)$productdetail['stock'];
                                    }
                                    else{
                                        $wodata['product']['manage_stock'] = false;
                                        $wodata['product']['stock_status'] = 'instock';
                                    }
                                    if($final['store_product_id'] == ""){
                                        $returndata = $this->stores_model->newProduct($wodata);
                                        $insertdata['store_product_id'] = $returndata['productdata']->id;
                                    }
                                    else{
                                        $insertdata['store_product_id'] = $final['store_product_id'];
                                        $returndata = $this->stores_model->updateProductDetail($wodata,$final['store_product_id']);
                                    }
                            
                                    $insertdata['store_id'] = $storeid;
                                    $insertdata['update_in'] = $final['update_type'];
                                    $insertdata['product_id'] = $final['product_id'];
                                    $insertdata['update_qty_in'] = $final['qty_type'];
                                    $insertdata['price_type'] = $final['price_type'];
                                    $insertdata['discount'] = $final['discount'];
                                    $insertdata['warehouse_id'] = $final['warehouse_id'];
                                    if($final['supplier_id'] == "" || $final['supplier_id'] == "0"){
                                        $insertdata['supplier_id'] = $productdetail['supplier1'];
                                    }
                                    else{
                                        $supplierdetail = $this->getSupplierName($final['supplier_id']);
                                        if($supplierdetail == "Invalid Supplier"){
                                            $insertdata['supplier_id'] = $productdetail['supplier1'];
                                        }
                                        else{
                                            $insertdata['supplier_id'] = $final['supplier_id'];
                                        }
                                    }
                                    $insertdata['created_by'] = $this->session->userdata('user_id');
                                    $insertdata['status'] = 'active';
                                    $this->db->insert('sma_store_products_tb',$insertdata);
                                }
                            }
                            $sendvalue['codestatus'] = 'Product Integrate Successfully';
                        }
                        else{
                            $error = array('error' => $this->upload->display_errors());
                            $sendvalue['codestatus'] = 'Upoading Faild';
                            $sendvalue['error'] = $error;
                        }
                    }
                    else{
                        $sendvalue['codestatus'] = 'Upload only CSV Excel File. This file type'.$_FILES["products"]['type'];
                    }
                }
                else{
                    $sendvalue['codestatus'] = 'Please Select File.';
                }
            }
            else{
                $sendvalue['codestatus'] = "Invalid Store";
            }
        }
        else
        {
            $sendvalue['codestatus'] = "Invalid Store";
        }

        $this->useractivities_model->add([
            'note'=>$sendvalue['codestatus'].'File Location: '.$extratext,
            'location'=>'Stores->Add Bulk->Submit',
            'store_id'=>$storeid,
            'action_by'=>$this->session->userdata('user_id')
        ]);

        echo json_encode($sendvalue);
    
    }
    function myfunction($num)
    {
    return $num;
    }

  
    public function calPrice($gpid = '', $gpricetype = '', $gdiscount = '', $gstocktype = '', $gwarehouse_id = '', $gstock_margin = '')
    {
        // $final['product_id'], $final['price_type'], $final['discount'], $final['qty_type'], $final['warehouse_id']


        $sendvalue['product_name'] = 0;
        $sendvalue['price'] = 0;
        $sendvalue['tax'] = 0;
        $sendvalue['discount'] = 0;
        $sendvalue['total'] = 0;

        if ($gpid == '') 
        {
            $pid = $this->input->get('pid');
            $pricetype = $this->input->get('pricetype');
            $discount = $this->input->get('discount');
            $stocktype = $this->input->get('stocktype');
            $warehouse_id = $this->input->get('warehouse_id');
            $stock_margin = $this->input->get('stock_margin');
        } 
        else
        {
            $pid = $gpid;
            $pricetype = $gpricetype;
            $discount = $gdiscount;
            $stocktype = $gstocktype;
            $warehouse_id = $gwarehouse_id;

            // $stock_margin = $gstock_margin;
      
        }
           $this->db->select('
            sma_products.name as product_name,
            sum(sma_purchase_items.quantity_balance),
            sma_products.product_details,
            sma_products.mrp,
            sma_products.cost,
            sma_products.price,
            sma_products.dropship,
            sma_products.crossdock,
            sma_products.supplier1,
            sma_products.discount_one,
            sma_products.discount_two,
            sma_products.discount_three,
            sma_products.discount_mrp,
            sma_products.pack_size,
            sma_products.carton_size,
            sma_tax_rates.rate as rate,
            "1" as type
        ');
        $this->db->from('sma_products');
        $this->db->join('sma_purchase_items','sma_purchase_items.product_id=sma_products.id','left');
        $this->db->join('sma_tax_rates', 'sma_tax_rates.id = sma_products.tax_rate', 'left');
        $this->db->where('sma_products.id', $pid);
        $this->db->where('sma_purchase_items.warehouse_id',$warehouse_id);
        
       $q = $this->db->get();

        if ($q->num_rows() > 0)
        {
            $product = $q->result()[0];
            $sendvalue['product_name'] = $product->product_name;
            if ($pricetype == 'mrp') {
                $sendvalue['price'] = $product->mrp;
                $sendvalue['discount'] = $this->calDiscount($product->price, $discount, $product);
            } elseif ($pricetype == 'consiment') {
                $sendvalue['price'] = $product->price;
                if ($product->type == '1') {
                    $sendvalue['tax'] = ($product->price / 100) * $product->rate;
                } else {
                    $sendvalue['tax'] = $product->rate;
                }
                $sendvalue['discount'] = $this->calDiscount($product->price, $discount, $product);
            } elseif ($pricetype == 'dropship') {
                $sendvalue['price'] = $product->price;
            } elseif ($pricetype == 'crossdock') {
                $sendvalue['price'] = $product->crossdock;
            } elseif ($pricetype == 'cost') {
                $sendvalue['price'] = $product->cost;
                if ($product->type == '1') {
                    $sendvalue['tax'] = ($product->cost / 100) * $product->rate;
                } else {
                    $sendvalue['tax'] = $product->rate;
                }
            }
            $qty = 1;
            if ($stocktype == 'pack') {
                $qty = (int) $product->pack_size;
            } elseif ($stocktype == 'carton') {
                $qty = (int) $product->carton_size;
            } else {
                $qty = 1;
            }
            $countStock = $this->countStock($pid, $warehouse_id);
            if ($countStock != '' && $countStock != 0 && $countStock != '0.0000') {
                $store_hold_qty = $this->stores_model->getPendingItemsInSO($warehouse_id, $pid);
                $sendvalue['store_hold_qty'] = $store_hold_qty;
                $sendvalue['countStock'] = $countStock;
                $countStock = $countStock - $store_hold_qty;
                $stock = (int) $countStock / $qty;
            } else {
                $stock = 0;
            }
            if ($stock < 0) {
                $stock = 0;
            }

            $stock_margin = !empty($stock_margin) ? $stock_margin : 100;
            $countstockmargin = ($stock_margin / 100) * $stock;
            $sendvalue['stock'] = (int) $countstockmargin;
            $sendvalue['product_name'] = $product->product_name;
            $sendvalue['product_details'] = $product->product_details;
            $sendvalue['mrp'] = $product->mrp * $qty;
            $sendvalue['price'] = decimalallow($sendvalue['price'] * $qty, 4);
            $sendvalue['tax'] = decimalallow($sendvalue['tax'] * $qty, 4);
            $sendvalue['discount'] = decimalallow($sendvalue['discount'] * $qty, 4);
            $sendvalue['supplier1'] = $product->supplier1;
            $sendvalue['codestatus'] = 'ok';
        }
        else
        {
            $sendvalue['codestatus'] = 'Invalid Product';
        }
        $sendvalue['total'] = decimalallow($sendvalue['price'] + $sendvalue['tax'] - $sendvalue['discount'], 4);
     
        if ($gpid == '')
        {
            echo json_encode($sendvalue);
        }
        else
        {
            return $sendvalue;
        }
    }
    public function runquery_store($onlycoun = 'no'){
        $column_order = [
            'stores_tb.id',
            'stores_tb.name',
            'stores_tb.types',
            'stores_tb.stock_margin',
            'stores_tb.update_qty_in',
            'stores_tb.update_price',
            'stores_tb.auto_so',
            'stores_tb.auto_batch_selete',
            'stores_tb.auto_invoice',
            'stores_tb.created_at',
            'stores_tb.status',
        ];
        $column_search = [
            'stores_tb.name',
            'stores_tb.types',
            'stores_tb.update_type',
            'stores_tb.default_category',
        ];
        // Get Data
        if ($onlycoun == 'yes') {
            $this->db->select('stores_tb.id as id');
        } else {
            $this->db->select('
                stores_tb.*,
                (SELECT SUM(id) FROM sma_store_products_tb WHERE store_id = stores_tb.id) as products
            ');
        }
        $this->db->from('stores_tb as stores_tb');
        $i = 0;
        // loop searchable columns
        if ($onlycoun != 'yes') {
            foreach ($column_search as $item) {
                // if datatable send POST for search
                if ($_POST['search']['value']) {
                    // first loop
                    if ($i === 0) {
                        // open bracket
                        $this->db->group_start();
                        $this->db->like($item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like($item, $_POST['search']['value']);
                    }
                    // last loop
                    if (count($column_search) - 1 == $i) {
                        // close bracket
                        $this->db->group_end();
                    }
                }
                ++$i;
            }
        }
        if ($onlycoun != 'yes') {
            $this->db->order_by($_POST['order']['0']['column'] + 1, $_POST['order']['0']['dir']);
        }
    }

    public function add()
    {
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('warehouse', $this->lang->line('warehouse'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line('supplier'), 'required');
        $this->session->unset_userdata('csrf_token');

        $this->data['tax_rates'] = $this->site->getAllTaxRates();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['own_company'] = $this->site->getAllown_companies();
        $this->data['customers'] = $this->getCustomers();

        $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => 'Add Store']];
        $meta = ['page_title' => 'Add Store', 'bc' => $bc];
        $this->page_construct2('store/add', $meta, $this->data);
    }

    public function create(){
        $name = $this->input->post('name');
        $type = $this->input->post('type');
        $warehouse = $this->input->post('warehouse');
        $store_url = $this->input->post('store_url');
        $update_qty_in = $this->input->post('update_qty_in');
        $update_price = $this->input->post('update_price');
        $discount = $this->input->post('discount');
        $darazstoreid = $this->input->post('darazstoreid');
        $darazapikey = $this->input->post('darazapikey');
        $wocommerce_key = $this->input->post('wocommerce_key');
        $wocommerce_secret = $this->input->post('wocommerce_secret');
        $stockmargin = $this->input->post('stockmargin');
        $stockmarginvalue = (int) preg_replace('/\D/ui', '', $stockmargin);
        $so_create = $this->input->post('so_create');
        $so_batch_select = $this->input->post('so_batch_select');
        $so_invoice_create = $this->input->post('so_invoice_create');
        $customer = $this->input->post('customer');
        $sendvalue['status'] = false;
        $sendvalue['message'] = '';
        if ($name != '') {
            if ($type == 'Daraz' && ($darazstoreid == '' || $darazapikey == '')) {
                $sendvalue['message'] = 'Enter Daraz Store ID or API Key';
            } elseif ($type == 'Wordpress (Wocommerce)' && ($wocommerce_key == '' || $wocommerce_secret == '')) {
                $sendvalue['message'] = 'Enter Wocommerce Key or Secret';
            } elseif ($stockmarginvalue == '') {
                $sendvalue['message'] = 'Enter Store Stock Margin';
            } else {
                if ($so_create == 'yes' && $customer == '') {
                    $sendvalue['message'] = 'Select Default Customer';
                } else {
                    $data['name'] = $name;
                    $data['types'] = $type;
                    $data['warehouse_id'] = $warehouse;
                    $data['update_qty_in'] = $update_qty_in;
                    $data['update_price'] = $update_price;
                    $data['discount'] = $discount;
                    $data['store_url'] = $store_url;
                    $data['daraz_store_id'] = $darazstoreid;
                    $data['daraz_api_key'] = $darazapikey;
                    $data['wordpress_wocommerce_consumer_key'] = $wocommerce_key;
                    $data['wordpress_wocommerce_consumer_secret'] = $wocommerce_secret;
                    $data['stock_margin'] = $stockmarginvalue;
                    $data['created_by'] = $this->session->userdata('user_id');
                    $data['updated_by'] = $this->session->userdata('user_id');
                    $data['auto_so'] = 'no';
                    $data['auto_batch_selete'] = $so_batch_select;
                    $data['auto_invoice'] = $so_invoice_create;
                    $data['webhook_id'] = 0;
                    $data['status'] = 'active';
                    $this->db->insert('stores_tb', $data);
                    $sid = $this->db->insert_id();
                    if ($so_create == 'yes') {
                        $setdata['auto_so'] = 'yes';
                        $wdata = [
                            'name' => 'Rhocom360OrderCreated',
                            'topic' => 'order.created',
                            'delivery_url' => base_url('api/salesorders/createso?sid=' . $sid),
                        ];
                        $setdata['webhook_id'] = $this->wp->createWebHook($sid, $wdata);
                    } else {
                        $setdata['auto_so'] = 'no';
                        $setdata['webhook_id'] = 0;
                    }
                    $this->db->set($setdata);
                    $this->db->where('id', $sid);
                    $this->db->update('stores_tb');
                    $sendvalue['message'] = 'Add New Store Successfully';
                    $sendvalue['status'] = true;
                }
            }
        } else {
            $sendvalue['message'] = 'Enter Store Code';
        }
        $this->useractivities_model->add([
            'note' => $sendvalue['message'] . '. Store Name: ' . $name,
            'location' => 'Stores->Add->Submit',
            'action_by' => $this->session->userdata('user_id'),
        ]);
        echo json_encode($sendvalue);
    }

    public function edit($quote_id = null){
        $storeid = $this->input->get('id');
        $activenote = '';
        if ($storeid != '') {
            $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
            $this->form_validation->set_rules('warehouse', $this->lang->line('warehouse'), 'required|is_natural_no_zero');
            $this->form_validation->set_rules('supplier', $this->lang->line('supplier'), 'required');
            $this->session->unset_userdata('csrf_token');
            $this->data['store'] = $this->stores_model->detail($storeid);
            if ($this->data['store']) {
                $this->data['tax_rates'] = $this->site->getAllTaxRates();
                $this->data['warehouses'] = $this->site->getAllWarehouses();
                $this->data['own_company'] = $this->site->getAllown_companies();
                $this->data['customers'] = $this->getCustomers();
                $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => 'Edit Store']];
                $meta = ['page_title' => 'Edit Store', 'bc' => $bc];
                $this->page_construct2('store/edit', $meta, $this->data);
                $activenote = 'Edit Store';
            } else {
                $activenote = 'Invalid Store';
                redirect(base_url('admin/stores'));
            }
        } else {
            $activenote = 'Store ID Empty';
            redirect(base_url('admin/stores'));
        }
    }

    public function updated(){
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $type = $this->input->post('type');
        $warehouse = $this->input->post('warehouse');
        $update_price = $this->input->post('update_price');
        $discount = $this->input->post('discount');
        $store_url = $this->input->post('store_url');
        $update_qty_in = $this->input->post('update_qty_in');
        $darazstoreid = $this->input->post('darazstoreid');
        $darazapikey = $this->input->post('darazapikey');
        $wocommerce_key = $this->input->post('wocommerce_key');
        $wocommerce_secret = $this->input->post('wocommerce_secret');
        $stockmargin = $this->input->post('stockmargin');
        $stockmarginvalue = (int) preg_replace('/\D/ui', '', $stockmargin);
        $so_create = $this->input->post('so_create');
        $so_batch_select = $this->input->post('so_batch_select');
        $so_invoice_create = $this->input->post('so_invoice_create');
        $customer = $this->input->post('customer');
        $status = $this->input->post('status');
        $sendvalue['status'] = false;
        $sendvalue['message'] = '';

        if ($name != '') {
            if ($type == 'Daraz' && ($darazstoreid == '' || $darazapikey == '')) {
                $sendvalue['message'] = 'Enter Daraz Store ID or API Key';
            } elseif ($type == 'Wordpress (Wocommerce)' && ($wocommerce_key == '' || $wocommerce_secret == '')) {
                $sendvalue['message'] = 'Enter Wocommerce Key or Secret';
            } elseif ($stockmarginvalue == '') {
                $sendvalue['message'] = 'Enter Store Stock Margin';
            } else {
                if ($so_create == 'yes' && $customer == '') {
                    $sendvalue['message'] = 'Select Default Customer';
                } else {
                    $data['webhook_id'] = 0;
                    $data['name'] = $name;
                    $data['types'] = $type;
                    $data['warehouse_id'] = $warehouse;
                    $data['update_qty_in'] = $update_qty_in;
                    $data['update_price'] = $update_price;
                    $data['discount'] = $discount;
                    $data['store_url'] = $store_url;
                    $data['daraz_store_id'] = $darazstoreid;
                    $data['daraz_api_key'] = $darazapikey;
                    $data['wordpress_wocommerce_consumer_key'] = $wocommerce_key;
                    $data['wordpress_wocommerce_consumer_secret'] = $wocommerce_secret;
                    $data['stock_margin'] = $stockmarginvalue;
                    $data['auto_so'] = $so_create;
                    $data['auto_batch_selete'] = $so_batch_select;
                    $data['auto_invoice'] = $so_invoice_create;
                    $data['customer_id'] = $customer;
                    $data['updated_by'] = $this->session->userdata('user_id');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $data['status'] = $status;
                    $this->db->set($data);
                    $this->db->where('id', $id);
                    $this->db->update('stores_tb');
                    $sendvalue['message'] = 'Edit Store Successfully';
                    $sendvalue['status'] = true;
                }
            }
        } else {
            $sendvalue['message'] = 'Enter Store Code';
        }
        $this->useractivities_model->add([
            'note' => $sendvalue['message'] . '. Store ID: ' . $id . ', Store Name: ' . $name,
            'location' => 'Stores->Edit->Submit',
            'store_id' => $id,
            'action_by' => $this->session->userdata('user_id'),
        ]);
        echo json_encode($sendvalue);
    }

    public function delete(){
        $senddata['status'] = false;
        $senddata['message'] = 'Try again!';
        $id = $this->input->post('id');
        $reason = $this->input->post('reason');
        if ($reason != '') {
            $this->db->select('id');
            $this->db->from('store_products_tb');
            $this->db->where('store_id', $id);
            $q = $this->db->get();
            if ($q->num_rows() == 0) {
                $this->db->delete('stores_tb', ['id' => $id]);
                $senddata['status'] = true;
                $senddata['message'] = 'Store delete successfully!';
            } else {
                $senddata['message'] = 'Delete Link Products form that Store then delete this store!';
            }
        } else {
            $senddata['message'] = 'Enter Reason!';
        }
        echo json_encode($senddata);
    }

    public function products(){
        $storeid = $this->input->get('id');
        if ($storeid != '') {
            $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
            $this->form_validation->set_rules('warehouse', $this->lang->line('warehouse'), 'required|is_natural_no_zero');
            $this->form_validation->set_rules('supplier', $this->lang->line('supplier'), 'required');
            $this->session->unset_userdata('csrf_token');
            $this->data['store'] = $this->stores_model->detail($storeid);
            if ($this->data['store']) {
                $this->data['storeid'] = $storeid;
                $this->data['warehouses'] = $this->site->getAllWarehouses();
                $this->data['products'] = $this->stores_model->products($storeid);
                $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => 'Products Integrate']];
                $meta = ['page_title' => 'Products Integrate', 'bc' => $bc];
                $this->page_construct2('store/products', $meta, $this->data);
            } else {
                redirect(base_url('admin/stores'));
            }
        } else {
            redirect(base_url('admin/stores'));
        }
    }

    public function product_add(){
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('warehouse', $this->lang->line('warehouse'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line('supplier'), 'required');
        $this->session->unset_userdata('csrf_token');

        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $id = $this->input->get('store_id');
        $this->data['store'] = $this->db->from('stores_tb')->where('id', $id)->get()->row();

        $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => 'Link Product']];
        $meta = ['page_title' => 'Link Product', 'bc' => $bc];
        $this->page_construct2('store/product_add', $meta, $this->data);
    }

    public function insert_product(){
        $sendvalue['status'] = false;
        $sendvalue['message'] = '';
        $insertdata['store_id'] = $this->input->post('store_id');
        $insertdata['product_id'] = $this->input->post('product');
        $store_product_code = $this->input->post('store_product_code');
        $insertdata['product_name'] = $this->input->post('title');
        $insertdata['update_in'] = $this->input->post('updatetype');
        $insertdata['update_qty_in'] = $this->input->post('stocktype');
        $insertdata['price_type'] = $this->input->post('pricetype');
        $insertdata['warehouse_id'] = $this->input->post('warehouse_id');
        $insertdata['discount'] = $this->input->post('discount');
        $insertdata['supplier_id'] = $this->input->post('supplier');
        $insertdata['created_by'] = $this->session->userdata('user_id');
        $insertdata['status'] = 'active';
        if ($insertdata['product_id'] == '') {
            $sendvalue['message'] = 'Select Product';
        } elseif (!isset($_POST['createproduct']) && $store_product_code == '') {
            $sendvalue['message'] = 'Enter Store Product ID';
        } else {
            $this->db->select('*');
            $this->db->from('sma_stores_tb');
            $this->db->where('id', $insertdata['store_id']);
            $storeq = $this->db->get();
            if ($storeq->num_rows() > 0) {
                $store = $storeq->result()[0];
                $productdetail = $this->calPrice($insertdata['product_id'], $insertdata['price_type'], $insertdata['discount'], $insertdata['update_qty_in'], $insertdata['warehouse_id'], $store->stock_margin);
                $insertdata['product_name'] = $productdetail['product_name'];
                if ($productdetail['codestatus'] == 'ok') {
                    if ($store->types == 'Wordpress (Wocommerce)' || $store->types == 'Shopify') {
                        $wodata['store_url'] = $store->store_url;
                        $wodata['wordpress_wocommerce_consumer_key'] = $store->wordpress_wocommerce_consumer_key;
                        $wodata['wordpress_wocommerce_consumer_secret'] = $store->wordpress_wocommerce_consumer_secret;
                        $wodata['product']['name'] = $insertdata['product_name'];
                        $wodata['product']['type'] = 'simple';
                        $wodata['product']['short_description'] = $productdetail['product_details'];
                        // Porduct Detail
                        if ($productdetail['mrp'] == $productdetail['total']) {
                            $wodata['product']['regular_price'] = (string) $productdetail['mrp'];
                            $wodata['product']['sale_price'] = '';
                        } else {
                            $wodata['product']['regular_price'] = (string) $productdetail['mrp'];
                            $wodata['product']['sale_price'] = (string) $productdetail['total'];
                        }
                        if ($insertdata['update_in'] == 'qty' || $insertdata['update_in'] == 'priceqty' || $insertdata['update_in'] == 'detailnqty' || $insertdata['update_in'] == 'full') {
                            $wodata['product']['manage_stock'] = true;
                            if ($productdetail['stock'] > 0) {
                                $wodata['product']['stock_status'] = 'instock';
                            } else {
                                $wodata['product']['stock_status'] = 'outofstock';
                            }
                            $wodata['product']['stock_quantity'] = (int) $productdetail['stock'];
                        } else {
                            // $wodata['product']['manage_stock'] = false;
                            // $wodata['product']['stock_status'] = 'instock';
                        }
                        if (isset($_POST['createproduct'])) {
                            // $wodata['product']['categories'][]['id'] = $store->default_category;
                            $returndata = $this->stores_model->newProduct($wodata);
                            $insertdata['store_product_id'] = $returndata['productdata']->id;
                        } else {
                            $insertdata['store_product_id'] = $this->input->post('store_product_code');
                            $returndata = $this->stores_model->updateProductDetail($wodata, $insertdata['store_product_id']);
                        }
                    } elseif ($store->types == 'Daraz') {
                        $darazdata['daraz_api_key'] = $store->daraz_api_key;
                        $darazdata['daraz_username'] = $store->daraz_store_id;
                        $darazdata['quantity'] = (int) $productdetail['stock'];
                        $darazdata['store_product_sku'] = $this->input->post('store_product_code');

                        $insertdata['store_product_id'] = $this->input->post('store_product_code');
                        // $returndata = $this->daraz_model->get_update_product_daraz_api($darazdata);
                        $returndata = $this->darazAPI_model->updateProduct($darazdata);
                    } else {
                        $returndata['codestatus'] = 'Invalid Store ID';
                    }

                    if ($returndata['codestatus'] == 'ok') {
                        $this->db->insert('sma_store_products_tb', $insertdata);

                        if ($store->types == 'Shopify') {
                            $this->stores_model->StoreQtyUpdate($insertdata['product_id'], $insertdata['warehouse_id'], $insertdata['store_id']);
                        }

                        $sendvalue['message'] = 'Product Integrate Successfully';
                        $sendvalue['status'] = true;
                    } else {
                        $sendvalue['message'] = $returndata['codestatus'];
                    }
                } else {
                    $sendvalue['message'] = 'Invalid Product';
                    $sendvalue['error'] = $productdetail['codestatus'];
                }
            } else {
                $sendvalue['message'] = 'Invalid Store';
            }
        }
        $this->useractivities_model->add([
            'note' => $sendvalue['message'] . '. Store Product Title: ' . $insertdata['product_name'],
            'location' => 'Stores->Products->Add->Submit',
            'store_id' => $insertdata['store_id'],
            'product_id' => $insertdata['product_id'],
            'action_by' => $this->session->userdata('user_id'),
        ]);
        echo json_encode($sendvalue);
    }

    public function discountlist(){
        $pid = $this->input->get('pid');
        $supplier_id = 0;
        $this->db->select('supplier1 as supplier_id');
        $this->db->from('sma_products');
        $this->db->where('id', $pid);
        $pq = $this->db->get();
        if ($pq->num_rows() > 0) {
            $presulth = $pq->result()[0];
            $supplier_id = $presulth->supplier_id;
        }
        $storediscount = $this->input->get('storediscount');

        $html['discount'] = '<option value="no">No Discount</option><option value="mrp" ';
        if ($storediscount == 'mrp') {
            $html['discount'] .= 'selected';
        }
        $html['discount'] .= ' >MRP Discount</option><option value="d1" ';
        if ($storediscount == 'd1') {
            $html['discount'] .= 'selected';
        }
        $html['discount'] .= ' >Discount 1</option><option value="d2" ';
        if ($storediscount == 'd2') {
            $html['discount'] .= 'selected';
        }
        $html['discount'] .= ' >Discount 2</option><option value="d3" ';
        if ($storediscount == 'd3') {
            $html['discount'] .= 'selected';
        }
        $html['discount'] .= ' >Discount 3</option>';

        $query = $this->db->query('
            select
                id,
                discount_name as name,
                percentage
            from 
                sma_bulk_discount 
            where 
                (CURDATE() between start_date and end_date) and 
                (
                    find_in_set(' . $supplier_id . ',supplier_id) OR 
                    find_in_set(' . $pid . ',product_id)  <> 0
                )

        ');
        $discounts = $query->result();
        foreach ($discounts as $discount) {
            $html['discount'] .= '<option value="' . $discount->id . '" data-rate="' . $discount->percentage.'"';
            if($discount->id == $storediscount){
                $html['discount'] .= 'selected';
            }
            $html['discount'] .= ' >' . $discount->name . '</option>';
        }

        $this->db->select('supplier1,supplier2,supplier3,supplier4,supplier5');
        $this->db->from('sma_products');
        $this->db->where('id', $pid);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $product = $q->result()[0];
            $html['suppliers'] = '<option selected value="' . $product->supplier1 . '">' . $this->getSupplierName($product->supplier1) . '</option>';
            if ($product->supplier2 != '' && $product->supplier2 != '0') {
                $html['suppliers'] .= '<option value="' . $product->supplier2 . '">' . $this->getSupplierName($product->supplier2) . '</option>';
            }
            if ($product->supplier3 != '' && $product->supplier3 != '0') {
                $html['suppliers'] .= '<option value="' . $product->supplier3 . '">' . $this->getSupplierName($product->supplier3) . '</option>';
            }
            if ($product->supplier4 != '' && $product->supplier4 != '0') {
                $html['suppliers'] .= '<option value="' . $product->supplier4 . '">' . $this->getSupplierName($product->supplier4) . '</option>';
            }
            if ($product->supplier5 != '' && $product->supplier5 != '0') {
                $html['suppliers'] .= '<option value="' . $product->supplier5 . '">' . $this->getSupplierName($product->supplier5) . '</option>';
            }
        } else {
            $html['suppliers'] = '<option value="">Supplier Not Found</option>';
        }
        echo json_encode($html);
    }

    public function product_edit(){
        $id = $this->input->get('id');
        $store_id = $this->input->get('store_id');

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('warehouse', $this->lang->line('warehouse'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('supplier', $this->lang->line('supplier'), 'required');
        $this->session->unset_userdata('csrf_token');

        $this->data['warehouses'] = $this->site->getAllWarehouses();
        // $this->data['suppliers'] =  $this->db->from('sma_companies')->where('group_id',4)->get()->result();

        $this->data['product'] = $this->db->from('store_products_tb')->where('id', $id)->get()->row();
        $this->data['id']  = $id;
        $this->data['store_id']  = $store_id;
        
        $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => 'Edit Link Product']];
        $meta = ['page_title' => 'Edit Link Product', 'bc' => $bc];
        $this->page_construct2('store/product_edit', $meta, $this->data);
    }

    public function update_submit(){
        $sendvalue['status'] = false;
        $sendvalue['message'] = 'Update Failed';
        $id = $this->input->post('updateid');
        $sid = $this->input->post('sid');
        $title = $this->input->post('title');
        $editupdatetype = $this->input->post('updatetype');
        $pid = $this->input->post('pid');
        $spid = $this->input->post('spid');
        $stocktype = $this->input->post('stocktype');
        $pricetype = $this->input->post('pricetype');
        $discount = $this->input->post('discount');
        $warehouseid = $this->input->post('warehouse_id');
        $supplier = $this->input->post('supplier');
        $status = $this->input->post('update_status');
        $returndata = [];
        if ($id != '') {
            $setdata['update_in'] = $editupdatetype;
            $setdata['update_qty_in'] = $stocktype;
            $setdata['price_type'] = $pricetype;
            $setdata['warehouse_id'] = $warehouseid;
            $setdata['discount'] = $discount;
            $setdata['supplier_id'] = $supplier;
            $setdata['updated_at'] = date('Y-m-d_H-i:s');
            $setdata['updated_by'] = $this->session->userdata('user_id');
            $setdata['status'] = $status;
            $this->db->set($setdata);
            $this->db->where('id', $id);
            $this->db->update('store_products_tb');
            if ($status == 'active') {
                $this->db->select('*');
                $this->db->from('sma_stores_tb');
                $this->db->where('id', $sid);
                $storeq = $this->db->get();
                if ($storeq->num_rows() > 0) {
                    $store = $storeq->result()[0];
                    $productdetail = $this->calPrice($pid, $pricetype, $discount, $stocktype, $warehouseid, $store->stock_margin);
                    if ($productdetail['codestatus'] == 'ok') {
                        if ($store->types == 'Wordpress (Wocommerce)') {
                            $wodata['store_url'] = $store->store_url;
                            $wodata['wordpress_wocommerce_consumer_key'] = $store->wordpress_wocommerce_consumer_key;
                            $wodata['wordpress_wocommerce_consumer_secret'] = $store->wordpress_wocommerce_consumer_secret;
                            $wodata['product']['name'] = $title;
                            if ($editupdatetype == 'detail' || $editupdatetype == 'detailnqty' || $editupdatetype == 'detailnprice' || $editupdatetype == 'full') {
                                $wodata['product']['short_description'] = $productdetail['product_details'];
                            }
                            if ($editupdatetype == 'price' || $editupdatetype == 'priceqty' || $editupdatetype == 'detailnprice' || $editupdatetype == 'full') {
                                if ($productdetail['mrp'] == $productdetail['total']) {
                                    $wodata['product']['regular_price'] = (string) $productdetail['mrp'];
                                    $wodata['product']['sale_price'] = '';
                                } else {
                                    $wodata['product']['regular_price'] = (string) $productdetail['mrp'];
                                    $wodata['product']['sale_price'] = (string) $productdetail['total'];
                                }
                            }
                            if ($editupdatetype == 'qty' || $editupdatetype == 'priceqty' || $editupdatetype == 'detailnqty' || $editupdatetype == 'full') {
                                if ($productdetail['stock'] > 0) {
                                    $wodata['product']['stock_status'] = 'instock';
                                } else {
                                    $wodata['product']['stock_status'] = 'outofstock';
                                }
                                $wodata['product']['manage_stock'] = true;
                                $wodata['product']['stock_quantity'] = (int) $productdetail['stock'];
                            } else {
                                $wodata['product']['manage_stock'] = false;
                                $wodata['product']['stock_status'] = 'instock';
                            }
                            $returndata = $this->stores_model->updateProductDetail($wodata, $spid);
                        } elseif ($store->types == 'Daraz') {
                            $darazdata['daraz_api_key'] = $store->daraz_api_key;
                            $darazdata['daraz_username'] = $store->daraz_store_id;
                            $darazdata['quantity'] = (int) $productdetail['stock'];
                            $darazdata['store_product_sku'] = $spid;

                            $insertdata['store_product_id'] = $spid;
                            // $returndata = $this->daraz_model->get_update_product_daraz_api($darazdata);
                            $returndata = $this->darazAPI_model->updateProduct($darazdata);
                            $sendvalue['sending_data'] = $darazdata;
                            $sendvalue['returndata'] = $returndata;
                        } elseif ($store->types == 'Shopify') {
                            if ($editupdatetype == 'price' || $editupdatetype == 'priceqty' || $editupdatetype == 'detailnprice' || $editupdatetype == 'full') {
                                $returndata = $this->stores_model->shopifyPriceUpdate($pid, $warehouseid, $sid);
                            }
                            if ($editupdatetype == 'qty' || $editupdatetype == 'priceqty' || $editupdatetype == 'detailnqty' || $editupdatetype == 'full') {
                                $returndata = $this->stores_model->StoreQtyUpdate($pid, $warehouseid, $sid);
                            }
                            echo '<pre>';
                            print_r($returndata);
                            exit;
                        } else {
                            $returndata['codestatus'] = 'Invalid Store ID';
                        }

                        if ($returndata['codestatus'] == 'ok') {
                            $sendvalue['status'] = true;
                            $sendvalue['message'] = 'Store Setting and Store Qty Update Successfully';
                        } else {
                            $sendvalue['message'] = $returndata['codestatus'];
                        }
                    } else {
                        $sendvalue['message'] = 'Invalid Product';
                    }
                } else {
                    $sendvalue['message'] = 'Invalid Store';
                }
            } else {
                $sendvalue['message'] = 'Store Setting Update Successfully ';
            }
        } else {
            $sendvalue['message'] = 'Invalid ID';
        }
        $returndata = json_encode($returndata);

        $this->useractivities_model->add([
            'note' => $sendvalue['message'] . '. Store Product Title: ' . $title,
            'json_data' => $returndata,
            'location' => 'Stores->Products->Update->Submit',
            'store_id' => $sid,
            'product_id' => $pid,
            'action_by' => $this->session->userdata('user_id'),
        ]);
        echo json_encode($sendvalue);
    }

    public function product_delete(){
        $senddata['status'] = false;
        $senddata['message'] = 'Try again!';
        $id = $this->input->post('id');
        $reason = $this->input->post('reason');
        if ($reason != '') {
            $this->db->delete('store_products_tb', ['id' => $id]);
            $senddata['status'] = true;
            $senddata['message'] = 'Link product delete successfully!';
        } else {
            $senddata['message'] = 'Enter Reason!';
        }
        echo json_encode($senddata);
    }

    public function getCustomers(){
        $this->db->select('id,name');
        $this->db->from('sma_companies');
        $this->db->where('group_name', 'customer');
        $q = $this->db->get();
        return $q->result();
    }


    public function getSupplierName($id){
        $sendvalue = 'Invalid Supplier';
        $this->db->select('name');
        $this->db->from('sma_companies');
        $this->db->where('id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $result = $q->result()[0];
            $sendvalue = $result->name;
        }

        return $sendvalue;
    }

    public function countStock($pid, $wid){
        $query = 'SELECT SUM(quantity_balance) AS qty FROM sma_purchase_items WHERE product_id = ' . $pid . ' AND quantity_balance != "0.0000"';
        if ($wid != 'all' && $wid != '') {
            $query .= ' AND warehouse_id = ' . $wid;
        }

        $query = $this->db->query($query);
        $r = $query->result()[0];

        return $r->qty;
    }

    public function calDiscount($price, $discount, $product){
        $sendvalue = 0;
        if ($discount == 'd1') {
            $sendvalue = ($price / 100) * $product->discount_one;
        } elseif ($discount == 'd2') {
            $sendvalue = ($price / 100) * $product->discount_two;
        } elseif ($discount == 'd3') {
            $sendvalue = ($price / 100) * $product->discount_three;
        } elseif ($discount == 'mrp') {
            $sendvalue = ($price / 100) * $product->discount_mrp;
        } elseif ($discount == 'no') {
            $sendvalue = 0;
        } else {
            $sendvalue = ($price / 100) * $this->get_discount_rate($discount);
        }

        return $sendvalue;
    }

    public function get_discount_rate($id){
        $rate = 0;
        $this->db->select('*');
        $this->db->from('sma_bulk_discount');
        $this->db->where('id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $discount = $q->result()[0];
            $rate = $discount->percentage;
        }

        return $rate;
    }

    public function createStoreWebhook(){
        $sid = $this->input->get('id');
        $wdata = [
            'name' => 'Rhocom360OrderCreated',
            'topic' => 'order.created',
            'delivery_url' => base_url('api/salesorders/createso?sid=' . $sid),
        ];
        $setdata['webhook_id'] = $this->wp->createWebHook($sid, $wdata);
        $wdata = [
            'name' => 'Rhocom360OrderUpdated',
            'topic' => 'order.updated',
            'delivery_url' => base_url('api/stores/orderupdated?sid=' . $sid),
        ];
        $this->wp->createWebHook($sid, $wdata);
        $wdata = [
            'name' => 'Rhocom360OrderDeleted',
            'topic' => 'order.deleted',
            'delivery_url' => base_url('api/stores/orderdeleted?sid=' . $sid),
        ];
        $this->wp->createWebHook($sid, $wdata);
        $wdata = [
            'name' => 'Rhocom360OrderRestored',
            'topic' => 'order.restored',
            'delivery_url' => base_url('api/stores/orderrestored?sid=' . $sid),
        ];
        $this->wp->createWebHook($sid, $wdata);
        // $wdata = [
        //     'name' => 'Rhocom360ProductUpdated',
        //     'topic' => 'product.updated',
        //     'delivery_url' => base_url('api/stores/productupdated?sid='.$sid)
        // ];
        // $this->wp->createWebHook($sid,$wdata);
        $wdata = [
            'name' => 'Rhocom360ProductDeleted',
            'topic' => 'product.deleted',
            'delivery_url' => base_url('api/stores/productdeleted?sid=' . $sid),
        ];
        $this->wp->createWebHook($sid, $wdata);
        $wdata = [
            'name' => 'Rhocom360ProductRestored',
            'topic' => 'product.restored',
            'delivery_url' => base_url('api/stores/productrestored?sid=' . $sid),
        ];
        redirect(base_url('admin/stores'));
    }

    public function system_products_report(){
        $this->data['products'] = [];
        $sid = $this->input->get('sid');
        $gsupplier = $this->input->get('supplier');
        $this->data['gsupplier'] = $gsupplier;
        $this->db->select('*');
        $this->db->from('sma_companies');
        $this->db->where('group_name', 'supplier');
        $suq = $this->db->get();
        $this->data['suppliers'] = $suq->result();
        if ($sid != '') {
            $this->db->select('*');
            $this->db->from('sma_stores_tb');
            $this->db->where('id', $sid);
            $storeq = $this->db->get();
            if ($storeq->num_rows() > 0) {
                $store = $storeq->result()[0];
                $this->data['store'] = $store;
                if ($gsupplier != '') {
                    $this->db->select('
                        sma_products.id,
                        sma_products.name,
                        sma_products.mrp,
                        (
                            SELECT
                                SUM(quantity_balance) AS qty
                            FROM
                                sma_purchase_items
                            WHERE
                                warehouse_id = ' . $store->warehouse_id . ' AND 
                                product_id = sma_products.id AND 
                                quantity_balance > 0
                        ) as qtycount,
                        sma_brands.name as brand_name
                    ');
                    $this->db->from('sma_products');
                    $this->db->join('sma_brands', 'sma_brands.id = sma_products.brand');
                    $this->db->join('sma_stores_tb', 'sma_stores_tb.id = ' . $sid, 'left');
                    if ($gsupplier != 'all') {
                        $this->db->where('(sma_products.supplier1 = ' . $gsupplier . ' OR sma_products.supplier2 = ' . $gsupplier . ' OR sma_products.supplier3 = ' . $gsupplier . ' OR sma_products.supplier4 = ' . $gsupplier . ' OR sma_products.supplier5 = ' . $gsupplier . ') AND sma_products.status = 1');
                    } else {
                        $this->db->where('sma_products.status = 1');
                    }

                    $q = $this->db->get();
                    $products = $q->result();
                    foreach ($products as $product) {
                        $qty = $product->qtycount > 0 ? $product->qtycount : 0;
                        $this->db->select('
                            sma_store_products_tb.*,
                        ');
                        $this->db->from('sma_store_products_tb');
                        $this->db->where('sma_store_products_tb.store_id', $sid);
                        $this->db->where('sma_store_products_tb.product_id', $product->id);
                        $pq = $this->db->get();
                        if ($pq->num_rows() > 0) {
                            $sproducts = $pq->result();
                            foreach ($sproducts as $sproduct) {
                                $checkdata['pid'] = $product->id;
                                $checkdata['pname'] = $product->name;
                                $checkdata['brand_name'] = $product->brand_name;
                                $checkdata['pmrp'] = $this->sma->formatMoney2($product->mrp);
                                $checkdata['qty'] = decimalallow($qty, 0);
                                $checkdata['update_in'] = $sproduct->update_in;
                                $checkdata['update_qty_in'] = $sproduct->update_qty_in;
                                $checkdata['price_type'] = $sproduct->price_type;
                                $checkdata['discount'] = $sproduct->discount;
                                $checkdata['spid'] = $sproduct->store_product_id;
                                $checkdata['spname'] = $sproduct->product_name;
                                $checkdata['status'] = $sproduct->status;
                                $this->data['products'][] = $checkdata;
                            }
                        } else {
                            $checkdata['pid'] = $product->id;
                            $checkdata['pname'] = $product->name;
                            $checkdata['brand_name'] = $product->brand_name;
                            $checkdata['pmrp'] = $this->sma->formatMoney2($product->mrp);
                            $checkdata['qty'] = decimalallow($qty, 0);
                            $checkdata['update_in'] = '<span style="color:red;" >Not Integrated</span>';
                            $checkdata['update_qty_in'] = '<span style="color:red;" >Not Integrated</span>';
                            $checkdata['price_type'] = '<span style="color:red;" >Not Integrated</span>';
                            $checkdata['discount'] = '<span style="color:red;" >Not Integrated</span>';
                            $checkdata['spid'] = '<span style="color:red;" >Not Integrated</span>';
                            $checkdata['spname'] = '<span style="color:red;" >Not Integrated</span>';
                            $checkdata['status'] = '<span style="color:red;" >Not Integrated</span>';
                            $this->data['products'][] = $checkdata;
                        }
                    }
                }
                $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => "Rhocom's Products Intergration - " . $store->name]];
                $meta = ['page_title' => "Rhocom's Products Intergration - " . $store->name, 'bc' => $bc];
                $this->page_construct2('store/system_products_report', $meta, $this->data);
            } else {
                redirect(base_url('admin/stores'));
            }
        } else {
            redirect(base_url('admin/stores'));
        }
    }
    public function store_products_report(){
        $this->load->model('admin/stores_model');
        $this->data['products'] = [];
        $sid = $this->input->get('sid');
        $update = $this->input->get('update');
        if ($sid != '') {
            $this->db->select('*');
            $this->db->from('sma_stores_tb');
            $this->db->where('id', $sid);
            $storeq = $this->db->get();
            if ($storeq->num_rows() > 0) {
                $store = $storeq->result()[0];
                $this->data['store'] = $store;
                $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => 'Verification Report - ' . $store->name]];
                $meta = ['page_title' => 'Verification Report - ' . $store->name, 'bc' => $bc];
                $this->page_construct2('store/store_products_report', $meta, $this->data);
            } else {
                redirect(base_url('admin/stores'));
            }
        } else {
            redirect(base_url('admin/stores'));
        }
    }

    public function store_products_report_ajax(){
        $sendvalue['codestatus'] = 'no';
        $sendvalue['count'] = 0;
        $sendvalue['products'] = [];
        $limit = $this->input->get('limit');
        $page = $this->input->get('page');
        $last = $this->input->get('last');
        $sid = $this->input->get('sid');
        if ($sid != '') {
            $this->db->select('*');
            $this->db->from('sma_stores_tb');
            $this->db->where('id', $sid);
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                $store = $q->result()[0];

                if ($store->types == 'Wordpress (Wocommerce)') {
                    $rows = $this->wp->getproducts($store, $page, $limit);
                    $sendvalue['wp_message'] = $rows['message'];
                    if ($rows['codestatus']) {
                        $sendvalue['count'] = count($rows['products']);
                        foreach ($rows['products'] as $p) {
                            $this->db->select('
                                sma_store_products_tb.product_id,
                                sma_store_products_tb.update_qty_in,
                                sma_store_products_tb.update_in,
                                sma_store_products_tb.price_type,
                                sma_store_products_tb.discount,
                                sma_store_products_tb.warehouse_id,
                                sma_store_products_tb.status as istatus,
                                sma_warehouses_products.quantity AS wqty, 
                                (
                                    SELECT SUM(quantity_balance) FROM sma_purchase_items WHERE product_id = sma_products.id AND  warehouse_id = sma_store_products_tb.warehouse_id
                                ) AS quantity,
                                sma_products.mrp,
                                sma_products.name,
                                sma_products.pack_size,
                                sma_products.carton_size,
                                sma_products.status
                            ');
                            $this->db->from('sma_store_products_tb');
                            $this->db->join('sma_warehouses_products', 'sma_warehouses_products.product_id  = sma_store_products_tb.product_id AND sma_warehouses_products.warehouse_id = sma_store_products_tb.warehouse_id', 'left');
                            $this->db->join('sma_products', 'sma_products.id = sma_store_products_tb.product_id', 'left');
                            $this->db->where('sma_store_products_tb.store_id', $sid);
                            $this->db->where('sma_store_products_tb.store_product_id', $p->id);
                            $q = $this->db->get();

                            if ($q->num_rows() > 0) {
                                $detail = $q->result()[0];
                                $price_detail = $this->calPrice($detail->product_id, $detail->price_type, $detail->discount, $detail->update_qty_in, $detail->warehouse_id);

                                $item['store_product_id'] = $p->id;
                                $item['rhocom_pid'] = $detail->product_id;
                                $item['rhocom_name'] = $detail->name;
                                $item['name'] = $p->name;
                                $item['update_in'] = $detail->price_type;
                                $item['type'] = $detail->update_in;
                                if ($detail->update_qty_in == 'carton') {
                                    $item['rhocom_mrp'] = $detail->mrp * $detail->carton_size;
                                } elseif ($detail->update_qty_in == 'pack') {
                                    $item['rhocom_mrp'] = $detail->mrp * $detail->pack_size;
                                } else {
                                    $item['rhocom_mrp'] = $detail->mrp;
                                }
                                $item['regular_price'] = $p->regular_price;
                                $mrp_status = 'False';
                                if ($item['rhocom_mrp'] == $item['regular_price']) {
                                    $mrp_status = 'True';
                                } elseif ($detail->update_in == 'qty' || $detail->update_in == 'detail' || $detail->update_in == 'detailnqty') {
                                    $mrp_status = 'True';
                                }
                                $item['mrp_status'] = $mrp_status;
                                $item['rhocom_selling_price'] = $price_detail['total'];
                                $item['selling_price'] = $p->sale_price;
                                $selling_status = 'False';
                                if ($price_detail['total'] == $p->sale_price) {
                                    $selling_status = 'True';
                                } elseif ($p->sale_price == '') {
                                    $selling_status = 'True';
                                } elseif ($detail->update_in == 'qty' || $detail->update_in == 'detail' || $detail->update_in == 'detailnqty') {
                                    $selling_status = 'True';
                                } else {
                                    $this->load->model('admin/stores_model');
                                    $this->stores_model->UpdatePrice($detail->product_id, $detail->warehouse_id, $sid, 'Check Report in Store');
                                }
                                $item['selling_status'] = $selling_status;
                                if ($detail->quantity == '') {
                                    $rhocom_qty = 0;
                                } else {
                                    $rhocom_qty = $detail->quantity;
                                }
                                if ($detail->update_qty_in == 'pack') {
                                    $rhocom_qty = $detail->quantity / $detail->pack_size;
                                } elseif ($detail->update_qty_in == 'carton') {
                                    $rhocom_qty = $detail->quantity / $detail->carton_size;
                                }
                                $item['update_in_qty'] = $detail->update_qty_in;
                                if ($detail->update_qty_in == 'pack') {
                                    $item['update_in_qty'] .= ' (' . $detail->pack_size . ')';
                                } elseif ($detail->update_qty_in == 'carton') {
                                    $item['update_in_qty'] .= ' (' . $detail->carton_size . ')';
                                }

                                $item['rhocom_qty'] = (int) $rhocom_qty;
                                $store_hold_qty = $this->stores_model->getPendingItemsInSO($detail->warehouse_id, $detail->product_id);
                                if ($detail->update_qty_in == 'pack') {
                                    $store_hold_qty = $store_hold_qty / $detail->pack_size;
                                } elseif ($detail->update_qty_in == 'carton') {
                                    $store_hold_qty = $store_hold_qty / $detail->carton_size;
                                }

                                $item['store_hold_qty'] = decimalallow($store_hold_qty, 2);
                                $item['so'] = $this->so_list($detail->product_id, $detail->warehouse_id);
                                // $item['store_hold_qty'] = 0;
                                $actualqty = $rhocom_qty - $store_hold_qty;
                                if ($actualqty < 0) {
                                    $actualqty = 0;
                                }
                                $actualqty = $actualqty / 100 * $store->stock_margin;
                                $item['actual_qty'] = (int) $actualqty;
                                $item['store_qty'] = $p->stock_quantity;
                                if ($item['actual_qty'] == $p->stock_quantity) {
                                    $item['stockstatus'] = 'True';
                                } else {
                                    if ($item['type'] == 'qty' || $item['type'] == 'priceqty' || $item['type'] == 'detailnqty' || $item['type'] == 'full') {
                                        $item['stockstatus'] = 'False';
                                        $this->load->model('admin/stores_model');
                                        $this->stores_model->updateStoreQty($detail->product_id, $detail->warehouse_id, $sid, 'Check Report in Store');
                                    } else {
                                        $item['stockstatus'] = 'True';
                                    }
                                }
                                if ($detail->status == 0) {
                                    $item['rhocomstatus'] = 'Deactivate';
                                } else {
                                    $item['rhocomstatus'] = 'Active';
                                }
                                $item['integrationstatus'] = $detail->istatus;
                                $item['storestatus'] = $p->status;
                                $sendvalue['products'][] = $item;
                            } else {
                                $item['store_product_id'] = $p->id;
                                $item['rhocom_pid'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_name'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['name'] = $p->name;
                                $item['update_in'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['type'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_mrp'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['regular_price'] = $p->regular_price;
                                $item['mrp_status'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_selling_price'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['selling_price'] = $p->sale_price;
                                $item['selling_status'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['update_in_qty'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_qty'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['store_hold_qty'] = 0;
                                $item['actual_qty'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['store_qty'] = $p->stock_quantity;
                                $item['stockstatus'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['integrationstatus'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['storestatus'] = $p->status;
                                $item['rhocomstatus'] = '<span style="color:red;" >Not Integrated</span>';
                                $sendvalue['products'][] = $item;
                            }
                        }
                        $sendvalue['last_id'] = '0';
                        $sendvalue['codestatus'] = 'ok';
                    } else {
                        $sendvalue['codestatus'] = $rows['codestatus'];
                    }
                } elseif ($store->types == 'Shopify') {
                    $this->load->model('admin/shopify_model', 'shopify');
                    $rows = $this->shopify->getproducts($store, $last, $limit);
                    if ($rows['codestatus']) {
                        $sendvalue['count'] = count($rows['products']);
                        $sendvalue['last_id'] = '0';
                        foreach ($rows['products'] as $p) {
                            $this->db->select('
                                sma_store_products_tb.product_id,
                                sma_store_products_tb.update_qty_in,
                                sma_store_products_tb.update_in,
                                sma_store_products_tb.price_type,
                                sma_store_products_tb.discount,
                                sma_store_products_tb.warehouse_id,
                                sma_store_products_tb.status as istatus,
                                sma_warehouses_products.quantity AS wqty, 
                                (
                                    SELECT SUM(quantity_balance) FROM sma_purchase_items WHERE product_id = sma_products.id AND  warehouse_id = sma_store_products_tb.warehouse_id
                                ) AS quantity,
                                sma_products.code,
                                sma_products.mrp,
                                sma_products.name,
                                sma_products.pack_size,
                                sma_products.carton_size,
                                sma_products.status
                            ');
                            $this->db->from('sma_store_products_tb');
                            $this->db->join('sma_warehouses_products', 'sma_warehouses_products.product_id  = sma_store_products_tb.product_id AND sma_warehouses_products.warehouse_id = sma_store_products_tb.warehouse_id', 'left');
                            $this->db->join('sma_products', 'sma_products.id = sma_store_products_tb.product_id', 'left');
                            $this->db->where('sma_store_products_tb.store_id', $sid);
                            $this->db->where('sma_store_products_tb.store_product_id', $p->id);
                            $q = $this->db->get();

                            if ($q->num_rows() > 0) {
                                $detail = $q->result()[0];
                                $price_detail = $this->calPrice($detail->product_id, $detail->price_type, $detail->discount, $detail->update_qty_in, $detail->warehouse_id);
                                $sendvalue['last_id'] = $p->id;

                                $item['store_product_id'] = $p->id;
                                $item['rhocom_pid'] = $detail->product_id;
                                $item['rhocom_name'] = $detail->name;
                                $item['name'] = $p->title;
                                $item['store_sku'] = $p->variants[0]->sku;
                                $item['rhocom_barcode'] = $detail->code;
                                $item['update_in'] = $detail->price_type;
                                $item['type'] = $detail->update_in;
                                if ($detail->update_qty_in == 'carton') {
                                    $item['rhocom_mrp'] = $detail->mrp * $detail->carton_size;
                                } elseif ($detail->update_qty_in == 'pack') {
                                    $item['rhocom_mrp'] = $detail->mrp * $detail->pack_size;
                                } else {
                                    $item['rhocom_mrp'] = $detail->mrp;
                                }
                                $item['regular_price'] = $p->variants[0]->compare_at_price;
                                $mrp_status = 'False';
                                if ($item['rhocom_mrp'] == $item['regular_price']) {
                                    $mrp_status = 'True';
                                } elseif ($p->variants[0]->compare_at_price == '') {
                                    $selling_status = 'True';
                                } elseif ($detail->update_in == 'qty' || $detail->update_in == 'detail' || $detail->update_in == 'detailnqty') {
                                    $mrp_status = 'True';
                                }
                                $item['mrp_status'] = $mrp_status;
                                $item['rhocom_selling_price'] = $price_detail['total'];
                                $item['selling_price'] = $p->variants[0]->price;
                                $selling_status = 'False';
                                if ($price_detail['total'] == $p->variants[0]->price) {
                                    $selling_status = 'True';
                                } elseif ($detail->update_in == 'qty' || $detail->update_in == 'detail' || $detail->update_in == 'detailnqty') {
                                    $selling_status = 'True';
                                } else {
                                    $this->load->model('admin/stores_model');
                                    $this->stores_model->UpdatePrice($detail->product_id, $detail->warehouse_id, $sid, 'Check Report in Store');
                                }
                                $item['selling_status'] = $selling_status;
                                if ($detail->quantity == '') {
                                    $rhocom_qty = 0;
                                } else {
                                    $rhocom_qty = $detail->quantity;
                                }
                                if ($detail->update_qty_in == 'pack') {
                                    $rhocom_qty = $detail->quantity / $detail->pack_size;
                                } elseif ($detail->update_qty_in == 'carton') {
                                    $rhocom_qty = $detail->quantity / $detail->carton_size;
                                }
                                $item['update_in_qty'] = $detail->update_qty_in;
                                if ($detail->update_qty_in == 'pack') {
                                    $item['update_in_qty'] .= ' (' . $detail->pack_size . ')';
                                } elseif ($detail->update_qty_in == 'carton') {
                                    $item['update_in_qty'] .= ' (' . $detail->carton_size . ')';
                                }

                                $item['rhocom_qty'] = (int) $rhocom_qty;
                                $store_hold_qty = $this->stores_model->getPendingItemsInSO($detail->warehouse_id, $detail->product_id);
                                if ($detail->update_qty_in == 'pack') {
                                    $store_hold_qty = $store_hold_qty / $detail->pack_size;
                                } elseif ($detail->update_qty_in == 'carton') {
                                    $store_hold_qty = $store_hold_qty / $detail->carton_size;
                                }

                                $item['store_hold_qty'] = decimalallow($store_hold_qty, 2);
                                $item['so'] = $this->so_list($detail->product_id, $detail->warehouse_id);
                                // $item['store_hold_qty'] = 0;
                                $actualqty = $rhocom_qty - $store_hold_qty;
                                if ($actualqty < 0) {
                                    $actualqty = 0;
                                }
                                $actualqty = $actualqty / 100 * $store->stock_margin;
                                $item['actual_qty'] = (int) $actualqty;
                                $item['store_qty'] = $p->variants[0]->inventory_quantity;
                                if ($item['actual_qty'] == $p->variants[0]->inventory_quantity) {
                                    $item['stockstatus'] = 'True';
                                } else {
                                    if ($item['type'] == 'qty' || $item['type'] == 'priceqty' || $item['type'] == 'detailnqty' || $item['type'] == 'full') {
                                        $item['stockstatus'] = 'False';
                                        $this->load->model('admin/stores_model');
                                        $this->stores_model->updateStoreQty($detail->product_id, $detail->warehouse_id, $sid, 'Check Report in Store');
                                    } else {
                                        $item['stockstatus'] = 'True';
                                    }
                                }
                                if ($detail->status == 0) {
                                    $item['rhocomstatus'] = 'Deactivate';
                                } else {
                                    $item['rhocomstatus'] = 'Active';
                                }
                                $item['integrationstatus'] = $detail->istatus;
                                $item['storestatus'] = $p->status;
                                $sendvalue['products'][] = $item;
                            } else {
                                $item['store_product_id'] = $p->id;
                                $item['rhocom_pid'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_name'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['name'] = $p->title;
                                $item['store_sku'] = $p->variants[0]->sku;
                                $item['rhocom_barcode'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['update_in'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['type'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_mrp'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['regular_price'] = $p->variants[0]->compare_at_price;
                                $item['mrp_status'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_selling_price'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['selling_price'] = $p->variants[0]->price;
                                $item['selling_status'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['update_in_qty'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_qty'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['store_hold_qty'] = 0;
                                $item['actual_qty'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['store_qty'] = $p->variants[0]->inventory_quantity;
                                $item['stockstatus'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['integrationstatus'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['storestatus'] = $p->status;
                                $item['rhocomstatus'] = '<span style="color:red;" >Not Integrated</span>';
                                $sendvalue['products'][] = $item;
                            }
                        }
                        $sendvalue['codestatus'] = 'ok';
                    }
                } else {
                    $rows = $this->daraz_model->getproducts($store, $page, $limit);
                    $rowsdata = $rows['darazproducts'];
                    if ($rows['codestatus']) {
                        $sendvalue['count'] = count($rowsdata['Products']);
                        foreach ($rowsdata['Products'] as $p) {
                            $this->db->select('
                                sma_store_products_tb.product_id,
                                sma_store_products_tb.update_qty_in,
                                sma_store_products_tb.warehouse_id,
                                sma_store_products_tb.update_in,
                                sma_store_products_tb.price_type,
                                sma_store_products_tb.discount,
                                sma_warehouses_products.quantity AS wqty, 
                                (
                                    SELECT SUM(quantity_balance) FROM sma_purchase_items WHERE product_id = sma_products.id AND  warehouse_id = sma_store_products_tb.warehouse_id
                                ) AS quantity,
                                sma_products.mrp,
                                sma_products.name,
                                sma_products.pack_size,
                                sma_products.carton_size,
                                sma_store_products_tb.status as istatus,
                                sma_products.status
                            ');
                            $this->db->from('sma_store_products_tb');
                            $this->db->join('sma_warehouses_products', 'sma_warehouses_products.product_id  = sma_store_products_tb.product_id AND sma_warehouses_products.warehouse_id = sma_store_products_tb.warehouse_id', 'left');
                            $this->db->join('sma_products', 'sma_products.id = sma_store_products_tb.product_id', 'left');
                            $this->db->where('sma_store_products_tb.store_id', $sid);
                            $this->db->where('sma_store_products_tb.store_product_id', $p['Skus'][0]['SellerSku']);
                            $q = $this->db->get();

                            if ($q->num_rows() > 0) {
                                $detail = $q->result()[0];

                                $price_detail = $this->calPrice($detail->product_id, $detail->price_type, $detail->discount, $detail->update_qty_in, $detail->warehouse_id);

                                $item['store_product_id'] = $p['Skus'][0]['SellerSku'];
                                $item['rhocom_pid'] = $detail->product_id;
                                $item['rhocom_name'] = $detail->name;
                                $item['name'] = $p['Attributes']['name'];
                                $item['update_in'] = $detail->price_type;
                                $item['type'] = $detail->update_in;
                                if ($detail->update_qty_in == 'carton') {
                                    $item['rhocom_mrp'] = $detail->mrp * $detail->carton_size;
                                } elseif ($detail->update_qty_in == 'pack') {
                                    $item['rhocom_mrp'] = $detail->mrp * $detail->pack_size;
                                } else {
                                    $item['rhocom_mrp'] = $detail->mrp;
                                }
                                $item['regular_price'] = $p['Skus'][0]['price'];
                                $mrp_status = 'False';
                                if ($item['rhocom_mrp'] == $item['regular_price']) {
                                    $mrp_status = 'True';
                                }
                                $item['mrp_status'] = $mrp_status;
                                $item['rhocom_selling_price'] = $price_detail['total'];
                                $item['selling_price'] = $p['Skus'][0]['special_price'];
                                $selling_status = 'False';
                                if ($price_detail['total'] == $p['Skus'][0]['special_price']) {
                                    $selling_status = 'True';
                                } elseif ($p['Skus'][0]['special_price'] == '') {
                                    $selling_status = 'True';
                                }
                                $item['selling_status'] = $selling_status;
                                if ($detail->quantity == '') {
                                    $rhocom_qty = 0;
                                } else {
                                    $rhocom_qty = $detail->quantity;
                                }
                                if ($detail->update_qty_in == 'pack') {
                                    $rhocom_qty = $detail->quantity / $detail->pack_size;
                                } elseif ($detail->update_qty_in == 'carton') {
                                    $rhocom_qty = $detail->quantity / $detail->carton_size;
                                }
                                $item['update_in_qty'] = $detail->update_qty_in;
                                if ($detail->update_qty_in == 'pack') {
                                    $item['update_in_qty'] .= ' (' . $detail->pack_size . ')';
                                } elseif ($detail->update_qty_in == 'carton') {
                                    $item['update_in_qty'] .= ' (' . $detail->carton_size . ')';
                                }

                                $item['rhocom_qty'] = (int) $rhocom_qty;
                                $store_hold_qty = $this->stores_model->getPendingItemsInSO($detail->warehouse_id, $detail->product_id);
                                if ($detail->update_qty_in == 'pack') {
                                    $store_hold_qty = $store_hold_qty / $detail->pack_size;
                                } elseif ($detail->update_qty_in == 'carton') {
                                    $store_hold_qty = $store_hold_qty / $detail->carton_size;
                                }

                                $item['store_hold_qty'] = decimalallow($store_hold_qty, 2);
                                $item['so'] = $this->so_list($detail->product_id, $detail->warehouse_id);
                                // $item['store_hold_qty'] = 0;
                                $actualqty = $rhocom_qty - $store_hold_qty;
                                if ($actualqty < 0) {
                                    $actualqty = 0;
                                }
                                $actualqty = $actualqty / 100 * $store->stock_margin;
                                $item['actual_qty'] = (int) $actualqty;
                                $item['store_qty'] = $p['Skus'][0]['quantity'];
                                if ($item['actual_qty'] == $p['Skus'][0]['quantity']) {
                                    $item['stockstatus'] = 'True';
                                } else {
                                    if ($item['type'] == 'qty' || $item['type'] == 'priceqty' || $item['type'] == 'detailnqty' || $item['type'] == 'full') {
                                        $item['stockstatus'] = 'False';
                                    } else {
                                        $item['stockstatus'] = 'True';
                                    }
                                }
                                if ($detail->status == 0) {
                                    $item['rhocomstatus'] = 'Deactivate';
                                } else {
                                    $item['rhocomstatus'] = 'Active';
                                }
                                $item['integrationstatus'] = $detail->istatus;
                                $item['storestatus'] = $p['Skus'][0]['Status'];
                                $sendvalue['products'][] = $item;
                            } else {
                                $item['store_product_id'] = $p['Skus'][0]['SellerSku'];
                                $item['rhocom_pid'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_name'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['name'] = $p['Attributes']['name'];
                                $item['update_in'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['type'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_mrp'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['regular_price'] = $p['Skus'][0]['price'];
                                $item['mrp_status'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_selling_price'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['selling_price'] = $p['Skus'][0]['special_price'];
                                $item['selling_status'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['update_in_qty'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['rhocom_qty'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['store_hold_qty'] = 0;
                                $item['actual_qty'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['store_qty'] = $p['Skus'][0]['quantity'];
                                $item['stockstatus'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['integrationstatus'] = '<span style="color:red;" >Not Integrated</span>';
                                $item['storestatus'] = $p['Skus'][0]['Status'];
                                $item['rhocomstatus'] = '<span style="color:red;" >Not Integrated</span>';
                                $sendvalue['products'][] = $item;
                            }
                        }
                        $sendvalue['last_id'] = '0';
                        $sendvalue['codestatus'] = 'ok';
                    } else {
                        $sendvalue['codestatus'] = $rows['codestatus'];
                    }
                }
            } else {
                $sendvalue['codestatus'] = 'Invalid Store';
            }
        } else {
            $sendvalue['codestatus'] = 'Invalid Store';
        }
        echo json_encode($sendvalue);
    }

    public function daraaz_products_report(){
        $this->load->model('admin/stores_model');
        $this->data['products'] = [];
        $sid = $this->input->get('sid');
        $update = $this->input->get('update');
        if ($sid != '') {
            $this->db->select('*');
            $this->db->from('sma_stores_tb');
            $this->db->where('id', $sid);
            $storeq = $this->db->get();
            if ($storeq->num_rows() > 0) {
                $store = $storeq->result()[0];
                $this->data['store'] = $store;
                $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => 'Daraz Occupy Stock Products - ' . $store->name]];
                $meta = ['page_title' => 'Daraz Occupy Stock Products - ' . $store->name, 'bc' => $bc];
                $this->page_construct2('store/daraaz_products_report', $meta, $this->data);
            } else {
                redirect(base_url('admin/stores'));
            }
        } else {
            redirect(base_url('admin/stores'));
        }
    }

    public function daraaz_products_report_ajax(){
        $sendvalue['codestatus'] = 'no';
        $sendvalue['count'] = 0;
        $sendvalue['products'] = [];
        $limit = $this->input->get('limit');
        $page = $this->input->get('page');
        $sid = $this->input->get('sid');
        if ($sid != '') {
            $this->db->select('*');
            $this->db->from('sma_stores_tb');
            $this->db->where('id', $sid);
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                $store = $q->result()[0];
                if ($store->types == 'Wordpress (Wocommerce)') {
                    $sendvalue['codestatus'] = 'This report only for daraz';
                } else {
                    $rows = $this->daraz_model->getproducts($store, $page, $limit);
                    $rowsdata = $rows['darazproducts'];

                    if ($rows['codestatus']) {
                        $sendvalue['count'] = count($rowsdata['Products']);
                        foreach ($rowsdata['Products'] as $p) {
                            $this->db->select('
                                sma_store_products_tb.product_id,
                                sma_store_products_tb.update_qty_in,
                                sma_store_products_tb.warehouse_id,
                                sma_store_products_tb.update_in,
                                sma_store_products_tb.price_type,
                                sma_store_products_tb.discount,
                                sma_warehouses_products.quantity AS wqty, 
                                (
                                    SELECT SUM(quantity_balance) FROM sma_purchase_items WHERE product_id = sma_products.id AND  warehouse_id = sma_store_products_tb.warehouse_id
                                ) AS quantity,
                                sma_products.mrp,
                                sma_products.name,
                                sma_products.pack_size,
                                sma_products.carton_size,
                                sma_store_products_tb.status as istatus,
                                sma_products.status
                            ');
                            $this->db->from('sma_store_products_tb');
                            $this->db->join('sma_warehouses_products', 'sma_warehouses_products.product_id  = sma_store_products_tb.product_id AND sma_warehouses_products.warehouse_id = sma_store_products_tb.warehouse_id', 'left');
                            $this->db->join('sma_products', 'sma_products.id = sma_store_products_tb.product_id', 'left');
                            $this->db->where('sma_store_products_tb.store_id', $sid);
                            $this->db->where('sma_store_products_tb.store_product_id', $p['Skus'][0]['SellerSku']);
                            $q = $this->db->get();

                            $item['store_product_id'] = $p['Skus'][0]['SellerSku'];
                            $item['name'] = $p['Attributes']['name'];
                            $item['occupystock'] = 0;

                            $stock = $p['Skus'][0]['multiWarehouseInventories'][0];
                            $stock = preg_split("/\,/", $stock);
                            foreach ($stock as $row) {
                                $row = str_replace('{', '', $row);
                                $row = str_replace('}', '', $row);
                                $row = str_replace(' ', '', $row);
                                $row = preg_split("/\=/", $row);
                                if ($row[0] == 'occupyQuantity') {
                                    if (isset($row[1])) {
                                        $item['occupystock'] = $row[1];
                                    }
                                    break;
                                }
                            }

                            if ($item['occupystock'] > 0) {
                                if ($q->num_rows() > 0) {
                                    $detail = $q->result()[0];
                                    $price_detail = $this->calPrice($detail->product_id, $detail->price_type, $detail->discount, $detail->update_qty_in, $detail->warehouse_id);
                                    $item['rhocom_pid'] = $detail->product_id;
                                    $item['rhocom_name'] = $detail->name;

                                    $rhocom_qty = 0;
                                    if ($detail->quantity != '') {
                                        $rhocom_qty = $detail->quantity;
                                    }
                                    if ($detail->update_qty_in == 'pack') {
                                        $rhocom_qty = $detail->quantity / $detail->pack_size;
                                    } elseif ($detail->update_qty_in == 'carton') {
                                        $rhocom_qty = $detail->quantity / $detail->carton_size;
                                    }
                                    $rhocom_qty = (int) $rhocom_qty;
                                    $store_hold_qty = $this->stores_model->getPendingItemsInSO($detail->warehouse_id, $detail->product_id);
                                    if ($detail->update_qty_in == 'pack') {
                                        $store_hold_qty = $store_hold_qty / $detail->pack_size;
                                    } elseif ($detail->update_qty_in == 'carton') {
                                        $store_hold_qty = $store_hold_qty / $detail->carton_size;
                                    }
                                    $store_hold_qty = (int) $store_hold_qty;
                                    $actualqty = $rhocom_qty - $store_hold_qty;
                                    if ($actualqty < 0) {
                                        $actualqty = 0;
                                    }
                                    $actualqty = $actualqty / 100 * $store->stock_margin;
                                    $item['actualqty'] = (int) $actualqty;

                                    if ($actualqty < $item['occupystock']) {
                                        $item['note'] = 'Rhocom Quantity less than Daraz Occupy Stock';
                                    } else {
                                        $item['note'] = '';
                                    }

                                    $sendvalue['products'][] = $item;
                                } else {
                                    $item['rhocom_pid'] = '<span style="color:red;" >Not Integrated</span>';
                                    $item['rhocom_name'] = '<span style="color:red;" >Not Integrated</span>';
                                    $item['note'] = '<span style="color:red;" >Not Integrated</span>';
                                    $item['actualqty'] = '<span style="color:red;" >Not Integrated</span>';
                                    $sendvalue['products'][] = $item;
                                }
                            }
                        }
                        $sendvalue['codestatus'] = 'ok';
                    } else {
                        $sendvalue['codestatus'] = $rows['codestatus'];
                    }
                }
            } else {
                $sendvalue['codestatus'] = 'Invalid Store';
            }
        } else {
            $sendvalue['codestatus'] = 'Invalid Store';
        }
        echo json_encode($sendvalue);
    }

    public function so_list($pid, $wid){
        $this->db->select('customer_id');
        $this->db->from('sma_stores_tb');
        $this->db->group_by('customer_id');
        $cidq = $this->db->get();
        $cids = $cidq->result();
        $sno = 0;
        $where = '';
        foreach ($cids as $cid) {
            if ($cid->customer_id != 0) {
                ++$sno;
                if ($sno == 1) {
                    $where .= 'sma_sales_orders_tb.customer_id = ' . $cid->customer_id;
                } else {
                    $where .= ' OR sma_sales_orders_tb.customer_id = ' . $cid->customer_id;
                }
            }
        }

        $this->db->select('
            sma_sales_orders_tb.id AS so_id,
            sma_sales_orders_tb.ref_no,
            sma_companies.name AS company,
            sma_sales_order_items.quantity AS quantity,
            (
                SELECT 
                    IFNULL(SUM(sma_sales_order_complete_items.quantity),0)
                FROM sma_sales_order_complete_items
                WHERE sma_sales_order_complete_items.soi_id = sma_sales_order_items.id
            ) AS complete_qty
       ');
        $this->db->from('sma_sales_orders_tb');
        $this->db->join('sma_sales_order_items', 'sma_sales_order_items.so_id = sma_sales_orders_tb.id', 'left');
        $this->db->join('sma_companies', 'sma_companies.id = sma_sales_orders_tb.customer_id', 'left');
        if ($where != '') {
            $this->db->where('(' . $where . ')');
        }
        $this->db->where('sma_sales_orders_tb.warehouse_id', $wid);
        $this->db->where('sma_sales_order_items.product_id', $pid);
        $this->db->where('sma_sales_order_items.status = "pending"');
        $this->db->where('(sma_sales_orders_tb.status != "cancel" AND sma_sales_orders_tb.status != "close")');
        $this->db->having('quantity != complete_qty');
        $q = $this->db->get();
        $lists = json_encode($q->result());

        return $lists;
    }
    // Old
    public function orders()
    {
        $id = $this->input->get('id');
        $last = $this->input->get('last');
        $store_q = $this->db->from('sma_stores_tb')->where('id', $id)->get();
        if ($store_q->num_rows() > 0) {
            $store = $store_q->row();
            if ($store->types == 'Shopify') {
                $this->load->model('admin/shopify_model', 'shopify');
                $status = 'any';
                $rows = $this->shopify->getOrders($store, $last, 10, $status);
                $this->data['rows'] = $rows;
                $this->data['store'] = $store;
                // echo '<pre>';
                // print_r($rows);
                $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => 'Verification Report - ' . $store->name]];
                $meta = ['page_title' => 'Verification Report - ' . $store->name, 'bc' => $bc];
                $this->page_construct2('store/orders', $meta, $this->data);
            } else {
                redirect(base_url('admin/stores'));
            }
        } else {
            redirect(base_url('admin/stores'));
        }
    }
    public function order()
    {
        $sid = $this->input->get('sid');
        $code = $this->input->get('code');
        $last = $this->input->get('last');
        $store_q = $this->db->from('sma_stores_tb')->where('id', $sid)->get();
        if ($store_q->num_rows() > 0) {
            $store = $store_q->row();
            if ($store->types == 'Shopify') {
                $this->load->model('admin/shopify_model', 'shopify');
                $status = 'any';
                $this->data['order_code'] = $code;
                $orderdata = $this->shopify->getOrder($store, $code);
                if ($orderdata['codestatus']) {
                    $this->data['order'] = $orderdata['order'];
                    $this->data['store'] = $store;
                    // echo '<pre>';
                    // print_r($orderdata);
                    // exit();
                    $bc = [['link' => base_url(), 'page' => lang('home')], ['link' => '#', 'page' => 'Verification Report - ' . $store->name]];
                    $meta = ['page_title' => 'Verification Report - ' . $store->name, 'bc' => $bc];
                    $this->page_construct2('store/order', $meta, $this->data);
                } else {
                    redirect(base_url('admin/stores/orders?id=' . $sid));
                }
            } else {
                redirect(base_url('admin/stores'));
            }
        } else {
            redirect(base_url('admin/stores'));
        }
    }
    public function get_products_for_sale()
    {
        $rows = [];
        $warehosue_id = $this->input->get('warehosue_id');
        $items = $this->input->get('items');
        $items = json_decode($items);
        foreach ($items as $item) {
            $this->db->select('
                products.id,
                products.code,
                products.company_code,
                products.name,
                products.cost,
                products.price,
                products.carton_size,
                products.formulas,
                products.mrp,
                products.alert_quantity,
                COALESCE((
                    SELECT SUM(sma_purchase_items.quantity_balance) FROM sma_purchase_items WHERE sma_purchase_items.product_id = products.id AND sma_purchase_items.warehouse_id = ' . $warehosue_id . '
                ),0) as balance_qty,
                products.fed_tax,
                products.tax_method,
                tax_rates.name as tax_name,
                tax_rates.rate as tax_rate,
                tax_rates.type as tax_type,
                0 as product_tax,
                ' . $item->quantity . ' as quantity,
                "" as batch,
                "" as expiry
            ');
            $this->db->from('products as products');
            $this->db->join('tax_rates', 'tax_rates.id = products.tax_rate', 'left');
            $this->db->join('sma_store_products_tb', 'sma_store_products_tb.product_id = products.id', 'left');
            $this->db->where('sma_store_products_tb.store_product_id', $item->id);
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                $products = $q->result()[0];
                if ($products->tax_type == 1) {
                    $products->product_tax = amountformate(($products->cost / 100) * $products->tax_rate);
                } else {
                    $products->product_tax = amountformate($products->tax_rate);
                }
            }
            $rows[] = $products;
        }
        if (count($rows) == 0) {
            $sendvalue['message'] = 'Item not found';
            $sendvalue['codestatus'] = false;
        } else {
            $sendvalue['codestatus'] = true;
        }
        $sendvalue['products'] = $rows;
        echo json_encode($sendvalue);
    }
}
