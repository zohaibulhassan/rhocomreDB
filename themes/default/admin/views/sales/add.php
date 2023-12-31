<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?php echo $Settings->default_tax_rate; ?>,
        product_tax = 0, invoice_tax = 0, product_discount = 0, order_discount = 0, total_discount = 0, total = 0, allow_discount = <?php echo ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    $(document).ready(function () {
        if (localStorage.getItem('remove_slls')) {
            if (localStorage.getItem('slitems')) {
                localStorage.removeItem('slitems');
            }
            if (localStorage.getItem('sldiscount')) {
                localStorage.removeItem('sldiscount');
            }
            if (localStorage.getItem('sltax2')) {
                localStorage.removeItem('sltax2');
            }
            if (localStorage.getItem('slref')) {
                localStorage.removeItem('slref');
            }
            if (localStorage.getItem('slshipping')) {
                localStorage.removeItem('slshipping');
            }
            if (localStorage.getItem('slwarehouse')) {
                localStorage.removeItem('slwarehouse');
            }
            if (localStorage.getItem('slnote')) {
                localStorage.removeItem('slnote');
            }
            if (localStorage.getItem('slinnote')) {
                localStorage.removeItem('slinnote');
            }
            if (localStorage.getItem('slcustomer')) {
                localStorage.removeItem('slcustomer');
            }
            if (localStorage.getItem('slbiller')) {
                localStorage.removeItem('slbiller');
            }
            if (localStorage.getItem('slcurrency')) {
                localStorage.removeItem('slcurrency');
            }
            if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            if (localStorage.getItem('slsale_status')) {
                localStorage.removeItem('slsale_status');
            }
            if (localStorage.getItem('slpayment_status')) {
                localStorage.removeItem('slpayment_status');
            }
            if (localStorage.getItem('paid_by')) {
                localStorage.removeItem('paid_by');
            }
            if (localStorage.getItem('amount_1')) {
                localStorage.removeItem('amount_1');
            }
            if (localStorage.getItem('paid_by_1')) {
                localStorage.removeItem('paid_by_1');
            }
            if (localStorage.getItem('pcc_holder_1')) {
                localStorage.removeItem('pcc_holder_1');
            }
            if (localStorage.getItem('pcc_type_1')) {
                localStorage.removeItem('pcc_type_1');
            }
            if (localStorage.getItem('pcc_month_1')) {
                localStorage.removeItem('pcc_month_1');
            }
            if (localStorage.getItem('pcc_year_1')) {
                localStorage.removeItem('pcc_year_1');
            }
            if (localStorage.getItem('pcc_no_1')) {
                localStorage.removeItem('pcc_no_1');
            }
            if (localStorage.getItem('cheque_no_1')) {
                localStorage.removeItem('cheque_no_1');
            }
            if (localStorage.getItem('payment_note_1')) {
                localStorage.removeItem('payment_note_1');
            }
            if (localStorage.getItem('slpayment_term')) {
                localStorage.removeItem('slpayment_term');
            }
            localStorage.removeItem('remove_slls');
        }
        <?php if ($quote_id) { ?>
        localStorage.setItem('slcustomer', '<?php echo $quote->customer_id; ?>');
        localStorage.setItem('slbiller', '<?php echo $quote->biller_id; ?>');
        localStorage.setItem('slwarehouse', '<?php echo $quote->warehouse_id; ?>');
        localStorage.setItem('slnote', '<?php echo str_replace(["\r", "\n"], '', $this->sma->decode_html($quote->note)); ?>');
        localStorage.setItem('sldiscount', '<?php echo $quote->order_discount_id; ?>');
        localStorage.setItem('sltax2', '<?php echo $quote->order_tax_id; ?>');
        localStorage.setItem('slshipping', '<?php echo $quote->shipping; ?>');
        localStorage.setItem('slitems', JSON.stringify(<?php echo $quote_items; ?>));
        <?php } ?>
        <?php if ($this->input->get('customer')) { ?>
        if (!localStorage.getItem('slitems')) {
            localStorage.setItem('slcustomer', <?php echo $this->input->get('customer'); ?>);
        }
        <?php } ?>
        <?php if ($Owner || $Admin) { ?>
        if (!localStorage.getItem('sldate')) {
            $("#sldate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
        $("#podate").datetimepicker({
            format: site.dateFormats.js_ldate,
            fontAwesome: true,
            language: 'sma',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0
        }).datetimepicker('update', new Date());
        $(document).on('change', '#podate', function (e) {
            localStorage.setItem('sldate', $(this).val());
        });
        if (sldate = localStorage.getItem('sldate')) {
            $('#sldate').val(sldate);
        }
        <?php } ?>
        $(document).on('change', '#slbiller', function (e) {
            localStorage.setItem('slbiller', $(this).val());
        });
        if (slbiller = localStorage.getItem('slbiller')) {
            $('#slbiller').val(slbiller);
        }
        if (!localStorage.getItem('slref')) {
            localStorage.setItem('slref', '<?php echo $slnumber; ?>');
        }
        if (!localStorage.getItem('sltax2')) {
            localStorage.setItem('sltax2', <?php echo $Settings->default_tax_rate2; ?>);
        }
        ItemnTotals();
        $('.bootbox').on('hidden.bs.modal', function (e) {
            $('#add_item').focus();
        });

        $("#slref").on('focusout', function(){
            $.ajax({
                type: 'get',
                url: '<?php echo admin_url('sales/CheckInvoiceNumber'); ?>',
                dataType: "json",
                data: {
                    ref_no: $("#slref").val(),
                },
                success: function (data) {
                    $(this).removeClass('ui-autocomplete-loading');
                    if(data == true) {
                        alert("Invoice Already Register");
                    }
                }
            });
        });


        $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#slcustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?php echo lang('select_above'); ?>');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?php echo admin_url('sales/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#slwarehouse").val(),
                        customer_id: $("#slcustomer").val(),
                        supplier_id: $("#posupplier").val(),
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
                    bootbox.alert('<?php echo lang('no_match_found'); ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
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
                    bootbox.alert('<?php echo lang('no_match_found'); ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    bootbox.alert('<?php echo lang('no_match_found'); ?>');
                }
            }
        });
        $(document).on('change', '#gift_card_no', function () {
            var cn = $(this).val() ? $(this).val() : '';
            if (cn != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_gift_card/" + cn,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?php echo lang('incorrect_gift_card'); ?>');
                        } else if (data.customer_id !== null && data.customer_id !== $('#slcustomer').val()) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?php echo lang('gift_card_not_for_customer'); ?>');

                        } else {
                            $('#gc_details').html('<small>Card No: ' + data.card_no + '<br>Value: ' + data.value + ' - Balance: ' + data.balance + '</small>');
                            $('#gift_card_no').parent('.form-group').removeClass('has-error');
                        }
                    }
                });
            }
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?php echo lang('add_sale'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = ['data-toggle' => 'validator', 'role' => 'form'];
echo admin_form_open_multipart('sales/add', $attrib);
if ($quote_id) {
    echo form_hidden('quote_id', $quote_id);
}
?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo lang('date', 'sldate'); ?>
                                <?php echo form_input('date', isset($_POST['date']) ? $_POST['date'] : '', 'class="form-control input-tip date" id="sldate" required="required"'); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                            <label for="slref">Invoice No</label>
                                <?php echo form_input('reference_no', isset($_POST['reference_no']) ? $_POST['reference_no'] : $slnumber, 'class="form-control input-tip" id="slref"'); ?>
                            </div>
                        </div>
                        <?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo lang('biller', 'slbiller'); ?>
                                    <?php
                    foreach ($billers as $biller) {
                        $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                    }
                    echo form_dropdown('biller', $bl, isset($_POST['biller']) ? $_POST['biller'] : $Settings->default_biller, 'id="slbiller" data-placeholder="'.lang('select').' '.lang('biller').'" required="required" class="form-control input-tip select" style="width:100%;"');
                            ?>
                                </div>
                            </div>
                        <?php } else {
                            $biller_input = [
                                'type' => 'hidden',
                                'name' => 'biller',
                                'id' => 'slbiller',
                                'value' => $this->session->userdata('biller_id'),
                            ];

                            echo form_input($biller_input);
                        } ?>

                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div
                                    class="panel-heading"><?php echo lang('please_select_these_before_adding_product'); ?></div>
                                <div class="panel-body" style="padding: 5px;">
                                    <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo lang('warehouse', 'slwarehouse'); ?>
                                                <?php
                                                $wh[''] = '';
                                        foreach ($warehouses as $warehouse) {
                                            $wh[$warehouse->id] = $warehouse->name;
                                        }
                                        echo form_dropdown('warehouse', $wh, isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse, 'id="slwarehouse" class="form-control input-tip select" data-placeholder="'.lang('select').' '.lang('warehouse').'" required="required" style="width:100%;" ');
                                        ?>
                                            </div>
                                        </div>
                                    <?php } else {
                                        $warehouse_input = [
                                            'type' => 'hidden',
                                            'name' => 'warehouse',
                                            'id' => 'slwarehouse',
                                            'value' => $this->session->userdata('warehouse_id'),
                                        ];

                                        echo form_input($warehouse_input);
                                    } ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="slcustomer">Customer Type</label>
                                            <input type="text" value="" hidden class="hidden_customer_id" readonly/>
                                            <div class="input-group">
                                                <?php
                                                echo form_input('customer', isset($_POST['customer']) ? $_POST['customer'] : '', 'id="slcustomer" data-placeholder="'.lang('select').' '.lang('customer').'" required="required" class="form-control input-tip" style="width:100%;"');
