<?php defined('BASEPATH') or exit('No direct script access allowed');
class Salesorders extends CI_Controller{

    public function createso2(){
        echo 'working2';
    }
    public function createso(){
        $soid = 0;
        $storeid =  $this->input->get('sid');
        $activitynote = "";
        try {
            header('Content-Type: application/json');
            $request = file_get_contents('php://input');
            $req_dump = print_r($request, true);
            $orderdata = json_decode($req_dump);
            if(isset($orderdata->line_items)){
                $items = $orderdata->line_items;
                $separateItemsBySupplier = $this->separateItemsBySupplier($items,$storeid);
                if($separateItemsBySupplier){
                    $sos = array();
                    $sno = 0;
                    foreach($separateItemsBySupplier as $key => $items){
                        $sno++;
                        $supplier_id = 0;
                        $warehouse_id = 0;
                        $customer_id = 0;
                        $soitems = array();
                        foreach($items as $item){
                            $itemsdetail['product_id'] = $item['product_id'];
                            $supplier_id = $item['supplier_id'];
                            $warehouse_id = $item['warehouse_id'];
                            $customer_id = $item['customer_id'];
                            $salenote = 'This SO create from '.$item['store_name'];
                            $itemsdetail['supplier_id'] = $item['supplier_id'];
                            $itemsdetail['warehouse_id'] = $item['warehouse_id'];
                            if($item['update_qty_in'] == 'carton'){
                                $itemsdetail['quantity'] = $item['quantity']*$item['carton_size'];
                            }
                            else if($item['update_qty_in'] == 'pack'){
                                $itemsdetail['quantity'] = $item['quantity']*$item['pack_size'];
                            }
                            else{
                                $itemsdetail['quantity'] = $item['quantity'];
                            }
                            $itemsdetail['unit'] = 'Pieces';
                            $itemsdetail['unit_code'] = 'pcs';
                            $itemsdetail['status'] = 'pending';
                            $soitems[] = $itemsdetail;
                        }
                        if(isset($orderdata->id)){
                            $data['detail']['store_order_id'] = $orderdata->id;
                        }
                        $data['detail']['date'] = date('Y-m-d');
                        $data['detail']['warehouse_id'] = $warehouse_id;
                        $data['detail']['po_number'] = $orderdata->number.'-'.$sno;
                        $data['detail']['po_date'] = $orderdata->date_created;
                        $data['detail']['delivery_date'] = date("Y-m-d",strtotime(date('Y-m-d')." +7 day"));;
                        $data['detail']['supplier_id'] = $supplier_id;
                        $data['detail']['customer_id'] = $customer_id;
                        $data['detail']['sale_note'] = $salenote;
                        $data['detail']['created_by'] = 77;
                        $data['items'] = $soitems;
                        $sos[] = $data;
                    }
                    foreach($sos as $so){
                        if(count($so['items'])>0){
                            $so['detail']['ref_no'] = $this->generate_ref();
                            $this->load->admin_model('salesorder_model');
                            $this->salesorder_model->add_so($so['detail'],$so['items']);
                        }
                    }
                    $activitynote = 'Auot SO Create Successfully';
                }
                else{
                    $activitynote = 'Supplier Not Found.';
                }
            }
            else{
                $activitynote = 'Order Item Not Found';
            }
            $activitynote .= ' Order Data: '.$req_dump;
            $this->useractivities_model->add([
                'note'=>$activitynote,
                'location'=>'API->Auto SO->Add->Submit',
                'store_id'=>$storeid,
                'action_by'=>77
            ]);
        }
        catch(Exception $e) {
            $insert['content2'] = 'Code Error';
            $activitynote = 'Code Error';
        }
    }
    public function generate_ref(){
        $sendvalue = "";
        // generate Ref Number
        $dbdetail = $this->db;
        $this->db->set_dbprefix('');
        $this->db->select('AUTO_INCREMENT');
        $this->db->from('information_schema.TABLES');
        $this->db->where('TABLE_SCHEMA = "'.$dbdetail->database.'" AND TABLE_NAME = "sma_sales_orders_tb"');
        $refq = $this->db->get();
        $refresult = $refq->result();
        $this->db->set_dbprefix('sma_');
        if(count($refresult)>0){
            $sendvalue = 'ASO-'.sprintf("%05d", $refresult[0]->AUTO_INCREMENT);
        }
        return $sendvalue;

    }
    public function separateItemsBySupplier($items,$sid){
        $sendvalue = array();
        foreach($items as $item){
            $data['store_product_id'] = $item->product_id;
            $data['quantity'] = $item->quantity;
            $supplier = $this->getProductSupplierDetail($item->product_id,$sid);
            if($supplier){
                $data = $supplier; 
                $data['store_product_id'] = $item->product_id;
                $data['quantity'] = $item->quantity;
                $sendvalue[$supplier['supplier_name']][] = $data;
            }
            else{
                // echo 'separateItemsBySupplier<br>';
                return false;
            }
        }
        return $sendvalue;
    }
    public function getProductSupplierDetail($spid,$sid){
        $this->db->select('
            sma_products.id as product_id,
            sma_products.pack_size,
            sma_products.carton_size,
            sma_companies.id,
            sma_companies.name,
            sma_store_products_tb.warehouse_id as warehouse_id,
            sma_stores_tb.customer_id as customer_id,
            sma_store_products_tb.update_qty_in as update_qty_in,
            sma_stores_tb.name as store_name
        ');
        $this->db->from('sma_store_products_tb');
        $this->db->join('sma_products','sma_products.id = sma_store_products_tb.product_id');
        $this->db->join('sma_companies','sma_companies.id = sma_store_products_tb.supplier_id');
        $this->db->join('sma_stores_tb','sma_stores_tb.id = sma_store_products_tb.store_id');
        $this->db->where('sma_store_products_tb.store_product_id',$spid);
        $this->db->where('sma_store_products_tb.store_id',$sid);
        $this->db->where('sma_stores_tb.auto_so','yes');
        $q =  $this->db->get();
        if($q->num_rows()){
            $result = $q->result()[0];
            $sendvalue['product_id'] = $result->product_id;
            $sendvalue['supplier_id'] = $result->id;
            $sendvalue['supplier_name'] = $result->name;
            $sendvalue['warehouse_id'] = $result->warehouse_id;
            $sendvalue['customer_id'] = $result->customer_id;
            $sendvalue['store_name'] = $result->store_name;
            $sendvalue['update_qty_in'] = $result->update_qty_in;
            $sendvalue['pack_size'] = $result->pack_size;
            $sendvalue['carton_size'] = $result->carton_size;
            return $sendvalue;
        }
        else{
            // echo 'Store Product ID: '.$spid.' Store ID '.$sid.' getProductSupplierDetail<br>';
            return false;
        }

    }

}