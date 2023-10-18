<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}

    .md-btn:active,
    .md-btn:focus,
    .md-btn:hover,
    .uk-button-dropdown.uk-open>.md-btn {
        background: #69b54a;
        color: white;

    }

    .md-btn>i.material-icons {
        margin-top: 0px;
    }

    .uk-dropdown,
    .uk-dropdown-blank {
        width: auto;
    }

    #dt_tableExport .dtfc-fixed-right {
        /* position: absolute !important; */
    }
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Direct Sales</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr>
                            <th style="width:180px">Date</th>
                            <th style="width:180px">Ref No</th>
                            <th style="width:100px">SO No</th>
                            <th style="width:120px">PO Number</th>
                            <th style="width:150px">Customer Name</th>
                            <th style="width:150px">Customer Phone</th>
                            <th style="width:150px">Own Compnay</th>
                            <th style="width:150px">Warehouse</th>
                            <th>Grand Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th class="s_po">Sale Type</th>
                            <th class="dt-no-export s_po">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="md-fab-wrapper md-fab-in-card" style="position: fixed;bottom: 20px;">
    <a class="md-fab md-fab-success md-fab-wave waves-effect waves-button addbtn"
        href="<?php echo base_url('admin/salesorders/add') ?>"><i class="fa-solid fa-plus"></i></a>
</div>

