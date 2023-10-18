<link href="<?= $assets ?>plugins/full_calender/main.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/full_calender/grid_main.css" rel="stylesheet" type="text/css" />

<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Assign Order List</h3>
            </div>
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-1" style="margin-top:15px" >
                        <table id="assignOrders" class="uk-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Pending Orders</th>
                                    <th>Delivered Orders</th>
                                    <th>Cancel Order</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($rows as $row){
                                        $status = array_count_values(explode(',',$row->status));
                                        $pending = 0;
                                        $delivered = 0;
                                        $cancel = 0;
                                        if(isset($status['pending'])){
                                            if($status['pending'] != ""){
                                                $pending = $status['pending'];
                                            }
                                        }
                                        if(isset($status['delivered'])){
                                            if($status['delivered'] != ""){
                                                $delivered = $status['delivered'];
                                            }
                                        }
                                        if(isset($status['cancel'])){
                                            if($status['cancel'] != ""){
                                                $cancel = $status['cancel'];
                                            }
                                        }
                                        $total = $pending+$delivered+$cancel; 
                                    ?>
                                <tr>
                                    <td><?php echo $row->delivery_date ?></td>
                                    <td><?php echo $pending?></td>
                                    <td><?php echo $delivered?></td>
                                    <td><?php echo $cancel?></td>
                                    <td><?php echo $total?></td>
                                    <td>
                                        <a href="<?php echo base_url('admin/assign_orders/detail?date='.$row->delivery_date.'&dispacher_id='.$user_id); ?>" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light md-btn-mini" >Detail</a>
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
    </div>
</div>
<div class="md-fab-wrapper md-fab-in-card" style="position: fixed;bottom: 20px;">
    <a class="md-fab md-fab-success md-fab-wave waves-effect waves-button" href="<?php echo base_url('admin/assign_orders/add/'.$user_id) ?>"><i class="fa-solid fa-plus"></i></a>
</div>