?>
                                                <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;">
                                                    <a href="#" id="toogle-customer-read-attr" class="external">
                                                        <i class="fa fa-pencil" id="addIcon" style="font-size: 1.2em;"></i>
                                                    </a>
                                                </div>
                                                <div class="input-group-addon no-print" style="padding: 2px 7px; border-left: 0;">
                                                    <a href="#" id="view-customer" class="external" data-toggle="modal" data-target="#myModal">
                                                        <i class="fa fa-eye" id="addIcon" style="font-size: 1.2em;"></i>
                                                    </a>
                                                </div>
                                                <?php if ($Owner || $Admin || $GP['customers-add']) { ?>
                                                <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                                    <a href="<?php echo admin_url('customers/add'); ?>" id="add-customer"class="external" data-toggle="modal" data-target="#myModal">
                                                        <i class="fa fa-plus-circle" id="addIcon"  style="font-size: 1.2em;"></i>
                                                    </a>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Delivery Address</label>
                                            <select name="deliveryaddress" id="deliveryaddressid" class="form-control" >
                                                <option value="0">Default Address</option>
                                            </select>
                                        </div>
                                    </div>

                                    <?php if ($Owner || $Admin || !$this->session->userdata('own_companies_id')) { ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo lang('own_companies', 'poown_companies'); ?>
                                                <?php
$oc[''] = '';
                                        foreach ($own_company as $own_companies) {
                                            $oc[$own_companies->id] = $own_companies->companyname;
                                        }
                                        echo form_dropdown('own_company', $oc, isset($_POST['own_companies']) ? $_POST['own_companies'] : $Settings->default_warehouse, 'id="poown_companies" class="form-control input-tip select" data-placeholder="'.lang('select').' '.lang('own_companies').'" required="required" style="width:100%;" ');
                                        ?>
                                            </div>
                                        </div>
                                    <?php } else {
                                        $own_companies_input = [
                                            'type' => 'hidden',
                                            'name' => 'own_companies',
                                            'id' => 'slown_companies',
                                            'value' => $this->session->userdata('own_companies_id'),
                                        ];

                                        echo form_input($own_companies_input);
                                    } ?>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sletaliers">E-taliers</label>
                                            <?php
                                                $lcu[''] = '';
foreach ($lcustomers as $lcustomer) {
    $lcu[$lcustomer->id] = $lcustomer->company;
}
echo form_dropdown('etaliers', $lcu, $_POST['etaliers'], 'id="etaliers" class="form-control input-tip searching_select" data-placeholder="'.lang('select').' '.lang('E-taliers').'" required="required" style="width:100%;" ');
?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <?php if ($Owner || $Admin || !$this->session->userdata('suppliers_id')) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?php echo lang('suppliers', 'posuppliers'); ?>
                                            <?php
$su[''] = '';
                                    foreach ($suppliers as $supplier) {
                                        $su[$supplier->id] = $supplier->name;
                                    }
                                    echo form_dropdown('supplier', $su, isset($_POST['supplier']) ? $_POST['supplier'] : $Settings->default_warehouse, 'id="posupplier" class="form-control input-tip searching_select" data-placeholder="'.lang('select').' '.lang('supplier').'" required="required" style="width:100%;" ');
                                    ?>
                                        </div>
                                    </div>
                                <?php } else {
                                    $supplier_input = [
                                        'type' => 'hidden',
                                        'name' => 'supplier',
                                        'id' => 'slsupplier',
                                        'value' => $this->session->userdata('supplier_id'),
                                    ];

                                    echo form_input($supplier_input);
                                } ?>
                            </div>
                        </div>

                        <div class="col-md-12" id="sticker">
                            <div class="well cuwell-sm">
                                <div class="form-group" style="margin-bottom:0;">
                                    <div class="input-group wide-tip">
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <i class="fa fa-2x fa-barcode addIcon"></i></a></div>
                                        <?php echo form_input('add_item', '', 'class="form-control input-lg" id="add_item" placeholder="'.lang('add_product_to_order').'"'); ?>
                                        <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="addManually" class="tip" title="<?php echo lang('add_product_manually'); ?>">
                                                <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                            </a>
                                        </div>
                                        <?php } if ($Owner || $Admin || $GP['sales-add_gift_card']) { ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="sellGiftCard" class="tip" title="<?php echo lang('sell_gift_card'); ?>">
                                               <i class="fa fa-2x fa-credit-card addIcon" id="addIcon"></i>
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <label class="table-label"><?php echo lang('order_items'); ?> *</label>

                                <div class="controls table-controls">
                                    <table id="slTable" class="table items table-striped table-bordered table-condensed table-hover sortable_table">
                                        <thead>
                                        <tr>
                                            <th class="col-md-3"><?php echo lang('product').' ('.lang('code').' - '.lang('name').')'; ?></th>
                                            <?php
                                            if ($Settings->product_serial) {
                                                echo '<th class="col-md-2">'.lang('serial_no').'</th>';
                                            }
?>
                                            <th class="col-md-1"><?php echo lang('net_unit_price'); ?></th>
                                            <th class="col-md-1"><?php echo lang('MRP'); ?></th>
                                            <th class="col-md-1"><?php echo lang('quantity'); ?></th>
                                            <th class="col-md-1"><?php echo lang('Remain quantity'); ?></th>
                                            <th class="col-md-1"><?php echo lang('batch#'); ?></th>
                                            <th class="col-md-1"><?php echo lang('expiry'); ?></th>
                                            <th class="col-md-1"><?php echo lang('Discount One'); ?></th>
                                            <th class="col-md-1"><?php echo lang('Discount Two'); ?></th>
                                            <th class="col-md-1"><?php echo lang('Discount Three'); ?></th>
                                            <th class="col-md-1"><?php echo lang('Discount Three Code'); ?></th>
                                            <th class="col-md-1"><?php echo lang('FED TAX'); ?></th>
                                            <?php
if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) {
    echo '<th class="col-md-1">'.lang('discount').'</th>';
}
?>
                                            <?php
