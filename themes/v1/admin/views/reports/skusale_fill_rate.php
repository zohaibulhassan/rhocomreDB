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
                <form action="<?php echo base_url('admin/reports/purchase_fill_rate'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Start Date</label>
                                <input class="md-input  label-fixed" type="text" name="start" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="on" value="<?= $this->data['start'];   ?>" readonly>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>To Date</label>
                                <input class="md-input  label-fixed" type="text" name="end" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="on" value="<?= $this->data['end'];   ?>" readonly>
                            </div>
                        </div>
                        <div class="uk-width-large-2-4" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
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
            <th>ID</th>
            <th>PID</th>
            <th>Barcode</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>TP Price</th>
            <th>Tax Value</th>
            <th>Tax Type</th>
            <th>Completed Qty</th>
            <th>Expected Complete Qty</th>
            <th>Group SKU Expected Qty</th>
            <th>SO Status</th>
            <th>SO ID</th>
            <th>Supplier ID</th>
            <th>Supplier Name</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Retailer ID</th>
            <th>Retailer Name</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $row) { ?>
        <tr>
            <td><?php echo $row->id; ?></td>
            <td><?php echo $row->pid; ?></td>
            <td><?php echo $row->barcode; ?></td>
            <td><?php echo $row->name; ?></td>
            <td><?php echo $row->quantity; ?></td>
            <td><?php echo $row->tp_price; ?></td>
            <td><?php echo $row->tax_value; ?></td>
            <td><?php echo $row->tax_type; ?></td>
            <td><?php echo $row->completed_qty; ?></td>
            <td><?php echo $row->expected_complete_qty; ?></td>
            <td><?php echo $row->group_sku_expected_qty; ?></td>
            <td><?php echo $row->so_status; ?></td>
            <td><?php echo $row->so_id; ?></td>
            <td><?php echo $row->supplier_id; ?></td>
            <td><?php echo $row->supplier_name; ?></td>
            <td><?php echo $row->customer_id; ?></td>
            <td><?php echo $row->customer_name; ?></td>
            <td><?php echo $row->etailer_id; ?></td>
            <td><?php echo $row->etailer_name; ?></td>
            <td><?php echo $row->order_date; ?></td>
        </tr>
        <!-- Add more rows if you have more data -->
    </tbody>
<?php } ?>
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
        $('.select2').select2();
    });
</script>