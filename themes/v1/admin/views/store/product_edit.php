 <div id="page_content">
     <div id="page_content_inner">
         <div class="md-card">
             <div class="md-card-toolbar">
                 <h3 class="md-card-toolbar-heading-text">Edit Link Product </h3>
             </div>
             <div class="md-card-content">
                 <?php
                    $attrib = ['data-toggle' => 'validator', 'role' => 'form', 'id' => 'updateForm'];
                    echo admin_form_open_multipart('#', $attrib);
                    ?>
                 <div class="uk-grid">
                     <div class="uk-width-large-1-3" style="margin-top: 15px;">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Store ID</label>
                             <input class="md-input label-fixed" type="text" name="sid" id="update_sid" value="<?php echo $product->store_id; ?>" readonly>
                             <input type="hidden" name="updateid" id="updateid" value="<?php echo $product->id; ?>" >
                         </div>
                     </div>
                     <div class="uk-width-large-1-3" style="margin-top: 15px;">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Product ID</label>
                             <input class="md-input label-fixed" type="text" name="pid" id="update_pid" value="<?php echo $product->product_id; ?>" readonly>
                         </div>
                     </div>
                     <div class="uk-width-large-1-3" style="margin-top: 15px;">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Store Product ID</label>
                             <input class="md-input label-fixed" type="text" name="spid" id="update_spid" value="<?php echo $product->store_product_id; ?>" readonly>
                         </div>
                     </div>
                     <div class="uk-width-large-1-1" style="margin-top: 15px;">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Product <span class="red">*</span></label>
                             <input type="text" name="product_name" id="product" class="md-input md-input-success label-fixed" value="<?php echo $product->product_name; ?>" readonly>
                         </div>
                     </div>
                     <div class="uk-width-large-1-3">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Warehouse <span class="red">*</span></label><br>
                             <select name="warehouse_id" id="warehouse_id" class="uk-width-1-1 select2">
                                 <?php
                                    $bydefult = $product->warehouse_id;
                                    foreach ($warehouses as $warehouse) {
                                        echo '<option value="' . $warehouse->id . '" ';
                                        if ($warehouse->id == $bydefult) {
                                            echo 'selected';
                                        }
                                        echo ' >' . $warehouse->name . '</option>';
                                    }
                                    ?>
                             </select>
                         </div>
                     </div>
                     <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Supplier <span class="red">*</span></label><br>
                            <select name="supplier" id="suppliers" class="uk-width-1-1 select2">
                                <option value="0">Select Supplier</option>
                            </select>
                        </div>
                    </div>
                    <div class="uk-width-large-1-3">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Discount Apply <span class="red">*</span></label><br>
                             <select name="discount" id="discount" class="uk-width-1-1 select2">
                                 <option value="no">No Discount</option>
                                 <option value="mrp" <?php if ($product->discount == "mrp") { echo 'selected'; } ?>>MRP Discount</option>
                                 <option value="d1" <?php if ($product->discount == "d1") { echo 'selected'; } ?>>Discount 1</option>
                                 <option value="d2" <?php if ($product->discount == "d2") { echo 'selected'; } ?>>Discount 2</option>
                                 <option value="d3" <?php if ($product->discount == "d3") { echo 'selected'; } ?>>Discount 3</option>
                             </select>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Update Type <span class="red">*</span></label><br>
                             <select name="updatetype" class="uk-width-1-1 select2">
                                 <option value="qty" <?php if ($product->update_in == 'qty') { echo 'selected'; } ?>>Ony Quantity</option>
                                 <option value="price" <?php if ($product->update_in == 'price') { echo 'selected'; } ?>>Ony Price</option>
                                 <option value="priceqty" <?php if ($product->update_in == 'priceqty') { echo 'selected'; } ?>>Price and Quantity</option>
                             </select>
                         </div>
                     </div>

                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Update Stock In <span class="red">*</span></label><br>
                             <select name="stocktype" id="stocktype" class="uk-width-1-1 select2">
                                 <option value="single" <?php if ($product->update_qty_in == "single") { echo 'selected'; } ?>>Single</option>
                                 <option value="pack" <?php if ($product->update_qty_in == "pack") { echo 'selected'; } ?>>Pack</option>
                                 <option value="carton" <?php if ($product->update_qty_in == "carton") { echo 'selected'; } ?>>Carton</option>
                             </select>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Update Price <span class="red">*</span></label><br>
                             <select name="pricetype" id="pricetype" class="uk-width-1-1 select2">
                                 <option value="mrp" <?php if ($product->price_type == 'mrp') { echo 'selected'; } ?>>MRP</option>
                                 <option value="cost" <?php if ($product->price_type == 'cost') { echo 'selected'; } ?>>Cost</option>
                                 <option value="consiment" <?php if ($product->price_type  == "consiment") {     echo 'selected'; } ?>>Consiment</option>
                                 <option value="dropship" <?php if ($product->price_type  == "dropship") {     echo 'selected'; } ?>>Dropship</option>
                                 <option value="crossdock" <?php if ($product->price_type   == "crossdock") {     echo 'selected'; } ?>>Cross Dock</option>
                             </select>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Status <span class="red">*</span></label><br>
                             <select name="update_status" class="uk-width-1-1 select2">
                                 <option value="active" <?php if ($product->status == 'active') { echo 'selected'; } ?>>Active</option>
                                 <option value="dective" <?php if ($product->status == 'dective') {     echo 'selected'; } ?>>Dective</option>
                             </select>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Price (W/O Tax)</label>
                             <input type="text" id="editpriceTxt" class="md-input md-input-success label-fixed" readonly>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Tax</label>
                             <input type="text" id="edittaxtTxt" class="md-input md-input-success label-fixed" readonly>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Discount</label>
                             <input type="text" id="editdiscountTxt" class="md-input md-input-success label-fixed" readonly>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Total</label>
                             <input type="text" id="edittotal" class="md-input md-input-success label-fixed" readonly>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Reqular Price</label>
                             <input type="text" id="editrprice" class="md-input md-input-success label-fixed" readonly>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Sale Price</label>
                             <input type="text" id="editsprice" class="md-input md-input-success label-fixed" readonly>
                         </div>
                     </div>
                     <div class="uk-width-large-1-4">
                         <div class="md-input-wrapper md-input-filled">
                             <label>Stock Qty</label>
                             <input type="text" id="editstock" class="md-input md-input-success label-fixed" readonly>
                         </div>
                     </div>
                 </div>
                 <div class="uk-grid" data-uk-grid-margin>
                     <div class="uk-width-large-1-1" style="padding-top: 20px;">
                         <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn">Submit</button>
                         <a href="<?php echo base_url('admin/stores/products?id=' . $product->store_id); ?>" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light">Cancel</a>
                     </div>
                 </div>
                 <?php echo form_close(); ?>
             </div>
         </div>
     </div>
 </div>
 <!-- CK Editor 5 -->
 <script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/ckeditor5/ckeditor.js"></script>
 <script>
     $(document).ready(function() {
         $('.select2').select2();
         $('#updateForm').submit(function(e) {
             e.preventDefault();
             $('#submitbtn').prop('disabled', true);
             $.ajax({
                 url: '<?php echo base_url('admin/stores/update_submit'); ?>',
                 type: 'POST',
                 data: new FormData(this),
                 contentType: false,
                 cache: false,
                 processData: false,
                 success: function(data) {
                     var obj = jQuery.parseJSON(data);
                     if(obj.status){
                         window.location.href = "<?php echo base_url('admin/stores/products?id=' . $product->store_id); ?>";
                         toastr.success(obj.message);
                     }
                     else{
                         toastr.error(obj.message);
                     }
                     $('#submitbtn').prop('disabled', false);
                 }
             });
         });
         function update_info(){
             var pid = $('#update_pid').val();
             var stocktype = $('#stocktype').val();
             var pricetype = $('#pricetype').val();
             var discount = $('#discount').val();
             var warehouse_id = $('#warehouse_id').val();
             $.ajax({
                 type: "get",
                 data: {
                     pid: pid,
                     stocktype: stocktype,
                     pricetype: pricetype,
                     discount: discount,
                     warehouse_id: warehouse_id,
    
                 },
                 url: '<?= admin_url('stores/calPrice'); ?>',
                 success: function(data) {
                     var obj = jQuery.parseJSON(data);
                     $('#editpriceTxt').val(obj.price);
                     $('#edittaxtTxt').val(obj.tax);
                     $('#editdiscountTxt').val(obj.discount);
                     $('#edittotal').val(obj.total);
                     $('#editrprice').val(obj.mrp);
                     $('#editsprice').val(obj.total);
                     $('#editstock').val(obj.stock);
    
                 }
             });
         }
         $('#stocktype').change(function() {
            update_info();
        });
        $('#pricetype').change(function() {
            update_info();
        });
        $('#discount').change(function() {
            update_info();
        });
        $('#warehouse_id').change(function() {
            update_info();
        });
        function update_discount_supplier(){
            var pid = $('#update_pid').val();
            $.ajax({
                type: "get",
                data: {
                    pid: pid,
                    storediscount: '<?php echo $product->discount; ?>'
                },
                async: false,
                url: '<?php echo admin_url('stores/discountlist'); ?>',
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    $('#discount').html(obj.discount);
                    $('#suppliers').html(obj.suppliers);
                    update_info();
                }
            });
        }
        update_discount_supplier();
     });
 </script>