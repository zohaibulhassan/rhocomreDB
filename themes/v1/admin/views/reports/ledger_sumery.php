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
                <form action="<?php echo base_url('admin/reports/ledger_summery'); ?>" method="get">
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
                                <?= lang("customers", "customers"); ?>
                                <?php
                                $bl["all"] = "All";
                                foreach ($customers as $customer) {
                                    $bl[$customer->id] = $customer->name;
                                }
                                echo form_dropdown('customers', $bl, $ccustomer, 'id="customers" data-placeholder="' . lang("select") . ' ' . lang("cuctomer") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div>
                        </div>



                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("companies", "companies"); ?>
                                <?php
                                $oc["all"] = "All";
                                foreach ($companies as $companies) {
                                    $oc[$companies->id] = $companies->name;
                                }
                                echo form_dropdown('companies', $oc, $companies, 'id="poown_companies" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("companies") . '" required="required" style="width:100%;" ');
                                ?> </div>
                        </div>

                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Form Date</label>
                                <input class="md-input  label-fixed" type="text" name="start_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo $date_from ?>" readonly>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <label>To Date</label>
                                <input class="md-input  label-fixed" type="text" name="end_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" value="<?php echo $date_to ?>" readonly>
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
                <h3 class="md-card-toolbar-heading-text">Ledger Summary</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="table" class="uk-table ">
                    <thead>
                        <tr>
                            <th colspan="<?php echo count($recivable_rows['thead']) + 2; ?>">Customer -
                                <?php
                                foreach ($showwarehouses as $showwarehouse) {
                                    echo $showwarehouse->name . ' (' . $showwarehouse->code . '), ';
                                }
                                ?> - <span style="color:red;">Recivable</span></th>
                        </tr>
                        <tr>
                            <th style="width:400px">Customer Name</th>
                            <?php
                            foreach ($recivable_rows['thead'] as $row) {
                                echo '<th>' . $row['name'] . '</th>';
                            }
                            ?>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($recivable_rows['tbody'] as $row) {
                        ?>
                            <tr>
                                <td><?= $row['customer_name'] ?></td>
                                <?php
                                foreach ($row['companies'] as $crow) {
                                    echo '<td>' . amountformate($crow['value'], 4, 'PKR') . '</td>';
                                }
                                ?>
                                <td></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <?php
                            foreach ($recivable_rows['thead'] as $row) {
                                echo '<th>' . $row['name'] . '</th>';
                            }
                            ?>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                    <thead>
                        <tr>
                            <th colspan="<?php echo count($recivable_rows['thead']) + 2; ?>">Customer -
                                <?php
                                foreach ($showwarehouses as $showwarehouse) {
                                    echo $showwarehouse->name . ' (' . $showwarehouse->code . '), ';
                                }
                                ?> - <span style="color:red;">Due</span></th>
                        </tr>

                        <tr>
                            <th style="width:400px">Customer Name</th>
                            <?php
                            foreach ($due_rows['thead'] as $row) {
                                echo '<th>' . $row['name'] . '</th>';
                            }
                            ?>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($due_rows['tbody'] as $row) {
                        ?>
                            <tr>
                                <td><?= $row['customer_name'] ?></td>
                                <?php
                                foreach ($row['companies'] as $crow) {
                                    echo '<td>' . amountformate($crow['value'], 4, 'PKR') . '</td>';
                                }
                                ?>
                                <td><a class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <?php
                            foreach ($due_rows['thead'] as $row) {
                                echo '<th>' . $row['name'] . '</th>';
                            }
                            ?>
                            <th></th>
                        </tr>
                    </tfoot>
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
            paging: true,
            info: true,
            scrollX:true,
            scrollCollapse:true,
            // ordering:false,
            pageLength: 10,
            buttons: [{
                    extend: 'copy',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                <?php
                $no = 0;
                foreach ($recivable_rows['thead'] as $row) {
                    $no++;
                ?>
                    var t<?= $no ?> = api.column(<?= $no ?>).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(<?= $no ?>).footer()).html(t<?= $no ?>);
                <?php
                }
                ?>





            }
        });


    });
</script>
<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>