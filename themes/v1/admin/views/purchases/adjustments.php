<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
</style>

<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Batch Adjustment</h3>
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
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Old Batch</th>
                            <th>Batch</th>
                            <th>Qty</th>
                            <th>Expiry</th>
                            <th>Cost</th>
                            <th>Price</th>
                            <th>Dropship</th>
                            <th>Crossdock</th>
                            <th>MRP</th>
                            <th>Adjust By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php
                                foreach($rows as $row){
                                    ?>
                                    <tr>
                                        <td><?= $row->adj_date ?></td>
                                        <td><?= $row->product_id ?></td>
                                        <td><?= $row->product_name ?></td>
                                        <td><?= $row->old_batch ?></td>
                                        <td><?= $row->batch ?></td>
                                        <td><?= $row->quantity_received ?></td>
                                        <td><?= $row->expiry ?></td>
                                        <td><?= $row->net_unit_cost ?></td>
                                        <td><?= $row->price ?></td>
                                        <td><?= $row->dropship ?></td>
                                        <td><?= $row->crossdock ?></td>
                                        <td><?= $row->mrp ?></td>
                                        <td><?= $row->first_name.' '.$row->last_name ?></td>
                                        <td>                                          
                                            <!-- <?php
                                                if($Owner || $Admin || $GP['purchase_adj_delete']){
                                                ?> -->
                                                <a  data-id="<?=  $row->id ?>" class="deletebtn" ><i class="fa fa-trash"></i></a>
                                                <!-- <?php
                                                }
                                            ?>                                             -->
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