
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Assigned Orders</h3>
            </div>
            <div class="md-card-content" >
                <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'actionFrom');
                    echo admin_form_open_multipart("#", $attrib);
                ?>
                    <input type="hidden" name="dispacher_id" value="<?php echo $user_id ?>" >
                    <div class="uk-grid">
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
                                        <th>Assign Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($rows as $row){
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                        if($row->status == "pending"){
                                                            ?>
                                                        <input type='checkbox' class='orderChks' name='ids[]' value='<?php echo $row->id; ?>' />
                                                            <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo $row->delivery_date ?></td>
                                                <td><?php echo $row->shop_name ?></td>
                                                <td><?php echo $row->reference_no ?></td>
                                                <td><?php echo $row->items ?></td>
                                                <td><?php echo $row->grand_total ?></td>
                                                <td><?php echo $row->created_at ?></td>
                                                <td><?php echo ucwords($row->status) ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-1" style="padding-top: 20px;">
                            <button type="submit" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" id="submitbtn" >Delete</button>
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


        $('#actionFrom').submit(function(e){
            e.preventDefault();
            $('#submitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/assign_orders/delect_assign'); ?>',
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
                        location.reload();
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

