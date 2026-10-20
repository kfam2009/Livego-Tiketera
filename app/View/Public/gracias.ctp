<?php
$o = $order['Order'];
$ev = $order['Event'];
$estado = $o['estado'];
?>
<div class="row justify-content-center">
	<div class="col-lg-8">
		<div class="lg-card"><div class="body">
		<?php if ($estado === 'aprobado') { ?>
			<h1 class="mb-0"><i class="fas fa-check-circle text-success mr-1"></i> ¡Listo! Ya tenés tu entrada</h1>
			<p class="mb-1">Para <strong><?php echo h($ev['titulo']); ?></strong> · <?php echo date('d/m/Y H:i', strtotime($ev['fecha'])); ?> hs</p>
			<p class="text-danger mb-2"><strong>Guardá estos datos.</strong> <?php echo $emailListo ? 'También te los mandamos a ' . h($o['email']) . '.' : ''; ?></p>
			<table class="table table-bordered mb-2">
				<thead><tr><th>Entrada</th><th>Usuario</th><th>Clave</th></tr></thead>
				<tbody>
				<?php foreach ($accesos as $a) { ?>
					<tr><td><?php echo h($a['nombre']); ?></td><td><span class="lg-codigo"><?php echo h($a['usuario']); ?></span></td><td><span class="lg-codigo"><?php echo h($a['clave']); ?></span></td></tr>
				<?php } ?>
				</tbody>
			</table>
			<?php
			$texto = "🎟️ Mi acceso para ver " . $ev['titulo'] . "\n👉 " . $urlIngreso;
			foreach ($accesos as $a) { $texto .= "\n👤 Usuario: " . $a['usuario'] . "  🔑 Clave: " . $a['clave']; }
			?>
			<div class="d-flex flex-wrap" style="gap:8px">
				<a class="btn btn-primary btn-lg" href="<?php echo h($urlIngreso); ?>"><i class="fas fa-play mr-1"></i> Entrar a ver</a>
				<a class="btn btn-success btn-lg" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo rawurlencode($texto); ?>"><i class="fab fa-whatsapp mr-1"></i> Guardármelo en WhatsApp</a>
			</div>
			<p class="text-muted small mt-3 mb-0">Cada acceso es personal: si se usa en otro dispositivo, se cierra en el anterior.</p>
		<?php } elseif ($estado === 'pendiente' || $estado === 'procesando') { ?>
			<h1 class="mb-0"><i class="fas fa-hourglass-half mr-1"></i> Esperando la confirmación del pago</h1>
			<p>Si pagaste con tarjeta o dinero en cuenta, esto tarda unos segundos: la página se actualiza sola.</p>
			<p>Si elegiste pagar en efectivo (Rapipago / Pago Fácil), cuando se acredite te mandamos el usuario y la clave a <strong><?php echo h($o['email']); ?></strong>.</p>
			<p class="text-muted small mb-0">Guardá esta página: también vas a poder ver tus datos acá.</p>
			<script>
			(function () {
				var n = 0;
				var t = setInterval(function () {
					if (++n > 40) { clearInterval(t); return; }
					fetch(<?php echo json_encode(Router::url('/comprar/estado/' . $o['token'])); ?>, { cache: 'no-store' })
						.then(function (r) { return r.json(); })
						.then(function (j) { if (j.estado !== 'pendiente' && j.estado !== 'procesando') { location.reload(); } })
						.catch(function () {});
				}, 5000);
			})();
			</script>
		<?php } elseif ($estado === 'devuelto') { ?>
			<h1 class="mb-0">Pago devuelto</h1>
			<p class="mb-0">Este pago fue devuelto o desconocido, así que la entrada quedó sin efecto.</p>
		<?php } else { ?>
			<h1 class="mb-0"><i class="fas fa-times-circle text-danger mr-1"></i> El pago no se completó</h1>
			<p>Mercado Pago rechazó o canceló el pago. No se te cobró nada.</p>
			<?php echo $this->Html->link('Intentar de nuevo', '/comprar/' . $ev['id'], array('class' => 'btn btn-lg-comprar')); ?>
		<?php } ?>
		</div></div>
	</div>
</div>
