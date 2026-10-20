<?php
$ev = $event['Event'];
$embed = livego_embed_url($ev['link']);
echo $this->element('section-header', array('section' => h($ev['titulo']), 'section_url' => 'Evento en Vivo'));
?>
<style>
	.lg-player { position: relative; width: 100%; padding-top: 56.25%; background: #000; border-radius: 6px; overflow: hidden; }
	.lg-player iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
	.lg-info img { width: 100%; height: auto; border-radius: 6px; display: block; }
	.lg-info h3 { margin: 15px 0 5px; font-weight: 700; line-height: 1.2; }
	.lg-fecha { display: inline-block; background: #0088cc; color: #fff; padding: 2px 10px; border-radius: 4px; font-size: .9em; }
	.lg-info h5 { margin: 18px 0 4px; }
	.lg-ayuda { margin-top: 10px; font-size: .95em; }
</style>
<div class="row" style="margin-top:20px;">
	<div class="col-lg-9 col-xl-9 order-lg-2 mb-4">
		<section class="card">
			<div class="card-body p-2">
				<div class="lg-player">
					<iframe src="<?php echo h($embed); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
				</div>
				<div class="lg-ayuda text-right">
					¿No se ve? <a href="<?php echo h($ev['link']); ?>" target="_blank" rel="noopener">Abrir la transmisión en otra pestaña <i class="fas fa-external-link-alt"></i></a>
				</div>
			</div>
		</section>
	</div>
	<div class="col-lg-3 col-xl-3 order-lg-1">
		<section class="card">
			<div class="card-body lg-info">
				<?php echo $this->Html->image(livego_imagen_url($ev), array('alt' => h($ev['titulo']))); ?>
				<h3><?php echo h($ev['titulo']); ?></h3>
				<span class="lg-fecha"><i class="fas fa-clock mr-1"></i><?php echo date_format(date_create($ev['fecha']), 'd-m-Y H:i'); ?> hs</span>
				<?php if (!empty($ev['descripcion'])) { ?>
					<h5>Descripción</h5>
					<p><?php echo nl2br(h($ev['descripcion'])); ?></p>
				<?php } ?>
				<?php if (!empty($ev['horario'])) { ?>
					<h5>Horarios</h5>
					<p><?php echo nl2br(h($ev['horario'])); ?></p>
				<?php } ?>
			</div>
		</section>
	</div>
</div>
