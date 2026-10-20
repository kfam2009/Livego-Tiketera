<?php $titulo = h($Event['Event']['titulo']); ?>
<div class="row">
	<div class="col-lg-6">
		<section class="card mb-4">
			<header class="card-header">
				<h2 class="card-title"><i class="fas fa-ticket-alt mr-1"></i> Generar accesos nuevos</h2>
				<p class="card-subtitle">Crea usuario y clave al azar, ya habilitados para <strong><?php echo $titulo; ?></strong>.</p>
			</header>
			<div class="card-body">
				<?php echo $this->Form->create('Generar', array('url' => array('controller' => 'Suscriptions', 'action' => 'generar', $id), 'autocomplete' => 'off')); ?>
				<div class="form-group row">
					<label class="col-sm-4 control-label text-sm-right pt-2">Cantidad</label>
					<div class="col-sm-8">
						<?php echo $this->Form->input('cantidad', array('type' => 'number', 'min' => 1, 'max' => 500, 'value' => 1, 'label' => false, 'div' => false, 'class' => 'form-control', 'required' => true)); ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 control-label text-sm-right pt-2">Comienzo del usuario</label>
					<div class="col-sm-8">
						<?php echo $this->Form->input('prefijo', array('type' => 'text', 'value' => $prefijoSugerido, 'maxlength' => 12, 'label' => false, 'div' => false, 'class' => 'form-control')); ?>
						<small class="form-text text-muted">Ej: <code><?php echo h($prefijoSugerido); ?>4827</code> — solo letras y números.</small>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 control-label text-sm-right pt-2">Nombre / referencia</label>
					<div class="col-sm-8">
						<?php echo $this->Form->input('nombre', array('type' => 'text', 'placeholder' => 'Opcional: ej. Juan Pérez, Club Sansinena…', 'maxlength' => 100, 'label' => false, 'div' => false, 'class' => 'form-control')); ?>
					</div>
				</div>
				<div class="text-right">
					<?php echo $this->Form->button('<i class="fas fa-magic mr-1"></i> Generar', array('type' => 'submit', 'class' => 'btn btn-primary', 'escape' => false)); ?>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</section>
	</div>

	<div class="col-lg-6">
		<section class="card mb-4">
			<header class="card-header">
				<h2 class="card-title"><i class="fas fa-user-plus mr-1"></i> Agregar un espectador existente</h2>
				<p class="card-subtitle">Para alguien que ya tiene usuario de eventos anteriores.</p>
			</header>
			<div class="card-body">
				<?php echo $this->Form->create('Suscription', array('url' => array('controller' => 'Suscriptions', 'action' => 'newsuscription'), 'autocomplete' => 'off')); ?>
				<?php echo $this->Form->hidden('event_id', array('value' => $id)); ?>
				<div class="form-group">
					<?php echo $this->Form->input('user_id', array('label' => false, 'empty' => 'Elegí un espectador…', 'required' => true, 'options' => $users, 'class' => 'form-control', 'div' => false)); ?>
				</div>
				<div class="text-right">
					<?php echo $this->Form->button('Agregar', array('type' => 'submit', 'class' => 'btn btn-default')); ?>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</section>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<section class="card">
			<header class="card-header">
				<h2 class="card-title">Habilitados para <?php echo $titulo; ?> (<?php echo count($suscriptions); ?>)</h2>
			</header>
			<div class="card-body">
				<table class="table table-responsive-md mb-0">
					<thead>
						<tr>
							<th>Usuario</th>
							<th>Nombre</th>
							<th>Apellido</th>
							<th>Quitar</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($suscriptions as $s) { ?>
							<tr>
								<td><?php echo h($s['Users']['username']); ?></td>
								<td><?php echo h($s['Users']['name']); ?></td>
								<td><?php echo h($s['Users']['lastname']); ?></td>
								<td class="actions">
									<?php echo $this->Form->postLink('<i class="far fa-trash-alt"></i>', array('controller' => 'Suscriptions', 'action' => 'delete', $s['Suscription']['id']), array('escape' => false, 'title' => 'Quitar de este evento', 'confirm' => '¿Quitar a este espectador del evento? (el usuario no se borra)')); ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</section>
	</div>
</div>
