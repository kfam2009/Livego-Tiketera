<?php
$ev = $event['Event'];
echo $this->element('section-header', array('section' => h($ev['titulo']), 'section_url' => 'Evento en Vivo'));
?>
<style>
	.lg-player { position: relative; width: 100%; padding-top: 56.25%; background: #000; border-radius: 6px; overflow: hidden; }
	.lg-player iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
	.lg-info img { width: 100%; height: auto; border-radius: 6px; display: block; }
	.lg-info h3 { margin: 15px 0 5px; font-weight: 700; line-height: 1.2; }
	.lg-fecha { display: inline-block; background: #121317; color: #4cc9f0; border: 1px solid #33363e; padding: 2px 10px; border-radius: 4px; font-size: .9em; }
	.lg-info h5 { margin: 18px 0 4px; }
	.lg-ayuda { margin-top: 10px; font-size: .95em; }
</style>
<div class="row" style="margin-top:20px;">
	<div class="col-lg-9 col-xl-9 order-lg-2 mb-4">
		<section class="card">
			<div class="card-body p-2">
				<?php if ($modo === 'vivo') { ?><div class="mb-2"><span class="badge badge-danger">EN VIVO</span> <small class="text-muted">Si todavía no empezó, vas a ver un aviso: dejá la página abierta o recargala a la hora del evento.</small></div><?php } ?>
				<?php if ($modo === 'repeticion') { ?><div class="mb-2"><span class="badge badge-primary">REPETICIÓN</span></div><?php } ?>
				<?php if ($error) { ?>
					<div class="alert alert-warning"><?php echo h($error); ?></div>
				<?php } elseif ($player) { ?>
				<div class="lg-player">
					<iframe src="<?php echo h($player); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
				</div>
				<?php } ?>
				<?php if ($modo === 'link' && !empty($ev['link'])) { ?>
				<div class="lg-ayuda text-right">
					¿No se ve? <a href="<?php echo h($ev['link']); ?>" target="_blank" rel="noopener">Abrir la transmisión en otra pestaña <i class="fas fa-external-link-alt"></i></a>
				</div>
				<?php } ?>
				<?php if ($modo === 'repeticion' && count($grabaciones) > 1) { ?>
				<div class="mt-2">Partes: <?php foreach ($grabaciones as $i => $g) {
					$label = 'Parte ' . (count($grabaciones) - $i);
					echo $g['uid'] === $grabacionActual ? '<strong class="mr-2">' . $label . '</strong>' : $this->Html->link($label, array('action' => 'view', $ev['id'], '?' => array('g' => $g['uid'])), array('class' => 'mr-2'));
				} ?></div>
				<?php } ?>
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
