<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->lang->line('transfer') . ' ' . $transfer->transfer_no; ?></title>
    <link href="<?= $assets ?>styles/pdf/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $assets ?>styles/pdf/pdf.css" rel="stylesheet">
</head>


<body>
    <div id="wrap">
        <div class="row">
            <div class="col-xs-12">
                <div class="text-center" style="margin-bottom:20px;">
                    <h1>Transfer</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <strong><?php echo $this->lang->line("Detail"); ?>:</strong><br><br>
                <strong>Reference No# : </strong><?php echo $transfer->transfer_no; ?><br>
                <strong> <?= lang('date'); ?>: </strong> <?= $this->sma->hrld($transfer->date); ?><br>
                <strong> <?= lang('Status'); ?>: </strong> <?php echo $transfer->status; ?><br>
            </div>
            <div class="col-xs-4">
                <strong><?php echo $this->lang->line("Warehouse From"); ?>:</strong><br><br>
                <strong>Name: </strong><?php echo $from_warehouse->name; ?><br>
                <strong>Code: </strong><?php echo $from_warehouse->code; ?><br>
                <strong>Phone: </strong><?php echo $from_warehouse->phone; ?><br>
                <strong>Email: </strong><?php echo $from_warehouse->email; ?><br>
                <strong>Address </strong><?php echo $from_warehouse->address; ?><br>
            </div>
            <div class="col-xs-4">
                <strong><?php echo $this->lang->line("Warehouse To"); ?>:</strong><br><br>
                <strong>Name: </strong><?php echo $to_warehouse->name; ?><br>
                <strong>Code: </strong><?php echo $to_warehouse->code; ?><br>
                <strong>Phone: </strong><?php echo $to_warehouse->phone; ?><br>
                <strong>Email: </strong><?php echo $to_warehouse->email; ?><br>
                <strong>Address </strong><?php echo $to_warehouse->address; ?><br>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" >
                        <thead>
                            <tr>
                                <th rowspan="2" style="width:15pt" ><?= lang('no'); ?></th>
                                <th rowspan="2" style="width:30pt" >SKU</th>
                                <th rowspan="2" style="width:200pt" >Company Code - <?= lang('description'); ?></th>
                                <?php if ($Settings->indian_gst) { ?>
                                    <th rowspan="2" ><?= lang("hsn_code"); ?></th>
                                <?php } ?>
                                <th rowspan="2" style="width:38pt" >MRP <br> Per Piece</th>
                                <th rowspan="2" style="width:30pt" >Pack Size</th>
                                <th rowspan="2" style="width:30pt" >Carton Size</th>
                                <th rowspan="2" style="width:68pt" ><?= lang('Batch'); ?> </th>
                                <th rowspan="2" style="width:45pt" ><?= lang('Expiry'); ?></th>
                                <th rowspan="2" style="width:30pt" ><?= lang('Quantity In PCS'); ?></th>
                                <th rowspan="2" style="width:45pt" >Total Weight</th>
                                <th colspan="2" style="width:60pt" ><?= lang('quantity'); ?></th>
                            </tr>
                            <tr>
                                <th style="width:30pt" >Carton</th>
                                <th style="width:30pt" >Loose</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $r = 1;
                            $total_qty = 0;
                            $total_carton_qty = 0;
                            $total_loose_qty = 0;
                            $total_weight = 0;
                            foreach ($rows as $row) :
                            ?>
                                <tr>
                                    <td style="text-align:center; vertical-align:middle;"><?= $r; ?></td>
                                    <td style="text-align:center;vertical-align:middle;"><?= $row->sku; ?> </td>
                                    <td style="vertical-align:middle;">
                                        <?= $row->company_code . '-' . $row->product_name; ?>
                                        <?= $row->second_name ? '<br>' . $row->second_name : ''; ?>
                                        <?= $row->details ? '<br>' . $row->details : ''; ?>
                                    </td>
                                    <?php if ($Settings->indian_gst) { ?>
                                        <td style=" text-align:center; vertical-align:middle;"><?= $row->hsn_code; ?></td>
                                    <?php } ?>
                                    <td style="text-align:center; vertical-align:middle;"><?= decimalallow($row->mrp,0); ?> </td>
                                    <td style="text-align:center; vertical-align:middle;"><?= $row->pack_size; ?> </td>
                                    <td style="text-align:center; vertical-align:middle;"> <?= $row->carton_size; ?> </td>
                                    <td style="text-align:center;"><?= $row->batch; ?></td>
                                    <td style="text-align:center;"><?= $row->expiry; ?></td>
                                    <td style="text-align:center;"><?php echo decimalallow($row->quantity,0); ?></td>
                                    <?php
                                        $total_qty += $row->quantity;
                                        $carton_qty=$row->quantity/$row->carton_size;
                                        $carton_qty = (int)$carton_qty;
                                        $loss_qty=$row->quantity-($carton_qty*$row->carton_size);
                                        $weight = $row->weight*$row->quantity;
                                        $total_carton_qty += $carton_qty;
                                        $total_loose_qty += $loss_qty;
                                        $total_weight += $weight;
                                    ?>
                                    <td style="text-align:center; vertical-align:middle;"> <?= $weight; ?> </td>
                                    <td style="text-align:center;"><?php echo $carton_qty; ?></td>
                                    <td style="text-align:center;"><?php echo $loss_qty; ?></td>
                                </tr>
                                <?php
                                $r++;
                            endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align:right; ">Total</th>
                                <th><?= $total_qty ?></th>
                                <th><?= $total_weight ?></th>
                                <th><?= $total_carton_qty ?></th>
                                <th><?= $total_loose_qty ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12">
                    <?php if ($transfer->note || $transfer->note != '') { ?>
                        <div class="well well-sm">
                            <p class="bold"><?= lang('note'); ?>:</p>
                            <div><?= $this->sma->decode_html($transfer->note); ?></div>
                        </div>
                    <?php }
                    ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-4 pull-left">
        <p><?= lang("created_by"); ?>: <?= $created_by->first_name.' '.$created_by->last_name; ?> </p>

        <p>&nbsp;</p>

        <p>&nbsp;</p>
        <hr>
        <p><?= lang("stamp_sign"); ?></p>
    </div>
    <div class="col-xs-4 pull-right">
        <p><?= lang("received_by"); ?>: </p>

        <p>&nbsp;</p>

        <p>&nbsp;</p>
        <hr>
        <p><?= lang("stamp_sign"); ?></p>
    </div>

</body>

</html>