<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Wallets</h3>
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
                            <th>Name</th>
                            <th>Available Balance</th>
                            <th>Location</th>
                            <th>Users</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th class="dt-no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($wallets as $w) {

                        ?>
                            <tr>
                                <td><?php echo $w->id ?></td>
                                <td><?php echo $w->title ?></td>
                                <td><?php echo $w->amount ?></td>
                                <td><?php echo $w->wname ?></td>
                                <td><?php echo $w->users ?></td>
                                <td><?php echo $w->created_at ?></td>
                                <td><?php echo ucwords($w->status) ?></td>
                                <td>
                                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light md-btn-mini" href="<?php echo base_url('admin/system_settings/wallet_transations?wid=' . $w->id); ?>">Transation List</a>
                                    <a class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light md-btn-mini" href="<?php echo base_url('admin/system_settings/edit_wallet?wid=' . $w->id); ?>">Edit</a>
                                    <?php
                                    if ($w->status == "active") {
                                    ?>
                                        <a class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light md-btn-mini" href="<?php echo base_url('admin/system_settings/wallets_add_transation?wid=' . $w->id); ?>">Deposit Amount</a>
                                        <button class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini deletebtn" data-id="<?php echo $w->id ?>">Delete</button>
                                    <?php
                                    }
                                    ?>
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
<div class="md-fab-wrapper md-fab-in-card" style="position: fixed;bottom: 20px;">
    <a class="md-fab md-fab-success md-fab-wave waves-effect waves-button " href="<?php echo base_url('admin/system_settings/add_wallet'); ?>"><i class="fa-solid fa-plus"></i></a>
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
    $.DataTableInit2({
        selector: '#dt_tableExport',
        aaSorting: [
            [0, "desc"]
        ],
        fixedColumns: {
            left: 0,
            right: 0
        },
        scrollX: false
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.deletebtn', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Do you want to delete this wallet",
                input: "text",
                showCancelButton: true,
                confirmButtonColor: "#e53935",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                buttonsStyling: true
            }).then(function(res) {
                console.log(res);
                if (res.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting Own wallet!',
                        showCancelButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: '<?php echo base_url("admin/system_settings/delete_wallet"); ?>',
                                type: 'POST',
                                data: {
                                    [csrfName]: csrfHash,
                                    id: id,
                                    reason: res.value
                                },
                                success: function(data) {
                                    var obj = jQuery.parseJSON(data);
                                    swal.close()
                                    if (obj.status) {
                                        toastr.success(obj.message);
                                        location.reload();
                                    } else {
                                        toastr.error(obj.message);
                                    }

                                }
                            });
                        }
                    });
                }
            })
        });
    });
</script>