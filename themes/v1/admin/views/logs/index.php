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
                            <th style="width:150px">Notes</th>
                            <th>Status</th>
                            <th>Duration</th> <!-- New column -->
                            <th>Time Left</th> <!-- New column -->
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
                                    <!-- <a href=""
                                    class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light md-btn-mini">Edit</a> -->
                                    <a href=""
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

<form action="<?php echo admin_url('package/add'); ?>" method="post" enctype="multipart/form-data" data-toggle="validator" role="form" id="stForm">
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
                </form>
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
</script>