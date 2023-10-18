<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Wallet Transections</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr class="primary">
                            <th>Date</th>
                            <th>Account Head</th>
                            <th>Detail</th>
                            <th>Particulars</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th style="max-width:65px; text-align:center;"><?= lang("actions") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $balance = 0;
                        foreach ($rows as $row) {
                            $balance = ($row->debit - $row->credit) + $balance;
                        ?>
                            <tr>
                                <td><?php echo $row->tdate; ?></td>
                                <td><?php echo $row->detail; ?></td>
                                <td><?php echo $row->category; ?></td>
                                <td><?php echo $row->particular; ?></td>
                                <td><?php echo amountformate($row->debit, 0); ?></td>
                                <td><?php echo amountformate($row->credit, 0); ?></td>
                                <td><?php echo amountformate($balance, 0); ?></td>
                                <td><?php echo $row->tstatus; ?></td>
                                <td>
                                    <?php if ($Owner || $Admin || $GP['wallet_payment_delete']) : ?>
                                        <?php
                                        if ($row->tstatus == "Cash-In") {
                                        ?>
                                            <!-- <button onclick="javascript:deletetransaction()"><i class="fa fa-trash"></i></button> -->
                                            <a onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo base_url('admin/system_settings/wallet_delete_deposit/' . $row->tid); ?>"><i class="fa fa-trash"></i></a>

                                        <?php
                                        }
                                        ?>
                                    <?php endif; ?>
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


<script>
    $(document).ready(function() {
        // $('.select2').select2();
        $(document).on('click', '.addbtn', function() {
            UIkit.modal('#modal_addwallet').show();
        });


        $(document).on('click', '.depbtn', function() {
            var wid = "<?php echo $w->id; ?>";
            $('input[name="wid"]').val(wid);
            UIkit.modal('#modal_depbtn').show();
        });

    });
</script>