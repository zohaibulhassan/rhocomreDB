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
                <form action="<?php echo base_url('admin/reports/ssalereport'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Start Date</label>
                                <input class="md-input  label-fixed" type="text" name="start" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="on" value="<?= $this->data['start'];   ?>" readonly>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>To Date</label>
                                <input class="md-input  label-fixed" type="text" name="end" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="on" value="<?= $this->data['end'];   ?>" readonly>
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
                <h3 class="md-card-toolbar-heading-text">Sale Report</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr>
                            <th>reference_no</th>
                            <th>monthp</th>
                            <th>date</th>
                            <!-- <th>customer</th> -->
                            <th>etailer ID</th>
                            <th>etailer</th>
                            <th>code</th>
                            <th>company_code</th>
                            <th>Product ID</th>
                            <th>product_name</th>
                            <th>quantity</th>
                            <th>litre_pcs</th>
                            <th>total_sales_in_ltr</th>
                            <th>product_unit_code</th>
                            <th>carton_size</th>
                            <th>carton_qty</th>
                            <th>net_unit_price</th>
                            <th>sale_price</th>
                            <th>value_excl_tax</th>
                            <th>tax</th>
                            <th>item_tax </th>
                            <th>further_tax</th>
                            <th>fed_tax</th>
                            <th>total_tax</th>
                            <th>discount_one/th>
                            <th>discount_two</th>
                            <th>discount_three</th>
                            <th>discount</th>
                            <th>subtotal</th>
                            <th>remarks</th>
                            <th>mrp_excl_tax/th>
                            <th>mrp</th>
                            <th>expiry</th>
                            <th>batch</th>
                            <th>brand</th>
                            <th>warehouse_name</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <?php 
                                 
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            foreach ($rows['data'] as $row) {


                           
                        

                            ?>
                                <td><?php echo $row->reference_no; ?></td>
                                <td><?php echo $row->monthp; ?></td>
                                <td><?php echo $row->date; ?></td>
                                <!-- <td>
                                    <?php  // echo $row->name; 
                                    ?></td> -->
                                <td><?php echo $row->etailerid; ?></td>
                                <td><?php echo $row->etailer; ?></td>
                                <td><?php echo $row->code; ?></td>
                                <td><?php echo $row->company_code; ?></td>
                                <td><?php echo $row->product_id; ?></td>
                                <td><?php echo $row->product_name; ?></td>
                                <td><?php echo $row->quantity; ?></td>
                                <td><?php echo $row->litre_pcs; ?></td>
                                <td><?php echo $row->total_sales_in_ltr; ?></td>
                                <td><?php echo $row->product_unit_code; ?></td>
                                <td><?php echo $row->carton_size; ?></td>
                                <td><?php echo $row->carton_qty; ?></td>
                                <td><?php echo $row->net_unit_price; ?></td>
                                <td><?php echo $row->sale_price; ?></td>
                                <td><?php echo $row->value_excl_tax; ?></td>
                                <td><?php echo $row->tax; ?></td>
                                <td><?php echo $row->item_tax; ?></td>
                                <td><?php echo $row->further_tax; ?></td>
                                <td><?php echo $row->fed_tax; ?></td>
                                <td><?php echo $row->total_tax; ?></td>
                                <td><?php echo $row->discount_one; ?></td>
                                <td><?php echo $row->discount_two; ?></td>
                                <td><?php echo $row->discount_three; ?></td>
                                <td><?php echo $row->discount; ?></td>
                                <td><?php echo $row->subtotal; ?></td>
                                <td><?php echo $row->remarks; ?></td>
                                <td><?php echo $row->mrp_excl_tax; ?></td>
                                <td><?php echo $row->mrp; ?></td>
                                <td><?php echo $row->expiry; ?></td>
                                <td><?php echo $row->batch; ?></td>
                                <td><?php echo $row->brand; ?></td>
                                <td><?php echo $row->warehouse_name; ?></td>



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
        $('.select2').select2();
    });
</script>