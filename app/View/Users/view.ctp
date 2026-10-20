<div class="users view">
<h2><?php echo __('User'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Password'); ?></dt>
		<dd>
			<?php echo h($user['User']['password']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Role'); ?></dt>
		<dd>
			<?php echo h($user['User']['role']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($user['User']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($user['User']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $user['User']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usersucursales'), array('controller' => 'usersucursales', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usersucursale'), array('controller' => 'usersucursales', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Usersucursales'); ?></h3>
	<?php if (!empty($user['Usersucursale'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Sucursale Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($user['Usersucursale'] as $usersucursale): ?>
		<tr>
			<td><?php echo $usersucursale['id']; ?></td>
			<td><?php echo $usersucursale['user_id']; ?></td>
			<td><?php echo $usersucursale['sucursale_id']; ?></td>
			<td><?php echo $usersucursale['created']; ?></td>
			<td><?php echo $usersucursale['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'usersucursales', 'action' => 'view', $usersucursale['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'usersucursales', 'action' => 'edit', $usersucursale['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'usersucursales', 'action' => 'delete', $usersucursale['id']), array('confirm' => __('Are you sure you want to delete # %s?', $usersucursale['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Usersucursale'), array('controller' => 'usersucursales', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
