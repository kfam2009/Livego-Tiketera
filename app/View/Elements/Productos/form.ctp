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
					'Event',
					array(
						'type'=>'file',
						'url' => $action,
						'class' => 'form-horizontal form-bordered'
					)
				); ?>

				<div class="form-group row">
					<div class="col-lg-6">
						<label class="col-lg-12 control-label pt-2" for="titulo">Estado</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('active', array('label' => false, 'required' => true, 'options'=>array('1'=>'Activada', '0'=>'Inactiva') ,'class' => 'form-control', 'div' => false)); ?>
						</div>
					</div>
					<div class="col-lg-6">
						<label class="col-lg-12 control-label pt-2" for="titulo">Titulo</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('id', array('label' => false, 'required' => false, 'hidden' => true, 'div' => false)); ?>
							<?php echo $this->Form->input('titulo', array('label' => false, 'required' => true, 'class' => 'form-control', 'div' => false)); ?>
						</div>
					</div>





					<div class="col-lg-6">
						<label class="col-lg-12 control-label pt-2" for="link">Link Show</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('link', array('label' => false, 'required' => true, 'class' => 'form-control', 'div' => false)); ?>
						</div>
					</div>


					<div class="col-lg-6">
						<label class="col-lg-12 control-label pt-2" for="fecha">Fecha de Inicio del Evento</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('fecha', array('label' => false, 'required' => true, 'separator' => '', 'dateFormat' => 'DMY', 'monthNames' => false, 'maxYear' => date('Y') + 1, 'minYear' => date('Y') - 1, 'class' => 'form-control col-xs-4', 'div' => false)); ?>
						</div>
						<label class="col-lg-12 control-label pt-2" for="fecha">Fecha de Finalizacion</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('fechafin', array('label' => false, 'required' => true, 'separator' => '', 'dateFormat' => 'DMY', 'monthNames' => false, 'maxYear' => date('Y') + 1, 'minYear' => date('Y') - 1, 'class' => 'form-control col-xs-4', 'div' => false)); ?>
						</div>
					</div>
					<div class="col-lg-6">
						<label class="col-lg-12 control-label pt-2" for="descripcion">Descripcion</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('descripcion', array('label' => false, 'required' => false, 'class' => 'form-control', 'div' => false)); ?>
						</div>
					</div>

					<div class="col-lg-6">
						<label class="col-lg-12 control-label pt-2" for="horario">Descripcion Horarios y Fecha</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('horario', array('label' => false, 'required' => true, 'class' => 'form-control', 'div' => false)); ?>
						</div>
					</div>
					<div class="col-lg-6">
						<label class="col-lg-12 control-label pt-2" for="imagen">Imagen</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('imagen', array('label' => false, 'required' => false,'type'=>'file', 'class' => 'form-control', 'div' => false)); ?>
							<small class="form-text text-muted">JPG o PNG, hasta 5 MB.<?php if (!empty($this->request->data['Event']['id'])) echo ' Dejalo vacío para mantener la actual.'; ?></small>
							<?php if (!empty($this->request->data['Event']['imagen']) && is_string($this->request->data['Event']['imagen'])) { ?>
							<div class="col-lg-12">

								<?php echo $this->Html->image(livego_imagen_url($this->request->data['Event']), array('style'=>'max-width:300px;'));?>
							</div>
						<?php } ?>
						</div>
					</div>


					<div class="col-lg-12" style="margin-bottom:20px; margin-top:30px;">
						<?php
						echo $this->Form->submit('Guardar', array('div' => false, 'class' => "btn btn-primary"));
						echo $this->Form->end();
						?>
					</div>

				</div>
		</section>
	</div>
</div>
<style>
	#EventFechafinDay,
	#EventFechafinMonth,
	#EventFechafinYear,
	#EventFechaDay,
	#EventFechaMonth,
	#EventFechaYear {
		width: 15%;
		display: inline-block;
	}
	#EventFechafinHour,
	#EventFechafinMin,
	#EventFechafinMeridian,
	#EventFechaHour,
	#EventFechaMin,
	#EventFechaMeridian{
		width: 15%;
		display: inline-block;
	}
</style>
