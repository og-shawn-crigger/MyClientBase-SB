<table style="width: 100%;" class="hover_links">
    <tr>
        <th scope="col" class="first"><?php echo $this->lang->line('status'); ?></th>
        <?php if (isset($sort_links)) { ?>
        <th scope="col"><?php echo $table_headers['invoice_number']; ?></th>
        <th scope="col"><?php echo $table_headers['date']; ?></th>
        <th scope="col"><?php echo $table_headers['due_date']; ?></th>
        <th scope="col" class="client"><?php echo $table_headers['client']; ?></th>
        <th scope="col" class="col_amount last"><?php echo $table_headers['amount']; ?></th>
        <?php } else { ?>
        <th scope="col"><?php echo (!uri_assoc('is_quote') ? $this->lang->line('invoice_number') : $this->lang->line('quote_number')); ?></th>
        <th scope="col"><?php echo $this->lang->line('date'); ?></th>
        <th scope="col"><?php echo $this->lang->line('due_date'); ?></th>
        <th scope="col" class="client"><?php echo $this->lang->line('client'); ?></th>
        <th scope="col" class="col_amount last"><?php echo $this->lang->line('amount'); ?></th>
        <?php } ?>
    </tr>
    <?php foreach ($invoices as $invoice) { ?>

    <tr id="invoice_<?php echo $invoice->invoice_id; ?>" class="hoverall">
        <td class="first invoice_<?php if (!$invoice->invoice_is_quote) { if ($invoice->invoice_is_overdue) { ?>4<?php } else { echo $invoice->invoice_status_type; } } ?>">
			<?php if ($invoice->invoice_is_quote) { echo $this->lang->line('quote'); } elseif ($invoice->invoice_is_overdue) { echo $this->lang->line('overdue'); } else { echo $invoice->invoice_status; } ?>
		</td>
        <td><?php echo invoice_id($invoice); ?></td>
        <td><?php echo format_date($invoice->invoice_date_entered); ?></td>
        <td><?php echo format_date($invoice->invoice_due_date); ?></td>
        <td class="client"><?php echo anchor('clients/form/client_id/' . $invoice->client_id, character_limiter($invoice->client_name, 20)); ?></td>
        <td class="col_amount last"><?php echo display_currency($invoice->invoice_total); ?></td>
	</tr>
	<tr class="actions" id="actions_invoice_<?php echo $invoice->invoice_id; ?>" style="display: none;">
		<td colspan="6" style="text-align: right;" class="last"><?php echo icon('arrow_up'); ?>
            <?php echo anchor('invoices/edit/invoice_id/' . $invoice->invoice_id, $this->lang->line('edit')); ?> |
            <a href="javascript:void(0)" class="output_link" id="<?php echo $invoice->invoice_id . ':' . $invoice->client_id . ':' . $invoice->invoice_is_quote; ?>"><?php echo $this->lang->line('generate_invoice'); ?></a> |
			<?php echo anchor('invoices/delete/invoice_id/' . $invoice->invoice_id, $this->lang->line('delete'), array("onclick"=>"javascript:if(!confirm('" . $this->lang->line('confirm_delete') . "')) return false")); ?>
        </td>
    </tr>

    <?php } ?>
</table>

<?php if ($this->mdl_invoices->page_links) { ?>
<div id="pagination">
<?php echo $this->mdl_invoices->page_links; ?>
</div>
<?php } ?>