if ($Settings->tax1) {
    echo '<th class="col-md-1">'.lang('product_tax').'</th>';
}
?>
                                             <th>
                                                <?php echo lang('Advance Income Tax'); ?>
                                            </th>
                                             <th>
                                                <?php echo lang('Further Tax'); ?>
                                                (<span class="currency"><?php echo $default_currency->code; ?></span>)
                                            </th>
                                            <th>
                                                <?php echo lang('subtotal'); ?>
                                                (<span class="currency"><?php echo $default_currency->code; ?></span>)
                                            </th>
                                            <th style="width: 30px !important; text-align: center;">
                                                <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php if ($Settings->tax2) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo lang('order_tax', 'sltax2'); ?>
                                    <?php
                                    $tr[''] = '';
                            foreach ($tax_rates as $tax) {
                                $tr[$tax->id] = $tax->name;
                            }
                            echo form_dropdown('order_tax', $tr, isset($_POST['order_tax']) ? $_POST['order_tax'] : $Settings->default_tax_rate2, 'id="sltax2" data-placeholder="'.lang('select').' '.lang('order_tax').'" class="form-control input-tip select" style="width:100%;"');
                            ?>
                                </div>
                            </div>
                        <?php } ?>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo lang('order_discount', 'sldiscount'); ?>
                                    <?php echo form_input('order_discount', '', 'class="form-control input-tip" id="sldiscount"'); ?>
                                </div>
                            </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo lang('shipping', 'slshipping'); ?>
                                <?php echo form_input('shipping', '', 'class="form-control input-tip" id="slshipping"'); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo lang('document', 'document'); ?>
                                <input id="document" type="file" data-browse-label="<?php echo lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo lang('sale_status', 'slsale_status'); ?>
                                <?php $sst = ['completed' => lang('completed'), 'pending' => lang('pending')];
