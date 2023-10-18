<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Daraz Store Products</h3>
                <span id="ajaxloading" style="float: right;line-height: 41px;padding-right: 38px;color: red;font-weight: bold;" ><b>0</b> Products Found. <strong style="display:none;" >Loading......</strong> </span>
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                </div>
            </div>
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table">
                    <thead>
                        <tr>
                        <th>Store Product ID</th>
                            <th>Rhocom P.ID</th>
                            <th>Store Name</th>
                            <th>Rhocom Name</th>
                            <th>Update Qty</th>
                            <th>Occupy Stock</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
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
    $(document).ready( function () {
        var i = 0;
        var sid = <?= $store->id ?>;
        var limit = 50;
        var products = [];
        var totalproduct =0;

        get_products();
        //Data Load
        function get_products(){
            $('#ajaxloading strong').show();
            i++;
            console.log(i);
            
            $.ajax({
                type: "get",
                data: {sid:sid,page:i,limit:limit},
                url: '<?= admin_url('stores/daraaz_products_report_ajax'); ?>',
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.codestatus == "ok"){
                        products = products.concat(obj.products);
                        totalproduct += obj.count;
                        $('#ajaxloading b').html(totalproduct);
                        if(obj.count>=limit){
                            get_products();
                            console.log('Send Again');
                        }
                        else{
                            console.log('Complete');
                            $('#ajaxloading strong').hide();
                            fullData();
                        }
                    }
                    else{
                        if (confirm("Something went wrong. Do you want to continues!") == false) {
                            $('#ajaxloading strong').hide();
                            fullData();
                        }
                        else{
                            i--;
                            get_products();
                        }
                    }
                }
            });
        }
        function fullData(){
            products.forEach(function(product) {
                var html = '';
                html += '<tr>';
                    html += '<td>'+product.store_product_id+'</td>';
                    html += '<td>'+product.rhocom_pid+'</td>';
                    html += '<td>'+product.name+'</td>';
                    html += '<td>'+product.rhocom_name+'</td>';
                    html += '<td>'+product.actualqty+'</td>';
                    html += '<td>'+product.occupystock+'</td>';
                    html += '<td>'+product.note+'</td>';
                html += '</tr>';
                $('#dt_tableExport tbody').append(html);
            });

            $("#dt_tableExport tfoot th").each( function () {
                var title = $(this).text();
                $(this).html( "<input type='text' class='' placeholder='Search "+title+"' />" );
            });
            $('#dt_tableExport').DataTable({
                dom: 'Bfrtip',
                scrollX: true,
                responsive: true,
                paging: true,
                pageLength: 25,
                buttons: [
                    {
                        extend: 'copy',
                    },
                    {
                        extend: 'csv',
                    },
                    {
                        extend: 'excel',
                    },
                ],
                initComplete: function () {
                    this.api().columns().every(function(){
                        var that = this;
                        $( "input", this.footer() ).on( "keyup change clear", function () {
                            if ( that.search() !== this.value ) {
                                that
                                .search(this.value)
                                .draw();
                            }
                        });
                    });
                }
            });
            setTimeout(function(){
                var windowwidth = $(window ).width();
                var sidewidth = $('.sidebar-con').width();
                var width = windowwidth-sidewidth-30;
                console.log(windowwidth+"px");
                console.log(sidewidth+"px");
                console.log(width+"px");
                $('.dataTables_scrollHeadInner').css('width',width+'px');
                $('.dataTables_scrollHeadInner table').css('width',width+'px');
                $('#dt_tableExport').css('width',width+'px');
                width = width-40;
                $('.dataTables_scroll').css('width',width+'px');
                $('.sorting_asc').click();
            }, 500);
        }
    });


</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>