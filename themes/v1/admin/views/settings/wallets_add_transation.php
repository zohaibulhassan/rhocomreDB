<style>
    .select2-container {}
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Deposit in Wallet </h3>
            </div>
            <div class="md-card-content">
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
                echo admin_form_open_multipart("system_settings/wallet_depoit", $attrib);
                ?>
                <div class="uk-grid">
                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Amount <span class="red">*</span></label>
                            <input type="hidden" name="wid" value="<?php echo $wid; ?>">
                            <input type="number" name="amount" class="md-input md-input-success label-fixed" required value="0">
                        </div>
                    </div>


                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Date <span class="red">*</span></label>
                            <input type="date" name="date" class="md-input md-input-success label-fixed" required>
                        </div>
                    </div>




                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Account Head <span class="red">*</span></label>
                            <input type="text" name="account_head" class="md-input md-input-success label-fixed">
                        </div>
                    </div>

                    <div class="uk-width-large-1-3">
                        <div class="md-input-wrapper md-input-filled">
                            <input type="hidden" value="0" name="paymentcollect_id" id="paymentcollect_id">
                            <label>Note</label>
                        </div>
                        <div class="md-input-wrapper md-input-filled">
                            <textarea name="note" class="md-input no_autosize" id="editor"></textarea>
                        </div>
                    </div>


                </div>








                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-1" style="padding-top: 20px;">
                        <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn">Submit</button>
                        <a href="<?php echo base_url('admin/purchases/wallets'); ?>" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light">Cancel</a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        // $('#addFrom').submit(function(e){
        //     e.preventDefault();
        //     $('#submitbtn').prop('disabled', true);
        //     $.ajax({
        //         url: '<?php echo base_url('admin/system_settings/insert_wallet'); ?>',
        //         type: 'POST',
        //         data: new FormData(this),
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         success: function(data) {
        //             var obj = jQuery.parseJSON(data);
        //             console.log(obj);
        //             if(obj.status){
        //                 toastr.success(obj.message);
        //                 window.location.href = "<?php echo base_url('admin/system_settings/wallets'); ?>";
        //             }
        //             else{
        //                 toastr.error(obj.message);
        //                 $('#submitbtn').prop('disabled', false);
        //             }
        //         }
        //     });
        // });
    });
</script>