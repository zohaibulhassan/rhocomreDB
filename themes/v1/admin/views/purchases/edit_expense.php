<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Edit Expense </h3>
            </div>
            <div class="md-card-content" >
                <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'updateFrom');
                    echo admin_form_open_multipart("#", $attrib);
                ?>
                    <div class="uk-grid">
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label for="uk_dp_1">Date</label>
                                <input type="hidden" name="id" value="<?php echo $expense->id; ?>" >
                                <input class="md-input  label-fixed" type="text" name="date" id="uk_dp_1" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" readonly value="<?php echo dateformate($expense->date); ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Reference No <span class="red" >*</span></label>
                                <input type="text" name="reference_no" class="md-input md-input-success label-fixed" readonly value="<?php echo $expense->reference; ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Category <span class="red" >*</span></label>
                                <select name="category" class="uk-width-1-1 select2" required >
                                    <option value="">Select Category</option>
                                    <?php
                                        foreach($categories as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            if($row->id == $expense->category_id){
                                                echo 'selected';
                                            }
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Location <span class="red" >*</span></label>
                                <select name="location" class="uk-width-1-1 select2" required >
                                    <option value="">Select Location</option>
                                    <?php
                                        foreach($warehouses as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            if($row->id == $expense->warehouse_id){
                                                echo 'selected';
                                            }
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Own Compnay <span class="red" >*</span></label>
                                <select name="owncompany" class="uk-width-1-1 select2">
                                    <option value="">Select Own Company</option>
                                    <?php
                                        foreach($companies as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            if($row->id == $expense->own_company){
                                                echo 'selected';
                                            }
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Expense Type <span class="red" >*</span></label>
                                <input type="hidden" name="type" value="<?php echo $expense->etype; ?>" >
                                <select class="uk-width-1-1 select2" id="extype" disabled >
                                    <option value="general" <?php if($expense->etype == "general"){ echo 'selected'; } ?> >General</option>
                                    <option value="inbound" <?php if($expense->etype == "inbound"){ echo 'selected'; } ?> >Inbound</option>
                                    <option value="outbound" <?php if($expense->etype == "outbound"){ echo 'selected'; } ?> >Outbound</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2" id="purchases" >
                            <div class="md-input-wrapper md-input-filled">
                                <label>Purchases <span class="red" >*</span></label>
                                <select name="purchases[]" class="uk-width-1-1" id="purchases_select" multiple >
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2" id="sales">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Sales <span class="red" >*</span></label>
                                <select name="sales[]" class="uk-width-1-1" id="sales_select" multiple >
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2" id="suppliers">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Suppliers <span class="red" >*</span></label>
                                <select name="suppliers[]" class="uk-width-1-1" id="suppliers_select" multiple >
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2" id="customers">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Customers <span class="red" >*</span></label>
                                <select name="customers[]" class="uk-width-1-1" id="customers_select" multiple >
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Payment Method <span class="red" >*</span></label>
                                <input type="hidden" name="paymentmethod" value="<?php echo $expense->pay_method; ?>" >
                                <select class="uk-width-1-1 select2" id="paymentmethod" disabled >
                                    <option value="cash" <?php if($expense->pay_method == "cash"){ echo 'selected'; } ?> >Cash</option>
                                    <option value="cheque" <?php if($expense->pay_method == "cheque"){ echo 'selected'; } ?> >Cheque</option>
                                    <option value="onlinetransfer" <?php if($expense->pay_method == "onlinetransfer"){ echo 'selected'; } ?> >Online Transfer</option>
                                    <option value="payorder" <?php if($expense->pay_method == "payorder"){ echo 'selected'; } ?> >Pay Order</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2" id="wallet">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Wallet <span class="red" >*</span></label>
                                <input type="hidden" name="wallet" value="<?php echo $expense->wallet_id; ?>" >
                                <select class="uk-width-1-1 select2" disabled >
                                    <option value="">Select Wallet</option>
                                    <?php
                                        foreach($wallets as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            if($row->id == $expense->wallet_id){
                                                echo 'selected';
                                            }
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2" id="transferid" >
                            <div class="md-input-wrapper md-input-filled">
                                <label>Transfer ID <span class="red" >*</span></label>
                                <input type="text" name="transferno" class="md-input md-input-success label-fixed" value="<?php echo $expense->transaction_no; ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2" id="chequeno" >
                            <div class="md-input-wrapper md-input-filled">
                                <label>Cheque No <span class="red" >*</span></label>
                                <input type="text" name="cheque" class="md-input md-input-success label-fixed" value="<?php echo $expense->cheeque_no; ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2" id="payorder" >
                            <div class="md-input-wrapper md-input-filled">
                                <label>Pay Order No <span class="red" >*</span></label>
                                <input type="text" name="payorder" class="md-input md-input-success label-fixed" value="<?php echo $expense->pay_order_no; ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Amount <span class="red" >*</span></label>
                                <input type="number" name="amount" class="md-input md-input-success label-fixed" required autocomplete="off" min="0" value="<?php echo $expense->amount; ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-1">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Note <span class="red" >*</span></label>
                                <textarea cols="30" rows="4" class="md-input autosized" style="overflow-x: hidden; overflow-wrap: break-word; height: 121px;" required name="note" ><?php echo $expense->note; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-1" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn" >Submit</button>
                            <a href="<?php echo base_url('admin/purchases/expenses'); ?>" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" >Cancel</a>
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
    ClassicEditor
    .create( document.querySelector( '#editor' ),{
        toolbar: {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
            shouldNotGroupWhenFull: true
        }
    })
    .then( editor => {
        window.editor = editor;
    })
    .catch( error => {
        console.error( error );
    });
</script>
<script>
    $(document).ready(function(){
        $('.select2').select2();
        $('#extype').change(function(){
            checkExType();
        });
        function checkExType(){
            var type = $('#extype').val();
            
            $('#customers select').val("").trigger('change');
            $('#suppliers select').val("").trigger('change');
            $('#sales select').val("").trigger('change');
            $('#purchases select').val("").trigger('change');

            $('#customers').hide();
            $('#suppliers').hide();
            $('#sales').hide();
            $('#purchases').hide();
            if(type=="inbound"){
                $('#purchases').show();
                $('#suppliers').show();
            }
            else if(type=="outbound"){
                $('#sales').show();
                $('#suppliers').show();
                $('#customers').show();
            }
        }
        $('#paymentmethod').change(function(){
            checkPaymentMethod();
        });
        function checkPaymentMethod(){
            var paymentmethod = $('#paymentmethod').val();
            $('#payorder').hide();
            $('#chequeno').hide();
            $('#transferid').hide();
            $('#wallet').hide();
            if(paymentmethod == "cash"){
                $('#wallet').show();
                $('#payorder').val('');
                $('#chequeno').val('');
                $('#transferid').val('');
            }
            else if(paymentmethod == "onlinetransfer"){
                $('#transferid').show();
                $('#wallet select').val("").trigger('change');
                $('#payorder').val('');
                $('#chequeno').val('');
            }
            else if(paymentmethod == "cheque"){
                $('#chequeno').show();
                $('#wallet select').val("").trigger('change');
                $('#payorder').val('');
                $('#transferid').val('');
            }
            else if(paymentmethod == "payorder"){
                $('#payorder').show();
                $('#wallet select').val("").trigger('change');
                $('#chequeno').val('');
                $('#transferid').val('');
            }
        }
        setTimeout(() => {
            checkExType();
            checkPaymentMethod();
        }, 1000);
        $('#purchases_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/purchases"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                return data;
            },
        });
        $('#sales_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/sales"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                return data;
            },
        });
        $('#suppliers_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/suppliers"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                return data;
            },
        });
        $('#customers_select').select2({
            ajax: {
                url: '<?php echo base_url("admin/general/customers"); ?>',
                dataType: 'json',
            },
            formatResult: function (data, term) {
                return data;
            },
        });
        $('#updateFrom').submit(function(e){
            e.preventDefault();
            $('#submitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/purchases/update_expense'); ?>',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    console.log(obj);
                    if(obj.status){
                        toastr.success(obj.message);
                        window.location.href = "<?php echo base_url('admin/purchases/expenses');?>";
                    }
                    else{
                        toastr.error(obj.message);
                        $('#submitbtn').prop('disabled', false);
                    }
                }
            });
        });
    });
</script>

