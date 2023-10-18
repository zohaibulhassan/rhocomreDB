<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">

        </div>
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Sale Return Items Report With Remarks</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                    <thead>
                        <tr>
                            <th>Sale Date</th>
                            <th>Refrence No</th>
                            <th>PO Number</th>
                            <th>Cutomer</th>
                            <th>Own Company</th>
                            <th>Warehouse</th>
                            <th>Product ID</th>
                            <th>Company Code</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Expiry</th>
                            <th>Price With-Out Tax</th>
                            <th>MRP</th>
                            <th>Tax</th>
                            <th>Sub Total</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rows as $row) {
                        ?>
                            <tr>
                                <td><?php echo date_format(date_create($row->sale_date), "Y-m-d"); ?></td>
                                <td><?php echo $row->reference_no; ?></td>
                                <td><?php echo $row->po_number; ?></td>
                                <td><?php echo $row->customer; ?></td>
                                <td><?php echo $row->own_company; ?></td>
                                <td><?php echo $row->warehouse_name; ?></td>
                                <td><?php echo $row->product_id; ?></td>
                                <td><?php echo $row->company_code; ?></td>
                                <td><?php echo $row->product_name; ?></td>
                                <td><?php echo $row->quantity; ?></td>
                                <td><?php echo $row->expiry; ?></td>
                                <td><?php echo $row->net_unit_price; ?></td>
                                <td><?php echo $row->mrp; ?></td>
                                <td><?php echo $row->tax; ?></td>
                                <td><?php echo $row->subtotal; ?></td>
                                <td><?php echo $row->note; ?></td>
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
            "scrollX": true,
            "scrollCollapse": true,
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