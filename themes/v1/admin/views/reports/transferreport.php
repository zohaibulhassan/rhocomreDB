<style>
    .uk-open>.uk-dropdown, .uk-open>.uk-dropdown-blank{

    }
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text">Transfer Report</h3>
                    <div class="md-card-toolbar-actions">
                        <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                    </div>
                </div>
                <div class="md-card-content">
                    <div class="dt_colVis_buttons"></div>
                    <table id="dt_tableExport" class="uk-table">
                    <thead>
                                        <tr>
                                            <!-- <th>Own Company</th>
                                            <th>Customer NIC</th>
                                            <th>Customer NTN</th> -->
                                            <th>Transfer No</th>
                                            <th>Date</th>
                                            <th>To Warehouse</th>
                                            <th>From Warehouse</th>
                                            <th>Product ID</th>
                                            <th>Company Code</th>
                                            <th>Barcode</th>
                                            <th>HSN_Code</th>
                                            <th>Product Name</th>
                                            <th>Carton Size</th>
                                            <th>MRP</th>
                                            <th>Qty</th>
                                            <th>UOM</th>
                                            <th>Carton Qty</th>
                                            <th>Expiry Date</th>
                                            <th>Batch</th>
                                            <th>Supplier Manufacturer Name</th>
                                            <th>Tax Type</th>
                                            <th>Group ID</th>
                                            <th>Group Name</th>
                                        </tr>
                                    </thead>
                        <tbody>
 
 <tr>

     <?php foreach ($rows as $row) {   ?>  
        


<!-- <td><?php echo $row->Own_Company; ?></td>
<td><?php echo $row->Customer_NIC; ?></td>
<td><?php echo $row->Customer_NTN; ?></td> -->
<td><?php echo $row->Transfer_No; ?></td>
<td><?php echo $row->Date; ?></td>
<td><?php echo $row->TO_Warehouse; ?></td>
<td><?php echo $row->FROM_Warehouse; ?></td>
<td><?php echo $row->Product_ID; ?></td>
<td><?php echo $row->Company_Code; ?></td>
<td><?php echo $row->Barcode; ?></td>
<td><?php echo $row->HSN_Code; ?></td>
<td><?php echo $row->Product_Name; ?></td>
<td><?php echo $row->Carton_Size; ?></td>
<td><?php echo $row->MRP; ?></td>
<td><?php echo $row->Qty; ?></td>
<td><?php echo $row->UOM; ?></td>
<td><?php echo $row->Carton_Qty; ?></td>
<td><?php echo $row->Expiry_Date; ?></td>
<td><?php echo $row->Batch; ?></td>
<td><?php echo $row->Supplier_Manufacturer_Name; ?></td>
<td><?php echo $row->Remarks; ?></td>
<td><?php echo $row->Group_ID; ?></td>
<td><?php echo $row->Group_Name; ?></td>



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
    $(document).ready(function(){
        $('.select2').select2();
    });
</script>
<!-- Your HTML code here -->

<!-- Add a script tag to include the JavaScript code -->
<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the input elements for start and end dates
        const startDateInput = document.querySelector("input[name='start']");
        const endDateInput = document.querySelector("input[name='end']");

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
</script> -->