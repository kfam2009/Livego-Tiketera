<?php
$titulo = $Event['Event']['titulo'];
echo $this->element('section-header', array('section' => 'Accesos generados', 'section_url' => h($titulo)));
$filas = array();
foreach ($accesos as $a) {
	$filas[] = array(
		'nombre' => $a['nombre'],
		'usuario' => $a['usuario'],
		'clave' => $a['clave'],
		'mensaje' => "🎟️ Tu acceso para ver *" . $titulo . "*\n\n👉 Entrá a: " . $urlIngreso . "\n👤 Usuario: " . $a['usuario'] . "\n🔑 Clave: " . $a['clave'] . "\n\nEl acceso es personal: si lo usás en otro dispositivo, se cierra en el anterior.",
	);
}
?>
<div class="row">
	<div class="col-lg-12">
		<section class="card">
			<header class="card-header">
				<h2 class="card-title"><?php echo count($accesos); ?> acceso(s) para <?php echo h($titulo); ?></h2>
				<p class="card-subtitle text-danger"><strong>Guardá o descargá estas claves ahora:</strong> después no se pueden volver a ver (se pueden regenerar desde Usuarios → <i class="fas fa-key"></i>).</p>
			</header>
			<div class="card-body">
				<div class="mb-3">
					<button type="button" class="btn btn-primary" id="btnCsv"><i class="fas fa-download mr-1"></i> Descargar planilla (CSV)</button>
					<button type="button" class="btn btn-default" id="btnCopiarTodo"><i class="far fa-copy mr-1"></i> Copiar todo</button>
					<?php echo $this->Html->link('<i class="fas fa-arrow-left mr-1"></i> Volver a espectadores', array('action' => 'newsuscription', $Event['Event']['id']), array('class' => 'btn btn-default', 'escape' => false)); ?>
				</div>
				<p class="mb-3">Link de ingreso: <code><?php echo h($urlIngreso); ?></code></p>
				<table class="table table-responsive-md table-striped mb-0">
					<thead>
						<tr><th>Nombre</th><th>Usuario</th><th>Clave</th><th>Enviar</th></tr>
					</thead>
					<tbody>
						<?php foreach ($filas as $i => $f) { ?>
							<tr>
								<td><?php echo h($f['nombre']); ?></td>
								<td><code style="font-size:1.1em"><?php echo h($f['usuario']); ?></code></td>
								<td><code style="font-size:1.1em"><?php echo h($f['clave']); ?></code></td>
								<td>
									<a class="btn btn-sm btn-success" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo rawurlencode($f['mensaje']); ?>"><i class="fab fa-whatsapp mr-1"></i> WhatsApp</a>
									<button type="button" class="btn btn-sm btn-default js-copiar" data-i="<?php echo $i; ?>"><i class="far fa-copy mr-1"></i> Copiar</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</section>
	</div>
</div>
<script>
(function () {
	var filas = <?php echo json_encode($filas, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP); ?>;
	var evento = <?php echo json_encode($titulo, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP); ?>;
	var url = <?php echo json_encode($urlIngreso, JSON_HEX_TAG | JSON_HEX_AMP); ?>;
	function copiar(texto, btn) {
		var ok = function () { if (btn) { var t = btn.innerHTML; btn.innerHTML = '¡Copiado!'; setTimeout(function () { btn.innerHTML = t; }, 1500); } };
		if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(texto).then(ok); return; }
		var ta = document.createElement('textarea'); ta.value = texto; document.body.appendChild(ta); ta.select();
		try { document.execCommand('copy'); ok(); } catch (e) {} document.body.removeChild(ta);
	}
	document.querySelectorAll('.js-copiar').forEach(function (b) {
		b.addEventListener('click', function () { copiar(filas[b.getAttribute('data-i')].mensaje, b); });
	});
	document.getElementById('btnCopiarTodo').addEventListener('click', function () {
		copiar(filas.map(function (f) { return f.nombre + '\t' + f.usuario + '\t' + f.clave; }).join('\n'), this);
	});
	document.getElementById('btnCsv').addEventListener('click', function () {
		var q = function (v) { return '"' + String(v).replace(/"/g, '""') + '"'; };
		var lineas = [['Evento', 'Nombre', 'Usuario', 'Clave', 'Link'].map(q).join(';')];
		filas.forEach(function (f) { lineas.push([evento, f.nombre, f.usuario, f.clave, url].map(q).join(';')); });
		var blob = new Blob(['﻿' + lineas.join('\r\n')], { type: 'text/csv;charset=utf-8' });
		var a = document.createElement('a'); a.href = URL.createObjectURL(blob);
		a.download = 'accesos-' + evento.replace(/[^a-z0-9]+/gi, '-').toLowerCase() + '.csv';
		document.body.appendChild(a); a.click(); document.body.removeChild(a);
	});
})();
</script>
