<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Store Products</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr>
                            <th>System ID</th>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Store Product ID</th>
                            <th>Type</th>
                            <th>Warehouse</th>
                            <th>Quantity Unit</th>
                            <th>Price Type</th>
                            <th>Discount Apply</th>
                            <th>Status</th>
                            <th class="dt-no-export">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) {
                        ?>
                            <tr>
                                <td><?= $product->id ?></td>
                                <td><?= $product->pid ?></td>
                                <td><?= $product->pname ?></td>
                                <td><?= $product->spid ?></td>
                                <td><?= ucwords($product->update_in) ?></td>
                                <td><?= $product->warehouse_name ?></td>
                                <td><?= ucwords($product->update_qty_in) ?></td>
                                <td><?= ucwords($product->price_type) ?></td>
                                <td><?= $product->discountname ?></td>
                                <td><?= ucwords($product->status) ?></td>
                                <td>
                                    <a href="<?php echo base_url('admin/stores/product_edit?id='.$product->id.'&store_id=' . $storeid); ?>" class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light md-btn-mini ">Edit</a>
                                    <button class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini " data-id="<?= $product->id ?>" id="deletebtn">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="md-fab-wrapper md-fab-in-card" style="position: fixed;bottom: 20px;">
    <a class="md-fab md-fab-success md-fab-wave waves-effect waves-button" href="<?php echo base_url('admin/stores/product_add?store_id=' . $storeid); ?>"><i class="fa-solid fa-plus"></i></a>
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
        $('.select2').select2();
        $(document).on('click', '#deletebtn', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Do you want to delete this link product. Please Enter Reason",
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
                        title: 'Deleting Store!',
                        showCancelButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: '<?php echo base_url("admin/stores/product_delete"); ?>',
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