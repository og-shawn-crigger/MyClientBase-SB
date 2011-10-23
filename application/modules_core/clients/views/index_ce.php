<?php $this->load->view('dashboard/header'); ?>

<div class="grid_14" id="content_wrapper" style="border: 3px solid blue;">

    <div class="section_wrapper">

        <?php 
        	$this->load->view('dashboard/system_messages'); 
        	$people = $contacts['people'];
        	$orgs = $contacts['orgs'];
        ?>

        <div class="content toggle no_padding">
        
		<!-- PEOPLE -->
		<div class="left-block">
			<h3 class="title_black"><?php echo $this->lang->line('clients'); ?><?php $this->load->view('dashboard/btn_add', array('btn_name'=>'btn_add_client', 'btn_value'=>$this->lang->line('add_client'))); ?></h3>
            <table class="table-clients">
                <tr>
                    <th scope="col" class="first">
                        <?php if ($this->uri->segment(4) == 'uid_desc') {
                            echo anchor('clients/index/order_by/uid_asc', $this->lang->line('id'));
                        } else {
                            echo anchor('clients/index/order_by/uid_desc', $this->lang->line('id'));
                        } ?>
                    </th>
                    <th scope="col" >
                        <?php if ($this->uri->segment(4) == 'sn_desc') {
                            echo anchor('clients/index/order_by/sn_asc', $this->lang->line('name'));
                        } else {
                            echo anchor('clients/index/order_by/sn_desc', $this->lang->line('name'));
                        } ?>
                    </th>
                    <th scope="col" class="col_amount">
                        <?php if ($this->uri->segment(4) == 'balance_desc') {
                            echo anchor('clients/index/order_by/balance_asc', $this->lang->line('balance'));
                        } else {
                            echo anchor('clients/index/order_by/balance_desc', $this->lang->line('balance'));
                        } ?>
                    </th>
                    <th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
                </tr>
                <?php foreach ($people as $person) { ?>
                <tr class="hoverall">
                    <td class="first"><?php echo $person->uid; ?></td>
                    <td nowrap="nowrap"><?php echo $person->cn; ?></td>
                    <td class="col_amount"><?php echo display_currency($person->total_balance); ?></td>
                    <td class="last">
                        <a href="<?php echo site_url('clients/details/client_id/' . $person->uid); ?>" title="<?php echo $this->lang->line('view'); ?>">
                        <?php echo icon('zoom'); ?>
                        </a>
                        <a href="<?php echo site_url('clients/form/client_id/' . $person->uid); ?>" title="<?php echo $this->lang->line('edit'); ?>">
                        <?php echo icon('edit'); ?>
                        </a>
                        <a href="<?php echo site_url('clients/delete/client_id/' . $person->uid); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('client_delete_warning'); ?>')) return false">
                        <?php echo icon('delete'); ?>
                        </a>
                        <?php if  ($person->client_active) { ?>
                        <a href="<?php echo site_url('invoices/create/client_id/' . $person->uid); ?>" title="<?php echo $this->lang->line('create_invoice'); ?>">
                        <?php echo icon('invoice'); ?>
                        </a>
                        <a href="<?php echo site_url('invoices/create/quote/client_id/' . $person->uid); ?>" title="<?php echo $this->lang->line('create_quote'); ?>">
                        <?php echo icon('quote'); ?>
                        </a>
                        <?php } ?>
                    </td>
                </tr>
                    <?php } ?>
            </table>
			
		</div>
		
		<div class="right-block">
		
            <!-- Organizations -->
            <h3 class="title_black">ORG<?php echo $this->lang->line('organizations'); ?><?php $this->load->view('dashboard/btn_add', array('btn_name'=>'btn_add_client', 'btn_value'=>$this->lang->line('add_client'))); ?></h3>
            <table class="table-orgs">
            <tr>
            <th scope="col" class="first">
            <?php if ($this->uri->segment(4) == 'uid_desc') {
            	echo anchor('clients/index/order_by/uid_asc', $this->lang->line('id'));
            } else {
            	echo anchor('clients/index/order_by/uid_desc', $this->lang->line('id'));
            } ?>
			</th>
            <th scope="col" >
            	<?php if ($this->uri->segment(4) == 'sn_desc') {
                		echo anchor('clients/index/order_by/sn_asc', $this->lang->line('name'));
               		} else {
                        echo anchor('clients/index/order_by/sn_desc', $this->lang->line('name'));
                    } ?>
			</th>
			<th scope="col" class="col_amount">
				<?php if ($this->uri->segment(4) == 'balance_desc') {
					echo anchor('clients/index/order_by/balance_asc', $this->lang->line('balance'));
				} else {
                	echo anchor('clients/index/order_by/balance_desc', $this->lang->line('balance'));
				} ?>
			</th>
			<th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
			</tr>
			
			<?php foreach ($orgs as $organization) { ?>
			<tr class="hoverall">
				<td class="first"><?php echo $organization->oid; ?></td>
				<td nowrap="nowrap"><?php echo $organization->o; ?></td>
				<td class="col_amount"><?php echo display_currency($organization->total_balance); ?></td>
				<td class="last">
					<a href="<?php echo site_url('clients/details/client_id/' . $organization->oid); ?>" title="<?php echo $this->lang->line('view'); ?>">
						<?php echo icon('zoom'); ?>
					</a>
					<a href="<?php echo site_url('clients/form/client_id/' . $organization->oid); ?>" title="<?php echo $this->lang->line('edit'); ?>">
						<?php echo icon('edit'); ?>
					</a>
					<a href="<?php echo site_url('clients/delete/client_id/' . $organization->oid); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('client_delete_warning'); ?>')) return false">
						<?php echo icon('delete'); ?>
					</a>
						<?php if  ($person->client_active) { ?>
					<a href="<?php echo site_url('invoices/create/client_id/' . $organization->oid); ?>" title="<?php echo $this->lang->line('create_invoice'); ?>">
						<?php echo icon('invoice'); ?>
					</a>
					<a href="<?php echo site_url('invoices/create/quote/client_id/' . $organization->oid); ?>" title="<?php echo $this->lang->line('create_quote'); ?>">
						<?php echo icon('quote'); ?>
					</a>
						<?php } ?>
				</td>
			</tr>
            <?php } ?>
			</table>		
		</div>
	
			
		    <?php if ($this->mdl_clients->page_links) { ?>
            <div id="pagination">
            <?php echo $this->mdl_clients->page_links; ?>
            </div>
            <?php } ?>

        </div>

    </div>

</div>

<?php //$this->load->view('dashboard/sidebar', array('side_block'=>'clients/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>