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
                <h3 class="md-card-toolbar-heading-text">Filters </h3>
            </div>
            <div class="md-card-content">
                <form action="<?php echo base_url('admin/reports/salessummary'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?php echo lang('own_companies', 'own_companies'); ?>
                                <?php
                                $oc['all'] = 'All';
                foreach ($own_companies as $own_companies) {
                    $oc[$own_companies->id] = $own_companies->companyname;
                }
                echo form_dropdown('own_company', $oc, $own_company, 'id="poown_companies" class="form-control input-tip select" data-placeholder="'.lang('select').' '.lang('own_companies').'" required="required" style="width:100%;" ');
                ?> </div>
                        </div>

                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?php echo lang('suppliers', 'suppliers'); ?>
                                <?php
                $bl['all'] = 'All';
                foreach ($suppliers as $supplier) {
                    $bl[$supplier->id] = $supplier->name;
                }
                echo form_dropdown('supplier', $bl, $csupplier, 'id="suppliers" data-placeholder="'.lang('select').' '.lang('supplier').'" required="required" class="form-control input-tip select" style="width:100%;"');
                ?>
                            </div>
                        </div>

                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?php echo lang('customers', 'customers'); ?>
                                <?php
                $bl['all'] = 'All';
                foreach ($customers as $customer) {
                    $bl[$customer->id] = $customer->name;
                }
                echo form_dropdown('customers', $bl, $ccustomer, 'id="customers" data-placeholder="'.lang('select').' '.lang('cuctomer').'" required="required" class="form-control input-tip select" style="width:100%;"');
                ?>
                            </div>
                        </div>



                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?php echo lang('warehouse', 'customers'); ?>
                                <?php
             $whl['all'] = 'All';
                foreach ($warehouses as $warehouse) {
                    $whl[$warehouse->id] = $warehouse->name;
                }
                echo form_dropdown('warehouse', $whl, $swarehouse, 'class="form-control input-tip select" id="warehouse" data-placeholder="'.$this->lang->line('select').' '.$this->lang->line('warehouse').'" style="width:100%;"');
                ?>
                            </div>
                        </div>


                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Start Date</label>
                                <input class="md-input  label-fixed" type="text" name="start_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off"  readonly>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>End Date</label>
                                <input class="md-input  label-fixed" type="text" name="end_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off"  readonly>
                            </div>
                        </div>





                        <div class="uk-width-large-1-4" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Product Sales Summary</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
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

                    <tr>

                    <?php  foreach($rows['data'] as $row){   ?>  


                        <td><?=  $row[0]; ?></td>
                        <td><?=  $row[1]; ?></td>
                        <td><?=  $row[2]; ?></td>
                        <td><?=  $row[3]; ?></td>
                        <td><?=  $row[4]; ?></td>
                        <td><?=  $row[5]; ?></td>
                        <td><?=  $row[6]; ?></td>
                        <td><?=  $row[7]; ?></td>
                        <td><?=  $row[8]; ?></td>
                        <td><?=  $row[9]; ?></td>
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

<!-- datatables -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<!-- datatables buttons-->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/buttons.uikit.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/vfs_fonts.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.colVis.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.html5.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.print.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-fixedcolumns/dataTables.fixedColumns.min.js"></script>
<!-- datatables custom integration -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/datatables.uikit.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/datatable.js"></script>
<script>
    var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
        csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var data = [];
    data[csrfName] = csrfHash;

    $(document).ready(function() {
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
    $(document).ready(function() {
        $('#dt_tableExport').DataTable();
    });
</script>