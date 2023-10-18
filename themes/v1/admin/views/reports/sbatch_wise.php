<style>
    .uk-open>.uk-dropdown, .uk-open>.uk-dropdown-blank{

    }
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
    </div>


        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Batch Wise Report</h3>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>

            
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr>
                            <th>Purchase Date</th>
                            <th>Barcode</th>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>MRP</th>
                            <th>Quantity Balance</th>
                            <th>Expiry</th>
                            <th>Batch</th>
                            <th>Company</th>
                            <th>Warehouse Name</th>
                            <th>Carton Size</th>
                            <th>Company Code</th>
                            <th>Brand</th>
                        </tr>
                    </thead>
                    <tbody>
              
                      <?php
                      $counter = 0;
                      foreach ($rows as $row) { 
                        foreach($row as $r){
                       
                        
                  ?>
              
              <tr>
                  <td><?= $r->purchase_date;  ?></td>
                  <td><?= $r->product_code;  ?></td>
                  <td><?= $r->product_id;  ?></td>
                  <td><?= $r->product_name;  ?></td>
                  <td><?= $r->mrp;  ?></td>
                  <td><?= $r->quantity_balance;  ?></td>
                  <td><?= $r->expiry;  ?></td>
                  <td><?= $r->batch;  ?></td>
                  <td><?= $r->company;  ?></td>
                  <td><?= $r->warehousename;  ?></td>
                  <td><?= $r->carton_size;  ?></td>
                  <td><?= $r->company_code;  ?></td>
                  <td><?= $r->brand_name;  ?></td>
                  </tr>
                  <?php 
                        $counter+=1;
                        # code...
                      }
                    }
                      ?>

            
                    </tbody>
                </table>
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

    $.DataTableInit2({
           selector:'#dt_tableExport',
        aaSorting: [[1, "desc"]],
        columnDefs: [
            { 
                "targets": 9,
                "orderable": false
            }
        ],
        fixedColumns:   {left: 0,right: 1},
        scrollX: true
    });

</script>
<script>
    $(document).ready(function(){
        $('.select2').select2();
    });
</script>