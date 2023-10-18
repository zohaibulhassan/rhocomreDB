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
                <form action="<?php echo base_url('admin/reports/supplier_legder'); ?>" method="get">
                    <input type="hidden" name="show_type" value="2">
                    <div class="uk-grid">
                    <div class="uk-width-large-1-3">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("suppliers", "suppliers"); ?>
                                <?php
                                $bl["all"] = "All";
                                foreach ($suppliers as $supplier) {
                                    $bl[$supplier->id] = $supplier->name;
                                }
                                echo form_dropdown('supplier', $bl, $csupplier, 'id="suppliers" data-placeholder="' . lang("select") . ' ' . lang("supplier") . '" required="required" class="form-control input-tip select" style="width:100%;"');
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


                        <div class="uk-width-large-1-3 " style="margin-top: 12px;">
                                <div class="md-input-wrapper md-input-filled" >
                                    <?= lang("Sorting Type", "sortingtype"); ?>
                                    <select name="sortingtype" class="form-control input-tip searching_select" >
                                        <option value="date">Date Wise</option>
                                        <option value="invoice">Invoice Wise</option>
                                    </select>
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
                <h3 class="md-card-toolbar-heading-text">Supplier Ledger</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table ">
                <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Transation Ref</th>
                            <th>Particulars</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                            <th>Due</th>
                            <th>Ageing</th>
                            <th>Note</th>
                            <th>Remarts</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($rows as $row){
                        ?>
                        <tr>
                            <td><?php echo date_format(date_create($row->date),"Y-m-d"); ?></td>
                            <td><?php echo $row->particular; ?></td>
                            <td><?php echo $row->tref; ?></td>
                            <td><?php echo $row->paid_by; ?></td>
                            <td><?php echo $row->debit; ?></td>
                            <td><?php echo $row->credit; ?></td>
                            <td><?php echo $row->balance; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td data-payid="<?php echo $row->pay_id; ?>" data-purchase_id="<?php echo $row->purchase_id; ?>" class="remarkschange"><?php echo $row->remarks; ?></td>
                            <td>
                                <?php
                                    if(isset($row->pay_id)){
                                        if($row->pay_id > 0){
                                        ?>
                                            <a href="<?= admin_url('purchases/edit_payment/'.$row->pay_id) ?>" data-toggle="modal" data-target="#myModal2"><i class="fa fa-edit"></i></a>
                                        <?php
                                        }
                                        else{
                                            if($row->pay_status != "paid" && $row->pay_status != "excise"){
                                            ?>
                                                <a href="<?= admin_url('purchases/add_payment/'.$row->purchase_id) ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></a>
                                            <?php
                                            }
                                        }
                                    }
                                ?>
                            </td>

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