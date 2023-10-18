<?php defined('BASEPATH') or exit('No direct script access allowed');
class Sales extends App_Controller{
    function __construct(){
        parent::__construct();
    }
    public function dueinvoices(){
        $id = $this->input->post('customer_id');
        $this->db->select('
            id,
            reference_no,
            grand_total,
            paid,
            (grand_total)-(paid) as balance,
            payment_status
        ');
        $this->db->from('sales');
        $this->db->where('customer_id',$id);
        $this->db->where('payment_status != "paid"');
        $q = $this->db->get();
        $this->data['invoices'] = $q->result();
        $this->data['code_status'] = true;
        $this->data['message'] = "Success!";
        $this->responsedata();
    }
    public function order(){
        $id = $this->input->post('id');
        if($id != ""){
            $this->db->select('
                s.id as order_id,
                s.date as order_date,
                s.reference_no as order_no,
                s.po_number as po_number,
                s.delivery_status as delivery_status,
                s.sale_status as order_status,
                s.grand_total as grand_total,
                s.paid as paid,
                (s.grand_total-s.paid) as balance,
                supplier.id as supplier_id,
                supplier.name as supplier_name,
                customer.id as customer_id,
                customer.name as customer_name,
                customer.phone as customer_phone,
                customer.email as customer_email,
                customer.gst_no as customer_gst_no,
                warehouse.id as warehouse_id,
                warehouse.name as warehouse_name,
                "" as items

            ');
            $this->db->from('sma_sales as s');
            $this->db->join('sma_companies as supplier','supplier.id = s.supplier_id','left');
            $this->db->join('sma_companies as customer','customer.id = s.customer_id','left');
            $this->db->join('sma_warehouses as warehouse','warehouse.id = s.warehouse_id','left');
            $this->db->where('s.id',$id);
            $q = $this->db->get();
            if($q->num_rows() > 0){
                $order = $q->result()[0];
                $this->db->select('
                    si.id as item_id,
                    si.product_id,
                    product.code as product_barcode,
                    product.name as product_name,
                    "no_image" as product_image,
                    si.quantity
                ');
                $this->db->from('sma_sale_items as si');
                $this->db->join('sma_products as product','product.id = si.product_id','left');
                $this->db->where('si.sale_id',$id);
                $q2 = $this->db->get();
                $order->items = $q2->result();
                $this->data['order'] = $order;
                $this->data['code_status'] = true;
                $this->data['message'] = "Success!";
            }
            else{
                $this->data['message'] = "Order Not Found";
                $this->data['error_code'] = '004';
            }
        }
        else{
            $this->data['message'] = "Invaild Order ID!";
            $this->data['error_code'] = '003';
        }
       $this->responsedata();
    }


}
