<?php $this->load->view('dashboard/header', array('header_insert'=>'dashboard/jquery_hover_links')); ?>

<div class="grid_10" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('user_accounts'); ?>
			<span style="font-size: 60%;">
				<?php $this->load->view('dashboard/btn_add', array('btn_value'=>$this->lang->line('create_user_account'))); ?>
			</span>
		</h3>

		<?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle no_padding">

			<table style="width: 100%;" class="hover_links">
				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('id'); ?></th>
					<th scope="col"><?php echo $this->lang->line('name'); ?></th>
					<th scope="col"><?php echo $this->lang->line('company_name'); ?></th>
					<th scope="col"><?php echo $this->lang->line('email'); ?></th>
					<th scope="col" class="last"><?php echo $this->lang->line('phone_number'); ?></th>
				</tr>
				<?php foreach ($users as $user) { ?>
				<tr id="user_<?php echo $user->user_id; ?>" class="hoverall">
					<td class="first"><?php echo $user->user_id; ?></td>
					<td><?php echo $user->last_name . ', ' . $user->first_name; ?></td>
					<td><?php echo $user->company_name; ?></td>
					<td><?php echo $user->email_address; ?></td>
					<td class="last"><?php echo $user->phone_number; ?></td>
				</tr>
				<tr class="actions" id="actions_user_<?php echo $user->user_id; ?>" style="display: none;">
					<td colspan="6" style="text-align: right;" class="last"><?php echo icon('arrow_up'); ?>
						<?php echo anchor('users/form/user_id/' . $user->user_id, $this->lang->line('edit')); ?> |
						<?php echo anchor('users/delete/user_id/' . $user->user_id, $this->lang->line('delete'), array("onclick"=>"javascript:if(!confirm('" . $this->lang->line('confirm_delete') . "')) return false")); ?> |
                    </td>
                </tr>
				<?php } ?>
			</table>

			<?php if ($this->mdl_users->page_links) { ?>
			<div id="pagination">
				<?php echo $this->mdl_users->page_links; ?>
			</div>
			<?php } ?>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar'); ?>

<?php $this->load->view('dashboard/footer'); ?>