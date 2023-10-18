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
                <form action="<?php echo base_url('admin/reports/spurchasereport'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Start Date</label>
                                <input class="md-input  label-fixed" type="text" name="start" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?= $this->data['start'];   ?>" readonly>
                            </div>
                        </div>


                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>To Date</label>
                                <input class="md-input  label-fixed" type="text" name="end" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?= $this->data['end'];   ?>" readonly>
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
                <h3 class="md-card-toolbar-heading-text">Purchase Report</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                    <thead>
                        <tr>
                            <!-- <th>Own Comapny</th> -->
                            <th>NTN No</th>
                            <th>GST Number</th>
                            <th>Refrence No</th>
                            <th>Company</th>
                            <th>Date</th>
                            <th>Brand</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>litre_pcs / Weight in pcs</th>
                            <th>total_purchase_in_ltr / Weight </th>

                            <th>MRP</th>
                            <th>HSN Code</th>
                            <th>Quantity</th>
                            <!-- <th>Quantity Recieved</th> -->
                            <th>UOM</th>
                            <th>Net Unit Cost</th>
                            <th>Total Price Ex. Tax</th>
                            <th>Tax</th>
                            <th>Item Tax</th>
                            <th>Further Tax</th>
                            <th>Fed Tax</th>
                            <th>Advance Income Tax</th>
                            <th>Total Taxes</th>
                            <th>Discount</th>
                            <th>Sub Total</th>
                            <th>Remarks</th>
                            <th>MRP Ex. Tax</th>
                            <th>MRP Ex. Tax</th>
                            <th>Expiry</th>
                            <th>Batch</th>
                            <th>Carton Size</th>
                            <th>Company Code</th>
                            <th>Warehouse ID</th>
                            <th>Warehouse Name</th>
                            <th>Group ID</th>
                            <th>Group Name</th>
                            <th>Sales Incentive</th>
                            <th>Trade Discount</th>
                            <th>Consumer Discount</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>

                            <?php foreach ($rows['data'] as $row) {   ?>


                                <!-- <td><?php
                                            //        echo $row[0]; 
                                            ?></td> -->
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
                                <td><?php echo $row[22]; ?></td>
                                <td><?php echo $row[23]; ?></td>
                                <td><?php echo $row[24]; ?></td>
                                <td><?php echo $row[25]; ?></td>
                                <td><?php echo $row[26]; ?></td>
                                <td><?php echo $row[27]; ?></td>
                                <td><?php echo $row[28]; ?></td>
                                <td><?php echo $row[29]; ?></td>
                                <td><?php echo $row[30]; ?></td>
                                <td><?php echo $row[31]; ?></td>
                                <td><?php echo $row[32]; ?></td>
                                <td><?php echo $row[33]; ?></td>
                                <td><?php echo $row[34]; ?></td>
                                <td><?php echo $row[35]; ?></td>
                                <td><?php echo $row[36]; ?></td>
                                <td><?php echo $row[37]; ?></td>
                                <td><?php echo $row[38]; ?></td>
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
