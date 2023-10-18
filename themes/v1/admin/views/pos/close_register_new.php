<?php
    $attrib = ['data-toggle' => 'validator', 'role' => 'form'];
    echo admin_form_open_multipart('pos/close_register_submit/' . $user_id, $attrib);
?>

<div class="uk-modal-header">
    <h3 class="uk-modal-title"><?= lang('sales') . ' (' . $this->sma->hrld($register_open_time) . ' - ' . $this->sma->hrld(date('Y-m-d H:i:s')) . ')'; ?></h3>
</div>
<div class="uk-modal-body">
    <table width="100%" class="uk-table registertable">
        <tr>
            <td style="border-bottom: 1px solid #EEE;"><h4>Cash in Hand:</h4></td>
            <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                <span><?php echo $cash_in_hand; ?></span></h4>
            </td>
        </tr>
        <?php
            $total_amount = 0;
            $cash_sale = 0;
            $total_cheques = 0;
            $total_cc = 0;
            foreach($payments as $payment){
                ?>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?php 
                        if($payment->paid_by == "cash"){
                            echo 'Received Cash Amount';
                            $cash_sale = $payment->total_amount;
                        }
                        else if($payment->paid_by == "onlinetransfer"){
                            echo 'Received Online Tansfer Amount';
                        }
                        else if($payment->paid_by == "payorder"){
                            echo 'Received Payorder Amount';
                        }
                        else if($payment->paid_by == "withholdingtax"){
                            echo 'Received With Holding Tax Amount';
                        }
                        else if($payment->paid_by == "retainer"){
                            echo 'Received Retainer Amount';
                        }
                        else if($payment->paid_by == "balance"){
                            echo 'Received Balance Amount';
                        }
                        else if($payment->paid_by == "gift_card"){
                            echo 'Received Gift Card Amount';
                        }
                        else if($payment->paid_by == "CC"){
                            echo 'Received Credit Card Amount';
                            $total_cc++;
                        }
                        else if($payment->paid_by == "Cheque"){
                            echo 'Received Cheque Amount';
                            $total_cheques++;
                        }
                        else if($payment->paid_by == "creaditnote"){
                            echo 'Received Credit Note Amount';
                        }
                        else if($payment->paid_by == "other"){
                            echo 'Received Other Amount';
                        }
                    ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                        <span><?php echo $this->sma->formatMoney($payment->total_amount); ?></span></h4>
                    </td>
                </tr>
                <?php
            $total_amount += $payment->total_amount;
        }
        ?>
        <tr>
            <th style="border-bottom: 1px solid #EEE;"><h4>Total Received Amount:</h4></th>
            <th style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                <span><?php echo $this->sma->formatMoney($total_amount); ?></span></h4>
            </th>
        </tr>
        <?php
            $total_return_amount = 0;
            $cash_refund = 0;
            foreach($refund as $payment){
                ?>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?php 
                        if($payment->paid_by == "cash"){
                            echo 'Refund Cash Amount';
                            $cash_refund = $payment->total_amount;
                        }
                        else if($payment->paid_by == "onlinetransfer"){
                            echo 'Refund Online Tansfer Amount';
                        }
                        else if($payment->paid_by == "payorder"){
                            echo 'Refund Payorder Amount';
                        }
                        else if($payment->paid_by == "withholdingtax"){
                            echo 'Refund With Holding Tax Amount';
                        }
                        else if($payment->paid_by == "retainer"){
                            echo 'Refund Retainer Amount';
                        }
                        else if($payment->paid_by == "balance"){
                            echo 'Refund Balance Amount';
                        }
                        else if($payment->paid_by == "gift_card"){
                            echo 'Refund Gift Card Amount';
                        }
                        else if($payment->paid_by == "CC"){
                            echo 'Refund Credit Card Amount';
                        }
                        else if($payment->paid_by == "Cheque"){
                            echo 'Refund Cheque Amount';
                        }
                        else if($payment->paid_by == "creaditnote"){
                            echo 'Refund Credit Note Amount';
                        }
                        else if($payment->paid_by == "other"){
                            echo 'Refund Other Amount';
                        }
                    ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                        <span><?php echo $this->sma->formatMoney($payment->total_amount); ?></span></h4>
                    </td>
                </tr>
                <?php
            $total_return_amount += $payment->total_amount;
        }
        ?>
        <tr>
            <th style="border-bottom: 1px solid #EEE;"><h4>Total Refund Amount:</h4></th>
            <th style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                <span><?php echo $this->sma->formatMoney($total_return_amount); ?></span></h4>
            </th>
        </tr>
        <tr>
            <th style="border-bottom: 1px solid #EEE;"><h4>Total Sales:</h4></th>
            <th style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                <span><?php echo $this->sma->formatMoney($sales->total_sale); ?></span></h4>
            </th>
        </tr>
        <tr>
            <th style="border-bottom: 1px solid #EEE;"><h4>Total Return Sale:</h4></th>
            <th style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                <span><?php echo $this->sma->formatMoney($returns->total); ?></span></h4>
            </th>
        </tr>
        <tr>
            <th style="border-bottom: 1px solid #EEE;"><h4>Total Credit Sale:</h4></th>
            <th style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                <span><?php
                    $balance = $sales->total_sale-$sales->total_paid;
                    if($balance < 0){ $balance = 0; }
                    echo $this->sma->formatMoney($balance);
                ?></span></h4>
            </th>
        </tr>
        <tr>
            <th style="border-bottom: 1px solid #EEE;"><h4><b>Total Current Hand Cash:</b></h4></th>
            <th style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                <span><b><?php 
                    $total_current_hand_cash = $cash_in_hand+$cash_sale+$cash_refund;
                    echo $this->sma->formatMoney($total_current_hand_cash);
                ?></b></span></h4>
            </th>
        </tr>


    </table>
    <?php
    if ($suspended_bills) {
        echo '<hr><h3>Open Bill</h3><table class="uk-table"><thead><tr><th>' . lang('customer') . '</th><th>' . lang('date') . '</th><th>' . lang('total_items') . '</th><th>' . lang('amount') . '</th></tr></thead><tbody>';
        foreach ($suspended_bills as $bill) {
            echo '<tr><td>' . $bill->customer . '</td><td>' . $this->sma->hrld($bill->date) . '</td><td class="text-center">' . $bill->count . '</td><td class="text-right">' . $bill->total . '</td></tr>';
        }
        echo '</tbody></table>';
    }
    ?>
    <div class="uk-grid">
        <div class="uk-width-large-1-2">
            <div class="md-input-wrapper md-input-filled">
                <label>Total Cash</label>
                <?= form_hidden('total_cash', $total_current_hand_cash); ?>
                <?= form_input('total_cash_submitted', ($_POST['total_cash_submitted'] ?? $total_current_hand_cash ?? 0 ), 'class="md-input md-input-success label-fixed" id="total_cash_submitted" required="required"'); ?>

            </div>
        </div>
        <div class="uk-width-large-1-2">
            <div class="md-input-wrapper md-input-filled">
                <label>Total Cheques</label>
                <?= form_hidden('total_cheques', $total_cheques ?? 0); ?>
                <?= form_input('total_cheques_submitted', ($_POST['total_cheques_submitted'] ?? $total_cheques ?? 0), 'class="md-input md-input-success label-fixed" required="required"'); ?>
            </div>
        </div>
        <div class="uk-width-large-1-2">
            <div class="md-input-wrapper md-input-filled">
                <label>Total Credit Card Slips</label>
                <?= form_hidden('total_cc_slips', $total_cc); ?>
                <?= form_input('total_cc_slips_submitted', ($_POST['total_cc_slips_submitted'] ?? $total_cc ?? 0), 'class="md-input md-input-success label-fixed" id="total_cc_slips_submitted" required="required"'); ?>
            </div>
        </div>
        <div class="uk-width-large-1-2">
            <div class="md-input-wrapper md-input-filled">
                <label>Total Available Cash</label>
                <?= form_input('total_available_cash_submitted', 0, 'class="md-input md-input-success label-fixed" id="total_available_cash_submitted" required="required"'); ?>
            </div>
        </div>
        <div class="uk-width-large-1-1">
            <div class="md-input-wrapper md-input-filled">
                <label>Note</label>
                <?= form_textarea('note', ($_POST['note'] ?? ''), 'class="md-input md-input-success label-fixed" id="note" style="margin-top: 10px; height: 100px;"'); ?> 

            </div>
        </div>
    </div>
</div>
<div class="uk-modal-footer uk-text-right">
    <button type="button" class="md-btn md-btn-danger md-btn-flat uk-modal-close" >Close</button>
    <button type="submit" class="md-btn md-btn-primary md-btn-flat" >Register Close</button>
</div>
<?= form_close(); ?>
