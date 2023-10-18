<?php defined('BASEPATH') or exit('No direct script access allowed');
class Orders extends App_Controller{
    function __construct(){
        parent::__construct();
    }
    public function order_history(){
        $customer_id = $this->input->post('customer_id');
        $this->db->select();
        $this->db->where('b.customer_id',$customer_id);
        $this->db->from('sma_sales as b');
        $this->db->join('sma_sale_items as a','b.id = a.sale_id');
        $this->db->join('sma_products as pro','pro.id = a.product_id');
        $this->db->order_by('b.id','Desc');
        $this->db->group_by('a.product_id');
        $q = $this->db->get();

        $this->data['code_status'] = true;
        $this->data['message'] = "Success!";
        $this->data['stocks'] = $q->result();
        $this->responsedata();
    }
}