<?php $this->load->view('header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('user_accounts'); ?></h3>

		<div class="content toggle no_padding">

			<table style="width: 100%;" class="hover_links">
				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('name'); ?></th>
					<th scope="col"><?php echo $this->lang->line('company_name'); ?></th>
					<th scope="col"><?php echo $this->lang->line('email'); ?></th>
					<th scope="col"  class="last"><?php echo $this->lang->line('phone_number'); ?></th>
				</tr>
				<?php foreach ($users as $user) { ?>
				<tr class="hoverall">
					<td class="first"><b><a href="<?php echo site_url('users/form/user_id/' . $user->user_id); ?>" title="<?php echo $this->lang->line('edit'); ?>"><?php echo $user->last_name . ', ' . $user->first_name; ?></a></b></td>
					<td><?php echo $user->company_name; ?></td>
					<td><a href="mailto:<?php echo $user->email_address; ?>"><?php echo $user->email_address; ?></a></td>
					<td class="last"><?php echo $user->phone_number; ?></td>
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

<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>