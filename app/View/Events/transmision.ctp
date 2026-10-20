<?php
$ev = $event['Event'];
echo $this->element('section-header', array('section' => 'Transmisión: ' . h($ev['titulo']), 'section_url' => 'Transmisión'));
$copiar = function ($valor, $id) {
	return '<div class="input-group"><input type="text" readonly class="form-control" style="font-family:monospace" id="' . $id . '" value="' . h($valor) . '">'
		. '<div class="input-group-append"><button class="btn btn-default js-copiar" type="button" data-target="' . $id . '"><i class="far fa-copy"></i> Copiar</button></div></div>';
};
?>
<div class="row" style="margin-top:20px">
	<div class="col-lg-7">
		<section class="card mb-4">
			<header class="card-header"><h2 class="card-title"><i class="fas fa-broadcast-tower mr-1"></i> Datos para vMix / OBS</h2></header>
			<div class="card-body">
			<?php if ($ev['fuente'] !== 'cloudflare') { ?>
				<p>Este evento usa un <strong>link externo</strong>. Para transmitir desde vMix u OBS de forma protegida, editá el evento y elegí <em>"Propia y protegida"</em>.</p>
				<?php echo $this->Html->link('Editar evento', array('action' => 'edit', $ev['id']), array('class' => 'btn btn-primary')); ?>
			<?php } elseif (empty($ev['cf_input_uid'])) { ?>
				<?php if (!$cfListo) { ?>
					<div class="alert alert-warning">Falta configurar Cloudflare: cargá <code>CF_ACCOUNT_ID</code> y <code>CF_API_TOKEN</code> en Render → Environment.</div>
				<?php } else { ?>
					<p>La transmisión todavía no está creada.</p>
					<?php echo $this->Form->postLink('Crear transmisión', array('action' => 'transmision', $ev['id']), array('class' => 'btn btn-primary')); ?>
				<?php } ?>
			<?php } else { ?>
				<p class="mb-1">Estado de la señal:
					<?php if ($estado === 'connected') { ?><span class="badge badge-success">RECIBIENDO SEÑAL</span>
					<?php } elseif ($error) { ?><span class="badge badge-warning">No se pudo consultar</span>
					<?php } else { ?><span class="badge badge-secondary">SIN SEÑAL</span><?php } ?>
					<?php echo $this->Html->link('<i class="fas fa-sync"></i> Actualizar', array('action' => 'transmision', $ev['id']), array('escape' => false, 'class' => 'ml-2')); ?>
				</p>
				<?php if ($error) { ?><div class="alert alert-warning"><?php echo h($error); ?></div><?php } ?>
				<h5 class="mt-3">RTMPS (vMix: Streaming → Destination "Custom RTMP Server" · OBS: Servicio "Personalizado")</h5>
				<label class="mb-0">Servidor / URL</label>
				<?php echo $copiar($ev['cf_rtmps_url'], 'rtmpsUrl'); ?>
				<label class="mb-0 mt-2">Clave de transmisión (Stream Key)</label>
				<?php echo $copiar($ev['cf_rtmps_key'], 'rtmpsKey'); ?>
				<?php if (!empty($ev['cf_srt_url'])) { ?>
				<h5 class="mt-4">SRT (opcional, más estable con internet inestable)</h5>
				<label class="mb-0">URL</label><?php echo $copiar($ev['cf_srt_url'], 'srtUrl'); ?>
				<label class="mb-0 mt-2">Stream ID</label><?php echo $copiar($ev['cf_srt_id'], 'srtId'); ?>
				<label class="mb-0 mt-2">Passphrase</label><?php echo $copiar($ev['cf_srt_pass'], 'srtPass'); ?>
				<?php } ?>
				<p class="text-muted small mt-3 mb-0">Sugerido: 1080p o 720p, 30 fps, 4.500–6.000 kbps, keyframe cada 2 segundos. La clave es secreta: con ella cualquiera podría transmitir en tu evento.</p>
			<?php } ?>
			</div>
		</section>
	</div>
	<div class="col-lg-5">
		<section class="card mb-4">
			<header class="card-header"><h2 class="card-title"><i class="fas fa-ticket-alt mr-1"></i> Venta</h2></header>
			<div class="card-body">
				<?php if (!empty($ev['venta_activa']) && (float)$ev['precio'] > 0) { ?>
					<p class="mb-1">Link para compartir (WhatsApp, Instagram):</p>
					<?php echo $copiar($urlCompra, 'urlCompra'); ?>
				<?php } else { ?>
					<p class="mb-0">No está a la venta online. Activá la venta y cargá el precio en <?php echo $this->Html->link('Editar evento', array('action' => 'edit', $ev['id'])); ?>.</p>
				<?php } ?>
				<p class="mt-3 mb-0"><?php echo $this->Html->link('<i class="fas fa-users"></i> Espectadores y accesos', array('controller' => 'Suscriptions', 'action' => 'newsuscription', $ev['id']), array('escape' => false)); ?>
				· <?php echo $this->Html->link('<i class="fas fa-dollar-sign"></i> Ventas', array('controller' => 'Orders', 'action' => 'index', '?' => array('evento' => $ev['id'])), array('escape' => false)); ?>
				· <?php echo $this->Html->link('<i class="fas fa-eye"></i> Ver como espectador', array('action' => 'view', $ev['id']), array('escape' => false)); ?></p>
			</div>
		</section>
		<?php if ($ev['fuente'] === 'cloudflare' && !empty($ev['cf_input_uid'])) { ?>
		<section class="card mb-4">
			<header class="card-header"><h2 class="card-title"><i class="fas fa-film mr-1"></i> Grabaciones</h2></header>
			<div class="card-body">
				<?php if (empty($grabaciones)) { ?>
					<p class="mb-0 text-muted">Cuando transmitas, cada vez que cortes la señal queda una grabación acá.</p>
				<?php } else { ?>
				<table class="table table-sm mb-0">
					<thead><tr><th>Fecha</th><th>Duración</th><th>Estado</th><th></th></tr></thead>
					<tbody>
					<?php foreach ($grabaciones as $g) { ?>
						<tr>
							<td><?php echo $g['creado'] ? date('d/m H:i', strtotime($g['creado'])) : '-'; ?></td>
							<td><?php echo $g['duracion'] > 0 ? gmdate('H:i:s', (int)$g['duracion']) : '-'; ?></td>
							<td><?php echo $g['estado'] === 'ready' ? 'Lista' : ($g['estado'] === 'live-inprogress' ? '<span class="badge badge-danger">EN VIVO</span>' : h($g['estado'])); ?></td>
							<td><?php if ($g['estado'] === 'ready') echo $this->Html->link('<i class="fas fa-download"></i> Bajar MP4', array('action' => 'descarga', $ev['id'], $g['uid']), array('escape' => false)); ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<p class="text-muted small mt-2 mb-0">Para archivar: bajá el MP4, subilo a YouTube y pegá el link en "Partidos anteriores" del evento.</p>
				<?php } ?>
			</div>
		</section>
		<?php } ?>
	</div>
</div>
<script>
document.querySelectorAll('.js-copiar').forEach(function (b) {
	b.addEventListener('click', function () {
		var i = document.getElementById(b.getAttribute('data-target'));
		var ok = function () { var t = b.innerHTML; b.innerHTML = '¡Copiado!'; setTimeout(function () { b.innerHTML = t; }, 1500); };
		if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(i.value).then(ok); }
		else { i.select(); try { document.execCommand('copy'); ok(); } catch (e) {} }
	});
});
</script>
