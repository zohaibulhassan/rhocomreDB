<?php defined('BASEPATH') or exit('No direct script access allowed');

class Db_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getLatestSales($user_id)
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            // $this->db->where('created_by', $this->session->userdata('user_id'));
            $this->db->where('supplier_id', $user_id);
        }

        // // $this->db->save_queries = TRUE;


        $this->db->order_by('id', 'desc');
        $q = $this->db->get("sales", 5);

        // echo $this->db->last_query();

        // die();
        // exit();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getUsersDetails($user_id)
    {
        // // $this->db->save_queries = TRUE;
        $this->db->where('id', $user_id);
        $q = $this->db->get("users");
        // echo $this->db->last_query();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLastestQuotes()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("quotes", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestPurchases($user_id)
    {

        // $this->db->save_queries = TRUE;

        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            // $this->db->where('created_by', $this->session->userdata('user_id'));
            $this->db->where('supplier_id', $user_id);
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("purchases", 5);

        // echo $this->db->last_query();
        // echo "123";
        // die();
        // exit();



        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestTransfers()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("transfers", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestCustomers()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get_where("companies", array('group_name' => 'customer'), 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestSuppliers()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get_where("companies", array('group_name' => 'supplier'), 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getChartData($user_id)
    {
        // // $this->db->save_queries = TRUE;

        $myQuery = "SELECT S.month,
        COALESCE(S.sales, 0) as sales,
        COALESCE( P.purchases, 0 ) as purchases,
        COALESCE(S.tax1, 0) as tax1,
        COALESCE(S.tax2, 0) as tax2,
        COALESCE( P.ptax, 0 ) as ptax
        FROM (  SELECT  date_format(date, '%Y-%m') Month,
                SUM(total) Sales,
                SUM(product_tax) tax1,
                SUM(order_tax) tax2
                FROM " . $this->db->dbprefix('sales') . "
                WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH )";
        if ($user_id != 0) {
            $myQuery .= "AND sma_sales.supplier_id = '" . $user_id . "'";
        }
        $myQuery .= "GROUP BY date_format(date, '%Y-%m')) S
            LEFT JOIN ( SELECT  date_format(date, '%Y-%m') Month,
                        SUM(product_tax) ptax,
                        SUM(order_tax) otax,
                        SUM(total) purchases
                        FROM " . $this->db->dbprefix('purchases') . "
                        GROUP BY date_format(date, '%Y-%m')) P
            ON S.Month = P.Month
            ORDER BY S.Month";
        $q = $this->db->query($myQuery);

        // echo $this->db->last_query();
        // die();
        // exit();


        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStockValue()
    {
        $q = $this->db->query("SELECT SUM(qty*price) as stock_by_price, SUM(qty*cost) as stock_by_cost
        FROM (
            Select sum(COALESCE(" . $this->db->dbprefix('warehouses_products') . ".quantity, 0)) as qty, price, cost
            FROM " . $this->db->dbprefix('products') . "
            JOIN " . $this->db->dbprefix('warehouses_products') . " ON " . $this->db->dbprefix('warehouses_products') . ".product_id=" . $this->db->dbprefix('products') . ".id
            GROUP BY " . $this->db->dbprefix('warehouses_products') . ".id ) a");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

 public function getBestSeller($user_details, $start_date = NULL, $end_date = NULL)
{
    if (!$start_date) {
        $start_date = date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00';
    }
    if (!$end_date) {
        $end_date = date('Y-m-d', strtotime('last day of this month')) . ' 23:59:59';
    }
    $this->db
        ->select("product_name, brands.name as brand, FORMAT(ROUND(SUM(sma_sale_items.quantity * sma_products.unit_weight)),2) AS quantity")
        ->from('sale_items')
        ->join('products', 'products.id = sale_items.product_id', 'left')
        ->join('brands', 'brands.id = products.brand', 'left')
        ->join('sales', 'sales.id = sale_items.sale_id', 'left')
        ->where('date >=', $start_date)
        ->where('date <', $end_date);

    if ($user_details != 0) {
        $this->db->where('sales.supplier_id = ', $user_details);
    }
    $this->db
        ->group_by('product_name, brand')
        ->order_by('quantity', 'DESC') // Order by the calculated quantity in descending order
        ->limit(10);
    $q = $this->db->get();

    if ($q->num_rows() > 0) {
        foreach (($q->result()) as $row) {
            $data[] = $row;
        }
        return $data;
    }
    return FALSE;
}


    public function getBrandwiseSeller($user_details, $start_date = NULL, $end_date = NULL)
    {
        if (!$start_date) {
            $start_date = date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00';
        }
        if (!$end_date) {
            $end_date = date('Y-m-d', strtotime('last day of this month')) . ' 23:59:59';
        }

        $sql = "
        SELECT
        sma_brands.name AS brandname,
        FORMAT(ROUND(SUM(sma_sale_items.quantity * sma_products.unit_weight)),2) AS Total_Sales_In_Liters,
        FORMAT(ROUND(SUM(sma_products.carton_size)),2) AS Carton_Size,
        FORMAT(ROUND(SUM((IF(sma_companies.sales_type = 'consignment', sma_sale_items.unit_price,
                IF(sma_companies.sales_type = 'crossdock', sma_sale_items.crossdock,
                    IF(sma_companies.sales_type = 'dropship', sma_sale_items.dropship, sma_sale_items.dropship))) * sma_sale_items.quantity + (sma_sale_items.item_tax + sma_sale_items.further_tax + sma_sale_items.fed_tax)))),2) AS value_excl_tax
    FROM
        sma_sales
    LEFT JOIN
        sma_sale_items ON sma_sales.id = sma_sale_items.sale_id
    LEFT JOIN
        sma_companies ON sma_companies.id = sma_sales.customer_id
    LEFT JOIN
        sma_etailers ON sma_etailers.id = sma_companies.etailers_id
    LEFT JOIN
        sma_tax_rates ON sma_sale_items.tax_rate_id = sma_tax_rates.id
    LEFT JOIN
        sma_own_companies ON sma_sales.own_company = sma_own_companies.id
    LEFT JOIN
        sma_products ON sma_products.id = sma_sale_items.product_id
    LEFT JOIN
        sma_brands ON sma_brands.id = sma_products.brand
    LEFT JOIN
        sma_warehouses ON sma_warehouses.id = sma_sale_items.warehouse_id
    WHERE
        sma_sales.date >= '$start_date' AND sma_sales.date < '$end_date'
    ";

        if ($user_details != 0) {
            $sql .= " AND sma_sales.supplier_id = $user_details";
        }

        $sql .= "
    GROUP BY
        sma_brands.name
    ";

        $q = $this->db->query($sql);

        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return FALSE;
    }


    public function getBestSellerByEtailer($user_details,$start_date = NULL, $end_date = NULL)
    {
        if (!$start_date) {
            $start_date = date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00';
        }
        if (!$end_date) {
            $end_date = date('Y-m-d', strtotime('last day of this month')) . ' 23:59:59';
        }

        $sql = "
        SELECT
            sma_etailers.name AS etailername,
            sma_etailers.id AS etailerID,
            FORMAT(ROUND(SUM(sma_sale_items.quantity * sma_products.unit_weight)),2) AS Total_Sales_In_Liters,
            FORMAT(ROUND(SUM(sma_products.carton_size)),2) AS Carton_Size,
            FORMAT(ROUND(SUM((IF(sma_companies.sales_type = 'consignment', sma_sale_items.unit_price,
                    IF(sma_companies.sales_type = 'crossdock', sma_sale_items.crossdock,
                        IF(sma_companies.sales_type = 'dropship', sma_sale_items.dropship, sma_sale_items.dropship))) * sma_sale_items.quantity + (sma_sale_items.item_tax + sma_sale_items.further_tax + sma_sale_items.fed_tax)))),2) AS value_excl_tax
        FROM
            sma_sales
        LEFT JOIN
            sma_sale_items ON sma_sales.id = sma_sale_items.sale_id
        LEFT JOIN
            sma_companies ON sma_companies.id = sma_sales.customer_id
        LEFT JOIN
            sma_etailers ON sma_etailers.id = sma_companies.etailers_id
        LEFT JOIN
            sma_tax_rates ON sma_sale_items.tax_rate_id = sma_tax_rates.id
        LEFT JOIN
            sma_own_companies ON sma_sales.own_company = sma_own_companies.id
        LEFT JOIN
            sma_products ON sma_products.id = sma_sale_items.product_id
        LEFT JOIN
            sma_warehouses ON sma_warehouses.id = sma_sale_items.warehouse_id
        WHERE
            sma_sales.date >= '$start_date' AND sma_sales.date < '$end_date'
            AND sma_etailers.name IS NOT NULL AND sma_etailers.name != ''
        ";
        

        if ($user_details != 0) {
            $sql .= " AND sma_sales.supplier_id = $user_details";
        }

        $sql .= "
    GROUP BY
    sma_etailers.name;
    ";
    
        $q = $this->db->query($sql);

        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return FALSE;
    }



}