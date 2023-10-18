<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <ul class="uk-tab" data-uk-tab="{connect:'#card_tabs',animation:'slide-horizontal'}">
                        <li class="uk-active"><a href="#">Detail</a></li>
                        <li><a href="#">Sales</a></li>
                        <li><a href="#">Purchases</a></li>
                        <li><a href="#">Transfers</a></li>
                        <li><a href="#">Sales Return</a></li>
                        <li><a href="#">Purchases Return</a></li>
                    </ul>
                </div>
            </div>
            <div class="md-card-content">
                <ul id="card_tabs" class="uk-switcher uk-margin">
                    <li class="tablecellwidth">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Basic Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" ><b>Product Image</b></td>
                                            <td class="uk-text-right" colspan="2" >
                                                <?php
                                                    $image_url = 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png';
                                                if ($product->image != '' && $product->image != 'no_image.png') {
                                                    $image_url = base_url('uploads/products/'.$product->image);
                                                }
                                                ?>
                                                <img src="<?php echo $image_url; ?>" style="width: 150px;" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Product ID</b></td>
                                            <td class="uk-text-right"><?php echo $product->id; ?></td>
                                            <td><b>Product Name</b></td>
                                            <td class="uk-text-right"><?php echo $product->name; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Group ID</b></td>
                                            <td class="uk-text-right"><?php echo $product->group_id; ?></td>
                                            <td><b>Group Name</b></td>
                                            <td class="uk-text-right"><?php echo $product->group_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Barcode</b></td>
                                            <td class="uk-text-right"><?php echo $product->code; ?></td>
                                            <td><b>Company Code</b></td>
                                            <td class="uk-text-right"><?php echo $product->company_code; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>HSN Code</b></td>
                                            <td class="uk-text-right"><?php echo $product->hsn_code; ?></td>
                                            <td><b>Brand</b></td>
                                            <td class="uk-text-right"><?php echo $product->brand; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Category</b></td>
                                            <td class="uk-text-right"><?php echo $product->category; ?></td>
                                            <td><b>Sub-Category</b></td>
                                            <td class="uk-text-right"><?php echo $product->subcategory; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Status</b></td>
                                            <td class="uk-text-right"><?php
                                                if ($product->status == 1) {
                                                    echo " <span class='uk-badge uk-badge-success'>Active</span>";
                                                } else {
                                                    echo "<span class='uk-badge uk-badge-danger'>Deactive</span>";
                                                }
                                                ?></td>
                                            <td></td>
                                            <td class="uk-text-right"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Inventory Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <tbody>
                                        <tr>
                                            <td><b>Pack Size</b></td>
                                            <td class="uk-text-right"><?php echo $product->pack_size; ?></td>
                                            <td><b>Carton Size</b></td>
                                            <td class="uk-text-right"><?php echo $product->carton_size; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Unit</b></td>
                                            <td class="uk-text-right"><?php echo $product->unit; ?></td>
                                            <td><b>Weight</b></td>
                                            <td class="uk-text-right"><?php echo $product->weight; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Hold Stock</b></td>
                                            <td class="uk-text-right"><?php echo $product->hold_stock; ?></td>
                                            <td><b>Expected Soldout Days</b></td>
                                            <td class="uk-text-right"><?php echo $product->es_durration; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Short Expiry Days</b></td>
                                            <td class="uk-text-right"><?php echo $product->short_expiry_duration; ?></td>
                                            <td><b>Alert Quantity</b></td>
                                            <td class="uk-text-right"><?php echo $product->alert_quantity; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Price Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <tbody>
                                        <tr>
                                            <td><b>Cost</b></td>
                                            <td class="uk-text-right"><?php echo $product->cost; ?></td>
                                            <td><b>Selling 1</b></td>
                                            <td class="uk-text-right"><?php echo $product->price; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Selling 2</b></td>
                                            <td class="uk-text-right"><?php echo $product->dropship; ?></td>
                                            <td><b>Selling 3</b></td>
                                            <td class="uk-text-right"><?php echo $product->crossdock; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>MRP</b></td>
                                            <td class="uk-text-right"><?php echo $product->mrp; ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Tax Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <tbody>
                                        <tr>
                                            <td><b>Tax Method</b></td>
                                            <td class="uk-text-right"><?php if ($product->tax_method == 0) {
                                                echo 'Inclusive';
                                            } else {
                                                echo 'Exclusive';
                                            } ?></td>
                                            <td><b>Tax Type</b></td>
                                            <td class="uk-text-right"><?php if ($product->tax_type == 1) {
                                                echo 'GST';
                                            } else {
                                                echo '3rd Schedule';
                                            } ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Product Tax</b></td>
                                            <td class="uk-text-right"><?php echo $product->tax_rate; ?></td>
                                            <td><b>FED Tax</b></td>
                                            <td class="uk-text-right"><?php echo $product->fed_tax; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Adv.Tax Sale For Non-Register</b></td>
                                            <td class="uk-text-right"><?php echo $product->adv_tax_nonreg; ?></td>
                                            <td><b>Adv.Tax Sale For Register</b></td>
                                            <td class="uk-text-right"><?php echo $product->adv_tax_reg; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Adv.Tax Purchase For Non-Register</b></td>
                                            <td class="uk-text-right">0</td>
                                            <td><b>Adv.Tax Purchase For Register</b></td>
                                            <td class="uk-text-right"><?php echo $product->adv_tax_for_purchase; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Discount Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <tbody>
                                        <tr>
                                            <td><b>Sales Incentive</b></td>
                                            <td class="uk-text-right"><?php echo $product->discount_one; ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Trade Discount</b></td>
                                            <td class="uk-text-right"><?php echo $product->discount_two; ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Consumer Discount</b></td>
                                            <td class="uk-text-right"><?php echo $product->discount_three; ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Suppliers</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th class="uk-text-right">Supplier ID</th>
                                            <th></th>
                                            <th class="uk-text-right">Supplier Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($suppliers as $key => $row) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $key + 1; ?></td>
                                                    <td class="uk-text-right"><?php echo $row->id; ?></td>
                                                    <td></td>
                                                    <td class="uk-text-right"><?php echo $row->name; ?></td>
                                                </tr>
                                                <?php
                                            }
                                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Stock Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <table class="uk-table">
                                    <thead>
                                        <tr>
                                            <th>Warehouse</th>
                                            <th class="uk-text-right">Available Stock</th>
                                            <th class="uk-text-right">Hold Stock</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                    $total_stock = 0;
                                                $total_hold_tock = 0;
                                                foreach ($warehouses as $key => $row) {
                                                    $total_stock += $row->quantity;
                                                    $total_hold_tock += 0;
                                                    ?>
                                                <tr>
                                                    <td><b><?php echo $row->name; ?></b></td>
                                                    <td class="uk-text-right"><?php echo $row->quantity; ?></td>
                                                    <td class="uk-text-right">0</td>
                                                    <td></td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th class="uk-text-right"><?php echo $total_stock; ?></th>
                                            <th class="uk-text-right"><?php echo $total_hold_tock; ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-10 uk-row-first">
                                <span class="uk-display-block uk-margin-small-top uk-text-large"><b>Product Detail</b></span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <?php echo $product->product_details; ?>
                            </div>
                        </div>
                    </li>
                    <li>
                    <div class="uk-grid">
                            <div class="uk-width-large-1-1">
                                <div class="dt_colVis_buttons"></div>
                                <table class="uk-table DataTable" cellspacing="0" width="100%" id="salesTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Reference No</th>
                                            <th>Own Company</th>
                                            <th>Supplier Name</th>
                                            <th>Warehouse</th>
                                            <th>Selling</th>
                                            <th>MRP</th>
                                            <th>Batch</th>
                                            <th>Expiry</th>
                                            <th>Quantity</th>
                                            <th>Product Tax</th>
                                            <th>Further Tax</th>
                                            <th>Advance Income Tax</th>
                                            <th>Grand Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                    foreach ($sales as $s) {
                                                        ?>
                                                <tr>
                                                    <td><?php echo $s->date; ?></td>
                                                    <td><?php echo $s->reference_no; ?></td>
                                                    <td><?php echo $s->own_company; ?></td>
                                                    <td><?php echo $s->customer; ?></td>
                                                    <td><?php echo $s->warehouse; ?></td>
                                                    <td><?php echo $s->selling; ?></td>
                                                    <td><?php echo $s->mrp; ?></td>
                                                    <td><?php echo $s->batch; ?></td>
                                                    <td><?php echo $s->expiry; ?></td>
                                                    <td><?php echo $s->quantity; ?></td>
                                                    <td><?php echo $s->item_tax; ?></td>
                                                    <td><?php echo $s->further_tax; ?></td>
                                                    <td><?php echo $s->adv_tax; ?></td>
                                                    <td><?php echo $s->subtotal; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </li>
               
                    <li>
                        <div class="uk-grid">
                            <div class="uk-width-large-1-1">
                                <div class="dt_colVis_buttons"></div>
                                <table class="uk-table DataTable" cellspacing="0" width="100%" id="purchasesTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Reference No</th>
                                            <th>Own Company</th>
                                            <th>Supplier Name</th>
                                            <th>Warehouse</th>
                                            <th>Cost</th>
                                            <th>MRP</th>
                                            <th>Batch</th>
                                            <th>Expiry</th>
                                            <th>Quantity</th>
                                            <th>Available Quantity</th>
                                            <th>Product Tax</th>
                                            <th>Advance Income Tax</th>
                                            <th>Grand Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                    foreach ($purchases as $p) {
                                                        ?>
                                                <tr>
                                                    <td><?php echo $p->date; ?></td>
                                                    <td><?php echo $p->reference_no; ?></td>
                                                    <td><?php echo $p->own_company; ?></td>
                                                    <td><?php echo $p->supplier; ?></td>
                                                    <td><?php echo $p->warehouse; ?></td>
                                                    <td><?php echo $p->cost; ?></td>
                                                    <td><?php echo $p->mrp; ?></td>
                                                    <td><?php echo $p->batch; ?></td>
                                                    <td><?php echo $p->expiry; ?></td>
                                                    <td><?php echo $p->quantity; ?></td>
                                                    <td><?php echo $p->quantity_balance; ?></td>
                                                    <td><?php echo $p->item_tax; ?></td>
                                                    <td><?php echo $p->adv_tax; ?></td>
                                                    <td><?php echo $p->subtotal; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="uk-grid">
                            <div class="uk-width-large-1-1">
                                <div class="dt_colVis_buttons"></div>
                                <table class="uk-table DataTable" cellspacing="0" width="100%" id="transfersTable">
                                    <thead>
                                        <tr>
                                            <th>Own Company</th>
                                            <th>Customer NIC</th>
                                            <th>Customer NTN</th>
                                            <th>Transfer No</th>
                                            <th>Date</th>
                                            <th>To Warehouse</th>
                                            <th>From Warehouse</th>
                                            <th>Product ID</th>
                                            <th>Company Code</th>
                                            <th>Barcode</th>
                                            <th>HSN_Code</th>
                                            <th>Product Name</th>
                                            <th>Carton Size</th>
                                            <th>MRP</th>
                                            <th>Qty</th>
                                            <th>UOM</th>
                                            <th>Carton Qty</th>
                                            <th>Expiry Date</th>
                                            <th>Batch</th>
                                            <th>Supplier Manufacturer Name</th>
                                            <th>Remarks</th>
                                            <th>Group ID</th>
                                            <th>Group Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                    foreach ($transfers as $t) {
                                                        ?>
                                                <tr>
                                                    <td><?php echo $t->Own_Company; ?></td>
                                                    <td><?php echo $t->Customer_NIC; ?></td>
                                                    <td><?php echo $t->Customer_NTN; ?></td>
                                                    <td><?php echo $t->Transfer_No; ?></td>
                                                    <td><?php echo $t->Date; ?></td>
                                                    <td><?php echo $t->TO_Warehouse; ?></td>
                                                    <td><?php echo $t->FROM_Warehouse; ?></td>
                                                    <td><?php echo $t->Product_ID; ?></td>
                                                    <td><?php echo $t->Company_Code; ?></td>
                                                    <td><?php echo $t->Barcode; ?></td>
                                                    <td><?php echo $t->HSN_Code; ?></td>
                                                    <td><?php echo $t->Product_Name; ?></td>
                                                    <td><?php echo $t->Carton_Size; ?></td>
                                                    <td><?php echo $t->MRP; ?></td>
                                                    <td><?php echo $t->Qty; ?></td>
                                                    <td><?php echo $t->UOM; ?></td>
                                                    <td><?php echo $t->Carton_Qty; ?></td>
                                                    <td><?php echo $t->Expiry_Date; ?></td>
                                                    <td><?php echo $t->Batch; ?></td>
                                                    <td><?php echo $t->Supplier_Manufacturer_Name; ?></td>
                                                    <td><?php echo $t->Remarks; ?></td>
                                                    <td><?php echo $t->Group_ID; ?></td>
                                                    <td><?php echo $t->Group_Name; ?></td>
                                                </tr>
                                                <?php
                                                    }
                                                ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="uk-grid">
                            <div class="uk-width-large-1-1">
                                <div class="dt_colVis_buttons"></div>
                                <table class="uk-table DataTable" cellspacing="0" width="100%" id="salesreturn">
                                <thead>
                        <tr>
                            <th>Refrence No</th>
                            <th>Sale Date</th>
                            <th>Sales Return Date</th>
                            <th>Brand</th>
                            <th>Warehouse ID</th>
                            <th>Warehouse Name</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>HSN Code</th>
                            <th>Company Code</th>
                            <th>Carton Size</th>
                            <th>Expiry</th>
                            <th>Batch</th>
                            <th>Quantity</th>
                            <th>MRP</th>
                            <th>Cost</th>
                            <th>Total Item Tax</th>
                            <th>Further Tax</th>
                            <th>FED Tax</th>
                            <th>Total Tax</th>
                            <th>Sub Total</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
 
 <tr>

<?php foreach ($salesreturn as $row) {   ?>  


<td><?php echo $row[0]; ?></td>
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




<?php
}

                                                ?>
                </tr>
 </tbody>

                </table>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="uk-grid">
                            <div class="uk-width-large-1-1">
                                <div class="dt_colVis_buttons"></div>
                                <table class="uk-table DataTable" cellspacing="0" width="100%" id="purchasereturn">
                                <thead>
                        <tr>
                            <th>Refrence No</th>
                            <th>Purchase Date</th>
                            <th>Return Date</th>
                            <th>Brand</th>
                            <th>Warehouse ID</th>
                            <th>Warehouse Name</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>HSN Code</th>
                            <th>Company Code</th>
                            <th>Carton Size</th>
                            <th>Expiry</th>
                            <th>Batch</th>
                            <th>Quantity</th>
                            <th>MRP</th>
                            <th>Cost</th>
                            <th>Total Item Tax</th>
                            <th>Further Tax</th>
                            <th>FED Tax</th>
                            <th>Total Tax</th>
                            <th>Sub Total</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
 
 <tr>

<?php foreach ($purchasereturn as $row) {   ?>  


<td><?php echo $row[0]; ?></td>
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




<?php
}
                                                ?>
                </tr>
 </tbody>
                                </table>
                            </div>
                        </div>
                    </li>
                </ul>
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
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-buttons/js/buttons.colVis.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables-fixedcolumns/dataTables.fixedColumns.min.js"></script>

<!-- datatables custom integration -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/datatables/datatables.uikit.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/datatable.js"></script>
<!-- Include DataTables CSS and JS -->

<script>
    $(document).ready(function() {
    $('#purchasesTable').DataTable({
        dom: 'Bfrtip',
        fixedColumns:   {left: 0,right: 1},
        scrollX: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    $('#transfersTable').DataTable({
        dom: 'Bfrtip',
        fixedColumns:   {left: 0,right: 1},
        scrollX: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    $('#salesTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        fixedColumns:   {left: 0,right: 1},
        scrollX: true
    });

    $('#salesreturn').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        fixedColumns:   {left: 0,right: 1},
        scrollX: true
    });

    $('#purchasereturn').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        // fixedColumns:   {left: 0,right: 1},
        scrollX: true,
        scrollCollapse: true

    });
});


</script>


