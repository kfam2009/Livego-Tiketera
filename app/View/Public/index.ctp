<?php $this->assign('title', 'Próximos eventos'); ?>
<div class="lg-eyebrow">En vivo por LiveGo</div>
<h1 class="lg-titulo">Próximos eventos</h1>
<p class="lg-sub">Comprá tu entrada online y miralo en vivo desde el celular, la compu o la tele. Pagás con Mercado Pago y al instante recibís tu usuario y clave.</p>
<?php if (empty($events)) { ?>
	<div class="alert alert-light">No hay eventos a la venta en este momento. Mirá los <?php echo $this->Html->link('eventos anteriores', '/eventos-anteriores'); ?>.</div>
<?php } else { ?>
<div class="row">
	<?php foreach ($events as $e) { $ev = $e['Event']; $enVivo = $ev['fecha'] <= date('Y-m-d H:i:s'); ?>
	<div class="col-sm-6 col-lg-4 mb-4">
		<div class="lg-card interactiva">
			<div class="lg-media">
				<?php echo $this->Html->image(livego_imagen_url($ev), array('alt' => h($ev['titulo']), 'loading' => 'lazy')); ?>
				<div class="lg-badges">
					<?php if ($enVivo) { ?><span class="lg-vivo">EN VIVO</span><?php } else { ?><span class="lg-pronto">Próximamente</span><?php } ?>
				</div>
			</div>
			<div class="body">
				<h3><?php echo h($ev['titulo']); ?></h3>
				<span class="lg-chip"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y · H:i', strtotime($ev['fecha'])); ?> hs</span>
				<div class="acciones">
					<span class="lg-precio">$<?php echo number_format((float)$ev['precio'], 0, ',', '.'); ?> <small>por entrada</small></span>
					<?php if ($ev['agotado']) { ?>
						<span class="btn btn-secondary disabled">Agotado</span>
					<?php } else { ?>
						<?php echo $this->Html->link('<i class="fas fa-ticket-alt mr-1"></i> Comprar', '/comprar/' . $ev['id'], array('class' => 'btn btn-lg-comprar', 'escape' => false)); ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<?php } ?>
<p class="text-muted mt-2">¿Ya compraste? <?php echo $this->Html->link('Ingresá con tu usuario y clave', '/users/login'); ?>.</p>
<div class="lg-card mt-4" style="height:auto;border-color:rgba(237,56,62,.45)">
	<div class="body" style="flex-direction:row;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:14px">
		<div>
			<div class="lg-eyebrow mb-1">¿Organizás eventos?</div>
			<h3 style="font-size:1.6rem">Transmitilo con LiveGo y vendé entradas online</h3>
			<span class="text-muted">Más de <?php echo (int)date('Y') - 2018; ?> años haciendo transmisiones en vivo con equipo técnico propio.</span>
		</div>
		<?php echo $this->Html->link('Conocenos <i class="fas fa-arrow-right ml-1"></i>', '/quienes-somos', array('class' => 'btn btn-lg-comprar', 'escape' => false)); ?>
	</div>
</div>
