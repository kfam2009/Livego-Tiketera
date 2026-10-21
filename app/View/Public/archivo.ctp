<div class="lg-eyebrow">Archivo</div>
<h1 class="lg-titulo">Eventos anteriores</h1>
<p class="lg-sub">Las transmisiones completas de eventos que ya pasaron.</p>
<?php if (empty($events)) { ?>
	<div class="alert alert-light">Todavía no hay eventos publicados.</div>
<?php } else { ?>
<div class="row">
	<?php foreach ($events as $e) { $ev = $e['Event']; $embed = livego_embed_url($ev['youtube_archivo']); ?>
	<div class="col-md-6 mb-4">
		<div class="lg-card">
			<div style="position:relative;padding-top:56.25%;background:#000">
				<iframe src="<?php echo h(str_replace('autoplay=1', 'autoplay=0', $embed)); ?>" style="position:absolute;inset:0;width:100%;height:100%;border:0" loading="lazy" allow="encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>
			</div>
			<div class="body">
				<h3><?php echo h($ev['titulo']); ?></h3>
				<span class="lg-chip"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y', strtotime($ev['fecha'])); ?></span>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<?php } ?>
