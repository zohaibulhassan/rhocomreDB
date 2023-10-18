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
                <form action="<?php echo base_url('admin/reports/creadits'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">
                    <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("suppliers", "suppliers"); ?>
                                <?php
                                $bl["all"] = "All";
                                foreach ($suppliers as $supplier) {
                                    $bl[$supplier->id] = $supplier->name;
                                }
                                echo form_dropdown('supplier', $bl, $supplier, 'id="suppliers" data-placeholder="' . lang("select") . ' ' . lang("supplier") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div>
                        </div>

                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("customers", "customers"); ?>
                                <?php
                                $bl["all"] = "All";
                                foreach ($customers as $customer) {
                                    $bl[$customer->id] = $customer->name;
                                }
                                echo form_dropdown('customers', $bl, $customer, 'id="customers" data-placeholder="' . lang("select") . ' ' . lang("cuctomer") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                ?>
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
                <h3 class="md-card-toolbar-heading-text">Creadit Limits Report</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Supplier</th>
                            <th>Creadit Limit</th>
                            <th>Due Amount</th>
                            <th>Available Limit</th>
                            <th>Testing</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($rows as $row){
                        ?>
                        <tr>
                            <td><?= $row->cname ?></td>
                            <td><?= $row->sname ?></td>
                            <td><?= $this->sma->formatMoney($row->creadit_limit) ?></td>
                            <td>
                                <?= $this->sma->formatMoney($row->due_amount) ?>
                                <a href="<?= admin_url('reports/due_invoices?supplier='.$row->supplier_id.'&customer='.$row->customer_id) ?>" style="display: block;width: 29px;float: right;">
                                    <i class="fa fa-list-ul tip" data-placement="left" style="margin-left: 6px;" title="Due Invoices"></i>
                                </a>
                            </td>
                            <td><?php
                                $available = $row->creadit_limit-$row->due_amount;
                                if($available < 0){
                                    echo '<span style="color:red;" >'.$this->sma->formatMoney($available).'</span>';
                                }
                                else {
                                    echo $this->sma->formatMoney($available);
                                }
                            ?></td>
                            <td><?= $row->testing_date ?></td>
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