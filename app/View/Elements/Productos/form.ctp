<?php App::uses('LivegoConfig', 'Lib/Livego'); ?>
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
						<label class="col-lg-12 control-label pt-2" for="EventFuente">Transmisión</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('fuente', array('label' => false, 'div' => false, 'class' => 'form-control', 'id' => 'EventFuente', 'options' => array(
								'cloudflare' => 'Propia y protegida (desde vMix / OBS, por Cloudflare)',
								'link' => 'Link externo (YouTube, Vimeo, Facebook, Twitch, Kick…)',
							), 'default' => (\LivegoConfig::cloudflareListo() ? 'cloudflare' : 'link'))); ?>
							<?php if (!\LivegoConfig::cloudflareListo()) { ?><small class="form-text text-warning">Cloudflare todavía no está configurado (faltan CF_ACCOUNT_ID y CF_API_TOKEN en Render).</small><?php } ?>
						</div>
						<div id="bloqueLink">
							<label class="col-lg-12 control-label pt-2" for="EventLink">Link del vivo</label>
							<div class="col-lg-12">
								<?php echo $this->Form->input('link', array('label' => false, 'required' => false, 'class' => 'form-control', 'div' => false, 'id' => 'EventLink')); ?>
								<small class="form-text text-muted">Pegá el link del vivo tal cual: YouTube, Vimeo, Facebook, Twitch o Kick (se adapta solo). En YouTube, el video tiene que tener permitida la opción <em>"Permitir insertar"</em>. Ojo: con un link externo el video no queda protegido.</small>
							</div>
						</div>
						<div id="bloqueCf" class="col-lg-12">
							<small class="form-text text-muted">Al guardar se crea la transmisión y en la lista de eventos (botón <i class="fas fa-broadcast-tower"></i>) vas a ver el servidor y la clave para cargar en vMix u OBS. Solo la ven los que tienen entrada.</small>
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


					<div class="col-lg-12"><hr><h4 class="mb-0">Venta online (Mercado Pago)</h4></div>
					<div class="col-lg-3">
						<label class="col-lg-12 control-label pt-2" for="EventVentaActiva">¿A la venta?</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('venta_activa', array('label' => false, 'div' => false, 'class' => 'form-control', 'id' => 'EventVentaActiva', 'options' => array('0' => 'No', '1' => 'Sí, mostrar en la cartelera'))); ?>
						</div>
					</div>
					<div class="col-lg-3">
						<label class="col-lg-12 control-label pt-2" for="EventPrecio">Precio por entrada ($)</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('precio', array('label' => false, 'div' => false, 'class' => 'form-control', 'type' => 'number', 'min' => 0, 'step' => '1', 'id' => 'EventPrecio')); ?>
						</div>
					</div>
					<div class="col-lg-3">
						<label class="col-lg-12 control-label pt-2" for="EventCupo">Cupo máximo</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('cupo', array('label' => false, 'div' => false, 'class' => 'form-control', 'type' => 'number', 'min' => 0, 'placeholder' => 'Sin límite', 'id' => 'EventCupo')); ?>
						</div>
					</div>
					<div class="col-lg-3">
						<label class="col-lg-12 control-label pt-2" for="EventRepeticionDias">Repetición (días)</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('repeticion_dias', array('label' => false, 'div' => false, 'class' => 'form-control', 'type' => 'number', 'min' => 0, 'max' => 60, 'default' => 7, 'id' => 'EventRepeticionDias')); ?>
							<small class="form-text text-muted">Días después del final en que los que compraron pueden ver la grabación (solo transmisión propia).</small>
						</div>
					</div>
					<div class="col-lg-12"><hr><h4 class="mb-0">Partidos anteriores</h4></div>
					<div class="col-lg-6">
						<label class="col-lg-12 control-label pt-2" for="EventYoutubeArchivo">Link de YouTube del partido completo</label>
						<div class="col-lg-12">
							<?php echo $this->Form->input('youtube_archivo', array('label' => false, 'div' => false, 'class' => 'form-control', 'required' => false, 'placeholder' => 'https://www.youtube.com/watch?v=…', 'id' => 'EventYoutubeArchivo')); ?>
							<small class="form-text text-muted">Si lo completás, el partido aparece gratis en la sección pública "Partidos anteriores".</small>
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
<script>
(function () {
	var sel = document.getElementById('EventFuente');
	if (!sel) return;
	function ver() {
		var cf = sel.value === 'cloudflare';
		document.getElementById('bloqueLink').style.display = cf ? 'none' : '';
		document.getElementById('bloqueCf').style.display = cf ? '' : 'none';
	}
	sel.addEventListener('change', ver); ver();
})();
</script>
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
