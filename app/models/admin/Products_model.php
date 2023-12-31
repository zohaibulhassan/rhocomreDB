<?php defined('BASEPATH') or exit('No direct script access allowed');

class Products_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/stores_model');
    }
    // New Code
    public function get_purchases($id)
    {

        $this->db->select('
            p.id,
            p.date,
            p.reference_no,
            w.name as warehouse,
            s.name as supplier,
            oc.companyname as own_company,
            pi.net_unit_cost as cost,
            pi.mrp,
            pi.batch,
            pi.expiry,
            pi.quantity,
            pi.quantity_balance,
            pi.item_tax,
            pi.adv_tax,
            pi.subtotal
        ');
        $this->db->from('sma_purchase_items as pi');
        $this->db->join('sma_purchases as p', 'p.id = pi.purchase_id', 'left');
        $this->db->join('sma_warehouses as w', 'w.id = p.warehouse_id', 'left');
        $this->db->join('sma_companies as s', 's.id = p.supplier_id', 'left');
        $this->db->join('sma_own_companies as oc', 'oc.id = p.own_company', 'left');
        $this->db->where('pi.product_id', $id);
        $this->db->where('pi.purchase_id != ""', $id);
        return $this->db->get()->result();
    }
    public function get_sales($id)
    {

        $this->db->select('
            si.id,
            sale.date,
            sale.reference_no,
            w.name as warehouse,
            customer.name as customer,
            oc.companyname as own_company,
            si.net_unit_price as selling,
            si.mrp,
            si.batch,
            si.expiry,
            si.quantity,
            si.item_tax,
            si.further_tax,
            si.adv_tax,
            si.subtotal
        ');
        $this->db->from('sma_sale_items as si');
        $this->db->join('sma_sales as sale', 'sale.id = si.sale_id', 'left');
        $this->db->join('sma_warehouses as w', 'w.id = sale.warehouse_id', 'left');
        $this->db->join('sma_companies as customer', 'customer.id = sale.customer_id', 'left');
        $this->db->join('sma_own_companies as oc', 'oc.id = sale.own_company', 'left');
        $this->db->where('si.product_id', $id);
        return $this->db->get()->result();
    }




    public function salesreturn($id)
    {

        $sendvalue = array();

        $this->db->select('
            sri.*,
            s.reference_no,
            s.date AS sale_date,
            sr.date,
            sma_brands.name AS brand_name,
            sma_products.name AS product_name,
            sma_products.hsn_code,
            sma_products.carton_size,
            sma_products.company_code,
            sma_warehouses.name AS warehouse_name
       ');
        $this->db->from('sma_sale_return_items_tb AS sri');
        $this->db->join('sma_sale_returns_tb AS sr', 'sr.id = sri.sale_return_id', 'left');
        $this->db->join('sma_sales AS s', 's.id = sr.sale_id', 'left');
        $this->db->join('sma_own_companies', 'sma_own_companies.id = s.own_company', 'left');
        $this->db->join('sma_products', 'sma_products.id = sri.product_id', 'left');
        $this->db->join('sma_brands', 'sma_brands.id = sma_products.brand', 'left');
        $this->db->join('sma_tax_rates', 'sma_tax_rates.id = sri.item_tax_id', 'left');
        $this->db->join('sma_warehouses', 'sma_warehouses.id = sri.warehouse_id', 'left');
        $this->db->join('sma_product_groups', 'sma_product_groups.id = sma_products.group_id', 'left');
        $this->db->where('sma_products.id', $id);

        $query = $this->db->get();
        $salesreturns = $query->result();

        foreach ($salesreturns as $salesreturn) {
            $data = array();
            array_push($data, $salesreturn->reference_no);
            array_push($data, $salesreturn->sale_date);
            array_push($data, $salesreturn->date);
            array_push($data, $salesreturn->brand_name);
            array_push($data, $salesreturn->warehouse_id);
            array_push($data, $salesreturn->warehouse_name);
            array_push($data, $salesreturn->product_id);
            array_push($data, $salesreturn->product_name);
            array_push($data, $salesreturn->hsn_code);
            array_push($data, $salesreturn->company_code);
            array_push($data, $salesreturn->carton_size);
            array_push($data, $salesreturn->expiry);
            array_push($data, $salesreturn->batch);
            array_push($data, $salesreturn->quantity);
            array_push($data, $salesreturn->mrp);
            // array_push($data, $salesreturn->net_unit_cost);
            array_push($data, $salesreturn->item_tax);
            array_push($data, $salesreturn->further_tax);
            array_push($data, $salesreturn->fed_tax);
            array_push($data, $salesreturn->total_tax);
            array_push($data, $salesreturn->subtotal);
            array_push($data, $salesreturn->reason);
            $sendvalue[] = $data;
        }

        return $sendvalue;
    }



    public function purchasereturn($id)
    {

        $sendvalue = array();

        $this->db->select('
                pri.*,
                p.reference_no,
                p.date as purchase_date,
                pr.return_date,
                sma_brands.name as brand_name,
                sma_products.name as product_name,
                sma_products.hsn_code,
                sma_products.carton_size,
                sma_products.company_code,
                sma_warehouses.name as warehouse_name,
            
            ');
        $this->db->from('sma_purchase_return_items_tb as pri');
        $this->db->join('sma_purchase_return_tb as pr', 'pr.id = pri.purchase_return_id', 'left');
        $this->db->join('sma_purchases as p', 'p.id = pr.purchase_id', 'left');

        $this->db->join('sma_companies as supplier', 'supplier.id = p.supplier_id', 'left');
        $this->db->join('sma_own_companies', 'sma_own_companies.id = p.own_company', 'left');
        $this->db->join('sma_products', 'sma_products.id = pri.product_id', 'left');
        $this->db->join('sma_brands', 'sma_brands.id = sma_products.brand', 'left');
        $this->db->join('sma_tax_rates', 'sma_tax_rates.id = pri.item_tax_id', 'left');
        $this->db->join('sma_warehouses', 'sma_warehouses.id = pri.warehouse_id', 'left');
        $this->db->join('sma_product_groups', 'sma_product_groups.id = sma_products.group_id', 'left');
        $this->db->where('sma_products.id', $id);

        $query = $this->db->get();
        $purchases = $query->result();
        foreach ($purchases as $purchase) {
            $data = array();
            array_push($data, $purchase->reference_no);
            array_push($data, $purchase->purchase_date);
            array_push($data, $purchase->return_date);
            array_push($data, $purchase->brand_name);
            array_push($data, $purchase->warehouse_id);
            array_push($data, $purchase->warehouse_name);
            array_push($data, $purchase->product_id);
            array_push($data, $purchase->product_name);
            array_push($data, $purchase->hsn_code);
            array_push($data, $purchase->company_code);
            array_push($data, $purchase->carton_size);
            array_push($data, $purchase->expiry);
            array_push($data, $purchase->batch);
            array_push($data, $purchase->quantity);
            array_push($data, $purchase->mrp);
            array_push($data, $purchase->net_unit_cost);
            array_push($data, $purchase->item_tax);
            array_push($data, $purchase->further_tax);
            array_push($data, $purchase->fed_tax);
            array_push($data, $purchase->total_tax);
            array_push($data, $purchase->subtotal);
            array_push($data, $purchase->reason);
            $sendvalue[] = $data;
        }

        return $sendvalue;
    }



    public function get_transfers($product_id)
    {
        $this->db->select("
            COALESCE(oc.companyname, 'Not found') AS Own_Company,
            '' AS Customer_NIC,
            '' AS Customer_NTN,
            t.transfer_no AS Transfer_No,
            t.date AS Date,
            w_to.name AS TO_Warehouse,
            w_from.name AS FROM_Warehouse,
            pi.product_id AS Product_ID,
            p.company_code AS Company_Code,
            p.code AS Barcode,
            p.hsn_code AS HSN_Code,
            p.name AS Product_Name,
            p.carton_size AS Carton_Size,
            pi.mrp AS MRP,
            pi.quantity AS Qty,
            p.sale_unit AS UOM,
            pi.unit_quantity AS Carton_Qty,
            pi.expiry AS Expiry_Date,
            pi.batch AS Batch,
            'Not found' AS Supplier_Manufacturer_Name,
            CASE
                WHEN tr.type = '1' THEN 'GST'
                WHEN tr.code = 'exp' THEN 'Exempted'
                ELSE '3rd Schedule'
            END AS Remarks,
            IFNULL(pg.id, 'Unknown Group') AS Group_ID,
            IFNULL(pg.name, 'Unknown Group') AS Group_Name
        ");
        $this->db->from('sma_transfers t');
        $this->db->join('sma_warehouses w_to', 'w_to.id = t.to_warehouse_id', 'left');
        $this->db->join('sma_warehouses w_from', 'w_from.id = t.from_warehouse_id', 'left');
        $this->db->join('sma_purchase_items pi', 'pi.transfer_id = t.id', 'left');
        $this->db->join('sma_products p', 'p.id = pi.product_id', 'left');
        $this->db->join('sma_tax_rates tr', 'tr.id = pi.tax_rate_id', 'left');
        $this->db->join('sma_product_groups pg', 'pg.id = p.group_id', 'left');
        $this->db->join('sma_own_companies oc', 'oc.id = 1', 'left');

        $this->db->where('p.id', $product_id);  // Filter by the specified product ID

        $query = $this->db->get();
        return $query->result();
    }







    // Old Code

    public function getAllProducts()
    {
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCategoryProducts($category_id)
    {
        $q = $this->db->get_where('products', array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSubCategoryProducts($subcategory_id)
    {
        $q = $this->db->get_where('products', array('subcategory_id' => $subcategory_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductOptions($pid)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductOptionsWithWH($pid)
    {
        $this->db->select($this->db->dbprefix('product_variants') . '.*, ' . $this->db->dbprefix('warehouses') . '.name as wh_name, ' . $this->db->dbprefix('warehouses') . '.id as warehouse_id, ' . $this->db->dbprefix('warehouses_products_variants') . '.quantity as wh_qty')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            ->join('warehouses', 'warehouses.id=warehouses_products_variants.warehouse_id', 'left')
            ->group_by(array('' . $this->db->dbprefix('product_variants') . '.id', '' . $this->db->dbprefix('warehouses_products_variants') . '.warehouse_id'))
            ->order_by('product_variants.id');
        $q = $this->db->get_where('product_variants', array('product_variants.product_id' => $pid, 'warehouses_products_variants.quantity !=' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getProductComboItems($pid)
    {
        $this->db->select($this->db->dbprefix('products') . '.id as id, ' . $this->db->dbprefix('products') . '.code as code, ' . $this->db->dbprefix('combo_items') . '.quantity as qty, ' . $this->db->dbprefix('products') . '.name as name, ' . $this->db->dbprefix('combo_items') . '.unit_price as price')->join('products', 'products.code=combo_items.item_code', 'left')->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function getProductByID($id)
    {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getSupplierByID($supplier)
    {

        $this->db->select('companies.id, companies.group_name, companies.company');
        $this->db->from('companies');
        $this->db->where('id', $supplier);

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductWithCategory($id)
    {
        $this->db->select($this->db->dbprefix('products') . '.*, ' . $this->db->dbprefix('categories') . '.name as category')
            ->join('categories', 'categories.id=products.category_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function has_purchase($product_id, $warehouse_id = NULL)
    {
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('purchase_items', array('product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public function getProductDetails($id)
    {
        $this->db->select($this->db->dbprefix('products') . '.code, ' . $this->db->dbprefix('products') . '.name, ' . $this->db->dbprefix('categories') . '.code as category_code, cost, price, quantity, alert_quantity')
            ->join('categories', 'categories.id=products.category_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function getAllProductList()
    {


        // // $this->db->save_queries = TRUE;

        $this->db->select($this->db->dbprefix('products') . '.code, ' . $this->db->dbprefix('products') . '.id, ' . $this->db->dbprefix('products') . '.name, ' . $this->db->dbprefix('categories') . '.code as category_code, cost, price, quantity, alert_quantity')
            ->join('categories', 'categories.id=products.category_id', 'left');
        $q = $this->db->get_where('products');

        // echo $this->db->last_query();

        // die();
        // exit();

        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }



    public function getProductDetail($id)
    {
        $this->db->select($this->db->dbprefix('products') . '.*, ' . $this->db->dbprefix('tax_rates') . '.name as tax_rate_name, ' . $this->db->dbprefix('tax_rates') . '.code as tax_rate_code, c.code as category_code, sc.code as subcategory_code', FALSE)
            ->join('tax_rates', 'tax_rates.id=products.tax_rate', 'left')
            ->join('categories c', 'c.id=products.category_id', 'left')
            ->join('categories sc', 'sc.id=products.subcategory_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSubCategories($parent_id)
    {
        $this->db->select('id as id, name as text')
            ->where('parent_id', $parent_id)->order_by('name');
        $q = $this->db->get("categories");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductByCategoryID($id)
    {

        $q = $this->db->get_where('products', array('category_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }
        return FALSE;
    }

    public function getAllWarehousesWithPQ($product_id)
    {
        $this->db->select('' . $this->db->dbprefix('warehouses') . '.*, ' . $this->db->dbprefix('warehouses_products') . '.quantity, ' . $this->db->dbprefix('warehouses_products') . '.rack')
            ->join('warehouses_products', 'warehouses_products.warehouse_id=warehouses.id', 'left')
            ->where('warehouses_products.product_id', $product_id)
            ->group_by('warehouses.id');
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductPhotos($id)
    {
        $q = $this->db->get_where("product_photos", array('product_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addProduct($data, $items, $warehouse_qty, $product_attributes, $photos)
    {
        if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();

            if ($items) {
                foreach ($items as $item) {
                    $item['product_id'] = $product_id;
                    $this->db->insert('combo_items', $item);
                }
            }

            $warehouses = $this->site->getAllWarehouses();
            if ($data['type'] != 'standard') {
                foreach ($warehouses as $warehouse) {
                    $this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
                }
            }

            $tax_rate = $this->site->getTaxRateByID($data['tax_rate']);

            if ($warehouse_qty && !empty($warehouse_qty)) {
                foreach ($warehouse_qty as $wh_qty) {
                    if (isset($wh_qty['quantity']) && !empty($wh_qty['quantity'])) {
                        $this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $wh_qty['warehouse_id'], 'quantity' => $wh_qty['quantity'], 'rack' => $wh_qty['rack'], 'avg_cost' => $data['cost']));

                        if (!$product_attributes) {
                            $tax_rate_id = $tax_rate ? $tax_rate->id : NULL;
                            $tax = $tax_rate ? (($tax_rate->type == 1) ? $tax_rate->rate . "%" : $tax_rate->rate) : NULL;
                            $unit_cost = $data['cost'];
                            if ($tax_rate) {
                                if ($tax_rate->type == 1 && $tax_rate->rate != 0) {
                                    if ($data['tax_method'] == '0') {
                                        $pr_tax_val = ($data['cost'] * $tax_rate->rate) / (100 + $tax_rate->rate);
                                        $net_item_cost = $data['cost'] - $pr_tax_val;
                                        $item_tax = $pr_tax_val * $wh_qty['quantity'];
                                    } else {
                                        $net_item_cost = $data['cost'];
                                        $pr_tax_val = ($data['cost'] * $tax_rate->rate) / 100;
                                        $unit_cost = $data['cost'] + $pr_tax_val;
                                        $item_tax = $pr_tax_val * $wh_qty['quantity'];
                                    }
                                } else {
                                    $net_item_cost = $data['cost'];
                                    $item_tax = $tax_rate->rate;
                                }
                            } else {
                                $net_item_cost = $data['cost'];
                                $item_tax = 0;
                            }

                            $subtotal = (($net_item_cost * $wh_qty['quantity']) + $item_tax);

                            $item = array(
                                'product_id' => $product_id,
                                'product_code' => $data['code'],
                                'product_name' => $data['name'],
                                'net_unit_cost' => $net_item_cost,
                                'unit_cost' => $unit_cost,
                                'dropship' => $data['dropship'],
                                'crossdock' => $data['crossdock'],
                                'mrp' => $data['mrp'],

                                'discount_one' => $data['discount_one'],
                                'discount_two' => $data['discount_two'],
                                'discount_three' => $data['discount_three'],

                                'real_unit_cost' => $unit_cost,
                                'quantity' => $wh_qty['quantity'],
                                'quantity_balance' => $wh_qty['quantity'],
                                'quantity_received' => $wh_qty['quantity'],
                                'item_tax' => $item_tax,
                                'tax_rate_id' => $tax_rate_id,
                                'tax' => $tax,
                                'subtotal' => $subtotal,
                                'warehouse_id' => $wh_qty['warehouse_id'],
                                'date' => date('Y-m-d'),
                                'status' => 'received',
                            );
                            $this->db->insert('purchase_items', $item);
                            $this->site->syncProductQty($product_id, $wh_qty['warehouse_id']);
                        }
                    }
                }
            }

            if ($product_attributes) {
                foreach ($product_attributes as $pr_attr) {
                    $pr_attr_details = $this->getPrductVariantByPIDandName($product_id, $pr_attr['name']);

                    $pr_attr['product_id'] = $product_id;
                    $variant_warehouse_id = $pr_attr['warehouse_id'];
                    unset($pr_attr['warehouse_id']);
                    if ($pr_attr_details) {
                        $option_id = $pr_attr_details->id;
                    } else {
                        $this->db->insert('product_variants', $pr_attr);
                        $option_id = $this->db->insert_id();
                    }
                    if ($pr_attr['quantity'] != 0) {
                        $this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $variant_warehouse_id, 'quantity' => $pr_attr['quantity']));

                        $tax_rate_id = $tax_rate ? $tax_rate->id : NULL;
                        $tax = $tax_rate ? (($tax_rate->type == 1) ? $tax_rate->rate . "%" : $tax_rate->rate) : NULL;
                        $unit_cost = $data['cost'];
                        if ($tax_rate) {
                            if ($tax_rate->type == 1 && $tax_rate->rate != 0) {
                                if ($data['tax_method'] == '0') {
                                    $pr_tax_val = ($data['cost'] * $tax_rate->rate) / (100 + $tax_rate->rate);
                                    $net_item_cost = $data['cost'] - $pr_tax_val;
                                    $item_tax = $pr_tax_val * $pr_attr['quantity'];
                                } else {
                                    $net_item_cost = $data['cost'];
                                    $pr_tax_val = ($data['cost'] * $tax_rate->rate) / 100;
                                    $unit_cost = $data['cost'] + $pr_tax_val;
                                    $item_tax = $pr_tax_val * $pr_attr['quantity'];
                                }
                            } else {
                                $net_item_cost = $data['cost'];
                                $item_tax = $tax_rate->rate;
                            }
                        } else {
                            $net_item_cost = $data['cost'];
                            $item_tax = 0;
                        }

                        $subtotal = (($net_item_cost * $pr_attr['quantity']) + $item_tax);
                        $item = array(
                            'product_id' => $product_id,
                            'product_code' => $data['code'],
                            'product_name' => $data['name'],
                            'net_unit_cost' => $net_item_cost,
                            'unit_cost' => $unit_cost,
                            'quantity' => $pr_attr['quantity'],
                            'option_id' => $option_id,
                            'quantity_balance' => $pr_attr['quantity'],
                            'quantity_received' => $pr_attr['quantity'],
                            'item_tax' => $item_tax,
                            'tax_rate_id' => $tax_rate_id,
                            'tax' => $tax,
                            'subtotal' => $subtotal,
                            'warehouse_id' => $variant_warehouse_id,
                            'date' => date('Y-m-d'),
                            'status' => 'received',
                        );
                        $item['option_id'] = !empty($item['option_id']) && is_numeric($item['option_id']) ? $item['option_id'] : NULL;
                        $this->db->insert('purchase_items', $item);
                    }

                    foreach ($warehouses as $warehouse) {
                        if (!$this->getWarehouseProductVariant($warehouse->id, $product_id, $option_id)) {
                            $this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
                        }
                    }

                    $this->site->syncVariantQty($option_id, $variant_warehouse_id);
                }
            }

            if ($photos) {
                foreach ($photos as $photo) {
                    $this->db->insert('product_photos', array('product_id' => $product_id, 'photo' => $photo));
                }
            }

            $this->site->syncQuantity(NULL, NULL, NULL, $product_id);
            return true;
        }
        return false;
    }

    public function getPrductVariantByPIDandName($product_id, $name)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id, 'name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addAjaxProduct($data)
    {
        if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();
            return $this->getProductByID($product_id);
        }
        return false;
    }

    public function add_products($products = array())
    {
        if (!empty($products)) {
            foreach ($products as $product) {
                $variants = explode('|', $product['variants']);
                unset($product['variants']);
                if ($this->db->insert('products', $product)) {
                    $product_id = $this->db->insert_id();
                    foreach ($variants as $variant) {
                        if ($variant && trim($variant) != '') {
                            $vat = array('product_id' => $product_id, 'name' => trim($variant));
                            $this->db->insert('product_variants', $vat);
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function getProductNames($term, $limit = 5)
    {

        $this->db->select('' . $this->db->dbprefix('products') . '.id, ' . $this->db->dbprefix('products') . '.code, ' . $this->db->dbprefix('products') . '.price, ' . $this->db->dbprefix('products') . '.cost, ' . $this->db->dbprefix('products') . '.dropship, ' . $this->db->dbprefix('products') . '.crossdock, ' . $this->db->dbprefix('products') . '.mrp, ' . $this->db->dbprefix('products') . '.name as name, ' . $this->db->dbprefix('products') . '.price as price, ' . $this->db->dbprefix('product_variants') . '.name as vname, ' . $this->db->dbprefix('tax_rates') . '.rate')
            ->where($this->db->dbprefix('products') . ".type != 'combo' AND " . $this->db->dbprefix('products') . ".status = 1 AND "
                . "(" . $this->db->dbprefix('products') . ".name LIKE '%" . $term . "%' OR " . $this->db->dbprefix('products') . ".code LIKE '%" . $term . "%' OR
                concat(" . $this->db->dbprefix('products') . ".name, ' (', " . $this->db->dbprefix('products') . ".code, ')') LIKE '%" . $term . "%')");
        $this->db->join($this->db->dbprefix('product_variants'), $this->db->dbprefix('product_variants') . '.product_id=' . $this->db->dbprefix('products') . '.id', 'left');
        $this->db->join($this->db->dbprefix('tax_rates'), $this->db->dbprefix('tax_rates') . '.id=' . $this->db->dbprefix('products') . ' .tax_rate', 'left')
            ->where('' . $this->db->dbprefix('product_variants') . '.name', NULL)
            ->group_by('products.id')->limit($limit);
        $q = $this->db->get('products');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getQASuggestions($term, $limit = 20)
    {
        $this->db->select('' . $this->db->dbprefix('products') . '.id, code, ' . $this->db->dbprefix('products') . '.name as name')
            ->where("type != 'combo' AND "
                . "(" . $this->db->dbprefix('products') . ".name LIKE '%" . $this->db->escape_like_str($term) . "%' OR code LIKE '%" . $this->db->escape_like_str($term) . "%' OR
                concat(" . $this->db->dbprefix('products') . ".name, ' (', code, ')') LIKE '%" . $this->db->escape_like_str($term) . "%')")
            ->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getProductsForPrinting($term, $limit = 5)
    {
        $this->db->select('' . $this->db->dbprefix('products') . '.id, code, ' . $this->db->dbprefix('products') . '.name as name, ' . $this->db->dbprefix('products') . '.price as price')
            ->where("(" . $this->db->dbprefix('products') . ".name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR
                concat(" . $this->db->dbprefix('products') . ".name, ' (', code, ')') LIKE '%" . $term . "%')")
            ->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function updateProduct($id, $data, $items, $warehouse_qty, $product_attributes, $photos, $update_variants)
    {
        if ($this->db->update('products', $data, array('id' => $id))) {

            if ($items) {
                $this->db->delete('combo_items', array('product_id' => $id));
                foreach ($items as $item) {
                    $item['product_id'] = $id;
                    $this->db->insert('combo_items', $item);
                }
            }

            $tax_rate = $this->site->getTaxRateByID($data['tax_rate']);

            if ($warehouse_qty && !empty($warehouse_qty)) {
                foreach ($warehouse_qty as $wh_qty) {
                    $this->db->update('warehouses_products', array('rack' => $wh_qty['rack']), array('product_id' => $id, 'warehouse_id' => $wh_qty['warehouse_id']));
                }
            }

            if (!empty($update_variants)) {
                foreach ($update_variants as $variant) {
                    $vr = $this->getProductVariantByID($id, $variant['id']);
                    if ($vr) {
                        $this->db->update('product_variants', $variant, ['id' => $vr->id]);
                    } else {
                        $this->db->insert('product_variants', $variant);
                    }
                }
            }

            if ($photos) {
                foreach ($photos as $photo) {
                    $this->db->insert('product_photos', array('product_id' => $id, 'photo' => $photo));
                }
            }

            if ($product_attributes) {
                foreach ($product_attributes as $pr_attr) {

                    $pr_attr['product_id'] = $id;
                    $variant_warehouse_id = $pr_attr['warehouse_id'];
                    unset($pr_attr['warehouse_id']);
                    $this->db->insert('product_variants', $pr_attr);
                    $option_id = $this->db->insert_id();

                    if ($pr_attr['quantity'] != 0) {
                        $this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $id, 'warehouse_id' => $variant_warehouse_id, 'quantity' => $pr_attr['quantity']));

                        $tax_rate_id = $tax_rate ? $tax_rate->id : NULL;
                        $tax = $tax_rate ? (($tax_rate->type == 1) ? $tax_rate->rate . "%" : $tax_rate->rate) : NULL;
                        $unit_cost = $data['cost'];
                        if ($tax_rate) {
                            if ($tax_rate->type == 1 && $tax_rate->rate != 0) {
                                if ($data['tax_method'] == '0') {
                                    $pr_tax_val = ($data['cost'] * $tax_rate->rate) / (100 + $tax_rate->rate);
                                    $net_item_cost = $data['cost'] - $pr_tax_val;
                                    $item_tax = $pr_tax_val * $pr_attr['quantity'];
                                } else {
                                    $net_item_cost = $data['cost'];
                                    $pr_tax_val = ($data['cost'] * $tax_rate->rate) / 100;
                                    $unit_cost = $data['cost'] + $pr_tax_val;
                                    $item_tax = $pr_tax_val * $pr_attr['quantity'];
                                }
                            } else {
                                $net_item_cost = $data['cost'];
                                $item_tax = $tax_rate->rate;
                            }
                        } else {
                            $net_item_cost = $data['cost'];
                            $item_tax = 0;
                        }

                        $subtotal = (($net_item_cost * $pr_attr['quantity']) + $item_tax);
                        $item = array(
                            'product_id' => $id,
                            'product_code' => $data['code'],
                            'product_name' => $data['name'],
                            'net_unit_cost' => $net_item_cost,
                            'unit_cost' => $unit_cost,
                            'quantity' => $pr_attr['quantity'],
                            'option_id' => $option_id,
                            'quantity_balance' => $pr_attr['quantity'],
                            'quantity_received' => $pr_attr['quantity'],
                            'item_tax' => $item_tax,
                            'tax_rate_id' => $tax_rate_id,
                            'tax' => $tax,
                            'subtotal' => $subtotal,
                            'warehouse_id' => $variant_warehouse_id,
                            'date' => date('Y-m-d'),
                            'status' => 'received',
                        );
                        $item['option_id'] = !empty($item['option_id']) && is_numeric($item['option_id']) ? $item['option_id'] : NULL;
                        $this->db->insert('purchase_items', $item);
                    }
                }
            }
            $this->site->syncQuantity(NULL, NULL, NULL, $id);
            return true;
        } else {
            return false;
        }
    }

    public function updateProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            if ($this->db->update('warehouses_products_variants', array('quantity' => $quantity), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        } else {
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function updatePrice($data = array())
    {
        if ($this->db->update_batch('products', $data, 'code')) {
            return true;
        }
        return false;
    }

    public function deleteProduct($id)
    {
        if ($this->db->delete('products', array('id' => $id)) && $this->db->delete('warehouses_products', array('product_id' => $id))) {
            $this->db->delete('warehouses_products_variants', array('product_id' => $id));
            $this->db->delete('product_variants', array('product_id' => $id));
            $this->db->delete('product_photos', array('product_id' => $id));
            $this->db->delete('product_prices', array('product_id' => $id));
            return true;
        }
        return FALSE;
    }


    public function totalCategoryProducts($category_id)
    {
        $q = $this->db->get_where('products', array('category_id' => $category_id));
        return $q->num_rows();
    }

    public function getCategoryByCode($code)
    {
        $q = $this->db->get_where('categories', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTaxRateByName($name)
    {
        $q = $this->db->get_where('tax_rates', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAdjustmentByID($id)
    {
        $q = $this->db->get_where('adjustments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAdjustmentItems($adjustment_id)
    {
        $this->db->select('adjustment_items.*, products.code as product_code, products.name as product_name, products.image, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=adjustment_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=adjustment_items.option_id', 'left')
            ->group_by('adjustment_items.id')
            ->order_by('id', 'asc');

        $this->db->where('adjustment_id', $adjustment_id);

        $q = $this->db->get('adjustment_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function syncAdjustment($data = array())
    {
        if (!empty($data)) {
            $clause = array('product_id' => $data['product_id'], 'option_id' => $data['option_id'], 'warehouse_id' => $data['warehouse_id'], 'status' => 'received');
            $qty = $data['type'] == 'subtraction' ? 0 - $data['quantity'] : 0 + $data['quantity'];
            $this->site->setPurchaseItem($clause, $qty);

            $this->site->syncProductQty($data['product_id'], $data['warehouse_id']);
            if ($data['option_id']) {
                $this->site->syncVariantQty($data['option_id'], $data['warehouse_id'], $data['product_id']);
            }
        }
    }

    public function reverseAdjustment($id)
    {
        if ($products = $this->getAdjustmentItems($id)) {
            foreach ($products as $adjustment) {
                $clause = array('product_id' => $adjustment->product_id, 'warehouse_id' => $adjustment->warehouse_id, 'option_id' => $adjustment->option_id, 'status' => 'received');
                $qty = $adjustment->type == 'subtraction' ? (0 + $adjustment->quantity) : (0 - $adjustment->quantity);
                $this->site->setPurchaseItem($clause, $qty);
                $this->site->syncProductQty($adjustment->product_id, $adjustment->warehouse_id);
                if ($adjustment->option_id) {
                    $this->site->syncVariantQty($adjustment->option_id, $adjustment->warehouse_id, $adjustment->product_id);
                }
            }
        }
    }

    public function addAdjustment($data, $products)
    {
        if ($this->db->insert('adjustments', $data)) {
            $adjustment_id = $this->db->insert_id();
            foreach ($products as $product) {
                $product['adjustment_id'] = $adjustment_id;
                $this->db->insert('adjustment_items', $product);
                $this->syncAdjustment($product);
            }
            if ($this->site->getReference('qa') == $data['reference_no']) {
                $this->site->updateReference('qa');
            }
            return true;
        }
        return false;
    }

    public function updateAdjustment($id, $data, $products)
    {
        $this->reverseAdjustment($id);
        if (
            $this->db->update('adjustments', $data, array('id' => $id)) &&
            $this->db->delete('adjustment_items', array('adjustment_id' => $id))
        ) {
            foreach ($products as $product) {
                $product['adjustment_id'] = $id;
                $this->db->insert('adjustment_items', $product);
                $this->syncAdjustment($product);
            }
            return true;
        }
        return false;
    }

    public function deleteAdjustment($id)
    {
        $this->reverseAdjustment($id);
        if (
            $this->db->delete('adjustments', array('id' => $id)) &&
            $this->db->delete('adjustment_items', array('adjustment_id' => $id))
        ) {
            return true;
        }
        return false;
    }

    public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array();
        }
        return FALSE;
    }

    public function addQuantity($product_id, $warehouse_id, $quantity, $rack = NULL)
    {

        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            if ($this->updateQuantity($product_id, $warehouse_id, $quantity, $rack)) {
                return TRUE;
            }
        } else {
            if ($this->insertQuantity($product_id, $warehouse_id, $quantity, $rack)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity, $rack = NULL)
    {
        $product = $this->site->getProductByID($product_id);
        if ($this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity, 'rack' => $rack, 'avg_cost' => $product->cost))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity, $rack = NULL)
    {
        $data = $rack ? array('quantity' => $quantity, 'rack' => $rack) : $data = array('quantity' => $quantity);
        if ($this->db->update('warehouses_products', $data, array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function products_count($category_id, $subcategory_id = NULL)
    {
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        $this->db->from('products');
        return $this->db->count_all_results();
    }

    public function fetch_products($category_id, $limit, $start, $subcategory_id = NULL)
    {

        $this->db->limit($limit, $start);
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        $this->db->order_by("id", "asc");
        $query = $this->db->get("products");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncVariantQty($option_id)
    {
        $wh_pr_vars = $this->getProductWarehouseOptions($option_id);
        $qty = 0;
        foreach ($wh_pr_vars as $row) {
            $qty += $row->quantity;
        }
        if ($this->db->update('product_variants', array('quantity' => $qty), array('id' => $option_id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getProductWarehouseOptions($option_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function setRack($data)
    {
        if ($this->db->update('warehouses_products', array('rack' => $data['rack']), array('product_id' => $data['product_id'], 'warehouse_id' => $data['warehouse_id']))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getSoldQty($id)
    {
        $this->db->select("date_format(" . $this->db->dbprefix('sales') . ".date, '%Y-%M') month, SUM( " . $this->db->dbprefix('sale_items') . ".quantity ) as sold, SUM( " . $this->db->dbprefix('sale_items') . ".subtotal ) as amount")
            ->from('sales')
            ->join('sale_items', 'sales.id=sale_items.sale_id', 'left')
            ->group_by("date_format(" . $this->db->dbprefix('sales') . ".date, '%Y-%m')")
            ->where($this->db->dbprefix('sale_items') . '.product_id', $id)
            //->where('DATE(NOW()) - INTERVAL 1 MONTH')
            ->where('DATE_ADD(curdate(), INTERVAL 1 MONTH)')
            ->order_by("date_format(" . $this->db->dbprefix('sales') . ".date, '%Y-%m') desc")->limit(3);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasedQty($id)
    {
        $this->db->select("date_format(" . $this->db->dbprefix('purchases') . ".date, '%Y-%M') month, SUM( " . $this->db->dbprefix('purchase_items') . ".quantity ) as purchased, SUM( " . $this->db->dbprefix('purchase_items') . ".subtotal ) as amount")
            ->from('purchases')
            ->join('purchase_items', 'purchases.id=purchase_items.purchase_id', 'left')
            ->group_by("date_format(" . $this->db->dbprefix('purchases') . ".date, '%Y-%m')")
            ->where($this->db->dbprefix('purchase_items') . '.product_id', $id)
            //->where('DATE(NOW()) - INTERVAL 1 MONTH')
            ->where('DATE_ADD(curdate(), INTERVAL 1 MONTH)')
            ->order_by("date_format(" . $this->db->dbprefix('purchases') . ".date, '%Y-%m') desc")->limit(3);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllVariants()
    {
        $q = $this->db->get('variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getWarehouseProductVariant($warehouse_id, $product_id, $option_id = NULL)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('product_id' => $product_id, 'option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchaseItems($purchase_id)
    {
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransferItems($transfer_id)
    {
        $q = $this->db->get_where('purchase_items', array('transfer_id' => $transfer_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUnitByCode($code)
    {
        $q = $this->db->get_where("units", array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getBrandByName($name)
    {
        $q = $this->db->get_where('brands', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStockCountProducts($warehouse_id, $type, $categories = NULL, $brands = NULL)
    {
        $this->db->select("{$this->db->dbprefix('products')}.id as id, {$this->db->dbprefix('products')}.code as code, {$this->db->dbprefix('products')}.name as name, {$this->db->dbprefix('warehouses_products')}.quantity as quantity")
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->where('warehouses_products.warehouse_id', $warehouse_id)
            ->where('products.type', 'standard')
            ->order_by('products.code', 'asc');
        if ($categories) {
            $r = 1;
            $this->db->group_start();
            foreach ($categories as $category) {
                if ($r == 1) {
                    $this->db->where('products.category_id', $category);
                } else {
                    $this->db->or_where('products.category_id', $category);
                }
                $r++;
            }
            $this->db->group_end();
        }
        if ($brands) {
            $r = 1;
            $this->db->group_start();
            foreach ($brands as $brand) {
                if ($r == 1) {
                    $this->db->where('products.brand', $brand);
                } else {
                    $this->db->or_where('products.brand', $brand);
                }
                $r++;
            }
            $this->db->group_end();
        }

        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStockCountProductVariants($warehouse_id, $product_id)
    {
        $this->db->select("{$this->db->dbprefix('product_variants')}.name, {$this->db->dbprefix('warehouses_products_variants')}.quantity as quantity")
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left');
        $q = $this->db->get_where('product_variants', array('product_variants.product_id' => $product_id, 'warehouses_products_variants.warehouse_id' => $warehouse_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function addStockCount($data)
    {
        if ($this->db->insert('stock_counts', $data)) {
            return TRUE;
        }
        return FALSE;
    }

    public function finalizeStockCount($id, $data, $products)
    {
        if ($this->db->update('stock_counts', $data, array('id' => $id))) {
            foreach ($products as $product) {
                $this->db->insert('stock_count_items', $product);
            }
            return TRUE;
        }
        return FALSE;
    }

    public function getStouckCountByID($id)
    {
        $q = $this->db->get_where("stock_counts", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStockCountItems($stock_count_id)
    {
        $q = $this->db->get_where("stock_count_items", array('stock_count_id' => $stock_count_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return NULL;
    }

    public function getAdjustmentByCountID($count_id)
    {
        $q = $this->db->get_where('adjustments', array('count_id' => $count_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductVariantID($product_id, $name)
    {
        $q = $this->db->get_where("product_variants", array('product_id' => $product_id, 'name' => $name), 1);
        if ($q->num_rows() > 0) {
            $variant = $q->row();
            return $variant->id;
        }
        return FALSE;
    }
    public function getProductVariantByID($product_id, $id)
    {
        $q = $this->db->get_where("product_variants", array('product_id' => $product_id, 'id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllStores($id)
    {
        $data = array();
        $this->db->select('stores_tb.id,stores_tb.name');
        $query = $this->db->get('stores_tb');
        $return = array();
        foreach ($query->result() as $store) {

            if (!empty($this->getIntegratedStoreProduct($store->id, $id))) {
                $store_products = $this->getIntegratedStoreProduct($store->id, $id);
                foreach ($store_products as $store_product) {
                    $temdata['store_name'] = $store->name;
                    $temdata['spid'] = $store_product->store_product_id;
                    $temdata['spname'] = $store_product->product_name;
                    $temdata['product_type'] = $store_product->update_qty_in;
                    $temdata['price_type'] = $store_product->price_type;
                    $data[] = $temdata;
                }
            } else {
                $temdata['store_name'] = $store->name;
                $temdata['spid'] = "Not Integrated";
                $temdata['spname'] = "Not Integrated";
                $temdata['product_type'] = "Not Integrated";
                $temdata['price_type'] = "Not Integrated";
                $data[] = $temdata;
            }
        }
        return $data;
    }
    public function getIntegratedStoreProduct($store_id, $id)
    {
        $this->db->select('store_products_tb.product_id,store_products_tb.product_name,store_products_tb.store_product_id,store_products_tb.update_qty_in,store_products_tb.price_type');
        $this->db->where('store_id', $store_id);
        $this->db->where('product_id', $id);
        $query = $this->db->get('store_products_tb');
        return $query->result();
    }
}
