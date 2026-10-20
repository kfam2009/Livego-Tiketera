<!-- start: page -->
<div class="row">
	<div class="col">
		<section class="card">
			<header class="card-header">
				<div class="card-actions">
					<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
				</div>

				<h2 class="card-title"><?php echo $title; ?></h2>
			</header>
			<div class="card-body">
				<?php echo $this->Form->create(
					'User',
					array(
						'type' => 'file',
						'url' => $action,
						'class' => 'form-horizontal form-bordered',
						'autocomplete' => "off"
					)
				); ?>
				<div class="form-group row">
					<label class="col-lg-3 control-label text-lg-right pt-2" for="nombre">Estado de Cuenta</label>
					<div class="col-lg-6">
						<?php echo $this->Form->input('id', array('label' => false, 'autocomplete' => "nope", 'required' => false, 'hidden' => true, 'div' => false)); ?>
						<?php echo $this->Form->input('activo', array('label' => false, 'required' => true, 'options' => array('1' => 'Activa', '0' => 'Inactiva'), 'class' => 'form-control', 'div' => false)); ?>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-lg-3 control-label text-lg-right pt-2" for="nombre">Nombre</label>
					<div class="col-lg-6">

						<?php echo $this->Form->input('name', array('label' => false, 'required' => true, 'autocomplete' => "nope", 'class' => 'form-control', 'div' => false)); ?>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-lg-3 control-label text-lg-right pt-2" for="apellido">Apellido</label>
					<div class="col-lg-6">
						<?php echo $this->Form->input('lastname', array('label' => false, 'required' => true, 'autocomplete' => "nope", 'class' => 'form-control', 'div' => false)); ?>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-lg-3 control-label text-lg-right pt-2" for="nombre">Usuario o email:</label>
					<div class="col-lg-6">
						<?php echo $this->Form->input('username', array('type' => 'text', 'label' => false, 'autocomplete' => "nope", 'required' => true, 'class' => 'form-control', 'div' => false)); ?>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-lg-3 control-label text-lg-right pt-2" for="nombre">Contraseña</label>
					<div class="col-lg-6">
						<?php echo $this->Form->input('password', array('type' => 'password', 'value' => '', 'label' => false, 'autocomplete' => "new-password", 'required' => ($action == 'add'), 'placeholder' => ($action == 'add' ? '' : 'Dejar vacío para no cambiarla'), 'class' => 'form-control', 'div' => false)); ?>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-lg-3 control-label text-lg-right pt-2" for="nombre">Perfil</label>
					<div class="col-lg-6">
						<?php echo $this->Form->input('role', array('label' => false, 'required' => true, 'class' => 'form-control', 'div' => false, 'type' => 'select', 'options' => array('Espectador' => 'Espectador'))); ?>
					</div>
				</div>


				<?php
				echo $this->Form->submit('Guardar', array('div' => false, 'class' => "btn btn-primary"));
				echo $this->Form->end();
				?>
			</div>
		</section>
	</div>
</div>
