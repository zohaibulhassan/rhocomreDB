<style>
    .table thead tr th {
        text-align: left !important;
    }
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i>Sales Summary</h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12" id="headerdiv">
                <p class="introtext">
                    <?= lang('customize_report'); ?>
                </p>
                <div>
                    <form action="<?= admin_url('reports/salessummary') ?>" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("own_companies", "poown_companies"); ?>
                                    <?php
                                    $oc["all"] = "All";
                                    foreach ($own_companies as $own_companies) {
                                        $oc[$own_companies->id] = $own_companies->companyname;
                                    }
                                    echo form_dropdown('own_company', $oc, $own_company, 'id="poown_companies" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("own_companies") . '" required="required" style="width:100%;" ');
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("suppliers", "suppliers"); ?>
                                    <?php
                                    $bl["all"] = "All";
                                    foreach ($suppliers as $supplier) {
                                        $bl[$supplier->id] = $supplier->name;
                                    }
                                    echo form_dropdown('supplier', $bl, $csupplier, 'id="suppliers" data-placeholder="' . lang("select") . ' ' . lang("supplier") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("customers", "customers"); ?>
                                    <?php
                                    $bl["all"] = "All";
                                    foreach ($customers as $customer) {
                                        $bl[$customer->id] = $customer->name;
                                    }
                                    echo form_dropdown('customers', $bl, $ccustomer, 'id="customers" data-placeholder="' . lang("select") . ' ' . lang("cuctomer") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                            <?php
                            if ($user_warehouses == "") {
                            ?>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="warehouse">
                                            <?= lang("warehouse"); ?>
                                        </label>
                                        <?php
                                        $whl[""] = lang('select') . ' ' . lang('warehouse');
                                        foreach ($warehouses as $warehouse) {
                                            $whl[$warehouse->id] = $warehouse->name;
                                        }
                                        echo form_dropdown('warehouse', $whl, $swarehouse, 'class="form-control select" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                        ?>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="text" name="start_date" value="<?php echo $start ?>" class="form-control date2" id="start_date" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="text" name="end_date" value="<?php echo $end ?>" class="form-control date2" id="end_date" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="controls">
                                    <input type="submit" value="Submit" class="btn btn-primary" id="submitbtn" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="tabledb">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Warehouse Name</th>
                            <th>Own Compnay</th>
                            <th>Invoice No</th>
                            <th>Customer Name</th>
                            <th>Supplier</th>
                            <th>Value Excluding Tax</th>
                            <th>Item Tax</th>
                            <th>Discount</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.22/b-1.6.4/b-flash-1.6.4/b-html5-1.6.4/b-print-1.6.4/sb-1.0.0/datatables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>

<script>
    $(document).ready(function() {
        $('.date2').datetimepicker({
            format: 'yyyy-mm-dd',
            fontAwesome: true,
            language: 'sma',
            todayBtn: 1,
            autoclose: 1,
            minView: 2
        });

        function loadTable(req = "") {
            <?php
            $req = "";
            if ($own_company != "") {
                $req .= "own_company=" . $own_company . "&";
            }
            if ($swarehouse != "") {
                $req .= "warehouse=" . $swarehouse . "&";
            }
            if ($csupplier != "") {
                $req .= "supplier=" . $csupplier . "&";
            }
            if ($ccustomer != "") {
                $req .= "customer=" . $ccustomer . "&";
            }
            if ($start != "") {
                $req .= "start=" . urlencode($start) . "&";
            }
            if ($end != "") {
                $req .= "end=" . urlencode($end) . "&";
            }
            ?>
            $('.tabledb').DataTable({
                dom: 'Bfrtip',
                ajax: "<?= admin_url('reports/salessummary_ajax?' . $req) ?>",
                buttons: [{
                        extend: 'copy',
                    },
                    {
                        extend: 'csv',
                    },
                    {
                        extend: 'excel',
                    },
                ]
            });
        }
        loadTable();
    });
</script>