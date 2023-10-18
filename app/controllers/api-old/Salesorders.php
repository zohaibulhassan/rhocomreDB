<?php defined('BASEPATH') or exit('No direct script access allowed');
class Salesorders extends CI_Controller{
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
                    $emessage = "";
                    foreach($sos as $so){
                        if(count($so['items'])>0){
                            $so['detail']['ref_no'] = $this->generate_ref();
                            $this->load->admin_model('salesorder_model');
                            $newso = $this->salesorder_model->add_so($so['detail'],$so['items']);
                            if($newso['codestatus'] == "Sale Order Create Successfully"){
                                $batch_selection = $this->batch_select($storeid,$newso['so_id']);
                                $emessage = "Auto Batch Selection: ".$batch_selection['message'];
                                if($batch_selection['codestatus']){
                                    $auto_inoivce = $this->invoice_create($storeid,$newso['so_id']);
                                    $emessage .= ". Auto Invoice: ".$auto_inoivce['message'];
                                }
                                else{
                                    $emessage .= ". Auto Invoice Failed";
                                }
                            }
                            else{
                                $emessage .= ". Auto Batch Selection Failed";
                            }
                        }
                    }
                    $activitynote = 'Auot SO Create Successfully. '.$emessage;
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
    public function so_status($id){
        $this->db->select('
            sma_sales_orders_tb.status,
            (
                SELECT 
                    SUM(COALESCE(sma_sales_order_items.quantity,0)) 
                FROM 
                    sma_sales_order_items 
                WHERE 
                    sma_sales_order_items.so_id=sma_sales_orders_tb.id
            ) AS total_qty,
            (
                SELECT 
                    SUM(COALESCE(sma_sales_order_complete_items.quantity,0)) 
                FROM 
                    sma_sales_order_complete_items 
                WHERE 
                    sma_sales_order_complete_items.so_id=sma_sales_orders_tb.id
            ) AS complete_qty,
            (
                SELECT 
                    SUM(COALESCE(sma_sales_order_complete_items.quantity,0)) 
                FROM 
                    sma_sales_order_complete_items 
                WHERE 
                    sma_sales_order_complete_items.so_id=sma_sales_orders_tb.id AND
                    sma_sales_order_complete_items.status="pending"
            ) AS pending_dc_item,
            (
                SELECT 
                    SUM(COALESCE(sma_sales_order_complete_items.quantity,0)) 
                FROM 
                    sma_sales_order_complete_items 
                WHERE 
                    sma_sales_order_complete_items.so_id=sma_sales_orders_tb.id AND
                    sma_sales_order_complete_items.status="complete"
            ) AS complete_dc_item,
        ');
        $this->db->from('sma_sales_orders_tb');
        $this->db->where('sma_sales_orders_tb.id',$id);
        $q = $this->db->get();
        if($q->num_rows()>0){
            $set['accounts_team_status'] = 'pending';
            $set['operation_team_stauts'] = 'pending';
            $so = $q->result()[0];
            if($so->complete_qty == 0){
                $set['accounts_team_status'] = 'pending';
                $set['operation_team_stauts'] = 'pending';
                $set['status'] = 'pending';
            }
            else if($so->total_qty == $so->complete_qty){
                $set['operation_team_stauts'] = 'complete dispatch';
                if($so->pending_dc_item == 0){
                    $set['accounts_team_status'] = 'completed invoiced';
                    $set['status'] = 'completed';
                }
                else if($so->total_qty == $so->pending_dc_item){
                    $set['accounts_team_status'] = 'pending';
                    $set['status'] = 'pending';
                }
                else{
                    // $set['accounts_team_status'] = 'partial invoiced';
                    $set['accounts_team_status'] = 'pending';
                }
                
            }
            else{
                $set['operation_team_stauts'] = 'partial dispatch';
                $complete_dc_item = $so->complete_dc_item == '' ? 0 : $so->complete_dc_item;
                if($so->complete_dc_item == 0){
                    $set['accounts_team_status'] = 'pending';
                    $set['status'] = 'pending';
                }
                else{
                    if($so->pending_dc_item == 0){
                        $set['accounts_team_status'] = 'partial invoiced';
                    }
                    else{
                        $set['accounts_team_status'] = 'pending';
                    }
                    $set['status'] = 'partial';
                }

            }
            $this->db->set($set);
            $this->db->where('id',$id);
            $this->db->update('sma_sales_orders_tb');
        }
    }
    public function batch_select($storeid,$so_id){
        $returndata['codestatus'] = false;
        $returndata['message'] = '';
        $where['id'] = $storeid;
        $where['auto_so'] = 'yes';
        $where['auto_batch_selete'] = 1;
        $where['status'] = 'active';
        $q = $this->db->select('id')->from('stores_tb')->where($where)->get();
        if($q->num_rows() > 0){
            $so_detail = $this->db->select('id,operation_team_stauts')->from('sales_orders_tb')->where('id',$so_id)->get();
            if($so_detail->num_rows() > 0){
                $so_data = $so_detail->result()[0];
                if($so_data->operation_team_stauts == "pending"){
                    $this->db->select('*');
                    $this->db->from('sales_order_items');
                    $this->db->where('so_id',$so_id);
                    $so_q =  $this->db->get();
                    if($so_q->num_rows() > 0){
                        $items = $so_q->result();
                        $complete_items = array();
                        foreach($items as $item){
                            $remainingqty = $item->quantity;
                            $this->db->select('id,product_id,product_name,warehouse_id,batch,quantity_balance');
                            $this->db->from('purchase_items');
                            $this->db->where('warehouse_id',$item->warehouse_id);
                            $this->db->where('product_id',$item->product_id);
                            $this->db->where('quantity_balance > 0');
                            $batchq = $this->db->get();
                            if($batchq->num_rows() > 0){
                                $batchs = $batchq->result();
                                foreach($batchs as $batch){
                                    $complete_qty = 0;
                                    if($remainingqty > $batch->quantity_balance){
                                        $remainingqty = $remainingqty-$batch->quantity_balance;
                                        $complete_qty = $batch->quantity_balance;
                                    }
                                    else{
                                        $complete_qty = $remainingqty;
                                        $remainingqty = 0;
                                    }
                                    $cinsert['purchase_item_id'] = $batch->id;
                                    $cinsert['insertdata']['so_id'] = $so_id;
                                    $cinsert['insertdata']['soc_id'] = 0;
                                    $cinsert['insertdata']['soi_id'] = $item->id;
                                    $cinsert['insertdata']['product_id'] = $item->product_id;
                                    $cinsert['insertdata']['supplier_id'] = $item->supplier_id;
                                    $cinsert['insertdata']['warehouse_id'] = $item->warehouse_id;
                                    $cinsert['insertdata']['net_unit_price'] = $item->net_unit_price;
                                    $cinsert['insertdata']['unit_price'] = $item->unit_price;
                                    $cinsert['insertdata']['dropship'] = $item->dropship;
                                    $cinsert['insertdata']['crossdock'] = $item->crossdock;
                                    $cinsert['insertdata']['mrp'] = $item->mrp;
                                    $cinsert['insertdata']['expiry_date'] = $batch->expiry;
                                    $cinsert['insertdata']['batch'] = $batch->batch;
                                    $cinsert['insertdata']['quantity'] = $complete_qty;
                                    $cinsert['insertdata']['unit'] = $item->unit;
                                    $cinsert['insertdata']['unit_code'] = $item->unit_code;
                                    $cinsert['insertdata']['total'] = $item->total;
                                    $cinsert['insertdata']['product_tax_id'] = $item->product_tax_id;
                                    $cinsert['insertdata']['product_tax'] = $item->product_tax;
                                    $cinsert['insertdata']['fed_tax'] = $item->fed_tax;
                                    $cinsert['insertdata']['further_tax'] = $item->further_tax;
                                    $cinsert['insertdata']['total_tax'] = $item->total_tax;
                                    $cinsert['insertdata']['discount_one'] = $item->discount_one;
                                    $cinsert['insertdata']['discount_two'] = $item->discount_two;
                                    $cinsert['insertdata']['discount_three'] = $item->discount_three;
                                    $cinsert['insertdata']['total_discount'] = $item->total_discount;
                                    $cinsert['insertdata']['sub_total'] = $item->sub_total;
                                    $cinsert['insertdata']['status'] = 'pending';
                                    // $complete_items[] = $cinsert;
                                    $complete_item = $cinsert;
                                    $complete_sale_order_id = $this->soc_created($so_id);
                                    // $complete_item['soc_id'] = $complete_sale_order_id;
                                    $complete_item['insertdata']['soc_id'] = $complete_sale_order_id;
                                    $this->db->insert('sales_order_complete_items',$complete_item['insertdata']);
            
                                    //Batch Quantity Update in Purchase Table
                                    $this->db->set('quantity_balance', 'quantity_balance-'.$complete_item['insertdata']['quantity'], FALSE);
                                    $this->db->where('id', $complete_item['purchase_item_id']);
                                    $this->db->update('purchase_items');
                                    
                                    //Warehouse Quantity Update in Warehouse Product Table
                                    $this->db->set('quantity', 'quantity-'.$complete_item['insertdata']['quantity'], FALSE);
                                    $this->db->where('product_id', $complete_item['insertdata']['product_id']);
                                    $this->db->where('warehouse_id', $complete_item['insertdata']['warehouse_id']);
                                    $this->db->update('warehouses_products');
                                    
                                    //Product Quantity Update in Product Table
                                    $this->db->set('quantity', 'quantity-'.$complete_item['insertdata']['quantity'], FALSE);
                                    $this->db->where('id', $complete_item['insertdata']['product_id']);
                                    $this->db->update('products');
            
                                    $this->load->model('admin/stores_model');
                                    $this->stores_model->updateStoreQty($complete_item['insertdata']['product_id'],$complete_item['insertdata']['warehouse_id'],0,"Complete Item in SO");

                                    if($remainingqty == 0){
                                        break;
                                    }
                                }
                            }
                            else{
                                $returndata['message'] = 'Batching not Found';
                                break;
                            }
                            if($remainingqty > 0){
                                $returndata['message'] = 'Stock not available';
                                $complete_items = array();
                            }
                        }

                        $this->so_status($so_id);
                        $returndata['message'] = 'Batchs selection complete';
                        $returndata['codestatus'] = true;

                    }
                    else{
                        $returndata['message'] = 'SO Items Not Found';
                    }
                }
                else{
                    $returndata['message'] = 'SO Already Completed';
                }
            }
            else{
                $returndata['message'] = 'SO Not Found';
            }
        }
        else{
            $returndata['message'] = 'Store Not Found';
        }
        return $returndata;
    }
    public function soc_created($id){
        $returndata = 0;
        $this->db->select('soc_id');
        $this->db->from('sma_sales_order_complete_items');
        $this->db->where('so_id = '.$id.' AND status = "pending"');
        $q = $this->db->get();
        if($q->num_rows()>0){
            $returndata = $q->result()[0]->soc_id;
        }
        else{
            $insert['so_id'] = $id;
            $insert['created_by'] = $this->session->userdata('user_id');
            $insert['status'] = 'pending';
            $this->db->insert('sma_sales_order_complete_tb',$insert);
            $returndata = $this->db->insert_id();
        }
        $this->so_status($id);
        return $returndata;
    }
    public function invoice_create($storeid,$so_id){
        $this->load->admin_model('salesorder_model');
        $returndata['codestatus'] = false;
        $returndata['message'] = '';
        $where['id'] = $storeid;
        $where['auto_so'] = 'yes';
        $where['auto_invoice'] = 1;
        $where['status'] = 'active';
        $q = $this->db->select('id')->from('stores_tb')->where($where)->get();
        if($q->num_rows() > 0){
            $this->db->select('
                sales_orders_tb.*,
                supplier.name as supplier_name,
                customer.name as customer_name,
                customer.sales_type as customer_sales_type,
                sales_order_complete_tb.id as socid
            ');
            $this->db->from('sales_orders_tb');
            $this->db->join('sales_order_complete_tb', 'sales_order_complete_tb.so_id = sales_orders_tb.id AND sales_order_complete_tb.status = "pending"', 'left');
            $this->db->join('companies as supplier', 'supplier.id = sales_orders_tb.supplier_id', 'left');
            $this->db->join('companies as customer', 'customer.id = sales_orders_tb.customer_id', 'left');
            $this->db->where('sales_orders_tb.id',$so_id);
            $so_q = $this->db->get();
            if($so_q->num_rows() > 0){
                $so_data = $so_q->result()[0];
                if($so_data->accounts_team_status == "pending" && $so_data->operation_team_stauts = "complete dispatch"){
                    $this->db->select('
                        sales_order_complete_items.product_id,
                        sales_order_complete_items.soc_id,
                        purchase_items.product_code,
                        products.company_code,
                        products.discount_one,
                        products.discount_two,
                        products.discount_three,
                        purchase_items.product_name,
                        products.type as product_type,
                        products.adv_tax_reg as adv_tax_reg,
                        products.adv_tax_nonreg as adv_tax_nonreg,
                        purchase_items.option_id,
                        purchase_items.net_unit_cost,
                        purchase_items.price,
                        purchase_items.dropship,
                        purchase_items.crossdock,
                        purchase_items.mrp,
                        purchase_items.expiry,
                        sales_order_complete_items.batch,
                        sales_order_complete_items.warehouse_id,
                        sales_order_complete_items.quantity,
                        purchase_items.gst,
                        purchase_items.cgst,
                        purchase_items.sgst,
                        purchase_items.igst,
                        purchase_items.further_tax,
                        purchase_items.fed_tax,
                        purchase_items.product_unit_id,
                        purchase_items.product_unit_code,
                        tax_rates.id as tax_rate_id,
                        tax_rates.name as tax_rate_name,
                        tax_rates.rate as tax_rate_rate,
                        tax_rates.code as tax_rate_code,
                        tax_rates.type as tax_rate_type,
                        companies.gst_no as customer_gst_no,
                        sales_order_complete_tb.id as socid
                    ');
                    $this->db->from('sales_order_complete_items');
                    $this->db->join('sales_orders_tb', 'sales_orders_tb.id = sales_order_complete_items.so_id', 'left');
                    $this->db->join('sales_order_complete_tb', 'sales_order_complete_tb.so_id = sales_order_complete_items.so_id AND sales_order_complete_tb.status = "pending"', 'left');
                    $this->db->join('companies', 'companies.id = sales_orders_tb.customer_id', 'left');
                    $this->db->join('purchase_items', 'purchase_items.product_id  = sales_order_complete_items.product_id AND purchase_items.batch  = sales_order_complete_items.batch AND purchase_items.warehouse_id  = sales_order_complete_items.warehouse_id', 'left');
                    $this->db->join('products', 'products.id  = sales_order_complete_items.product_id', 'left');
                    $this->db->join('tax_rates', 'tax_rates.id = purchase_items.sale_tax_id', 'left');
                    $this->db->where('sales_order_complete_items.so_id = '.$so_id);
                    $this->db->where('sales_order_complete_items.status = "pending"');
                    $this->db->group_by("sales_order_complete_items.id");
                    $checkpendingso = $this->db->get();
                    if($checkpendingso->num_rows() > 0){
                        $soc_data = $checkpendingso->result();
                        $soitems = array();
                        $orderdiscount = 0;
                        $total = 0;
                        $shipping = 0;
                        $productdiscount = 0;
                        $totaldiscount = 0;
                        $soproducttax = 0;
                        $total_adv_tax = 0;
                        $ordertax = 0;
                        $totaltax = 0;
                        $totalitem = 0;
                                
                        $setting_further_tax = $this->salesorder_model->further_tax()->further_tax;
                        foreach($soc_data as $socrow){
                            $apply_discount = 0;
                            // discount work start
                            $this->db->select('discount');
                            $this->db->from('store_products_tb');
                            $this->db->where('store_id',$storeid);
                            $this->db->where('product_id',$socrow->product_id);
                            $this->db->where('warehouse_id',$socrow->warehouse_id);
                            $disq = $this->db->get();
                            if($disq->num_rows() > 0){
                                $disqdata = $disq->result()[0];
                                if($disqdata->discount == "d1"){
                                    $apply_discount = $socrow->discount_one;
                                }
                                else if($disqdata->discount == "d2"){
                                    $apply_discount = $socrow->discount_two;
                                }
                                else if($disqdata->discount == "d3"){
                                    $apply_discount = $socrow->discount_three;
                                }
                                else if($disqdata->discount == "no"){
                                    $apply_discount = 0;
                                }
                                else{
                                    $this->db->select('percentage');
                                    $this->db->from('bulk_discount');
                                    $this->db->where('id',$disqdata->discount);
                                    $disq2 = $this->db->get();
                                    if($disq2->num_rows() > 0){
                                        $disqdata2 = $disq2->result()[0];
                                        $apply_discount = $disqdata2->percentage;
                                    }
                                    else{
                                        $apply_discount = 0;
                                    }
                                }
                            }
                            else{
                                $apply_discount = 0;
                            }
                            // discount work end
                            $soi_data = $socrow;
                            $socid = $soi_data->socid;
                            if ($so_data->customer_sales_type === 'cost') {
                                $selling_price = $soi_data->net_unit_cost;
                            }
                            else if ($so_data->customer_sales_type === 'mrp') {
                                $selling_price = $soi_data->mrp;
                            }
                            else{
                                $selling_price = $soi_data->price;
                            }
                            $itemsfedtax = $soi_data->fed_tax*$soi_data->quantity;
                            $further_tax = 0;
                            if($soi_data->tax_rate_type == 1){
                                if($soi_data->customer_gst_no == ""){
                                    $further_tax = (($selling_price/100)*$setting_further_tax)*$soi_data->quantity;
                                }
    
                                $itemtotaltax = (($selling_price/100)*$soi_data->tax_rate_rate)*$soi_data->quantity;
                            }
                            else{
                                $itemtotaltax = $soi_data->tax_rate_rate*$soi_data->quantity;
                            }
    
                            $adv_tax = 0;
                            if($soi_data->customer_gst_no == ""){
                                $adv_tax = decimalallow(((($selling_price*$soi_data->quantity)+$itemtotaltax)/100)*$soi_data->adv_tax_nonreg,2 );
                            }
                            else{
                                $adv_tax = decimalallow(((($selling_price*$soi_data->quantity)+$itemtotaltax)/100)*$soi_data->adv_tax_reg,2 );
                            }
                            $total_adv_tax += $adv_tax;
    
    
                            $soproducttax += $itemtotaltax+$further_tax+$adv_tax;
    
                            $da1 = (($selling_price/100)*0)*$soi_data->quantity;
                            $da2 = (($selling_price/100)*0)*$soi_data->quantity;
                            $da3 = (($selling_price/100)*$apply_discount)*$soi_data->quantity;
                            $itemtotaldiscount = $da1+$da2+$da3;
                            $productdiscount += $itemtotaldiscount;
                            $totalitem += $soi_data->quantity;
                            $itemtotal = ($selling_price*$soi_data->quantity)+($itemtotaltax+$further_tax+$adv_tax)-$itemtotaldiscount;
                            $total += $itemtotal;
                            // $itemitemdata['sale_id'] = 'id';
                            $itemitemdata['product_id'] = $soi_data->product_id;
                            $itemitemdata['product_code'] = $soi_data->product_code;
                            $itemitemdata['company_code'] = $soi_data->company_code;
                            $itemitemdata['product_name'] = $soi_data->product_name;
                            $itemitemdata['product_type'] = $soi_data->product_type;
                            $itemitemdata['option_id'] = $soi_data->option_id;
                            $itemitemdata['net_unit_price'] = $selling_price;
                            $itemitemdata['unit_price'] = $selling_price;
                            $itemitemdata['consignment'] = $soi_data->price;
                            $itemitemdata['dropship'] = $soi_data->dropship;
                            $itemitemdata['crossdock'] = $soi_data->crossdock;
                            $itemitemdata['mrp'] = $soi_data->mrp;
                            $itemitemdata['expiry'] = $soi_data->expiry;
                            $itemitemdata['batch'] = $soi_data->batch;
                            $itemitemdata['quantity'] = $soi_data->quantity;
                            $itemitemdata['warehouse_id'] = $soi_data->warehouse_id;
                            $itemitemdata['item_tax'] = $itemtotaltax;
                            $itemitemdata['tax_rate_id'] = $soi_data->tax_rate_id;
                            $itemitemdata['tax'] = $soi_data->tax_rate_rate;
                            $itemitemdata['discount'] = $itemtotaldiscount;
                            $itemitemdata['item_discount'] = $itemtotaldiscount;
                            $itemitemdata['subtotal'] = $itemtotal;
                            $itemitemdata['real_unit_price'] = $selling_price;
                            $itemitemdata['product_unit_id'] = $soi_data->product_unit_id;
                            $itemitemdata['product_unit_code'] = $soi_data->product_unit_code;
                            $itemitemdata['unit_quantity'] = $soi_data->quantity;
                            $itemitemdata['gst'] = $soi_data->gst;
                            $itemitemdata['cgst'] = $soi_data->cgst;
                            $itemitemdata['sgst'] = $soi_data->sgst;
                            $itemitemdata['igst'] = $soi_data->igst;
                            $itemitemdata['discount_one'] = 0;
                            $itemitemdata['discount_two'] = 0;
                            $itemitemdata['discount_three'] = $apply_discount;
                            $itemitemdata['product_price'] = $selling_price;
                            $itemitemdata['further_tax'] = $further_tax;
                            $itemitemdata['fed_tax'] = $itemsfedtax;
                            $itemitemdata['adv_tax'] = $adv_tax;
                            $soitems[] = $itemitemdata;
                        }
                        $groudtotal = $total+$totaltax+$shipping-$totaldiscount;


                        // Reference No Generate Start
                        $reference_no = '';
                        $this->db->select('id');
                        $this->db->from('sma_own_companies');
                        $this->db->where('id = 2');
                        $q2 = $this->db->get();
                        if($q2->num_rows() > 0){
                            $this->db->select('MAX(reference_no) as ref');
                            $this->db->from('sma_sales');
                            $this->db->where("
                                own_company = 2 AND 
                                reference_no REGEXP '^[0-9]+$' AND 
                                reference_no < 100000
                            ");
                            $q = $this->db->get();
                            if($q->num_rows()>0){
                                $r = $q->result();
                                $reference_no = $r[0]->ref+1;
                            }
                        }
                        // Reference No Generate End
                        // *********************************************************************************
                        $insertso['supplier_id'] = $so_data->supplier_id;
                        $insertso['date'] = date('Y-m-d H:i:s');
                        $insertso['reference_no'] = $reference_no;
                        $insertso['customer_id'] = $so_data->customer_id;
                        $insertso['customer_address_id'] = $so_data->customer_address_id;
                        $insertso['own_company'] = 2;
                        $insertso['po_number'] = $so_data->po_number;
                        $insertso['customer'] = $so_data->customer_name;
                        $insertso['biller_id'] = 87;
                        $insertso['etalier_id'] = $so_data->etalier_id;
                        $insertso['biller'] = 'Rhocom';
                        $insertso['warehouse_id'] = $so_data->warehouse_id;
                        $insertso['note'] = 'Auto Invoice Generate by Online Store';
                        $insertso['staff_note'] = '';
                        $insertso['total'] = $total;
                        $insertso['product_discount'] = $productdiscount;
                        $insertso['total_discount'] = $totaldiscount;
                        $insertso['order_discount'] = $orderdiscount;
                        $insertso['product_tax'] = $soproducttax;
                        $insertso['order_tax'] = $ordertax;
                        $insertso['adv_tax'] = $total_adv_tax;
                        $insertso['total_tax'] = $totaltax;
                        $insertso['shipping'] = $shipping;
                        $insertso['grand_total'] = $groudtotal;
                        $insertso['sale_status'] = "completed";
                        $insertso['payment_status'] = "pending";
                        $insertso['created_by'] = 77;
                        $insertso['total_items'] = $totalitem;
                        $insertso['hash'] = hash('sha256', microtime() . mt_rand());
                        $insertso['payment_terms'] = '';
                        $insertso['po_date'] = $so_data->po_date;
                        $insertso['dc_num'] = '';
                        $insertso['so_id'] = $so_id;
                        $insertso['soc_id'] = $so_data->socid;
                        $returnsale = $this->salesorder_model->salecreated($insertso,$soitems);
                        $this->so_status($so_id);
                        if($returnsale['codestatus'] == "Sale Create Successfully"){
                            $returndata['codestatus'] = true;
                        }
                        $returndata['message'] = $returnsale['codestatus'];
                    }
                    else{
                        $sendvalue['message'] = "Batches not found";
                    }
                }
                else{
                    $sendvalue['message'] = "Invoice not create";
                }
            }
            else{
                $sendvalue['message'] = "Invalid Sales Order";
            }
        }
        else{
            $returndata['message'] = 'Store Not Found';
        }
        print_r($returndata);
    }




}