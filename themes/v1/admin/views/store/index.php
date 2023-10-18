<div id="page_content">

    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Stores</h3>
               
                <div class="md-card-toolbar-actions" style="float: right;">
                <button class="uk-button uk-button-primary" id="addInBulkButton">Add In Bulk</button>
            </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Stock Margin</th>
                            <th>Default Quantity Unit</th>
                            <th>Default Price Type</th>
                            <th>Auto SO</th>
                            <th>Auto Batch Selection</th>
                            <th>Auto Invoice</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th class="dt-no-export" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="uk-modal" id="modal_extendpackage">
            <div class="uk-modal-dialog">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">
                    Add Product In BULK
                </h2>
                <div class="uk-margin">
                    <label class="uk-form-label" for="csv_file">Please Fill The Fields And Upload CSV File.:</label>
                        <!-- <form id="csvUploadForm" action="stores/addbulk_submit" method="POST" enctype="multipart/form-data"> -->
                        <form id="csvUploadForm" enctype="multipart/form-data">
                            <div class="uk-form-controls uk-width-1-1">
                            <p>Store ID: <span id="store_id_placeholder"></span></p>
                            <input class="uk-input" type="file" id="columnNames" name="csv_file" required>
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <button class="uk-button uk-button-primary" id="saveButton">Save</button>
                        </form>
                    </div>
                </div>
                </div>
                <div class="uk-modal-body"></div>
                    <div class="uk-modal-footer uk-flex uk-flex-between">
                        <a href="#" class="uk-button uk-button-primary" id="generateButton">Download Sample</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="md-fab-wrapper md-fab-in-card" style="position: fixed;bottom: 20px;">
    <a class="md-fab md-fab-success md-fab-wave waves-effect waves-button" href="<?php echo base_url('admin/stores/add'); ?>"><i class="fa-solid fa-plus"></i></a>
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


<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --> -->

<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->

<script>
    var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
        csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var data = {
        [csrfName]: csrfHash,
    };
    $.DataTableInit({
        selector:'#dt_tableExport',
        dom: 'Bfrtip',
        url:"<?php echo admin_url('stores/get_stores'); ?>",
        data:data,
        aaSorting: [[0, "desc"]],
        columnDefs: [
            { 
                "targets": 11,
            }
        ],
        fixedColumns:   {left: 0,right: 1},
        scrollX: false
    });
</script>
<script>
$(document).ready(function(){

    $('.select2').select2();

    $(document).on('click','.deletebtn',function(){
        
        var id = $(this).data('id');
        Swal.fire({
            title: "Do you want to delete this store. Please Enter Reason",
            input: "text",
            showCancelButton: true,
            confirmButtonColor: "#e53935",
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            buttonsStyling: true
        }).then(function (res) {
            console.log(res);
            if(res.isConfirmed){
                Swal.fire({
                    title: 'Deleting Store!',
                    showCancelButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        $.ajax({
                            url: '<?php echo base_url('admin/stores/delete'); ?>', //
                            type: 'POST',
                            data: {[csrfName]:csrfHash,id:id,reason:res.value},
                            success: function(data) {
                                var obj = jQuery.parseJSON(data);
                                swal.close()
                                if(obj.status){
                                    toastr.success(obj.message);
                                    $('#dt_tableExport').DataTable().ajax.reload()
                                }
                                else{
                                    toastr.error(obj.message);
                                }
                                
                            }
                        });
                    }
                });
            }
        })
    });
        
    $(document).on('click', '.bulk_workbtn', function () 
    {
        event.preventDefault(); 
 
        var storeId = $(this).data('store-id');
        $('#store_id_placeholder').text(storeId);

        UIkit.modal('#modal_extendpackage').show();
    });

    $(document).on('click', '#generateButton', function () 
    {
        event.preventDefault(); 
        // const columnNames = "name, Integration Type, stock_margin, Default Quantity Unit, Default Price Type, Auto SO, Auto Batch Selection, Auto Invoice";
        // const csvData = `${columnNames}\nrho_hr1,Shopify,100,Single,cost,No,Yes,Yes`;

        // const columnNames = "product_id, name, store_product_id,update_type,qty_type,price_type,warehouse_id,discount,supplier_id"; 
        // const csvData = `${columnNames}\n4156,Shopify,100,Single,5,online,55,50%,20`;

        const columnNames = "Product ID, Product Name, Store Product ID,Update In Type,Update Quantity Type,Price Type,Warehouse ID,Discount,Supplier ID"; 
        const csvData = `${columnNames}\n4156,Shopify,100,qty,single,online,55,50%,20`;

        const blob = new Blob([csvData], { type: 'text/csv' });

        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'BulkIntegration.csv'; 

        link.click();

        window.URL.revokeObjectURL(link.href);
    
    });

    document.getElementById('saveButton').addEventListener('click', function (e) 
    {
        e.preventDefault();
        
        var stored_id = 0;

        var formData = new FormData($('#csvUploadForm')[0]);
        stored_id = $('#store_id_placeholder').text();
        // formData.append('storeid', stored_id);
   
        var csrfToken = '<?= $this->security->get_csrf_hash(); ?>';
 
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('/admin/stores/addbulk_submit?storeid='); ?>" + stored_id,
            data: formData,
            processData: false, 
            contentType: false, 
            dataType: "json",
            headers: {
                'X-CSRF-Token': csrfToken 
            },
            success: function(response) 
            {
                alert(response.codestatus); 
                // UIkit.modal('#modal_extendpackage').hide();
                // location.reload();
            },
            error: function() {
                alert("Error occurred during the AJAX request.");
            }
        });
    });
});

</script>