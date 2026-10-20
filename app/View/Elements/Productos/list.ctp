<div class="row">
	<div class="col">
		<section class="card">
			<header class="card-header">
				<div class="card-actions">
					<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
					<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
				</div>
				<h2 class="card-title">Listado de Eventos </h2>
			</header>
			<div class="card-body">
				<table class="table table-bordered table-striped mb-0" id="datatable-tabletools">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Titulo</th>
							<th>Descripcion</th>
							<th>Imagen</th>
							<th>Estado</th>
							<th style="text-align:center">Editar</th>
							<th style="text-align:center">Espectadores</th>
							<th style="text-align:center">Eliminar</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1;
						foreach ($events as $producto) { ?>
							<tr>
								<td><?php echo $producto['Event']['fecha']; ?></td>
								<td><?php echo $producto['Event']['titulo']; ?></td>
								<td><?php echo $producto['Event']['descripcion']; ?></td>
								<td><?php echo $this->Html->image(livego_imagen_url($producto['Event']), array('style'=>"max-width:100px;", 'alt'=>$producto['Event']['titulo']));?></td>

								<td><?php echo $producto['Event']['active']; ?></td>

								<td class="actions" style="text-align:center">
									<?php echo $this->Html->link('<i class="fas fa-pencil-alt"></i>', array('action' => 'edit', $producto['Event']['id']), array('escape' => false)); ?>


								</td>
								<td class="actions" style="text-align:center">
									<?php echo $this->Html->link('<i class="fas fa-ticket-alt"></i> Accesos', array('controller' => 'Suscriptions', 'action' => 'newsuscription', $producto['Event']['id']), array('escape' => false, 'title' => 'Espectadores y accesos')); ?>
								</td>
								<td class="actions" style="text-align:center">


									<?php echo $this->Html->link('<i class="far fa-trash-alt"></i>', array('action' => 'delete', $producto['Event']['id']), array('escape' => false, 'onclick' => "return confirm('¿Eliminar este evento?')")); ?>
								</td>
							</tr>
						<?php $i++;
						} ?>
					</tbody>
				</table>
			</div>
		</section>
	</div>
</div>

<?php echo $this->Html->script(array(
	'../vendor/select2/js/select2',
	'../vendor/datatables/media/js/jquery.dataTables.min',
	'../vendor/datatables/media/js/dataTables.bootstrap4.min',
	'../vendor/datatables/extras/TableTools/Buttons-1.4.2/js/dataTables.buttons.min',
	'../vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.bootstrap4.min',
	'../vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.html5.min',
	'../vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.print.min',
	'../vendor/datatables/extras/TableTools/JSZip-2.5.0/jszip.min',
	'../vendor/datatables/extras/TableTools/pdfmake-0.1.32/pdfmake.min',
	'../vendor/datatables/extras/TableTools/pdfmake-0.1.32/vfs_fonts',
	'examples.datatables.tabletools'
)); ?>
