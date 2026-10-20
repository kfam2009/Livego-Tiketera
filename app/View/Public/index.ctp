<?php $this->assign('title', 'Próximos partidos'); ?>
<h1 class="mb-1">Próximos partidos</h1>
<p class="text-muted mb-4">Comprá tu entrada online y miralo en vivo desde el celular, la compu o la tele.</p>
<?php if (empty($events)) { ?>
	<div class="alert alert-light">No hay partidos a la venta en este momento. Mirá los <?php echo $this->Html->link('partidos anteriores', '/partidos-anteriores'); ?>.</div>
<?php } else { ?>
<div class="row">
	<?php foreach ($events as $e) { $ev = $e['Event']; $enVivo = $ev['fecha'] <= date('Y-m-d H:i:s'); ?>
	<div class="col-sm-6 col-lg-4 mb-4">
		<div class="lg-card">
			<?php echo $this->Html->image(livego_imagen_url($ev), array('alt' => h($ev['titulo']))); ?>
			<div class="body">
				<?php if ($enVivo) { ?><span class="lg-vivo">EN VIVO</span><?php } ?>
				<h3><?php echo h($ev['titulo']); ?></h3>
				<div class="lg-fecha"><i class="far fa-calendar-alt mr-1"></i><?php echo date('d/m/Y · H:i', strtotime($ev['fecha'])); ?> hs</div>
				<div class="acciones">
					<span class="lg-precio">$<?php echo number_format((float)$ev['precio'], 0, ',', '.'); ?></span>
					<?php if ($ev['agotado']) { ?>
						<span class="btn btn-secondary disabled">Agotado</span>
					<?php } else { ?>
						<?php echo $this->Html->link('Comprar entrada', '/comprar/' . $ev['id'], array('class' => 'btn btn-lg-comprar')); ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<?php } ?>
<p class="text-muted mt-2">¿Ya compraste? <?php echo $this->Html->link('Ingresá con tu usuario y clave', '/users/login'); ?>.</p>
