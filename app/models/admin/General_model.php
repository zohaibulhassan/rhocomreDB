<?php defined('BASEPATH') or exit('No direct script access allowed');

class General_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function GetAllWarehouses()
    {
        $this->db->select('id,name as text');
        $this->db->from('warehouses');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllBrands()
    {
        $this->db->select('id,name as text');
        $this->db->from('brands');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllFormulas()
    {
        $this->db->select('id,code as text');
        $this->db->from('product_formulas');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllDisease()
    {
        $this->db->select('id,name as text');
        $this->db->from('diseases');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllFormulaForm()
    {
        $this->db->select('id,name as text');
        $this->db->from('formula_forms');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllFormulaStrengths()
    {
        $this->db->select('id,name as text');
        $this->db->from('formula_strengths');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllProductForms()
    {
        $this->db->select('id,name as text');
        $this->db->from('product_forms');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllManufacturers()
    {
        $this->db->select('id,name as text');
        $this->db->from('manufacturers');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllCategories($id = 'all')
    {
        $this->db->select('id,name as text');
        $this->db->from('categories');
        if ($id != "" && $id != "all") {
            $this->db->where('parent_id', $id);
        }
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllSubCategories()
    {
        $this->db->select('id,name as text');
        $this->db->from('categories');
        $this->db->where('parent_id != 0');
        $q = $this->db->get();
        return $q->result();
    }

    public function GetAllRoutes()
    {
        $this->db->select('id,name as text');
        $this->db->from('routes_tb');
        $this->db->where('status', 1);
        $q = $this->db->get();
        return $q->result();
    }

    public function GetAllGroups()
    {
        $this->db->select('id,name as text');
        $this->db->from('product_groups');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllUnits()
    {
        $this->db->select('id,name as text');
        $this->db->from('units');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllProduct_tax()
    {
        $this->db->select('id,name as text');
        $this->db->from('tax_rates');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllSuppliers()
    {
        $this->db->select('id,name as text');
        $this->db->from('companies');
        $this->db->where('group_name', 'supplier');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllCustomers()
    {
        $this->db->select('id,name as text');
        $this->db->from('companies');
        $this->db->where('group_name', 'customer');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllOwnCompanies()
    {
        $this->db->select('id,companyname as text');
        $this->db->from('own_companies');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllWallets()
    {
        $this->db->select('id,title as text');
        $this->db->from('wallets');
        $this->db->where('status', 'active');
        $q = $this->db->get();
        return $q->result();
    }
    public function GetAllExpenseCategories()
    {
        $this->db->select('id,name as text');
        $this->db->from('expense_categories');
        $q = $this->db->get();
        return $q->result();
    }

    //  public function getRemainingQuantity($selected_batch_code) {
       
    //     $this->db->select('quantity_balance, batch');
    //     $this->db->where('batch', $selected_batch_code);
    //     $query = $this->db->get('purchase_items');

    //     if ($query->num_rows() > 0) {
    //         $row = $query->row();
    //         return $row->quantity_column;
    //     } else {
    //         return 0; 
    //     }
    // }

// public function get_quantity_balance($batch_id) {
//         $this->db->select('quantity_balance');
//         $this->db->where('batch', $batch_id);
//         $query = $this->db->get('purchase_items');
//         $result = $query->row();
//         return $result->quantity_balance;
//     }

//     public function get_quantity_balance($batch_id) {
//     $this->db->select('quantity_balance');
//     $this->db->where('batch', $batch_id);
//     $query = $this->db->get('purchase_items');
//     if ($query->num_rows() > 0) {
//         $result = $query->row();
//         return $result->quantity_balance;
//     } else {
      
//         return "No data found for batch_id: $batch_id";
//     }
// }




    //  public function get_quantity_balance($batch) {
    //     $this->db->select('quantity_balance');
    //     $this->db->where('batch', $batch);
    //     $query = $this->db->get('purchase_items'); 
    //     $result = $query->row();
    //     return $result->quantity_balance;
    // }

//     public function get_quantity_balance($batch_id) {
//     $this->db->select('quantity_balance');
//     $this->db->where('batch', $batch_id);
//     $query = $this->db->get('purchase_items');

//     if ($query->num_rows() > 0) {
//         $row = $query->row();
//         return $row->quantity_balance;
//     } 
// }

// public function get_quantity_balance($batch_id) {
//     $this->db->select_sum('quantity_balance'); 
//     $this->db->where('batch', $batch_id);
//     $query = $this->db->get('purchase_items');

//     if ($query->num_rows() > 0) {
//         $row = $query->row();
//         return $row->quantity_balance;
//     } 
// }

    public function get_quantity_balance($batch_id) {
          // echo "Batch ID: " . $batch_id . "<br>";
    $this->db->select_sum('quantity_balance');
    $this->db->where('batch', $batch_id);
    $query = $this->db->get('purchase_items');

    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row->quantity_balance;
    } 
}


// public function get_quantity_balance($batch_id) {
//     $this->db->select_sum('quantity_balance');
//     $this->db->where('batch', $batch_id);
//     $query = $this->db->get('purchase_items');

//     if ($query->num_rows() > 0) {
//         $row = $query->row();
//         return $row->quantity_balance;
//     } else {
//         return 0; 
//     }
// }

public function select_products2($product_id,$warehouse_id,$qty)
    {
      
        $sendvalue['codestatus'] = false;
        $id = $product_id;
        $user_id = $this->session->userdata('user_id');
        $q = $this->db->select()->from('users')->where('id', $user_id)->get()->row();
        if ($q->warehouse_id != 0) {
            $warehouse_id = $q->warehouse_id;
        } else {
            $warehouse_id = $warehouse_id;
        }

        $this->db->select('
            products.id,
            products.code,
            products.company_code,
            products.name,
            products.cost,
            products.price,
            products.pack_size,
            products.carton_size,
            products.formulas,
            products.category_id,
            products.subcategory_id,
            products.brand,
            products.adv_tax_for_purchase,
            products.mrp,
            products.alert_quantity,
            COALESCE((
                SELECT SUM(sma_purchase_items.quantity_balance) FROM sma_purchase_items WHERE sma_purchase_items.product_id = ' . $id . ' AND sma_purchase_items.warehouse_id = ' . $warehouse_id . '
            ),0) as balance_qty,
            products.fed_tax,
            products.tax_method,
            tax_rates.id as tax_id,
            tax_rates.name as tax_name,
            tax_rates.rate as tax_rate,
            tax_rates.type as tax_type,
            0 as product_tax,
            0 as product_discount_all,
            0 as product_discount_pos,
            0 as product_discount_web,
            '.$qty.' as quantity,
            "" as batch,
            "" as expiry
        ');
        $this->db->from('products as products');
        $this->db->join('tax_rates', 'tax_rates.id = products.tax_rate', 'left');
        $this->db->where('products.id', $id);
        $q =  $this->db->get();
   
        if ($q->num_rows() > 0) {
            $products = $q->result()[0];
            if ($products->tax_type == 1) {
                $products->product_tax = amountformate((($products->cost / 100) * $products->tax_rate));
            } else {
                $products->product_tax = amountformate($products->tax_rate);
            }
            $bq1 = $this->db->select('percentage')->from('bulk_discount')->group_start()->like('brand_id', $products->brand)->or_like('product_id', $products->id)->group_end()->where('start_date <= ', date('Y-m-d H:i:s'))->where('end_date >= ', date('Y-m-d H:i:s'))->where('end_date >= ', date('Y-m-d H:i:s'))->get();
            if ($bq1->num_rows() > 0) {
                $discount = $bq1->result()[0];
                $products->product_discount_all = $products->mrp / 100 * $discount->percentage;
            }
            $bq2 = $this->db->select('percentage')->from('bulk_discount')->group_start()->like('brand_id', $products->brand)->or_like('product_id', $products->id)->group_end()->where('start_date <= ', date('Y-m-d H:i:s'))->where('end_date >= ', date('Y-m-d H:i:s'))->where('end_date >= ', date('Y-m-d H:i:s'))->get();
            if ($bq2->num_rows() > 0) {
                $discount = $bq2->result()[0];
                $products->product_discount_pos = $products->mrp / 100 * $discount->percentage;
            }
            $bq3 = $this->db->select('percentage')->from('bulk_discount')->group_start()->like('brand_id', $products->brand)->or_like('product_id', $products->id)->group_end()->where('start_date <= ', date('Y-m-d H:i:s'))->where('end_date >= ', date('Y-m-d H:i:s'))->where('end_date >= ', date('Y-m-d H:i:s'))->get();
            if ($bq3->num_rows() > 0) {
                $discount = $bq3->result()[0];
                $products->product_discount_web = $products->mrp / 100 * $discount->percentage;
            }
  
       
        }
        return $products;
    }

public function GetAllDispatchers(){
    $this->db->select('id,CONCAT(first_name,last_name) as text');
    $this->db->from('users');
    $this->db->where('group_id',5);
    $q = $this->db->get();
    return $q->result();
}



}
