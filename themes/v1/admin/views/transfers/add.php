<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}

    .dt_colVis_buttons {
        display: none;
    }

    .summarytable {}

    .summarytable table {
        width: 30%;
        float: right;
    }

    .summarytable tr {}

    .summarytable th {}

    .summarytable td {}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Create Transfer</h3>
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
                            <input class="md-input label-fixed" type="text" name="date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo isset($_POST['date']) ? $_POST['date'] : date('Y-m-d'); ?>" readonly required>


                        </div>
                    </div>
                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Reference No</label>
                            <input class="md-input label-fixed ref" type="text" name="reference_no" value="<?php echo isset($_POST['reference_no']) ? $_POST['reference_no'] : $rnumber; ?>" id="ref" />
                        </div>
                    </div>

                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>To Warehouse *</label>
                            <select name="to_warehouse" id="to_warehouse" class="form-control input-tip select" data-placeholder="<?php echo $this->lang->line("select") . ' ' . $this->lang->line("to_warehouse"); ?>" required style="width: 100%;">
                                <option value=""></option>
                                <?php foreach ($warehouses as $warehouse) { ?>
                                    <option value="<?php echo $warehouse->id; ?>" <?php echo (isset($_POST['to_warehouse']) && $_POST['to_warehouse'] == $warehouse->id) ? 'selected' : ''; ?>><?php echo $warehouse->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Status *</label>
                            <select name="status" id="tostatus" class="form-control input-tip select" data-placeholder="<?php echo $this->lang->line("select") . ' ' . $this->lang->line("status"); ?>" required style="width: 100%;">
                                <?php
                                $post['completed'] = lang('completed');
                                foreach ($post as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo (isset($_POST['status']) && $_POST['status'] == $key) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>

                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Suppliers</label>
                            <select name="supplier" id="supplier_id" class="uk-width-1-1 select2" required>

                                <?php foreach ($suppliers as $supplier) { ?>
                                    <option value="<?php echo $supplier->id; ?>" <?php echo (isset($_POST['supplier']) && $_POST['supplier'] == $supplier->id) ? 'selected' : ''; ?>><?php echo $supplier->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>From Warehouse *</label>
                            <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
                                <div class="panel panel-warning">
                                    <div class="panel-body" style="padding: 5px;">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select name="from_warehouse" id="warehosue_id" class="uk-width-1-1 select2" required>

                                                    <!-- <option value=""></option> -->
                                                    <?php foreach ($warehouses as $warehouse) { ?>
                                                        <option value="<?php echo $warehouse->id; ?>" <?php echo (isset($_POST['from_warehouse']) && $_POST['from_warehouse'] == $warehouse->id) ? 'selected' : ''; ?>>
                                                            <?php echo $warehouse->name; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else {
                                $warehouse_input = array(
                                    'type' => 'hidden',
                                    'name' => 'from_warehouse',
                                    'id' => 'from_warehouse',
                                    'value' => $this->session->userdata('warehouse_id'),
                                );
                                echo form_input($warehouse_input);
                            } ?>
                        </div>
                    </div>

                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-1">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Select Products </label>
                            <!-- <input type="text" name="product_code[]" id="searchproduct" class="md-input md-input-success label-fixed" placeholder="Enter Product Name or Barcode"> -->
                              <input type="text" name="search_code[]" id="searchproduct" class="md-input md-input-success label-fixed" placeholder="Enter Product Name or Barcode">
                            <div id="suggesstion-box"></div>
                        </div>
                    </div>
                </div>
                <div style="margin-top:50px">
                    <div class="dt_colVis_buttons"></div>
                    <table class="uk-table" style="width:100%" id="dt_tableExport">
                        <thead>
                            <tr>
                            <tr>
                                <th class="col-md-3"><?php echo lang('product') . ' (' . lang('code') . ' - ' . lang('name') . ')'; ?></th>
                                <th class="col-md-3"><?php echo lang('product') . ' (' . lang('code') . ' - ' . lang('name') . ')'; ?></th>
                                <th class="col-md-1"><?php echo lang('MRP'); ?></th>
                                <th class="col-md-1"><?php echo lang('quantity'); ?></th>
                                <!-- <th class="col-md-1"><?php //echo lang('Remain Batch'); ?></th> -->
                                <th class="col-md-1"><?php echo lang('Remain Batch'); ?></th>
                                <th class="col-md-2"><?php echo lang('Batch'); ?></th>
                                <th class="col-md-2"><?php echo lang('Expiry'); ?></th>
                                <th style="width: 30px !important; text-align: center;"><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th>

                            </tr>

                            </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="summarytable">
                    <table class="uk-table uk-table-striped ">
                        <tbody>
                            <tr>
                                <td style="width:50%"><b>Total Quantity</b></td>
                                <td style="width:50%" id="totalitems">0</td>
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
                    <div style="clear:both"></div>
                </div>
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Note <span class="red">*</span></label>
                        <textarea cols="30" rows="4" class="md-input autosized" style="overflow-x: hidden; overflow-wrap: break-word; height: 121px;" required name="note"></textarea>
                    </div>
                </div>

                <br><br>

                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-1">
                        <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn" type="submit">Submit</button>
                        <!-- <button id="resetButton" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" type="button">Reset</button> -->
                        <button id="resetButton" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" type="button">Reset</button>

                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
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
    $(document).ready(function() {

        // $('#resetBtn').click(function(){
        //     localStorage.removeItem("po_items");
        //     $("#dt_tableExport").DataTable().destroy();
        //     loaditems();
        // });
        // $('#alertQtybtn').click(function(){
        //     $("#alertQtybtn").prop('disabled', true);
        //     $.ajax({
        //         type: 'get',
        //         url: '<?= admin_url('purchases/alertqty'); ?>',
        //         data: {
        //             supplier_id: $("#supplier_id").val(),
        //             warehouse_id: $("#warehosue_id").val()
        //         },
        //         success: function (data) {
        //             localStorage.setItem('po_items',data);
        //             // localStorage.setItem('po_items',JSON.stringify(data));
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

        // $('.select2').select2();
        // $("#searchproduct").autocomplete({
        //     source: function(request, response) {
        //         var supplier_id = $('#supplier_id').val();
        //         console.log(supplier_id);
        //         $.ajax({
        //             type: 'get',
        //             url: '<?php echo base_url('admin/general/searching_products'); ?>',
        //             dataType: "json",
        //             data: {
        //                 term: request.term,
        //                 supplier_id: supplier_id
        //             },
        //             success: function(data) {
        //                 $(this).removeClass('ui-autocomplete-loading');
        //                 response(data);
        //             }
        //         });
        //     },
        //     minLength: 1,
        //     autoFocus: false,
        //     delay: 250,
        //     response: function(event, ui) {
        //         if ($(this).val().length >= 16 && ui.content[0].id == 0) {
        //             $(this).removeClass('ui-autocomplete-loading');
        //             $(this).val('');
        //         } else if (ui.content.length == 1 && ui.content[0].id != 0) {
        //             ui.item = ui.content[0];
        //             $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
        //             $(this).autocomplete('close');
        //             $(this).removeClass('ui-autocomplete-loading');
        //         } else if (ui.content.length == 1 && ui.content[0].id == 0) {
        //             $(this).removeClass('ui-autocomplete-loading');
        //             $(this).val('');
        //         }
        //     },
        //     select: function(event, ui) {
        //         event.preventDefault();
        //         var warehouse_id = $('#warehosue_id').val();
        //         $.ajax({
        //             type: 'get',
        //             url: '<?php echo base_url('admin/general/select_products3'); ?>',
        //             data: {
        //                 id: ui.item.item_id,
        //                 warehouse_id: warehouse_id
        //             },
        //             success: function(data) {
        //                 var obj = jQuery.parseJSON(data);
        //                 if (!obj.status) {
        //                     var items = localStorage.getItem('po_items');

        //                     if (items != null) {
        //                         items = [obj.rows];
        //                         console.log(items);
        //                         localStorage.setItem('po_items', JSON.stringify(items));
        //                     } else {
        //                         var getitems = JSON.parse(localStorage.getItem('po_items'));
        //                         getitems.push(obj.rows);
        //                         localStorage.setItem('po_items', JSON.stringify(getitems));

        //                     }
        //                     $("#dt_tableExport").DataTable().destroy();
        //                     loaditems();

        //                     $('#searchproduct').val('');
        //                 }
        //             }
        //         });
        //     }
        // });



        // function loaditems() {
        //     var getitems = JSON.parse(localStorage.getItem('po_items'));
        //     var html = "";
        //     var totalnetamount = 0;
        //     var totalptax = 0;
        //     var totalitems = 0;
        //     var batchOptions = []; // To store unique batch options

        //     $.each(getitems, function(index) {
        //         var item = this[0]; // Accessing the first element of the item object

        //         // Assuming the property names for the item object are as follows:
        //         var itemName = item.product_code;
        //         var itemMrp = item.mrp;
        //         var itemQuantity = item.quantity;
        //         var itemBalanceQty = item.quantity_balance;
        //         var itemBatch = item.batch;
        //         var itemExpiry = item.expiry;

        //         total = (parseFloat(item.price) + parseFloat(item.crossdock) + parseFloat(item.mrp)) * parseFloat(item.quantity_balance);
        //         total = parseFloat(total).toFixed(4);
        //         var total_tax = parseFloat(item.mrp) * parseFloat(item.quantity_balance);
        //         total_tax = parseFloat(total_tax).toFixed(4);

        //         totalitems += parseFloat(item.quantity_balance);
        //         totalptax += parseFloat(total_tax);
        //         totalnetamount += parseFloat(total);

        //         // Add the batch to the batchOptions array if it's not already present
        //         if (batchOptions.indexOf(itemBatch) === -1) {
        //             batchOptions.push(itemBatch);
        //         }

        //         html += "<tr>";
        //         html += "<td>" + itemName + "</td>";
        //         html += "<td>" + itemMrp + "</td>";
        //         html += "<td><input type='text' class='itemqty' name='qty[]' data-index='" + index + "' value='" + item.quantity + "'></td>";
        //         html += "<td>" + itemBalanceQty + "</td>";
        //         html += "<td><select class='form-control batch-select' data-index='" + index + "'>";
        //         $.each(batchOptions, function(index, batch) {
        //             var selected = (batch === itemBatch) ? 'selected' : '';
        //             html += "<option value='" + batch + "' " + selected + ">" + batch + "</option>";
        //         });
        //         html += "</select></td>";
        //         html += "<td>" + itemExpiry + "</td>";
        //         html += "<td><a class='md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini itemremove' data-index='" + index + "'>Remove</a></td>";
        //         html += "</tr>";
               

        //     });

           
        //     $('#dt_tableExport tbody').html(html);

        //     // Update the total values
        //     $('#totalnetamount').html(totalnetamount.toFixed(4));
        //     $('#totalptax').html(totalptax.toFixed(4));
        //     $('#totalitems').html(totalitems);

        //     $('#dt_tableExport').DataTable({
        //         fixedColumns: {
        //             left: 0,
        //             right: 2
        //         },
        //         scrollX: true,
        //         searching: false,
        //         paging: false
        //     });
        // }

        $('.select2').select2();

        $("#searchproduct").autocomplete({
  source: function (request, response) {
    var supplier_id = $('#supplier_id').val();
    console.log(supplier_id);
    $.ajax({
      type: 'get',
      url: '<?php echo base_url('admin/general/searching_products'); ?>',
      dataType: "json",
      data: {
        term: request.term,
        supplier_id: supplier_id
      },
      success: function (data) {
        $(this).removeClass('ui-autocomplete-loading');
        response(data);
        
        if (data.length === 0) {
          alert("No matching records found."); 
        }
      }
    });
  },
  
  select: function (event, ui) {
    event.preventDefault();
    var warehouse_id = $('#warehosue_id').val();
    $.ajax({
      type: 'get',
      url: '<?php echo base_url('admin/general/select_products3'); ?>',
      data: {
        id: ui.item.item_id,
        warehouse_id: warehouse_id
      },
      success: function (data) {
        var obj = jQuery.parseJSON(data);
        if (!obj.status) {
          var items = [obj.rows]; 
          console.log(items);
          localStorage.setItem('po_items', JSON.stringify(items));
          $("#dt_tableExport").DataTable().destroy();
          loaditems();
          $('#searchproduct').val('');
        }
      }
    });
  }
});


// $("#searchproduct").autocomplete({
//   source: function (request, response) {
//     var supplier_id = $('#supplier_id').val();
//     console.log(supplier_id);
//     $.ajax({
//       type: 'get',
//       url: '<?php echo base_url('admin/general/searching_products'); ?>',
//       dataType: "json",
//       data: {
//         term: request.term,
//         supplier_id: supplier_id
//       },
//       success: function (data) {
//         $(this).removeClass('ui-autocomplete-loading');
//         response(data);
        
//         if (data.length === 0) {
//           alert("No matching records found."); 
//         }
//       }
//     });
//   },
//   minLength: 1,
//   autoFocus: false,
//   delay: 250,
//   select: function (event, ui) {
//     event.preventDefault();
//     var warehouse_id = $('#warehosue_id').val();
//     $.ajax({
//       type: 'get',
//       url: '<?php echo base_url('admin/general/select_products3'); ?>',
//       data: {
//         id: ui.item.item_id,
//         warehouse_id: warehouse_id
//       },
//         success: function (data) {
//         var obj = jQuery.parseJSON(data);
//         if (!obj.status) {
//           var items = localStorage.getItem('po_items');
//           console.log(items);
//           // if (items != null) {
//           if (items == null) {
//             items = [obj.rows];
//             console.log(items);
//             localStorage.setItem('po_items', JSON.stringify(items));
//           } else {
//             var getitems = JSON.parse(localStorage.getItem('po_items'));
//             getitems.push(obj.rows);
//             localStorage.setItem('po_items', JSON.stringify(getitems));
//           }
//           $("#dt_tableExport").DataTable().destroy();
//           loaditems();
//           $('#searchproduct').val('');
//         }
//       }
//     });
//   }
// });



$(document).on('change', '.batch-select', function () {
    var selectedBatch = $(this).val();
    var quantityCell = $(this).closest('tr').find('td.quantity-cell');
    var totalQuantityCell = $('#totalitems');

    var csrfToken = $('[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();

    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '<?php echo base_url("admin/general/get_remain_quantity"); ?>',
        data: {
            batch_id: selectedBatch,
            <?php echo $this->security->get_csrf_token_name(); ?>: csrfToken
        },
        success: function (data) {
            quantityCell.text(data);
            updateTotalQuantityAndNetAmountAndTax();
        }
    });
});

function updateTotalQuantityAndNetAmountAndTax() {
    var totalQuantity = 0;
    var totalNetAmount = 0;
    var totalProductTax = 0; 

    $('tr').each(function () {
        var rowQuantity = parseFloat($(this).find('td.quantity-cell').text());
        var rowMrp = parseFloat($(this).find('td:eq(1)').text()); 
        var rowTax = parseFloat($(this).find('td.tax-cell').text()); 

        var rowTotal = rowQuantity * rowMrp;

        if (!isNaN(rowQuantity)) {
            totalQuantity += rowQuantity;
            totalNetAmount += rowTotal;
            totalProductTax += isNaN(rowTax) ? 0 : rowTax; 
        }
    });

    $('#totalitems').text(totalQuantity.toFixed(4));
    $('#totalnetamount').text(totalNetAmount.toFixed(4));
    $('#totalproducttax').text(totalProductTax.toFixed(4)); 
}



function loaditems() {
  var getitems = JSON.parse(localStorage.getItem('po_items'));
  var html = "";
  var totalnetamount = 0;
  var totalptax = 0;
  var totalitems = 0;
  var productBatchMap = {};

  $.each(getitems, function (index, items) {
    $.each(items, function () {
      var item = this;
      var itemName = item.product_code ;
      let productgoodname = item.product_name;
      var itemMrp = item.mrp;
      var itemBalanceQty = item.quantity_balance;
      var itemBatch = item.batch;
      var itemExpiry = item.expiry;

      var itemQuantity = 1;

      totalitems += parseFloat(item.total_quantity_balance);
      totalptax += parseFloat(item.mrp) * parseFloat(item.total_quantity_balance);


      var total = (parseFloat(item.price) + parseFloat(item.crossdock) + parseFloat(item.mrp)) * parseFloat(item.total_quantity_balance);
      totalnetamount += parseFloat(total);

      if (!productBatchMap[itemName]) {
        productBatchMap[itemName] = {
          mrp: itemMrp,
          productgoodname : productgoodname,
          quantity: itemQuantity, 
          balanceQty: itemBalanceQty,
          expiry: itemExpiry,
          batches: [],
        };
      }
      productBatchMap[itemName].batches.push(itemBatch);
    });
  });


  $.each(productBatchMap, function (itemName, productInfo) {
    html += "<tr>";
    // html += "<td> <input type='text' name='product_code[]' class='form-control ' value='" + itemName + "'></td>";
    // html += "<td> <input type='text' name='product_code[]' class='md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light' readonly style='border: none; color:rgb(120, 120, 120);background:rgb(160, 160, 160) !important;' value='" + itemName + "'></td>";

    html += "<td><input type='text' name='product_code[]' class='md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light' readonly style='border: none; color: black; background: rgb(160, 160, 160) !important;' value='" + itemName + "'></td>";


    // html += "<td> <input type='text' name='product_code[]' class='md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light' readonly style='border: none; color: black; background: rgb(160, 160, 160) !important;' value='" + itemName + "'></td>";

    html += "<td>" + productInfo.productgoodname + "</td>";
    html += "<td>" + productInfo.mrp + "</td>";
    // html += "<td><input type='number' name='quantity[]' class='form-control quantity-input' value='" + productInfo.quantity + "'></td>";
   // html += "<td><input type='number' name='quantity[]' class='form-control quantity-input' style='width: 40px;' value='" + productInfo.quantity + "'></td>";
    html += "<td><input type='number' name='quantity[]' class='form-control quantity-input' style='width: 40px; font-size: 16px;' value='" + productInfo.quantity + "'></td>";


    // html += "<td>" + productInfo.balanceQty + "</td>";
    html += "<td id='quantityBalance' name='batch_remain_quantity[]' class='quantity-cell'>" + productInfo.balanceQty + "</td>"; 

    // html += "<td><input type='text' name='batch_remain_quantity[]' class='quantity-cell' value='" + productInfo.balanceQty + "'></td>";

    // html += "<td><input type='text' name='batch_remain_quantity[]' id='quantityBalance' class='quantity-cell' value='" + productInfo.balanceQty + "'></td>";

    html += "<td><select class='form-control batch-select' name='batch[]'>";

    $.each(productInfo.batches, function (batchIndex, batch) {    
      html += "<option value='" + batch + "'>" + batch + "</option>";     
    });

    html += "</select></td>";
    html += "<td>" + productInfo.expiry + "</td>";
    html += "<td><a id='remove' class='md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini itemremove'>Remove</a></td>";
    html += "</tr>";
  });


  $('#dt_tableExport tbody').append(html);
  $('#totalnetamount').html(totalnetamount.toFixed(4));
  $('#totalptax').html(totalptax.toFixed(4));
  $('#totalitems').html(totalitems);

  $('.itemremove').on('click', function () {
    var indexToRemove = $(this).data('index');
    var updatedQuantity = parseFloat($(this).closest('tr').find('.quantity-input').val()); 

    var getitems = JSON.parse(localStorage.getItem('po_items'));
   
    getitems[indexToRemove][0].quantity = updatedQuantity;

    localStorage.setItem('po_items', JSON.stringify(getitems));

    loaditems();
  });

  var dataTable = $('#dt_tableExport').DataTable();
  if (dataTable) {
    dataTable.destroy();
  }

  $('#dt_tableExport').DataTable({
    fixedColumns: {
      left: 0,
      right: 2
    },
    scrollX: true,
    searching: false,
    paging: false
  });
}

$(document).ready(function () {
  loaditems();
});





        loaditems();
        $(document).on('change', '.itemqty', function() {
            var qty = $(this).val();
            var getitems = JSON.parse(localStorage.getItem('po_items'));
            var index = $(this).data('index');
            if (qty.indexOf('*') != -1) {
                qty = qty.replace("*", "");
                qty = qty * getitems[index].pack_size;
            } else if (qty.indexOf('^') != -1) {
                qty = qty.replace("^", "");
                qty = qty * getitems[index].carton_size;
            }
            getitems[index].quantity = qty;
            localStorage.setItem('po_items', JSON.stringify(getitems));
            // console.log(localStorage.getItem('po_items'));
            $("#dt_tableExport").DataTable().destroy();
            loaditems();
        });


        $(document).on('click', '.itemremove', function() {
    var index = parseInt($(this).data('index'));
    var getitems = JSON.parse(localStorage.getItem('po_items'));

    getitems.splice(index, 1);

    localStorage.setItem('po_items', JSON.stringify(getitems));
    $(this).closest('tr').remove();
});



          
    $('.quantity-input').on('keyup', function () {
        var row = $(this).closest('tr');
        var enteredQuantity = parseInt($(this).val());
        var remainingQuantity = parseInt(row.find('.quantity-cell').text());

     
        row.removeClass('red-row green-row');

        if (isNaN(enteredQuantity) || enteredQuantity <= 0) {
            toastr.error('Invalid quantity.');
            return;
        }

        if (enteredQuantity > remainingQuantity) {
            toastr.error('Entered quantity is greater than remaining batch quantity.');
            row.addClass('red-row');
        } else {
            row.addClass('green-row');
        }
    });



//         $('.quantity-input').on('keyup', function () {
//   var row = $(this).closest('tr');
//   var enteredQuantity = parseInt($(this).val());
//   var remainingQuantity = parseInt(row.find('.quantity-cell').text());

//   row.removeClass('red-row green-row');

//   $(this).css('border', '');

//   if (isNaN(enteredQuantity) || enteredQuantity <= 0) {
//     toastr.error('Invalid quantity.');
//     $(this).css('border', '1px solid red');
//     return;
//   }

//   if (enteredQuantity > remainingQuantity) {
//     toastr.error('Entered quantity is greater than remaining batch quantity.');
//     row.addClass('red-row');
//     $(this).css('border', '1px solid red');
//   } else {
//     row.addClass('green-row');
//     $(this).css('border', '1px solid green');
//   }
// });





    $('#submitFrom').submit(function (e) {
    e.preventDefault();


    if ($('.red-row').length > 0) {
        toastr.error('One or more rows have invalid quantities.');
        return;
    }

    $('#submitbtn').prop('disabled', true);

    $.ajax({
        // url: '<?php echo base_url('admin/purchaseorder/submit'); ?>',
         url: '<?php echo base_url('admin/transfers/add'); ?>',
        type: 'POST',
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            console.log(data)
            var obj = jQuery.parseJSON(data);
            if (obj.status) {
                toastr.success(obj.message);
                $('#submitFrom')[0].reset();
                localStorage.removeItem("po_items");
                  window.location.href = "<?php echo base_url('admin/transfers/index/'); ?>";
            } else {
                toastr.error(obj.message);
            }
            $('#submitbtn').prop('disabled', false);
        }
    });
});


        $(document).on('keyup', '.quantity-input', function () {
    var row = $(this).closest('tr');
    var enteredQuantity = parseInt($(this).val());
    var remainingQuantity = parseInt(row.find('.quantity-cell').text());

    row.removeClass('red-row green-row');

    if (isNaN(enteredQuantity) || enteredQuantity <= 0) {
        toastr.error('Invalid quantity.');
        return;
    }

    if (enteredQuantity > remainingQuantity) {
        toastr.error('Entered quantity is greater than remaining batch quantity.');
        row.addClass('red-row');
    } else {
        row.addClass('green-row');
    }
});


$('#resetButton').on('click', function () {
    $('.quantity-input').val('');  
     $('.ref').val('');  
     $('.input-tip').val(''); 
     $('.autosized').val(''); 
    $('#tbody').empty(); 

});


    });
</script>