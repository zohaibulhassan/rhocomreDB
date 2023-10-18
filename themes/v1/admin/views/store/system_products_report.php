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
                <form action="<?php echo base_url('admin/stores/system_products_report'); ?>" method="get">
                    <div class="uk-grid">
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Supplier</label>
                                <input type="hidden" name="sid" value="<?= $store->id ?>" >
                                <select name="supplier" class="uk-width-1-1 select2" >
                                    <option value="">Select Supplier</option>
                                    <option value="all" <?php if($gsupplier == 'all'){ echo 'selected'; } ?> >All</option>
                                    <?php
                                            foreach($suppliers as $supplier){
                                                echo '<option ';
                                                if($supplier->id == $gsupplier){
                                                    echo 'selected';
                                                }
                                                echo ' value="'.$supplier->id.'">'.$supplier->name.'</option>';
                                            }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-1" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
                            <a href="<?php echo base_url('admin/stores/system_products_report'); ?>?sid=<?= $store->id ?>" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


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
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Avaiable Qty</th>
                            <th>MRP</th>
                            <th>Store Product ID</th>
                            <th>Store Product Name</th>
                            <th>Type</th>
                            <th>Update Qty In</th>
                            <th>Update Price In</th>
                            <th>Apply Discount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) {
                        ?>
                            <tr>
                                <td><?=  $product['pid']; ?></td>
                                <td><?=  $product['pname']; ?></td>
                                <td><?=  $product['brand_name']; ?></td>
                                <td><?=  $product['qty']; ?></td>
                                <td><?=  $product['pmrp']; ?></td>
                                <td><?=  $product['spid']; ?></td>
                                <td><?=  $product['spname']; ?></td>
                                <td><?=  $product['update_in']; ?></td>
                                <td><?=  $product['update_qty_in']; ?></td>
                                <td><?=  $product['price_type']; ?></td>
                                <td><?=  $product['discount']; ?></td>
                                <td><?=  $product['status']; ?></td>
                            </tr>
                        <?php } ?>
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
        $.DataTableInit2({
            selector:'#dt_tableExport',
            paging:true,
            dom: 'Bfrtip',
            aaSorting: [[0, "desc"]],
            fixedColumns:   {left: 0,right: 0},
            scrollX: false
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>