<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-toggle" style="opacity: 1; transform: scale(1);"></i>
                </div>
                <h3 class="md-card-toolbar-heading-text">Filters (Sales Return Report) </h3>
            </div>
            <div class="md-card-content">
                <!-- <p>Direct Download sale report <a href="#modal-full" uk-toggle id="upgrade-link">Download</a></p> -->


                <form action="<?php echo base_url('admin/reports/salesreturnreport'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">


                    
                       <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled"> 
                                <?php echo lang('warehouse', 'customers'); ?>
                                <?php
                                $whl[''] = lang('select') . ' ' . lang('warehouse');
                                foreach ($warehouses as $warehouse) {
                                    $whl[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $whl, $swarehouse, 'class="form-control input-tip select" id="warehouse" data-placeholder="' . $this->lang->line('select') . ' ' . $this->lang->line('warehouse') . '" style="width:100%;"');
                                ?>
                            </div>
                        </div> 
                        
                        <!-- <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled"> -->
                                <?php //echo lang('Company', 'Company'); ?>
                                <?php

                                // $oc['all'] = 'All';
                                // foreach ($own_companies as $own_companies) {
                                //     $oc[$own_companies->id] = $own_companies->companyname;
                                // }
                                // // echo form_dropdown('own_company', $oc, $own_company, 'id="poown_companies" class="form-control input-tip select" data-placeholder="' . lang('select') . ' ' . lang('own_companies') . '" required="required" style="width:100%;" ');
                                ?>
                            <!-- </div>
                        </div> -->

                        <!-- <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled"> -->
                                <?php //echo lang('suppliers', 'suppliers'); ?>
                                <?php
                                // $bl['all'] = 'All';
                                // foreach ($suppliers as $supplier) {
                                    // $bl[$supplier->id] = $supplier->name;
                                // }
                                //echo form_dropdown('supplier', $bl, $csupplier, 'id="suppliers" data-placeholder="' . lang('select') . ' ' . lang('supplier') . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            <!-- </div>
                        </div> -->

                        <!-- <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled"> -->
                                <?php 
                                    // echo lang('customers', 'customers');
                                 ?>
                                <?php
                                // $whl[''] = lang('select') . ' ' . lang('Customers');
                                // foreach ($customers as $customer) {
                                    // $whl[$customer->id] = $customer->name;
                                // }
                                // echo form_dropdown('customer', $whl, $customer, 'class="form-control input-tip select" id="customer" data-placeholder="' . $this->lang->line('select') . ' ' . $this->lang->line('customer') . '" style="width:100%;"');
                                ?>
                            <!-- </div>
                        </div> -->
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Start Date</label>
                                <input class="md-input  label-fixed" type="text" name="start"
                                    data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="on"
                                    value="<?= $this->data['start']; ?>" readonly>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>To Date</label>
                                <input class="md-input  label-fixed" type="text" name="end"
                                    data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="on"
                                    value="<?= $this->data['end']; ?>" readonly>
                            </div>
                        </div>

                        <div class="uk-width-large-2-4" style="padding-top: 20px;">
                            <button type="submit"
                                class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Sale Report</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr>
                            <th>Reference No</th>
                            <th>Sale Date</th>
                            <th>Sales Return Date</th>
                            <th>Brand</th>
                            <th>Warehouse ID</th>
                            <th>Warehouse Name</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>HSN Code</th>
                            <th>Company Code</th>
                            <th>Carton Size</th>
                            <th>Expiry</th>
                        </tr>
                    </thead>
                    <tbody>

                              <!-- array_push($data, $salesreturn->reference_no);  // 0
                                    array_push($data, $salesreturn->sale_date); //1
                                    array_push($data, $salesreturn->date);  // 2
                                    array_push($data, $salesreturn->brand_name); //3
                                    array_push($data, $salesreturn->warehouse_id); // 4
                                    array_push($data, $salesreturn->warehouse_name); //5
                                    array_push($data, $salesreturn->product_id);  //6
                                    array_push($data, $salesreturn->product_name); // 7 
                                    array_push($data, $salesreturn->hsn_code); // 8
                                    array_push($data, $salesreturn->company_code); // 9
                                    array_push($data, $salesreturn->carton_size); // 10
                                    array_push($data, $salesreturn->expiry); // 11
                                    array_push($data, $salesreturn->batch);
                                    array_push($data, $salesreturn->quantity);
                                    array_push($data, $salesreturn->mrp);
                                    // array_push($data, $salesreturn->net_unit_cost);
                                    array_push($data, $salesreturn->item_tax);
                                    array_push($data, $salesreturn->further_tax);
                                    array_push($data, $salesreturn->fed_tax);
                                    array_push($data, $salesreturn->total_tax);
                                    array_push($data, $salesreturn->subtotal);
                                    array_push($data, $salesreturn->reason); -->
             
                                    <?php 
                                        // echo "<pre>";
                                        // print_r($row);
                                        // echo "<pre>";
                                        // die();
                                        
                                    ?> 

                            <tr>

                                <?php foreach ($rows['data'] as $row) { ?>    

                                    <td>
                                        <?php echo $row[0]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[1]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[2]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[3]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[4]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[5]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[6]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[7]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[8]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[9]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[10]; ?>
                                    </td>
                                    <td>
                                        <?php echo $row[11]; ?>
                                    </td>

                                   
                                </tr>
                                <?php
                                }
                                ?> 

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>


<div class="uk-modal" id="modal_full_body">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Direct Sale Report Download</h3>
            <p>Download sale report via csv format</p>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
                echo admin_form_open_multipart("reports/salesdirectcsv", $attrib);
                ?>


                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <?php echo lang('own_companies', 'own_companies'); ?>
                        <?php
                        $oc['all'] = 'All';
                        foreach ($own_companies as $own_companies) {
                            $oc[$own_companies->id] = $own_companies->companyname;
                        }
                        echo form_dropdown('own_company', $oc, $own_company, 'id="poown_companies" class="form-control input-tip select" data-placeholder="' . lang('select') . ' ' . lang('own_companies') . '" required="required" style="width:100%;" ');
                        ?>
                    </div>
                </div>

                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <?php echo lang('suppliers', 'suppliers'); ?>
                        <?php
                        $bl['all'] = 'All';
                        foreach ($suppliers as $supplier) {
                            $bl[$supplier->id] = $supplier->name;
                        }
                        echo form_dropdown('supplier', $bl, $csupplier, 'id="suppliers" data-placeholder="' . lang('select') . ' ' . lang('supplier') . '" required="required" class="form-control input-tip select" style="width:100%;"');
                        ?>
                    </div>
                </div>

                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <?php echo lang('customers', 'customers'); ?>
                        <?php
                        $whl[''] = lang('select') . ' ' . lang('Customers');
                        foreach ($customers as $customer) {
                            $whl[$customer->id] = $customer->name;
                        }
                        echo form_dropdown('warehouse', $whl, $customer, 'class="form-control input-tip select" id="warehouse" data-placeholder="' . $this->lang->line('select') . ' ' . $this->lang->line('customer') . '" style="width:100%;"');
                        ?>
                    </div>
                </div>


                <!-- <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?php echo lang('warehouse', 'customers'); ?>
                                <?php
                                $whl[''] = lang('select') . ' ' . lang('warehouse');
                                foreach ($warehouses as $warehouse) {
                                    $whl[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $whl, $swarehouse, 'class="form-control input-tip select" id="warehouse" data-placeholder="' . $this->lang->line('select') . ' ' . $this->lang->line('warehouse') . '" style="width:100%;"');
                                ?>
                            </div>
                        </div> -->

                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Start Date</label>
                        <input class="md-input  label-fixed" type="text" name="start"
                            data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="on"
                            value="<?= $this->data['start']; ?>" readonly>
                    </div>
                </div>


                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>To Date</label>
                        <input class="md-input  label-fixed" type="text" name="end"
                            data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="on"
                            value="<?= $this->data['end']; ?>" readonly>
                    </div>
                </div>

                <div class="uk-width-large-1-1" style="padding-top: 20px;">
                    <button type="submit"
                        class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>





<!-- datatables -->
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<!-- datatables buttons-->
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/buttons.uikit.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/vfs_fonts.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.colVis.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.html5.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.print.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-fixedcolumns/dataTables.fixedColumns.min.js"></script>
<!-- datatables custom integration -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/datatables.uikit.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/datatable.js"></script>
<script>
    var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
        csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var data = [];
    data[csrfName] = csrfHash;

    $(document).ready(function () {
        $('#dt_tableExport').DataTable({
            dom: 'Bfrtip',
            "scrollX": true,
            "scrollCollapse": true,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
    $(document).ready(function () {
        $('#upgrade-link').click(function (e) {
            e.preventDefault();
            UIkit.modal('#modal_full_body').show();
        });
    });
</script>