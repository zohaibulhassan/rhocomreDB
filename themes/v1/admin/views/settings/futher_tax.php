<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-toggle" style="opacity: 1; transform: scale(1);"></i>
                </div>
                <h3 class="md-card-toolbar-heading-text">Further Tax</h3>
            </div>
            <div class="md-card-content">
                <form action="<?php echo base_url('admin/system_settings/updatefurtherdax'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">
                        <div class="uk-width-large-1-1">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Further Tax</label>
                                <input class="md-input label-fixed" type="text" name="furthertax" value="<?php echo $furthertax[0]->further_tax; ?>" readonly>
                            </div>
                        </div>
                        <div class="uk-width-large-1-4" style="padding-top: 20px;">
                            <button type="button" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" id="openUpdateModal">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="uk-modal" id="modal_update">
    <?php
    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
    echo admin_form_open_multipart("system_settings/updatefurtherdax", $attrib);
    ?>
    <input type="hidden" name="show_type" value="2">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Update Further Tax</h3>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Further Tax</label>
                        <input class="md-input label-fixed" type="text" name="furthertax" value="<?php echo $furthertax[0]->further_tax; ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="submit" class="md-btn md-btn-primary md-btn-flat" id="updatebtn">Update</button>
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
        </div>
    </div>
    <?php echo form_close(); ?>
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
    $.DataTableInit({
        selector: '#dt_tableExport',
        url: "<?= admin_url('system_settings/get_tax_rates'); ?>",
        data: data,
        aaSorting: [
            [0, "desc"]
        ],
        columnDefs: [{
            "targets": 5,
            "orderable": false
        }],
        fixedColumns: {
            left: 0,
            right: 1
        },
        scrollX: false
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        // Show modal on "Update" button click
        $('#openUpdateModal').click(function() {
            UIkit.modal('#modal_update').show(); // Corrected modal ID here
        });

        // ... rest of your code ...
    });
</script>