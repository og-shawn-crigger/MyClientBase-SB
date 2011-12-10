<?php $this->load->view('dashboard/header', array('header_insert'=>'dashboard/jquery_hover_links')); ?>

<div class="grid_10" id="content_wrapper">

    <div class="section_wrapper">

        <h3 class="title_black"><?php echo $this->lang->line('clients'); ?>
			<span style="font-size: 60%;">
			<?php $this->load->view('dashboard/btn_add', array('btn_name'=>'btn_add_client', 'btn_value'=>$this->lang->line('add_client'))); ?>
			</span>
		</h3>

        <?php $this->load->view('dashboard/system_messages'); ?>

        <div class="content toggle no_padding">

            <table style="width: 100%;" class="hover_links">
                <tr>
                    <th scope="col" class="first"><?php echo $table_headers['client_id']; ?></th>
                    <th scope="col" ><?php echo $table_headers['client_name']; ?></th>
					<th scope="col"><?php echo $table_headers['client_email']; ?></th>
					<th scope="col"><?php echo $table_headers['client_phone']; ?></th>
                    <th scope="col" class="col_amount"><?php echo $table_headers['credit_amount']; ?></th>
                    <th scope="col" class="col_amount last"><?php echo $table_headers['balance']; ?></th>
                </tr>
                <?php foreach ($clients as $client) { ?>
                <tr id="client_<?php echo $client->client_id; ?>" class="hoverall">
                    <td class="first"><?php echo $client->client_id; ?></td>
                    <td nowrap="nowrap"><?php echo $client->client_name; ?></td>
					<td><?php echo auto_link($client->client_email_address); ?></td>
					<td><?php echo $client->client_phone_number; ?></td>
					<td class="col_amount"><?php echo display_currency($client->client_credit_amount); ?></td>
                    <td class="col_amount last"><?php echo display_currency($client->client_total_balance); ?></td>
				</tr>
				<tr class="actions" id="actions_client_<?php echo $client->client_id; ?>" style="display: none;">
					<td colspan="6" style="text-align: right;" class="last"><?php echo icon('arrow_up'); ?>
						<?php echo anchor('clients/form/client_id/' . $client->client_id, $this->lang->line('edit')); ?> |
						<?php echo anchor('clients/delete/client_id/' . $client->client_id, $this->lang->line('delete'), array("onclick"=>"javascript:if(!confirm('" . $this->lang->line('confirm_delete') . "')) return false")); ?> |
                        <?php if  ($client->client_active) { ?>
						<?php echo anchor('invoices/create/client_id/' . $client->client_id, $this->lang->line('create_invoice')); ?> |
						<?php echo anchor('invoices/create/quote/client_id/' . $client->client_id, $this->lang->line('create_quote')); ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <?php if ($this->mdl_clients->page_links) { ?>
            <div id="pagination">
            <?php echo $this->mdl_clients->page_links; ?>
            </div>
            <?php } ?>

        </div>

    </div>

</div>

<?php $this->load->view('dashboard/footer'); ?>