<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f1f1f1;
    }

    #per_month_amount,
    #amount,
    #gst,
    #total_amount {
        padding: 2px;
        font-size: 14px;
        font-weight: bolder;
    }

    .right-align {
        text-align: right;
        margin-top: 20px;
        position: relative;
        margin-left: 60%;
    }

    .double-border {
        border-top: 3px double #000;
        padding-top: 10px;
        margin-top: 10px;
    }

    .pricing-package {
        background-color: maroon;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        transition: box-shadow 0.3s;
        height: 500px;
        color: #fff;
    }

    .pricing-package-standard {
        background-color: green;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        transition: box-shadow 0.3s;
        height: 500px;
        color: #fff;
    }

    .pricing-package-proffesional {
        background-color: #60a8eb;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        transition: box-shadow 0.3s;
        height: 500px;
        color: #fff;
    }

    .pricing-package-gold {
        background-color: #f7db77;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        transition: box-shadow 0.3s;
        height: 500px;
        color: #fff;
    }

    .pricing-package:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .pricing-package-standard h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .pricing-package-standard h5 {
        font-size: 22px;
        border-bottom: 2px solid white;
        padding-bottom: 10px;
        margin-bottom: 30px;
        color: orange;
    }


    .pricing-package-proffesional h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .pricing-package-proffesional h5 {
        font-size: 22px;
        border-bottom: 2px solid white;
        padding-bottom: 10px;
        margin-bottom: 30px;
        color: orange;
    }



    .pricing-package-gold h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .pricing-package-gold h5 {
        font-size: 22px;
        border-bottom: 2px solid white;
        padding-bottom: 10px;
        margin-bottom: 30px;
        color: orange;
    }



    .pricing-package h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .pricing-package h5 {
        font-size: 22px;
        border-bottom: 2px solid white;
        padding-bottom: 10px;
        margin-bottom: 30px;
        color: orange;
    }




    .features-list {
        text-align: left;
        margin-bottom: 30px;
    }

    .features-list ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .features-list ul li {
        margin-bottom: 10px;
    }

    .cmk-button {
        display: inline-block;
        background-color: #db6f00;
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .cmk-button:hover {
        background-color: #af4400;
    }

    .special-note {
        margin-top: 20px;
        font-style: italic;
        font-size: 14px;
    }

    .pricing-package p {
        font-size: 14px;
    }
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Package</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th style="width:150px">Package Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Amount</th>
                            <th style="width:150px">Notes</th>
                            <th>Status</th>
                            <th>Duration</th>
                            < <th>Time Left</th>
                                <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) {

                            $startDate = new DateTime($row->start_date);
                            $endDate = new DateTime($row->end_date);
                            $duration = $endDate->diff($startDate);

                            $years = $duration->y;
                            $months = $duration->m;

                            $currentDate = new DateTime();
                            $timeLeft = $currentDate > $endDate ? $currentDate->diff($endDate) : $endDate->diff($currentDate);

                            $leftYears = $timeLeft->y;
                            $leftMonths = $timeLeft->m;
                            $leftDays = $timeLeft->d;
                            ?>
                            <tr>
                                <td>
                                    <?php echo $row->id; ?>
                                </td>
                                <td>
                                    <?php echo $row->name; ?>
                                </td>
                                <td>
                                    <?php echo $row->start_date; ?>
                                </td>
                                <td>
                                    <?php echo $row->end_date; ?>
                                </td>
                                <td>
                                    <?php echo $row->amount; ?> //per month
                                </td>
                                <td>
                                    <?php echo $row->notes; ?>
                                </td>
                                <td>
                                    <?php echo $row->status == 0 ? "Deactive" : "Active"; ?>
                                </td>
                                <td>
                                    <?php
                                    if ($years > 0) {
                                        echo $years . " year" . ($years > 1 ? "s" : "") . " ";
                                    }
                                    if ($months > 0) {
                                        echo $months . " month" . ($months > 1 ? "s" : "");
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($leftYears > 0) {
                                        echo $leftYears . " year" . ($leftYears > 1 ? "s" : "") . " ";
                                    }
                                    if ($leftMonths > 0) {
                                        echo $leftMonths . " month" . ($leftMonths > 1 ? "s" : "") . " ";
                                    }
                                    if ($leftDays > 0) {
                                        echo $leftDays . " day" . ($leftDays > 1 ? "s" : "");
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button id="extendbtn"
                                        class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light md-btn-mini extendbtn"
                                        data-package-id="<?php echo $row->id; ?>">Extend Package</button>

                                    <a href="<?php echo base_url('admin/system_settings/package_status/' . $row->id); ?>"
                                        class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light md-btn-mini">Change
                                        Status</a>
                                    <a href="<?php echo base_url('admin/system_settings/package_delete/' . $row->id); ?>"
                                        class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light md-btn-mini deleteproduct">Delete</a>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>


                </table>
            </div>
        </div>
    </div>
</div>
<!-- datatables -->

<div class="md-fab-wrapper md-fab-in-card" style="position: fixed; bottom: 20px;">
    <button class="md-fab md-fab-success md-fab-wave waves-effect waves-button addbtn" type="button">
        <i class="fa-solid fa-plus"></i>
    </button>
</div>

<div class="uk-modal" id="modal_addpackage">
    <!-- Add wallet modal content goes here -->
    <?php
    // $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
    // echo admin_form_open_multipart("package/add", $attrib);
    ?>

    <?php
    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
    echo admin_form_open_multipart("system_settings/package_add", $attrib);
    ?>
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Create Package</h3>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid">
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <label>Title</label>
                        <input type="text" name="name" class="md-input md-input-success label-fixed">
                    </div>

                    <div class="md-input-wrapper md-input-filled">
                        <label>Start Date</label>
                        <input id="start_date" class="md-input label-fixed" type="text" name="start_date"
                            data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" readonly>
                    </div>

                    <div class="md-input-wrapper md-input-filled">
                        <label>Number of Months</label>
                        <select id="num_months" class="md-input label-fixed" name="end_date">
                            <option value="1">1 month</option>
                            <option value="2">2 months</option>
                            <option value="3">3 months</option>
                            <option value="4">1 Qtr</option>
                            <option value="12">1 Year</option>
                        </select>
                    </div>
                    <div class="md-input md-input-success label-fixed">
                        <label>Notes</label>
                        <textarea cols="30" rows="2" class="md-input autosized" style="  height: 121px;" required=""
                            name="note" spellcheck="false"></textarea>
                    </div>


                    <div class="uk-modal-footer uk-text-right">
                        <button type="submit" class="md-btn md-btn-success md-btn-flat submitbtn">Submit</button>
                        <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
</div>
<div class="uk-modal" id="modal_extendpackage">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Extend Package</h3>
        </div>
        <div class="uk-modal-body">
            <p>Please select the duration to extend the package:</p>
            <a href="#modal-full" uk-toggle id="upgrade-link">Upgrade</a>
            <?php
            $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
            echo admin_form_open_multipart("system_settings/package_extend/" . $row->id);
            ?>
            <div class="uk-grid">
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <input type="text" id="packageid" value="<?php echo $row->id; ?>" hidden>
                        <input type="text" id="packagestartdate" value="<?php echo $row->start_date ?>" hidden>
                        <input type="text" id="packageprice" value="<?php echo $row->amount ?>" hidden>
                        <label>Number of Months</label>
                        <select id="extend_num_months" class="md-input label-fixed" name="extend_num_months">
                            <option value="1">1 month</option>
                            <option value="2">2 months</option>
                            <option value="3">3 months</option>
                            <option value="4">4 months</option>
                            <option value="4">1 Qtr</option>
                            <option value="12">1 Year</option>
                        </select>
                    </div>
                </div>
                <div class="right-align">
                    <span id="per_month">Per Month : </span>
                    <span id="per_month_amount">---</span>
                    <br>
                    <span id="amount_heading">Total Price: </span>
                    <span id="amount">---</span>
                    <br>
                    <span id="gst_heading">GST: </span>
                    <span id="gst">---</span>
                    <br>
                    <div class="double-border">
                        <span id="total_amount_heading">Total Amount: </span>
                        <span id="total_amount">---</span>
                    </div>
                    <br>
                    <span id="valid_upto_heading">Valid Upto Date: </span>
                    <span id="valid_upto_date">xxxx-xx-xx</span>
                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="submit" class="md-btn md-btn-success md-btn-flat submitbtn">Extend</button>
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Cancel</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>


<div class="uk-modal uk-modal-full uk-height-viewport" id="modal_full_body">
    <div class="uk-modal-dialog uk-modal-dialog-large uk-height-viewport">
        <div class="uk-grid">



            <div class="uk-width-1-4">
                <div class="pricing-package">
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
                    echo admin_form_open_multipart("system_settings/package_upgrade", $attrib);
                    ?>
                    <h2>Starter</h2>
                    <input type="text" value="starter" name="name" hidden>
                    <h5> ₨5,00 Per Month</h5>
                    <input type="text" value="<?= 500 - 500 * 0.10 ?>" name="price" hidden>
                    <input type="text" value="1" name="end_date" hidden>
                    <input type="text" value="I am starter package" name="note" hidden>
                    <div class="features-list">
                        <ul>
                            <li><i class="fa fa-check"></i> 50 sales orders / month incl.</li>
                            <li><i class="fa fa-check"></i> 1 team member.</li>
                            <li><i class="fa fa-check"></i> 1 location.</li>
                            <li><i class="fa fa-check"></i> Inventory sync after 2 hours.</li>
                        </ul>
                    </div>
                    <button class="cmk-button" type="submit">
                        <i class="fa fa-arrow-right"></i> Buy Now
                    </button>
                    <?php echo form_close(); ?>
                    <div class="special-note">10% Discount</div>
                </div>
            </div>



            <div class="uk-width-1-4">
                <div class="pricing-package-standard">
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
                    echo admin_form_open_multipart("system_settings/package_upgrade", $attrib);
                    ?>
                    <h2>Standard</h2>
                    <h5>₨5,500 Per Month</h5>
                    <input type="text" value="<?= 5500 - 5500 * 0.20 ?>" name="price" hidden>
                    <input type="text" value="1" name="end_date" hidden>
                    <input type="text" value="I am standard package" name="note" hidden>
                    <input type="text" value="Standard" name="name" hidden>
                    <div class="features-list">
                        <ul>
                            <li><i class="fa fa-check"></i> 500 sales orders / month incl.</li>
                            <li><i class="fa fa-check"></i> 2 team members.</li>
                            <li><i class="fa fa-check"></i> 1 location.</li>
                            <li><i class="fa fa-check"></i> Inventory sync after 2 hours.</li>
                        </ul>
                    </div>
                    <button class="cmk-button" type="submit">
                        <i class="fa fa-arrow-right"></i> Buy Now
                    </button>
                    <?php echo form_close(); ?>
                    <div class="special-note">Quarterly (Save 20%)</div>
                </div>
            </div>


            <div class="uk-width-1-4">
                <div class="pricing-package-proffesional">
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
                    echo admin_form_open_multipart("system_settings/package_upgrade", $attrib);
                    ?>
                    <h2>Professional</h2>
                    <h5>₨35,500 Per Month</h5>
                    <input type="text" value="<?= 3500 - 3500 * 0.20 ?>" name="price" hidden>
                    <input type="text" value="1" name="end_date" hidden>
                    <input type="text" value="I am Professional package" name="note" hidden>
                    <input type="text" value="Professional" name="name" hidden>
                    <div class="features-list">
                        <ul>
                            <li><i class="fa fa-check"></i> 1,000 sales orders / month incl.</li>
                            <li><i class="fa fa-check"></i> 3 team members.</li>
                            <li><i class="fa fa-check"></i> 1 location.</li>
                            <li><i class="fa fa-check"></i> Inventory sync after 15 mins.</li>
                        </ul>
                    </div>
                    <button class="cmk-button" type="submit">
                        <i class="fa fa-arrow-right"></i> Buy Now
                    </button>
                    <?php echo form_close(); ?>
                    <div class="special-note">Quarterly (Save 20%)</div>
                </div>
            </div>

            <div class="uk-width-1-4">
                <div class="pricing-package-gold">
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'stForm');
                    echo admin_form_open_multipart("system_settings/package_upgrade", $attrib);
                    ?>
                    <h2>Premium</h2>
                    <h5>₨15,000 Per Month</h5>
                    <input type="text" value="<?= 1500 - 1500 * 0.20 ?>" name="price" hidden>
                    <input type="text" value="1" name="end_date" hidden>
                    <input type="text" value="I am Premium package" name="note" hidden>
                    <input type="text" value="Premium" name="name" hidden>
                    <div class="features-list">
                        <ul>
                            <li><i class="fa fa-check"></i> 1,500 sales orders / month incl.</li>
                            <li><i class="fa fa-check"></i> 4 team members.</li>
                            <li><i class="fa fa-check"></i> 2 locations.</li>
                            <li><i class="fa fa-check"></i> Inventory sync after 15 mins.</li>
                        </ul>
                    </div>
                    <button class="cmk-button" type="submit">
                        <i class="fa fa-arrow-right"></i> Buy Now
                    </button>
                    <?php echo form_close(); ?>
                    <div class="special-note">Quarterly (Save 20%)</div>
                </div>
            </div>



        </div>
    </div>
</div>
</div>
</div>
</div>

<script
    src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
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
<!-- datatables custom integration -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/datatables.uikit.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/datatable.js"></script>
<script>
    var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
        csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var data = [];
    data[csrfName] = csrfHash;

    $(document).ready(function () {
        $('#dt_tableExport').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#dt_tableExport').DataTable();
    });
</script>


<script>
    $(document).ready(function () {
        $('#dt_tableExport').DataTable();
    });
</script>

<script>
    $(document).ready(function () {
        // $('.select2').select2();
        $(document).on('click', '.addbtn', function () {
            UIkit.modal('#modal_addpackage').show();
        });


        $(document).on('click', '.depbtn', function () {
            var wid = "<?php echo $w->id; ?>";
            $('input[name="wid"]').val(wid);
            UIkit.modal('#modal_depbtn').show();
        });


        $(document).ready(function () {
            $('#upgrade-link').click(function (e) {
                e.preventDefault(); // Prevent the default link behavior
                UIkit.modal('#modal_addpackage').hide();
                UIkit.modal('#modal_depbtn').hide();
                UIkit.modal('#modal_full_body').show(); // Open the full body modal
            });
        });

    });

</script>


<!-- <script>
    $(document).ready(function () {
        // $('.select2').select2();
        $(document).on('click', '#extendbtn', function () {
            UIkit.modal('#modal_extendpackage').show();
        });
    });

</script> -->


<script>
    $(document).ready(function () {
        $(document).on('click', '.extendbtn', function () {
            UIkit.modal('#modal_extendpackage').show();
        }); 
    });
</script>






<script>
    document.addEventListener('DOMContentLoaded', function () {
        var startDateInput = document.getElementById('start_date');
        var endDateInput = document.getElementById('end_date');
        var validationMessage = document.getElementById('validation_message');

        endDateInput.addEventListener('change', function () {
            var startDate = new Date(startDateInput.value);
            var endDate = new Date(endDateInput.value);
            var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
            var diffInDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (diffInDays % 30 !== 0) {
                validationMessage.textContent = 'The difference between start date and end date should be a multiple of 30.';
            } else {
                validationMessage.textContent = '';
            }
        });
    });


    $(document).on('change', '#extend_num_months', function () {
        var packagemonths = document.getElementById('extend_num_months').value;
        var startdate = document.getElementById('packagestartdate').value;
        var price = document.getElementById('packageprice').value;
        document.getElementById('per_month_amount').innerHTML = price;
        document.getElementById('amount').innerHTML = price * packagemonths;
        document.getElementById('gst').innerHTML = "17%";
        document.getElementById('total_amount').innerHTML = Math.round(price * packagemonths * 0.17);
        var validUptoDate = new Date(startdate);
        validUptoDate.setMonth(validUptoDate.getMonth() + parseInt(packagemonths));
        document.getElementById('valid_upto_date').innerHTML = validUptoDate.toISOString().slice(0, 10);
    });
</script>