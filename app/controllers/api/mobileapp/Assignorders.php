<?php defined('BASEPATH') or exit('No direct script access allowed');
class Assignorders extends App_Controller{
    function __construct(){
        parent::__construct();
    }
    public function routes(){
        $user_id = $this->input->post('user_id');
        $date = $this->input->post('date');
        $status = $this->input->post('status');
        $this->db->select('
            r.id as route_id,
            r.name as route_name,
            r.latitude as route_lag,
            r.longitude as route_lat,
            r.address as route_address

        ');
        $this->db->from('sma_sales_dispatch as sd');
        $this->db->join('sma_sales as s','s.id = sd.sale_id','left');
        $this->db->join('sma_companies as c','c.id = s.customer_id','left');
        $this->db->join('sma_routes_tb as r','r.id = c.route_id','left');
        $this->db->group_by('c.route_id');
        if($date != ""){
            $this->db->where('sd.delivery_date',$date);
        }
        if($status != "" && $status != "all"){
            $this->db->where('sd.status',$status);
        }
        $this->db->where('sd.dispatcher_id',$user_id);
        $this->data['rows'] = $this->db->get()->result();

        $this->data['code_status'] = true;
        $this->data['message'] = "Success!";
        $this->responsedata();
    }
    public function shops(){
        $user_id = $this->input->post('user_id');
        $date = $this->input->post('date');
        $route = $this->input->post('route');
        $status = $this->input->post('status');
        $this->db->select('
            c.id as customer_id,
            c.name as customer_name,
            c.longitude as customer_log,
            c.latitude as customer_lat,
            c.address as customer_address,
            r.id as route_id,
            r.name as route_name,
            r.latitude as route_log,
            r.longitude as route_lat,
            r.address as route_address
        ');
        $this->db->from('sma_sales_dispatch as sd');
        $this->db->join('sma_sales as s','s.id = sd.sale_id','left');
        $this->db->join('sma_companies as c','c.id = s.customer_id','left');
        $this->db->join('sma_routes_tb as r','r.id = c.route_id','left');
        $this->db->group_by('s.customer_id');
        if($date != ""){
            $this->db->where('sd.delivery_date',$date);
        }
        if($route != ""){
            $this->db->where('c.route_id',$route);
        }
        if($status != "" && $status != "all"){
            $this->db->where('sd.status',$status);
        }
        $this->db->where('sd.dispatcher_id',$user_id);
        $this->data['rows'] = $this->db->get()->result();
        $this->data['code_status'] = true;
        $this->data['message'] = "Success!";
        $this->responsedata();
    }
    public function orders(){
        $user_id = $this->input->post('user_id');
        $date = $this->input->post('date');
        $route = $this->input->post('route');
        $shop = $this->input->post('shop');
        $status = $this->input->post('status');
        $this->db->select('
            sd.*,
            s.id as order_id,
            s.date as sale_date,
            s.reference_no,
            s.grand_total,
            s.payment_status,
            c.id as customer_id,
            c.name as customer_name,
            c.longitude as customer_log,
            c.latitude as customer_lat,
            c.address as customer_address,
            r.id as route_id,
            r.name as route_name,
            r.latitude as route_log,
            r.longitude as route_lat,
            r.address as route_address
        ');
        $this->db->from('sma_sales_dispatch as sd');
        $this->db->join('sma_sales as s','s.id = sd.sale_id','left');
        $this->db->join('sma_companies as c','c.id = s.customer_id','left');
        $this->db->join('sma_routes_tb as r','r.id = c.route_id','left');
        if($date != ""){
            $this->db->where('sd.delivery_date',$date);
        }
        if($route != ""){
            $this->db->where('c.route_id',$route);
        }
        if($shop != ""){
            $this->db->where('s.customer_id',$shop);
        }
        if($status != "" && $status != "all"){
            $this->db->where('sd.status',$status);
        }
        $this->db->where('sd.dispatcher_id',$user_id);
        $this->data['rows'] = $this->db->get()->result();
        $this->data['code_status'] = true;
        $this->data['message'] = "Success!";
        $this->responsedata();
    }
    public function change_status(){
        $user_id = $this->input->post('user_id');
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $sd = $this->db->from('sma_sales_dispatch as sd')->where('id',$id)->get()->result();
        if(count($sd) > 0){
            $this->db->set('status',$status);
            $this->db->where('id',$id);
            $this->db->update('sma_sales_dispatch');



            $this->db->set('delivery_status',$status);
            $this->db->where('id',$sd[0]->sale_id);
            $this->db->update('sma_sales');

            $this->data['sale_id'] = $sd[0]->sale_id;
            $this->data['code_status'] = true;
            $this->data['message'] = "Status updated";
        }
        else{
            $this->data['message'] = "Invalid ID";
        }
        $this->responsedata();
    }
    
    public function summary_detail(){

        $user_id = $this->input->post('user_id');
        $date = $this->input->post('date');
        $rows = array();
        $this->db->select('
            delivery_date,
            GROUP_CONCAT(status) AS status
        ');
        $this->db->from('sma_sales_dispatch');
        $this->db->where('dispatcher_id',$user_id);
        $this->db->where('delivery_date',$date);
        $this->db->group_by('delivery_date');
        $rs = $this->db->get()->result();
        foreach($rs as $r){
            $status = array_count_values(explode(',',$r->status));
            $temp['delivery_date'] = $date;
            $temp['pending'] = 0;
            $temp['delivered'] = 0;
            $temp['cancel'] = 0;
            if(isset($status['pending'])){
                if($status['pending'] != ""){
                    $temp['pending'] = $status['pending'];
                }
            }
            if(isset($status['delivered'])){
                if($status['delivered'] != ""){
                    $temp['delivered'] = $status['delivered'];
                }
            }
            if(isset($status['cancel'])){
                if($status['cancel'] != ""){
                    $temp['cancel'] = $status['cancel'];
                }
            }
            $temp['total'] = $temp['pending']+$temp['delivered']+$temp['cancel'];
            $rows[] = $temp;
        }
        $this->data['rows'] = $rows;
        $this->data['code_status'] = true;
        $this->data['message'] = "Success!";
        $this->responsedata();
    }
}

