<table style="width: 100%;" class="hover_links">
    <tr>
		<?php if (isset($sort_links) AND $sort_links == TRUE) { ?>
			<th scope="col" class="first"><?php echo $table_headers['payment_id']; ?></th>
			<th scope="col"><?php echo $table_headers['date']; ?></th>
			<th scope="col"><?php echo $table_headers['invoice_id']; ?></th>
			<th scope="col"><?php echo $table_headers['client']; ?></th>
			<th scope="col" class="col_amount last"><?php echo $table_headers['amount']; ?></th>
		<?php } else { ?>
			<th scope="col" class="first"><?php echo $this->lang->line('id'); ?></th>
			<th scope="col"><?php echo $this->lang->line('date'); ?></th>
			<th scope="col"><?php echo $this->lang->line('invoice_number'); ?></th>
			<th scope="col"><?php echo $this->lang->line('client'); ?></th>
			<th scope="col" class="col_amount last"><?php echo $this->lang->line('amount'); ?></th>
		<?php } ?>
	</tr>
	<?php foreach ($payments as $payment) {
		if(!uri_assoc('payment_id') OR uri_assoc('payment_id') <> $payment->payment_id) { ?>
			<tr id="payment_<?php echo $payment->payment_id; ?>" class="hoverall">
				<td class="first"><?php echo $payment->payment_id; ?></td>
				<td><?php echo format_date($payment->payment_date); ?></td>
				<td><?php echo $payment->invoice_number; ?></td>
				<td><?php echo anchor('clients/form/client_id/' . $payment->client_id, $payment->client_name); ?></td>
				<td class="col_amount last"><?php echo display_currency($payment->payment_amount); ?></td>
			</tr>
			<tr class="actions" id="actions_payment_<?php echo $payment->payment_id; ?>" style="display: none;">
				<td colspan="6" style="text-align: right;"><?php echo icon('arrow_up'); ?>
					<?php echo anchor('payments/form/invoice_id/' . $payment->invoice_id . '/payment_id/' . $payment->payment_id, $this->lang->line('edit')); ?> |
					<a href="javascript:void(0)" class="output_link_receipt" id="<?php echo $payment->invoice_id . '-' . $payment->payment_id; ?>"><?php echo $this->lang->line('payment_receipt'); ?></a> |
					<?php echo anchor('payments/delete/invoice_id/' . $payment->invoice_id . '/payment_id/' . $payment->payment_id, $this->lang->line('delete'), array("onclick"=>"javascript:if(!confirm('" . $this->lang->line('confirm_delete') . "')) return false")); ?>
				</td>
			</tr>
		<?php } ?>
	<?php } ?>

</table>

<?php if ($this->mdl_payments->page_links) { ?>
<div id="pagination">
	<?php echo $this->mdl_payments->page_links; ?>
</div>
<?php } ?>