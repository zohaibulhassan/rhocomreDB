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
                <form action="<?php echo base_url('admin/reports/etailersale_fill_rate'); ?>" method="get">
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
            <th>Date</th>
            <th>Ref No</th>
            <th>PO Number</th>
            <th>Delivery Date</th>
            <th>Created At</th>
            <th>Accounts Team Status</th>
            <th>Operation Team Status</th>
            <th>Status</th>
            <th>Supplier ID</th>
            <th>Supplier Name</th>
            <th>Etailier ID</th>
            <th>Etailier Name</th>
            <th>Customer Name</th>
            <th>Warehouse Name</th>
            <th>Warehouse Code</th>
            <th>Total Qty</th>
            <th>Total Val</th>
            <th>Complete Qty</th>
            <th>Total Cval</th>
            <th>Percal</th>
            <th>Pervcal</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($rows as $row) : ?>

            <tr>
                <td><?= $row->date ?></td>
                <td><?= $row->ref_no ?></td>
                <td><?= $row->po_number ?></td>
                <td><?= $row->delivery_date ?></td>
                <td><?= $row->created_at ?></td>
                <td><?= $row->accounts_team_status ?></td>
                <td><?= $row->operation_team_status ?></td>
                <td><?= $row->status ?></td>
                <td><?= $row->supplierid ?></td>
                <td><?= $row->supplier_name ?></td>
                <td><?= $row->etalier_id ?></td>
                <td><?= $row->etalier_name ?></td>
                <td><?= $row->customer_name ?></td>
                <td><?= $row->warehouse_name ?></td>
                <td><?= $row->warehouse_code ?></td>
                <td><?= $row->total_qty ?></td>
                <td><?= $row->total_val ?></td>
                <td><?= $row->complete_qty ?></td>
                <td><?= $row->total_cval ?></td>
                <td><?= $row->percal ?></td>
                <td><?= $row->pervcal ?></td>
            </tr>
        <?php endforeach; ?>
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
        $('.select2').select2();
    });
</script>