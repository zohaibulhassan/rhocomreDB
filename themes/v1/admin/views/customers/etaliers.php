<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Customers</h3>
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
                            <th>Date</th>
                            <th>User</th>

                            <th class="dt-no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;

                        foreach ($etailers as $r) :  ?>
                            <tr>
                                <td><?= $counter ?></td>
                                <td><?= $r->name ?></td>
                                <td><?= $r->created_at ?></td>
                                <td><?= $r->created_by ?></td>
                                <td>
                                <a class="btn btn-sm btn-warning edit-etailers" data-id="<?= $r->id ?>" data-name="<?= $r->name ?>" href="#">Edit</a>
                                    <a class="btn btn-sm btn-danger" href="<?php echo admin_url('customsers/etailers_delete/' . $r->id); ?>">Delete</a>
                                </td>
                            </tr>
                        <?php $counter++;
                        endforeach;  ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="md-fab-wrapper md-fab-in-card" style="position: fixed; bottom: 20px;">
    <button class="md-fab md-fab-success md-fab-wave waves-effect waves-button addbtn" type="button">
        <i class="fa-solid fa-plus"></i>
    </button>
</div>

<div class="uk-modal" id="modal_addpackage">
    <!-- Add wallet modal content goes here -->
    <?php
    // $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
    // echo admin_form_open_multipart("package/add", $attrib);
    ?>

    <?php
    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
    echo admin_form_open_multipart("customers/etailers_add", $attrib);
    ?>
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Add Etailers</h3>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Etailers Name</label>
                        <input type="text" name="etailers" class="md-input md-input-success label-fixed">
                    </div>

                    <div class="uk-modal-footer uk-text-right">
                        <button type="submit" class="md-btn md-btn-success md-btn-flat submitbtn">Submit</button>
                        <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
</div>


<div class="uk-modal" id="modal_edit_etailers">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Edit Etailers</h3>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Etailers Name</label>
                        <input type="text" id="edit_etailers_name" class="md-input md-input-success label-fixed">
                    </div>
                    <!-- You can add more input fields for editing other data here -->
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button id="update_etailers_btn" class="md-btn md-btn-success md-btn-flat">Update</button>
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
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



        $(document).on('click', '.addbtn', function() {
            UIkit.modal('#modal_addpackage').show();
        });
    
        $(document).on('click', '.edit-etailers', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
    
            // Populate the modal with data from the selected row
            $('#edit_etailers_name').val(name);
    
            // Store the ID for later use when updating the data
            $('#update_etailers_btn').data('id', id);
    
            // Show the modal
            UIkit.modal('#modal_edit_etailers').show();
        });
    
    
        $('#update_etailers_btn').click(function() {
            var id = $(this).data('id');
            var newName = $('#edit_etailers_name').val();
    
            // Perform the update operation using AJAX
            $.ajax({
                url: '<?php echo base_url('admin/customerd/etailers_edit'); ?>',
                type: 'POST',
                data: {
                    id: id,
                    name: newName,
                    // Add other data you want to update
                },
                success: function(data) {
                    // Handle the response from the server (e.g., show success message)
                    // Close the modal if the update is successful
                    UIkit.modal('#modal_edit_etailers').hide();
                }
            });
        });
    });

</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $(document).on('click', '.deletebtn', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Do you want to delete this customer",
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
                        title: 'Deleting Own Customer!',
                        showCancelButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: '<?php echo base_url("admin/customers/delete"); ?>',
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
                                        $('#dt_tableExport').DataTable().ajax.reload()
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