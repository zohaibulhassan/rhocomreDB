<style>
    .uk-open>.uk-dropdown, .uk-open>.uk-dropdown-blank{

    }
    .dt_colVis_buttons {
        display:none;
    }
    .summarytable {}
    .summarytable table{
        width: 30%;
        float: right;
    }
    .summarytable tr{}
    .summarytable th{}
    .summarytable td{}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Create Purchase Simple</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'submitFrom');
                    echo admin_form_open_multipart("#", $attrib);
                ?>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Date</label>
                                <input class="md-input  label-fixed" type="text" name="date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" readonly required >
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Reference No</label>
                                <input class="md-input  label-fixed" type="text" name="reference_no" required >
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Warehouse</label>
                                <select name="warehouse" id="warehosue_id" class="uk-width-1-1 select2" required >
                                    <?php
                                        foreach($warehouses as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Own Companies</label>
                                <select name="own_company" class="uk-width-1-1 select2" required >
                                    <?php
                                        foreach($owncompanies as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Supplier</label>
                                <select name="supplier" id="supplier" class="uk-width-1-1 select2" required >
                                    <?php
                                        foreach($suppliers as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                                <input type="hidden" name="supplier_id" id="supplier_id" value="0" >
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-1">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Select Products </label>
                                <input type="text" name="products" id="searchproduct" class="md-input md-input-success label-fixed" placeholder="Enter Product Name or Barcode">
                                <div id="suggesstion-box"></div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:50px">
                        <div class="dt_colVis_buttons"></div>
                        <table class="uk-table"  style="width:100%" id="dt_tableExport">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Net Unit Cost</th>
                                    <th>MRP</th>
                                    <th>Quantity</th>
                                    <th>Batch</th>
                                    <th>Expiry</th>
                                    <th>FED Tax</th>
                                    <th>Product Tax</th>
                                    <th>Advance Income Tax</th>
                                    <th>Subtotal</th>
                                    <th class="dt-no-export" >Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="summarytable" >
                        <table class="uk-table uk-table-striped ">
                            <tbody>
                                <tr>
                                    <td style="width:50%" ><b>Total Quantity</b></td>
                                    <td style="width:50%"  id="totalitems">0</td>
                                </tr>
                                <tr>
                                    <td><b>Total Product Tax</b></td>
                                    <td id="totalptax">0</td>
                                </tr>
                                <tr>
                                    <td><b>Net Amount</b></td>
                                    <td id="totalnetamount">0</td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="clear:both" ></div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-1">
                            <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn" type="submit" >Submit</button>
                            <button class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" id="resetBtn" type="button" >Reset</button>
                            <!-- <button class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light" type="button" id="alertQtybtn" >Get Alert Quantity</button> -->
                        </div>
                    </div>
                
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="">
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

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){

        // $('#alertQtybtn').click(function(){
        //     $("#alertQtybtn").prop('disabled', true);
        //     $.ajax({
        //         type: 'get',
        //         url: '<?= admin_url('purchases/alertqty'); ?>',
        //         data: {
        //             supplier_id: $("#supplier").val(),
        //             warehouse_id: $("#warehosue_id").val(),
        //         },
        //         success: function (data) {
        //             localStorage.setItem('purchase_items',data);
        //             // localStorage.setItem('purchase_items',JSON.stringify(data));
        //             $("#alertQtybtn").prop('disabled', false);
        //             $("#dt_tableExport").DataTable().destroy();
        //             loaditems();

        //         },
        //         error: function(jqXHR, textStatus){
        //             var errorStatus = jqXHR.status;
        //             $("#alertQtybtn").prop('disabled', false);
        //         }
        //     });
        // });
        $('#resetBtn').click(function(){
            localStorage.removeItem("purchase_items");
            location.reload();
        });

        $('.select2').select2();
        $("#searchproduct").autocomplete({
            source: function (request, response) {
                var supplier = $('#supplier').val();
                $.ajax({
                    type: 'get',
                    url: '<?php echo base_url('admin/general/searching_products'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        supplier_id:supplier
                    },
                    success: function (data) {
                        $(this).removeClass('ui-autocomplete-loading');
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 250,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                var warehouse_id = $('#warehosue_id').val();
                $.ajax({
                    type: 'get',
                    url: '<?php echo base_url('admin/general/select_products2'); ?>',
                    data: {id: ui.item.item_id,warehouse_id:warehouse_id},
                    success: function (data) {
                        var obj = jQuery.parseJSON(data);
                        if(obj.codestatus){

                            var items = localStorage.getItem('purchase_items');
                            if(items == null){
                                items = [obj.products];
                                localStorage.setItem('purchase_items',JSON.stringify(items));
                            }
                            else{
                                var getitems = JSON.parse(localStorage.getItem('purchase_items'));
                                getitems.push(obj.products);
                                localStorage.setItem('purchase_items', JSON.stringify(getitems));
                            }
                            $("#dt_tableExport").DataTable().destroy();
                            loaditems();

                            $('#searchproduct').val('');
                        }
                    }
                });
            }
        });
        function loaditems(){
            var getitems = JSON.parse(localStorage.getItem('purchase_items'));
            var html = "";
            var totalnetamount = 0;
            var totalptax = 0;
            var totalitems = 0;
            // $("#supplier").prop("disabled", false);
            // var get_supplier = localStorage.getItem('supplier_id');
            // console.log(get_supplier);
            // $('#supplier').val(get_supplier).trigger('change');
            // $('#supplier_id').val(get_supplier);

            $.each(getitems, function(index) {
                var item = this;
                var advtax = (parseFloat(item.cost)+parseFloat(item.product_tax))/100*parseFloat(item.adv_tax_for_purchase);
                total = (parseFloat(item.cost)+parseFloat(item.fed_tax)+parseFloat(item.product_tax)+advtax)*parseFloat(item.quantity);
                total = parseFloat(total).toFixed(4);
                var total_tax = parseFloat(item.product_tax)*parseFloat(item.quantity);
                total_tax = parseFloat(total_tax).toFixed(4);


                totalitems += parseFloat(item.quantity);
                totalptax += parseFloat(total_tax);
                totalnetamount += parseFloat(total);

                html += "<tr>";
                    html += "<td>"+(index+1);
                    html += "<input type='hidden' name='product_id[]' value='"+item.id+"' >";
                    html += "</td>";
                    html += "<td>"+item.name+"</td>";
                    html += "<td>"+item.code+"</td>";
                    html += "<td>"+item.cost+"</td>";
                    html += "<td>"+item.mrp+"</td>";
                    html += "<td><input type='text' class='itemqty' name='qty[]' data-index='"+index+"' value='"+item.quantity+"'></td>";
                    html += "<td><input type='text' class='itembatch' name='batch[]' data-index='"+index+"' value='"+item.batch+"'></td>";
                    html += '<td><input type="text" class="itemexpiry" name="expiry[]" data-uk-datepicker="{format:';
                    html += "'DD-MM-YYYY'";
                    html += '}" autocomplete="off" data-index="'+index+'" value="'+item.expiry+'" ></td>';
                    html += "<td>"+item.fed_tax+"</td>";
                    html += "<td>"+total_tax+"<input type='hidden' name='product_tax[]' value='"+item.tax_id+"' ></td>";
                    html += "<td>"+advtax*item.quantity+"</td>";
                    html += "<td>"+total+"</td>";
                    html += "<td>";
                        html += "<a class='md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini itemremove' data-index='"+index+"' >Remove</a>";
                    html += "</td>";
                html += "</tr>";
                // $("#supplier").prop("disabled", true);
            });
            $('#totalnetamount').html(totalnetamount.toFixed(4));
            $('#totalptax').html(totalptax.toFixed(4));
            $('#totalitems').html(totalitems);
            $('#dt_tableExport tbody').html(html);
            $('#dt_tableExport').DataTable({
                fixedColumns:   {left: 0,right: 2},
                scrollX: true,
                searching:false,
                paging :false

            });
        }
        loaditems();
        $(document).on('change','.itemqty',function(){
            var qty = $(this).val();
            var getitems = JSON.parse(localStorage.getItem('purchase_items'));
            var index = $(this).data('index');
            if (qty.indexOf('*') != -1) {
                qty = qty.replace("*", "");
                qty = qty*getitems[index].pack_size;
            }
            else if (qty.indexOf('^') != -1) {
                qty = qty.replace("^", "");
                qty = qty*getitems[index].carton_size;
            }

            getitems[index].quantity = qty;
            localStorage.setItem('purchase_items', JSON.stringify(getitems));
            $("#dt_tableExport").DataTable().destroy();
            loaditems();
        });
        $(document).on('change','.itembatch',function(){
            var val = $(this).val();
            var getitems = JSON.parse(localStorage.getItem('purchase_items'));
            var index = $(this).data('index');
            getitems[index].batch = val;
            localStorage.setItem('purchase_items', JSON.stringify(getitems));
            // $("#dt_tableExport").DataTable().destroy();
            // loaditems();
        });
        $(document).on('change','.itemexpiry',function(){
            var val = $(this).val();
            var getitems = JSON.parse(localStorage.getItem('purchase_items'));
            var index = $(this).data('index');
            getitems[index].expiry = val;
            localStorage.setItem('purchase_items', JSON.stringify(getitems));
            // $("#dt_tableExport").DataTable().destroy();
            // loaditems();
        });
        $(document).on('click','.itemremove',function(){
            var index = parseInt($(this).data('index'));
            var getitems = JSON.parse(localStorage.getItem('purchase_items'));
            getitems.splice(index,1)
            localStorage.setItem('purchase_items', JSON.stringify(getitems));
            $("#dt_tableExport").DataTable().destroy();
            loaditems();
        });
        $('#submitFrom').submit(function(e){
            e.preventDefault();
            $('#submitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/purchases/submit'); ?>',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    $('#submitbtn').prop('disabled', false);
                    if(obj.status){
                        toastr.success(obj.message);
                        $('#submitFrom')[0].reset();
                        localStorage.removeItem("purchase_items");
                        window.location.href = "<?php echo base_url('admin/purchases'); ?>";
                    }
                    else{
                        toastr.error(obj.message);
                    }
                }
            });
        });
        $('#supplier').change(function(){
            var sval = $(this).val();
            $('#supplier_id').val(sval);
        });
        $('#supplier_id').val($('#supplier').val());
    });
</script>