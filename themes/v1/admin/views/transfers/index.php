<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
</style>

<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Transfers</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                    <thead>
                        <tr class="active">
                            <th><?= lang("date"); ?></th>
                            <th><?= lang("ref_no"); ?></th>
                            <th><?= lang("warehouse") . ' (' . lang('from') . ')'; ?></th>
                            <th><?= lang("warehouse") . ' (' . lang('to') . ')'; ?></th>

                            <th>
                                <?= lang("Transfer Name"); ?>
                            </th>

                            <th>
                                <?= lang("Transfer Code"); ?>
                            </th>

                            <th>
                                <?= lang("Transfer Name"); ?>
                            </th>

                            <th>
                                <?= lang("total"); ?>
                            </th>
                            <th>
                                <?= lang("product_tax"); ?>
                            </th>
                            <th>
                                <?= lang("grand_total"); ?>
                            </th>
                            <th>
                           
                            <?= lang("Actions"); ?>
                             
                            </th>
                        </tr>
                    </thead>
                  
                        <?php foreach ($rows as $row) : ?>
                            <tr>
                                <td><?= $row->date ?></td>
                                <td><?= $row->transfer_no ?></td>
                                <td><?= $row->fname ?></td>
                                <td><?= $row->fcode ?></td>
                                <td><?= $row->tname ?></td>
                                <td><?= $row->tcode ?></td>
                                <td><?= $row->total ?></td>
                                <td><?= $row->total_tax ?></td>
                                <td><?= $row->grand_total ?></td>
                                <td><?= $row->status ?></td>
                                <td><a href="<?= base_url('admin/transfers/pdf/'.$row->id) ?>">Download As Pdf</a></td>


                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="md-fab-wrapper md-fab-in-card" style="position: fixed; bottom: 20px;">
    <a class="md-fab md-fab-success md-fab-wave waves-effect waves-button addbtn" href="<?= base_url('admin/transfers/add') ?>">
        <i class="fa-solid fa-plus"></i>
    </a>
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


    // $.DataTableInit({
    //     selector: '#dt_tableExport',
    //     url: "<?php echo admin_url('transfers/getTransfers'); ?>",
    //     data: data,
    //     aaSorting: [
    //         [1, "desc"]
    //     ],
    //     columnDefs: [{
    //             "targets": 0,
    //             "orderable": false
    //         },
    //         {
    //             "targets": 30,
    //             "orderable": false
    //         }
    //     ],
    //     fixedColumns: {
    //         left: 0,
    //         right: 2
    //     },
    //     scrollX: true
    // });
</script>
<script>
    $(document).ready(function() {
        $('#dt_tableExport').DataTable();
    });
</script>