echo form_dropdown('sale_status', $sst, '', 'class="form-control input-tip" required="required" id="slsale_status"'); ?>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo lang('payment_term', 'slpayment_term'); ?>
                                <?php echo form_input('payment_term', '', 'class="form-control " '); ?>

                            </div>
                        </div>
                        <?php if ($Owner || $Admin || $GP['sales-payments']) { ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo lang('payment_status', 'slpayment_status'); ?>
                                <?php $pst = ['pending' => lang('pending'), 'due' => lang('due'), 'partial' => lang('partial'), 'paid' => lang('paid')];
                            echo form_dropdown('payment_status', $pst, '', 'class="form-control input-tip" required="required" id="slpayment_status"'); ?>

                            </div>
                        </div>
                        <?php
                        } else {
                            echo form_hidden('payment_status', 'pending');
                        }
?>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="po_number">P.O Number </label>
                                <?php echo form_input('po_number', '', 'class="form-control input-tip" id="po_number"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="po_number">P.O Date </label>
                                <?php echo form_input('po_date', '', 'class="form-control " id="podate"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="po_number">D.C Number </label>
                                <?php echo form_input('dc_number', '', 'class="form-control input-tip" id="dc_number"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="po_number">Cartdiage </label>
                                <?php echo form_input('cartidiage', '', 'class="form-control input-tip" id="cartidiage"'); ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div id="payments" style="display: none;">
                            <div class="col-md-12">
                                <div class="well well-sm well_1">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <?php echo lang('payment_reference_no', 'payment_reference_no'); ?>
                                                    <?php echo form_input('payment_reference_no', isset($_POST['payment_reference_no']) ? $_POST['payment_reference_no'] : $payment_ref, 'class="form-control tip" id="payment_reference_no"'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="payment">
                                                    <div class="form-group ngc">
                                                        <?php echo lang('amount', 'amount_1'); ?>
                                                        <input name="amount-paid" type="text" id="amount_1"
                                                               class="pa form-control kb-pad amount"/>
                                                    </div>
                                                    <div class="form-group gc" style="display: none;">
                                                        <?php echo lang('gift_card_no', 'gift_card_no'); ?>
                                                        <input name="gift_card_no" type="text" id="gift_card_no"
                                                               class="pa form-control kb-pad"/>

                                                        <div id="gc_details"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <?php echo lang('paying_by', 'paid_by_1'); ?>
                                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by">
                                                        <?php echo $this->sma->paid_opts(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="pcc_1" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_no" type="text" id="pcc_no_1"
                                                               class="form-control" placeholder="<?php echo lang('cc_no'); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_holder" type="text" id="pcc_holder_1"
                                                               class="form-control"
                                                               placeholder="<?php echo lang('cc_holder'); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="pcc_type" id="pcc_type_1"
                                                                class="form-control pcc_type"
                                                                placeholder="<?php echo lang('card_type'); ?>">
                                                            <option value="Visa"><?php echo lang('Visa'); ?></option>
                                                            <option
                                                                value="MasterCard"><?php echo lang('MasterCard'); ?></option>
                                                            <option value="Amex"><?php echo lang('Amex'); ?></option>
                                                            <option value="Discover"><?php echo lang('Discover'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_month" type="text" id="pcc_month_1"
                                                               class="form-control" placeholder="<?php echo lang('month'); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_year" type="text" id="pcc_year_1"
                                                               class="form-control" placeholder="<?php echo lang('year'); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_ccv" type="text" id="pcc_cvv2_1"
                                                               class="form-control" placeholder="<?php echo lang('cvv2'); ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pcheque_1" style="display:none;">
                                            <div class="form-group"><?php echo lang('cheque_no', 'cheque_no_1'); ?>
                                                <input name="cheque_no" type="text" id="cheque_no_1"
                                                       class="form-control cheque_no"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('payment_note', 'payment_note_1'); ?>
                                            <textarea name="payment_note" id="payment_note_1"
                                                      class="pa form-control kb-text payment_note"></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo lang('sale_note', 'slnote'); ?>
                                        <?php echo form_textarea('note', isset($_POST['note']) ? $_POST['note'] : '', 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo lang('staff_note', 'slinnote'); ?>
                                        <?php echo form_textarea('staff_note', isset($_POST['staff_note']) ? $_POST['staff_note'] : '', 'class="form-control" id="slinnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_sale', lang('submit'), 'id="add_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?php echo lang('reset'); ?></div>
                        </div>
                    </div>
                </div>
                <div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
                    <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
                        <tr class="warning">
                            <td><?php echo lang('items'); ?> <span class="totals_val pull-right" id="titems">0</span></td>
                            <td><?php echo lang('total'); ?> <span class="totals_val pull-right" id="total">0.00</span></td>
                            <?php if ($Owner || $Admin || $this->session->userdata('allow_discount')) { ?>
                            <td><?php echo lang('order_discount'); ?> <span class="totals_val pull-right" id="tds">0.00</span></td>
                            <?php }?>
                            <?php if ($Settings->tax2) { ?>
                                <td><?php echo lang('order_tax'); ?> <span class="totals_val pull-right" id="ttax2">0.00</span></td>
                            <?php } ?>
                            <td><?php echo lang('shipping'); ?> <span class="totals_val pull-right" id="tship">0.00</span></td>
                            <td><?php echo lang('grand_total'); ?> <span class="totals_val pull-right" id="gtotal">0.00</span></td>
                        </tr>
                    </table>
                </div>

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?php echo lang('close'); ?></span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo lang('product_tax'); ?></label>
                            <div class="col-sm-8">
                                <?php
        $tr[''] = '';
                        foreach ($tax_rates as $tax) {
                            $tr[$tax->id] = $tax->name;
                        }
                        echo form_dropdown('ptax', $tr, '', 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');
                        ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="pserial" class="col-sm-4 control-label"><?php echo lang('serial_no'); ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pserial">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?php echo lang('quantity'); ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="punit" class="col-sm-4 control-label"><?php echo lang('product_unit'); ?></label>
                        <div class="col-sm-8">
                            <div id="punits-div"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?php echo lang('product_option'); ?></label>
                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
                    <?php if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) { ?>
                        <div class="form-group">
                            <label for="pdiscount"
                                   class="col-sm-4 control-label"><?php echo lang('product_discount'); ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pprice" class="col-sm-4 control-label"><?php echo lang('unit_price'); ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pprice" <?php echo ($Owner || $Admin || $GP['edit_price']) ? '' : 'readonly'; ?>>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?php echo lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                            <th style="width:25%;"><?php echo lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_price" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_price" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?php echo lang('submit'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="select_pr_Modal" tabindex="-1" role="dialog" aria-labelledby="select_pr_ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?php echo lang('close'); ?></span></button>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;">Product Price</th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                            <th style="width:25%;">Purchase Price</th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="product_price" value=""/>
                    <input type="hidden" id="purchase_price" value=""/>
                </form> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?php echo lang('submit'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?php echo lang('close'); ?></span></button>
                <h4 class="modal-title" id="mModalLabel"><?php echo lang('add_product_manually'); ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?php echo lang('product_code'); ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?php echo lang('product_name'); ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mname">
                        </div>
                    </div>
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?php echo lang('product_tax'); ?> *</label>

                            <div class="col-sm-8">
                                <?php
                        $tr[''] = '';
                        foreach ($tax_rates as $tax) {
                            $tr[$tax->id] = $tax->name;
                        }
                        echo form_dropdown('mtax', $tr, '', 'id="mtax" class="form-control input-tip select" style="width:100%;"');
                        ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?php echo lang('quantity'); ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mquantity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="munit" class="col-sm-4 control-label"><?php echo lang('unit'); ?> *</label>

                        <div class="col-sm-8">
                            <?php
                            $uts[''] = '';
foreach ($units as $unit) {
    $uts[$unit->id] = $unit->name;
}
echo form_dropdown('munit', $uts, '', 'id="munit" class="form-control input-tip select" style="width:100%;"');
?>
                        </div>
                    </div>
                    <?php if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) { ?>
                        <div class="form-group">
                            <label for="mdiscount"
                                   class="col-sm-4 control-label"><?php echo lang('product_discount'); ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?php echo lang('unit_price'); ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?php echo lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?php echo lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItemManually"><?php echo lang('submit'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang('sell_gift_card'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo lang('enter_info'); ?></p>

                <div class="alert alert-danger gcerror-con" style="display: none;">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <span id="gcerror"></span>
                </div>
                <div class="form-group">
                    <?php echo lang('card_no', 'gccard_no'); ?> *
                    <div class="input-group">
                        <?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no"'); ?>
                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                                                                                           id="genNo"><i
                                    class="fa fa-cogs"></i></a></div>
                    </div>
                </div>
                <input type="hidden" name="gcname" value="<?php echo lang('gift_card'); ?>" id="gcname"/>

                <div class="form-group">
                    <?php echo lang('value', 'gcvalue'); ?> *
                    <?php echo form_input('gcvalue', '', 'class="form-control" id="gcvalue"'); ?>
                </div>
                <div class="form-group">
                    <?php echo lang('price', 'gcprice'); ?> *
                    <?php echo form_input('gcprice', '', 'class="form-control" id="gcprice"'); ?>
                </div>
                <div class="form-group">
                    <?php echo lang('customer', 'gccustomer'); ?>
                    <?php echo form_input('gccustomer', '', 'class="form-control" id="gccustomer"'); ?>
                    
                </div>
                <div class="form-group">
                    <?php echo lang('expiry_date', 'gcexpiry'); ?>
                    <?php echo form_input('gcexpiry', $this->sma->hrsd(date('Y-m-d', strtotime('+2 year'))), 'class="form-control date" id="gcexpiry"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="addGiftCard" class="btn btn-primary"><?php echo lang('sell_gift_card'); ?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#gccustomer').select2({
            minimumInputLength: 1,
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
        $('#genNo').click(function () {
            var no = generateCardNo();
            $(this).parent().parent('.input-group').children('input').val(no);
            return false;
        });
        $('#slcustomer').change(function(){
            getAddressLis(true);

        });
        function getAddressLis(palert = false){
            var customerID = $('#slcustomer').val();
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

            $.ajax({
                url: '<?php echo admin_url('sales/getaddress'); ?>',
                type: 'POST',
                data: {customerID:customerID,[csrfName]:csrfHash},
                success: function(data){
                    var obj = jQuery.parseJSON(data);
                    $('#deliveryaddressid').html(obj.html);
                    if(palert){
                        alert(obj.pricemessage);
                    }
                },
                error: function(jqXHR, textStatus){
                    var errorStatus = jqXHR.status;
                }
            });
        }
        getAddressLis();
        $('#poown_companies').change(function(){
            var customerID = $('#slcustomer').val();
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
            var owncom = $(this).val();
            $.ajax({
                url: '<?php echo admin_url('salesorders/autoinvoicecheck'); ?>',
                type: 'POST',
                data: {owncom:owncom,[csrfName]:csrfHash},
                success: function(data){
                    if(data == "true"){
                        $('#slref').val('Auto Generate After Create');
                        $('#slref').attr("readonly","readonly");
                    }
                    else{
                        $('#slref').val('');
                        $('#slref').removeAttr("readonly");
                    }
                },
                error: function(jqXHR, textStatus){
                    var errorStatus = jqXHR.status;
                }
            });
        });
    });
</script>
