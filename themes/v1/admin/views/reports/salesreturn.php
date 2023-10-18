<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-toggle" style="opacity: 1; transform: scale(1);"></i>
                </div>
                <h3 class="md-card-toolbar-heading-text">Filters </h3>
            </div>
            <div class="md-card-content">
                <form action="<?php echo base_url('admin/reports/purchasereturn'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">


                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?php echo lang('own_companies', 'own_companies'); ?>
                                <?php
                                $oc['all'] = 'All';
                foreach ($own_companies as $own_companies) {
                    $oc[$own_companies->id] = $own_companies->companyname;
                }
                echo form_dropdown('own_company', $oc, $own_company, 'id="poown_companies" class="form-control input-tip select" data-placeholder="'.lang('select').' '.lang('own_companies').'" required="required" style="width:100%;" ');
                ?> </div>
                        </div>

                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?php echo lang('suppliers', 'suppliers'); ?>
                                <?php
                $bl['all'] = 'All';
                foreach ($suppliers as $supplier) {
                    $bl[$supplier->id] = $supplier->name;
                }
                echo form_dropdown('supplier', $bl, $csupplier, 'id="suppliers" data-placeholder="'.lang('select').' '.lang('supplier').'" required="required" class="form-control input-tip select" style="width:100%;"');
                ?>
                            </div>
                        </div>


                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?php echo lang('warehouse', 'customers'); ?>
                                <?php
                $whl[''] = lang('select').' '.lang('warehouse');
                foreach ($warehouses as $warehouse) {
                    $whl[$warehouse->id] = $warehouse->name;
                }
                echo form_dropdown('warehouse', $whl, $swarehouse, 'class="form-control input-tip select" id="warehouse" data-placeholder="'.$this->lang->line('select').' '.$this->lang->line('warehouse').'" style="width:100%;"');
                ?>
                            </div>
                        </div>


                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Start Date</label>
                                <input class="md-input  label-fixed" type="text" name="start_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" readonly>
                            </div>
                        </div>


                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>To Date</label>
                                <input class="md-input  label-fixed" type="text" name="end_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" readonly>
                            </div>
                        </div>

                        <div class="uk-width-large-2-4" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Purchase Return Report</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                    <thead>
                        <tr>
                            <th>Refrence No</th>
                            <th>Sale Date</th>
                            <th>Sales Return Date</th>
                            <th>Brand</th>
                            <th>Warehouse ID</th>
                            <th>Warehouse Name</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>HSN Code</th>
                            <th>Company Code</th>
                            <th>Carton Size</th>
                            <th>Expiry</th>
                            <th>Batch</th>
                            <th>Quantity</th>
                            <th>MRP</th>
                            <th>Cost</th>
                            <th>Total Item Tax</th>
                            <th>Further Tax</th>
                            <th>FED Tax</th>
                            <th>Total Tax</th>
                            <th>Sub Total</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
 
 <tr>

<?php foreach ($rows['data'] as $row) {   ?>  


<td><?php echo $row[0]; ?></td>
<td><?php echo $row[1]; ?></td>
<td><?php echo $row[2]; ?></td>
<td><?php echo $row[3]; ?></td>
<td><?php echo $row[4]; ?></td>
<td><?php echo $row[5]; ?></td>
<td><?php echo $row[6]; ?></td>
<td><?php echo $row[7]; ?></td>
<td><?php echo $row[8]; ?></td>
<td><?php echo $row[9]; ?></td>
<td><?php echo $row[10]; ?></td>
<td><?php echo $row[11]; ?></td>
<td><?php echo $row[12]; ?></td>
<td><?php echo $row[13]; ?></td>
<td><?php echo $row[14]; ?></td>
<td><?php echo $row[15]; ?></td>
<td><?php echo $row[16]; ?></td>
<td><?php echo $row[17]; ?></td>
<td><?php echo $row[18]; ?></td>
<td><?php echo $row[19]; ?></td>
<td><?php echo $row[20]; ?></td>
<td><?php echo $row[21]; ?></td>



</tr>
<?php
}
                ?>
 </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

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
<script>
    var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
        csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    var data = [];
    data[csrfName] = csrfHash;

    $(document).ready(function() {
        $('#dt_tableExport').DataTable({
            dom: 'Bfrtip',
            "scrollX": true,
            "scrollCollapse": true,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#dt_tableExport').DataTable();
    });
</script>

<!-- Your HTML code here -->

<!-- Add a script tag to include the JavaScript code -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the input elements for start and end dates
        const startDateInput = document.querySelector("input[name='start_date']");
        const endDateInput = document.querySelector("input[name='end_date']");

        // Get today's date
        const today = new Date();

        // Set the default start date as today's date
        startDateInput.value = today.toISOString().slice(0, 10);

        // Calculate one month ago from today
        const oneMonthAgo = new Date(today);
        oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);

        // Set the default end date as one month ago from today
        endDateInput.value = oneMonthAgo.toISOString().slice(0, 10);

        // Check if end date is provided in the URL
        const urlParams = new URLSearchParams(window.location.search);
        const endDateFromURL = urlParams.get("end");
        if (endDateFromURL) {
            endDateInput.value = endDateFromURL;
        }
    });
</script>
