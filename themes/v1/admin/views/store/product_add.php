<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Link Product </h3>
            </div>
            <div class="md-card-content">
                <?php
                $attrib = ['data-toggle' => 'validator', 'role' => 'form', 'id' => 'addFrom'];
                echo admin_form_open_multipart('#', $attrib);
                ?>
                <div class="uk-grid">
                    <div class="uk-width-large-1-1" style="margin-top: 15px;">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Product <span class="red">*</span></label>
                            <input type="hidden" name="store_id" value="<?php echo $store->id; ?>">
                            <select name="product" id="product" class="uk-width-1-1 product_searching" style="width: 100%">
                                <option value="">Select Product</option>
                            </select>
                        </div>
                    </div>
                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Warehouse <span class="red">*</span></label><br>
                            <select name="warehouse_id" id="warehouse_id" class="uk-width-1-1 select2">
                                <?php
                                $bydefult = 1;
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
                            <label>Store Product Code <span class="red">*</span></label>
                            <input type="text" name="store_product_code" class="md-input md-input-success label-fixed" required>
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
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Update Type <span class="red">*</span></label><br>
                            <select name="updatetype" class="uk-width-1-1 select2">
                                <option value="qty" <?php if ($store->integration_type == 'qty') { echo 'selected'; } ?>>Ony Quantity</option>
                                <option value="price" <?php if ($store->integration_type == 'price') { echo 'selected';} ?>>Ony Price</option>
                                <option value="detail" <?php if ($store->integration_type == 'detail') { echo 'selected';} ?>>Ony Detail</option>
                                <option value="priceqty" <?php if ($store->integration_type == 'priceqty') { echo 'selected'; } ?>>Price and Quantity</option>
                                <option value="detailnqty" <?php if ($store->integration_type == 'detailnqty') { echo 'selected'; } ?>>Product Detail and Quantity</option>
                                <option value="detailnprice" <?php if ($store->integration_type == 'detailnprice') { echo 'selected'; } ?>>Product Detail and Price</option>
                                <option value="full" <?php if ($store->integration_type == 'full') { echo 'selected'; } ?>>Full Integration</option>
                            </select>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Update Stock In <span class="red">*</span></label><br>
                            <select name="stocktype" id="stocktype" class="uk-width-1-1 select2">
                                <option value="single" <?php if ($store->update_qty_in == "single") {echo 'selected';} ?>>Single</option>
                                <option value="pack" <?php if ($store->update_qty_in == "pack") {echo 'selected';} ?>>Pack</option>
                                <option value="carton" <?php if ($store->update_qty_in == "carton") {echo 'selected';} ?>>Carton</option>
                            </select>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Update Price <span class="red">*</span></label><br>
                            <select name="pricetype" id="pricetype" class="uk-width-1-1 select2">
                                <option value="mrp" <?php if ($store->update_price == "mrp") {echo 'selected'; } ?>>MRP</option>
                                <option value="consiment" <?php if ($store->update_price == "consiment") { echo 'selected';} ?>>Consiment</option>
                                <option value="dropship" <?php if ($store->update_price == "dropship") { echo 'selected';} ?>>Dropship</option>
                                <option value="crossdock" <?php if ($store->update_price == "crossdock") { echo 'selected';} ?>>Cross Dock</option>
                                <option value="cost" <?php if ($store->update_price == "cost") {echo 'selected';} ?>>Cost</option>
                            </select>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Discount Apply <span class="red">*</span></label><br>
                            <select name="discount" id="discount" class="uk-width-1-1 select2">
                                <option value="no">No Discount</option>
                                <option value="mrp" <?php if ($store->discount == "mrp") {echo 'selected'; } ?>>MRP Discount</option>
                                <option value="d1" <?php if ($store->discount == "d1") {echo 'selected'; } ?>>Discount 1</option>
                                <option value="d2" <?php if ($store->discount == "d2") {echo 'selected'; } ?>>Discount 2</option>
                                <option value="d3" <?php if ($store->discount == "d3") {echo 'selected'; } ?>>Discount 3</option>
                            </select>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Price (W/O Tax)</label>
                            <input type="text" id="priceTxt" class="md-input md-input-success label-fixed" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Tax</label>
                            <input type="text" id="taxtTxt" class="md-input md-input-success label-fixed" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Discount</label>
                            <input type="text" id="discountTxt" class="md-input md-input-success label-fixed" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Total</label>
                            <input type="text" id="total" class="md-input md-input-success label-fixed" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Reqular Price</label>
                            <input type="text" id="rprice" class="md-input md-input-success label-fixed" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Sale Price</label>
                            <input type="text" id="sprice" class="md-input md-input-success label-fixed" readonly>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Stock Qty</label>
                            <input type="text" id="stock" class="md-input md-input-success label-fixed" readonly>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-1" style="padding-top: 20px;">
                        <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn">Submit</button>
                        <a href="<?php echo base_url('admin/stores/products?id=' . $store->id); ?>" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light">Cancel</a>
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
        $(".product_searching").select2({
            minimumInputLength: 2,
            tags: [],
            ajax: {
                url: "<?php echo base_url('admin/general/searching_products2'); ?>",
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                data: function(term) {
                    return {
                        term: term,
                        supplier_id: 0
                    };
                },
                results: function(data) {
                    console.log(data);
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.completeName,
                                slug: item.slug,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
        $('#addFrom').submit(function(e) {
            e.preventDefault();
            $('#submitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/stores/insert_product'); ?>',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    console.log(obj);
                    if (obj.status) {
                        toastr.success(obj.message);
                        window.location.href = "<?php echo base_url('admin/stores/products?id=' . $store->id); ?>";
                    } else {
                        toastr.error(obj.message);
                    }
                    $('#submitbtn').prop('disabled', false);
                }
            });
        });
    });
    $('#product').change(function() {
        var pid = $(this).val();
        var selecteddata = $(this).select2('data');
        $.ajax({
            type: "get",
            data: {
                pid: pid,
                storediscount: '<?php echo $store->discount; ?>'
            },
            async: false,
            url: '<?php echo admin_url('stores/discountlist'); ?>',
            success: function(data) {
                console.log(data)
                var obj = jQuery.parseJSON(data);
                $('#discount').html(obj.discount);
                $('#suppliers').html(obj.suppliers);
                $('#producttitle').val(selecteddata.text);
                calProductPrice()
            }
        });
    });
    $('#stocktype').change(function() {
        calProductPrice();
    });
    $('#pricetype').change(function() {
        calProductPrice();
    });
    $('#discount').change(function() {
        calProductPrice();
    });
    $('#warehouse_id').change(function() {
        calProductPrice();
    });
    function calProductPrice($edit = "") {
        var pid = $('#product').val();
        var stocktype = $('#stocktype').val();
        var pricetype = $('#pricetype').val();
        var discount = $('#discount').val();
        var warehouse_id = $('#warehouse_id').val();
        var stock_margin = <?= $store->stock_margin ?>;
        $.ajax({
            type: "get",
            data: {
                pid: pid,
                stocktype: stocktype,
                pricetype: pricetype,
                discount: discount,
                warehouse_id: warehouse_id,
                stock_margin: stock_margin
            },
            url: '<?= admin_url('stores/calPrice'); ?>',
            success: function(data) {
            
                var obj = jQuery.parseJSON(data);
                $('#priceTxt').val(obj.price);
                $('#taxtTxt').val(obj.tax);
                $('#discountTxt').val(obj.discount);
                $('#total').val(obj.total);
                $('#rprice').val(obj.mrp);
                $('#sprice').val(obj.total);
                $('#stock').val(obj.stock);
                $('#product_id').val(pid);
            }
        });
    }
</script>