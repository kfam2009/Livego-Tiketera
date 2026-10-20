<h1 class="mb-1">Partidos anteriores</h1>
<p class="text-muted mb-4">Las transmisiones completas de partidos ya jugados.</p>
<?php if (empty($events)) { ?>
	<div class="alert alert-light">Todavía no hay partidos publicados.</div>
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
				<div class="lg-fecha"><?php echo date('d/m/Y', strtotime($ev['fecha'])); ?></div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<?php } ?>