<div class="uk-modal" id="modal_dispatchfrom">
    <?php
    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'dispatchFrom');
    echo admin_form_open_multipart("#", $attrib);
    ?>
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Assign order to dispatcher</h3>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Delivery Date <span class="red">*</span></label>
                        <input class="md-input  label-fixed" type="text" name="date"
                            data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" readonly
                            value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="uk-width-large-1-1" style="margin-top: 15px;">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Dispatcher <span class="red">*</span></label>
                        <input type="hidden" name="sale_id" id="sale_id" value="0">
                        <select name="dispatcher" class="uk-width-1-1 select2" style="width: 100%">
                            <?php
                            foreach ($dispatches as $row) {
                                echo '<option value="' . $row->id . '" ';
                                echo ' >' . $row->text . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="uk-width-large-1-1">
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="submit" class="md-btn md-btn-success md-btn-flat" id="submitbtn3">Submit</button>
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>


<div class="uk-modal" id="payment-modal">
   <div class="uk-modal-dialog">
   <div class="uk-modal-header">
        <h3 class="uk-modal-title">Add Payment</h3>
    </div>
    <div class="uk-modal-body">
    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form','id' => 'paymentaddmodal');
         echo admin_form_open_multipart("#", $attrib); ?>
        <div class="uk-grid">
            <div class="uk-width-large-1-2">
                <div class="md-input-wrapper md-input-filled">
                    <label for="uk_dp_1">Date</label>
                    <!-- <input type="hidden" value="<?php echo $inv->id; ?>" name="sale_id" /> -->
                    <input class="md-input  label-fixed" type="text" name="date" id="uk_dp_1"
                        data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" readonly
                        value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="uk-width-large-1-2">
                <div class="md-input-wrapper md-input-filled">
                    <label>Reference No <span class="red">*</span></label>
                    <input type="text" name="reference_no" class="md-input md-input-success label-fixed" required>
                </div>
            </div>
            <div class="uk-width-large-1-2">
                <div class="md-input-wrapper md-input-filled">
                    <label>Amount <span class="red">*</span></label>
                    <input type="text" name="amount-paid" class="md-input md-input-success label-fixed" required
                        autocomplete="off" value="<?= $this->sma->formatDecimal($inv->grand_total - $inv->paid) ?>">
                </div>
            </div>
            <div class="uk-width-large-1-2">
                <div class="md-input-wrapper md-input-filled">
                    <label>Payment Method <span class="red">*</span></label>
                    <select name="paid_by" class="uk-width-1-1 select2" id="paid_by">
                        <option value="cash" selected>Cash</option>
                        <option value="onlinetransfer">Online Tansfer</option>
                        <option value="payorder">Payorder</option>
                        <option value="withholdingtax">With Holding Tax</option>
                        <option value="retainer">Retainer</option>
                        <option value="balance">Balance</option>
                        <option value="gift_card">Gift Card</option>
                        <option value="CC">Credit Card</option>
                        <option value="Cheque">Cheque</option>
                        <option value="creaditnote">Creadit Note</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div class="uk-width-large-1-2 ccn_div">
                <div class="md-input-wrapper md-input-filled">
                    <label>CPR Status <span class="red">*</span></label>
                    <select name="pcc_status" class="uk-width-1-1 select2" id="cpr_status">
                        <option value="0">Not Recived</option>
                        <option value="1">Recived</option>
                    </select>
                </div>
            </div>
            <div class="uk-width-large-1-2 ccn_div">
                <div class="md-input-wrapper md-input-filled">
                    <label>CPR No <span class="red">*</span></label>
                    <input type="number" name="cprno" class="md-input md-input-success label-fixed" autocomplete="off"
                        min="0">
                </div>
            </div>
            <div class="uk-width-large-1-2 cheaqueno_div" style="display:none;">
                <div class="md-input-wrapper md-input-filled">
                    <label>Cheque No <span class="red">*</span></label>
                    <input type="text" name="cheque_no" class="md-input md-input-success label-fixed">
                </div>
            </div>
            <div class="uk-width-large-1-1">
                <div class="md-input-wrapper md-input-filled">
                    <label>Note </label>
                    <textarea rows="4" class="md-input autosized"
                        style="overflow-x: hidden; overflow-wrap: break-word; height: 121px;" name="note"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-modal-footer uk-text-right">
        <button type="submit" class="md-btn md-btn-success md-btn-flat">Submit</button>
        <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
    </div>
    <?php echo form_close(); ?>
   </div>
</div>




<!-- datatables -->
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<!-- datatables buttons-->
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/buttons.uikit.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/pdfmake/build/vfs_fonts.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.colVis.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.html5.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.print.js"></script>
<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-fixedcolumns/dataTables.fixedColumns.min.js"></script>
<!-- datatables custom integration -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/datatables.uikit.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/datatable.js"></script>
<script>
    var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
        csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var data = [];
    data[csrfName] = csrfHash;
    data['warehouse'] = '<?php echo $warehouse; ?>';
    data['supplier'] = '<?php echo $supplier; ?>';
    data['customer'] = '<?php echo $customer; ?>';
    data['own_company'] = '<?php echo $own_company; ?>';
    data['start_date'] = '<?php echo $start_date; ?>';
    data['end_date'] = '<?php echo $end_date; ?>';
    $.DataTableInit({
        selector: '#dt_tableExport',
        url: "<?= admin_url('sales/get_listspayments'); ?>",
        data: data,
        aaSorting: [
            [1, "desc"]
        ],
        columnDefs: [{
            "targets": 15,
            "orderable": false
        }],
        responsive: true,
        // fixedColumns:   {left: 0,right: 1},
        scrollX: true,
    });
</script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $("body").on("click", ".deletebtn", function () {
            var iid = $(this).data('id');
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
                csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
            console.log(iid);
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this sale!",
                icon: "warning",
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Delete',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    return fetch(`<?= base_url('admin/sales/delete/') ?>${iid}?reason=${reason}&[${csrfName}]=${csrfHash}`)
                        .then(response => {
                            console.log(response);
                            if (!response.ok) {
                                console.log('Error');
                                throw new Error(response.statusText)
                            } else if (reason == "") {
                                throw new Error('Enter Reason')
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
                .then((result) => {
                    console.log('CR: ' + result);
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sale Delete Successfully',
                            showConfirmButton: false,
                            timer: 10000
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                });
        });
        $("body").on("click", ".printBtn", function () {
            var id = $(this).data('id');
            $.ajax({
                url: '<?php echo base_url('admin/sales/print_slip'); ?>',
                type: 'GET',
                data: {
                    id: id
                },
                success: function (data) {
                    var obj = jQuery.parseJSON(data);
                    toastr.success(obj.message);
                    if (obj.status) {
                        if (obj.print) {
                            // window.open(obj.url, '_blank'); 
                            var mapForm = document.createElement("form");
                            mapForm.target = "Map";
                            mapForm.method = "POST"; // or "post" if appropriate
                            mapForm.action = obj.url2;

                            var mapInput = document.createElement("input");
                            mapInput.type = "text";
                            mapInput.name = "print_data";
                            mapInput.value = obj.form_data;
                            mapForm.appendChild(mapInput);

                            document.body.appendChild(mapForm);

                            map = window.open("", "Map", "status=0,title=0,height=600,width=800,scrollbars=1");
                            if (map) {
                                mapForm.submit();
                            } else {
                                alert('You must allow popups for this map to work.');
                            }
                        }
                    } else {
                        toastr.error(obj.message);
                    }
                }
            });
        });
    });
    $(document).ready(function () {
        // Sales Tax Invoice 1
        $("body").on("click", ".stiPDF", function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Sales Tax Invoice',
                input: 'select',
                icon: "warning",
                confirmButtonText: "Download",
                inputOptions: {
                    'original': 'Original',
                    'duplicate': 'Duplicate'
                },
                inputPlaceholder: 'Select Option',
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        console.log(value);
                        if (value == "original") {
                            window.location.href = "<?= admin_url('sales/salestaxpdf1') ?>/" + id + "?invoicestatus=original";
                        } else if (value == "duplicate") {
                            window.location.href = "<?= admin_url('sales/salestaxpdf1') ?>/" + id + "?invoicestatus=duplicate";
                        }
                    })
                }
            });
        });
        // Sales Tax Invoice Trader 1
        $("body").on("click", ".stiPDF2", function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Sales Tax Invoice Trading',
                input: 'select',
                icon: "warning",
                confirmButtonText: "Download",
                inputOptions: {
                    'original': 'Original',
                    'duplicate': 'Duplicate'
                },
                inputPlaceholder: 'Select Option',
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        console.log(value);
                        if (value == "original") {
                            window.location.href = "<?= admin_url('sales/salestaxpdf1trading') ?>/" + id + "?invoicestatus=original";
                        } else if (value == "duplicate") {
                            window.location.href = "<?= admin_url('sales/salestaxpdf1trading') ?>/" + id + "?invoicestatus=duplicate";
                        }
                    })
                }
            });
        });
        // Sales Tax Invoice 1
        $("body").on("click", ".sti2PDF", function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Sales Tax Invoice 2',
                input: 'select',
                icon: "warning",
                confirmButtonText: "Download",
                inputOptions: {
                    'original': 'Original',
                    'perfoma': 'Perfoma'
                },
                inputPlaceholder: 'Select Option',
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        console.log(value);
                        if (value == "original") {
                            window.location.href = "<?= admin_url('sales/salestaxpdf2') ?>/" + id + "?invoicestatus=original";
                        } else if (value == "perfoma") {
                            window.location.href = "<?= admin_url('sales/salestaxpdf2') ?>/" + id + "?invoicestatus=perfoma";
                        }
                    })
                }
            });
        });
        $("body").on("click", ".sti2PDF2", function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Sales Tax Invoice 2 New',
                input: 'select',
                icon: "warning",
                confirmButtonText: "Download",
                inputOptions: {
                    'original': 'Original',
                    'perfoma': 'Perfoma'
                },
                inputPlaceholder: 'Select Option',
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        console.log(value);
                        if (value == "original") {
                            window.location.href = "<?= admin_url('sales/salestaxpdf2_new') ?>/" + id + "?invoicestatus=original";
                        } else if (value == "perfoma") {
                            window.location.href = "<?= admin_url('sales/salestaxpdf2_new') ?>/" + id + "?invoicestatus=perfoma";
                        }
                    })
                }
            });
        });
    });
    $(document).on('click', '.dispatcheBtn', function () {
        $('#sale_id').val($(this).data('id'));
        UIkit.modal('#modal_dispatchfrom').show();
    });
    $('#dispatchFrom').submit(function (e) {
        e.preventDefault();
        $('#submitbtn3').prop('disabled', true);
        $.ajax({
            url: '<?php echo base_url('admin/assign_orders/submit_assign_single'); ?>',
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                console.log(obj);
                if (obj.status) {
                    toastr.success(obj.message);
                    location.reload();
                } else {
                    toastr.error(obj.message);
                    $('#submitbtn3').prop('disabled', false);
                }
            }
        });
    });


    $(document).on('click', '.payment-modal', function () {
   var sales_id = $(this).data('id');
   UIkit.modal('#payment-modal').show();

   $('#paymentaddmodal').submit(function (e) {
       e.preventDefault();
       $('#submitbtn3').prop('disabled', true);
       $.ajax({
           url: '<?php echo base_url("admin/sales/add_payment_submit") ?>' + '?id=' + sales_id,
           type: 'POST',
           data: new FormData(this),
           contentType: false,
           cache: false,
           processData: false,
           success: function (data) {
               var obj = jQuery.parseJSON(data);
               console.log(obj);
               if (obj.status) {
                   toastr.success(obj.message);
                   location.reload();
               } else {
                   toastr.error(obj.message);
                   $('#submitbtn3').prop('disabled', false);
               }

               // Close the modal after form submission
               UIkit.modal('#payment-modal').hide();
           }
       });
       UIkit.modal('#payment-modal').hide();
       location.reload();
   });

});






</script>
<script>
    // Initialize UIkit
    UIkit.dropdown('.uk-dropdown', {
        mode: 'click'
    });
</script>

<script>

</script>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('#paid_by').change(function () {
            changepay();
        });
        function changepay() {
            var paidby = $('#paid_by').val();
            $('.ccn_div').hide();
            $('.cheaqueno_div').hide();
            if (paidby == "Cheque") {
                $('.cheaqueno_div').show();
            }
            if (paidby == "creaditnote") {
                $('.ccn_div').show();
            }
        }
        changepay();
    });

</script>