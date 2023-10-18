
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Order Assign </h3>
            </div>
            <div class="md-card-content" >
                <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addFrom');
                    echo admin_form_open_multipart("#", $attrib);
                ?>
                    <div class="uk-grid">
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <input type="hidden" name="dispacher_id" value="<?php echo $user_id ?>" >
                                <label>Delivery Date <span class="red" >*</span></label>
                                <input class="md-input  label-fixed" type="text" name="date" id="deliveryDate" data-uk-datepicker="{format:'YYYY-MM-DD'}" autocomplete="off" readonly value="<?php echo date('Y-m-d'); ?>" >
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Area <span class="red" >*</span></label>
                                <select name="routeVal" id="routeVal" class="uk-width-1-1 select2" style="width: 100%">
                                    <?php
                                        foreach($routes as $row){
                                            echo '<option value="'.$row->id.'" ';
                                            echo ' >'.$row->text.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-1" style="margin-top:15px" >
                            <button type="button" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" id="fatchOrder" >Fetch Order</button>

                        </div>
                        <div class="uk-width-large-1-1" style="margin-top:15px" >
                            <table id="assignOrders" class="uk-table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="check_all" id="check_all" /></th>
                                        <th>Date</th>
                                        <th>Shop Name</th>
                                        <th>Reference</th>
                                        <th>Item Quantity</th>
                                        <th>Grand Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-1" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn" >Submit</button>
                            <a href="<?php echo base_url('admin/routes'); ?>" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" >Cancel</a>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.select2').select2();
        $('#fatchOrder').click(function(){
            var date = $('#deliveryDate').val();
            var route = $('#routeVal').val();
            var dispatcher_id = <?php echo $user_id; ?>;
            console.log(route);

            $.ajax({
                url: '<?php echo base_url('admin/assign_orders/fatch_orders'); ?>',
                type: 'GET',
                data: {date:date,route:route,dispatcher_id:dispatcher_id},
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    var html = "";
                    $.each(obj, function(index) {
                        var item = this;
                        console.log(item);

                        html += "<tr>";
                            html += "<td>";
                            if(item.delivery_status > 0){
                            }
                            else{
                                html += "<input type='checkbox' class='orderChks' name='order[]' value='"+item.order_id+"' />";
                            }
                            html += "</td>";
                            html += "<td>"+item.date+"</td>";
                            html += "<td>"+item.shop_name+"</td>";
                            html += "<td>"+item.reference_no+"</td>";
                            html += "<td>"+item.items+"</td>";
                            html += "<td>"+item.grand_total+"</td>";
                        html += "</tr>";


                    });
                    $('#assignOrders tbody').html(html);

                    // if(obj.status){
                    //     toastr.success(obj.message);
                    //     window.location.href = "<?php echo base_url('admin/routes'); ?>";
                    // }
                    // else{
                    //     toastr.error(obj.message);
                    //     $('#submitbtn').prop('disabled', false);
                    // }
                }
            });

        });


        $('#addFrom').submit(function(e){
            e.preventDefault();
            $('#submitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/assign_orders/submit_assign'); ?>',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    console.log(obj);
                    if(obj.status){
                        toastr.success(obj.message);
                        window.location.href = "<?php echo base_url('admin/assign_orders/index/'.$user_id); ?>";
                    }
                    else{
                        toastr.error(obj.message);
                        $('#submitbtn').prop('disabled', false);
                    }
                }
            });
        });
        $('#check_all').click(function(){
            $('.orderChks').prop('checked', $(this).prop('checked'));
        });
    });
</script>

