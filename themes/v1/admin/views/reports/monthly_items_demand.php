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
                <form action="<?php echo base_url('admin/reports/monthly_items_demand'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">

                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("warehouse", "customers"); ?>
                                <?php
                                $whl[""] = lang('select') . ' ' . lang('warehouse');
                                foreach ($warehouses as $warehouse) {
                                    $whl[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $whl, $swarehouse, 'class="form-control input-tip select" id="warehouse" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" style="width:100%;"');
                                ?>
                            </div>
                        </div>



                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("Brand", "brands"); ?>
                                <?php
                                $whl[""] = lang('select') . ' ' . lang('brand');
                                foreach ($brands as $brand) {
                                    $whl[$brand->id] = $brand->name;
                                }
                                echo form_dropdown('warehouse', $whl, $brand, 'class="form-control input-tip select" id="warehouse" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("brand") . '" style="width:100%;"');
                                ?>
                            </div>
                        </div>


                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("customers", "customers"); ?>
                                <?php
                                $whl[""] = lang('select') . ' ' . lang('Customers');
                                foreach ($customers as $customer) {
                                    $whl[$customer->id] = $customer->name;
                                }
                                echo form_dropdown('warehouse', $whl, $customer, 'class="form-control input-tip select" id="warehouse" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer") . '" style="width:100%;"');
                                ?>
                            </div>
                        </div>





                        <div class="uk-width-large-1-4" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Monthly Items Demand</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Group ID</th>
                            <th>Group Name</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Warehouse</th>
                            <th>Cutomer</th>
                            <th>Brand</th>
                            <th>Carton Size</th>
                            <th>MRP</th>
                            <th>Cost (With Tax)</th>
                            <th>Demand Quantity</th>
                            <th>Demand Master Carton</th>
                            <!-- <th>Sold Quantity</th>
                            <th>Sold Master Carton</th>
                            <th>Remaining Quantity</th>
                            <th>Remaining Master Carton</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rows as $row) {
                            $month = DateTime::createFromFormat('!m', $row->d_month);
                        ?>
                            <tr>
                                <td><?= $month->format('M') ?>-<?= $row->d_year ?></td>
                                <td><?= $row->group_id ?></td>
                                <td><?= $row->group_name ?></td>
                                <td><?= $row->pid ?></td>
                                <td><?= $row->pname ?></td>
                                <td><?= $row->wid ?></td>
                                <td><?= $row->customer ?></td>
                                <td><?= $row->brand ?></td>
                                <td><?= $row->carton_size ?></td>
                                <td><?= $row->pmrp ?></td>
                                <td>
                                    <?php
                                    if ($row->tax_type == 2) {
                                        echo $row->tax_rate + $row->cost;
                                    } else {
                                        echo ($row->cost / 100 * $row->tax_rate) + $row->cost;
                                    }
                                    ?>


                                </td>
                                <td><?= $row->quantity ?></td>
                                <td><?= $dc = decimalallow($row->quantity / $row->carton_size, 2) ?></td>

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