<div class="row">
	<div class="col-lg-12">
		<section class="card">
			<header class="card-header">
				<div class="card-actions">
					<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
				</div>

				<h2 class="card-title">Listado</h2>
			</header>
			<div class="card-body">
				<table class="table table-responsive-md mb-0">
					<thead>
						<tr>
							<th>#</th>
							<th>Nombre</th>
							<th>Apellido</th>
							<th>Email</th>
							<th>Perfil</th>
							<th>Acciones</th>

						</tr>
					</thead>
					<tbody>
						<?php foreach ($users as $user) { ?>
							<tr>
								<td><?php echo $user['User']['id']; ?></td>
								<td><?php echo $user['User']['name']; ?></td>
								<td><?php echo $user['User']['lastname']; ?></td>
								<td><?php echo $user['User']['username']; ?></td>

								<td><?php echo $user['User']['role']; ?></td>
								<td class="actions">
									<?php
									if ($user['User']['role'] != 'Admin') {
										echo $this->Html->link('<i class="fas fa-pencil-alt"></i>', array('action' => 'edit', $user['User']['id']), array('escape' => false));
										echo ' ' . $this->Form->postLink('<i class="fas fa-key"></i>', array('action' => 'resetpass', $user['User']['id']), array('escape' => false, 'title' => 'Generar clave nueva', 'confirm' => '¿Generar una clave nueva para este usuario? La anterior deja de funcionar.'));
										echo ' ' . $this->Form->postLink('<i class="far fa-trash-alt"></i>', array('action' => 'delete', $user['User']['id']), array('escape' => false, 'title' => 'Eliminar', 'confirm' => '¿Está seguro que desea eliminar este usuario?'));
									}
									?>
								</td>

							</tr>
						<?php } ?>
					</tbody>
				</table>

				<p>
					<?php
					echo $this->Paginator->counter(array(
						'format' => 'Página {:page} de {:pages} — {:count} usuarios en total'
					));
					?>
				</p>
				<div class="paging">
					<?php
					echo $this->Paginator->prev('< anterior ', array(), null, array('class' => 'prev disabled'));
					echo $this->Paginator->numbers(array('separator' => ''));
					echo $this->Paginator->next(' siguiente >', array(), null, array('class' => 'next disabled'));
					?>
				</div>
		</section>
	</div>

</div>
