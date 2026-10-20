<?php
$o = $order['Order'];
echo $this->element('section-header', array('section' => 'Compra #' . $o['id'], 'section_url' => 'Ventas'));
$texto = "🎟️ Tu acceso para ver " . $order['Event']['titulo'] . "\n👉 " . $urlIngreso;
foreach ($accesos as $a) { $texto .= "\n👤 Usuario: " . $a['usuario'] . "  🔑 Clave: " . $a['clave']; }
$tel = preg_replace('/[^0-9]/', '', (string)$o['telefono']);
?>
<div class="row" style="margin-top:20px"><div class="col-lg-8"><section class="card"><div class="card-body">
	<p><strong><?php echo h($o['nombre']); ?></strong> · <?php echo h($o['email']); ?> · <?php echo h($o['telefono']); ?></p>
	<p><?php echo h($order['Event']['titulo']); ?> · <?php echo (int)$o['cantidad']; ?> entrada(s) · $<?php echo number_format((float)$o['monto'], 0, ',', '.'); ?> · pago MP <?php echo h($o['mp_payment_id']); ?></p>
	<table class="table table-bordered"><thead><tr><th>Entrada</th><th>Usuario</th><th>Clave</th></tr></thead><tbody>
	<?php foreach ($accesos as $a) { ?><tr><td><?php echo h($a['nombre']); ?></td><td><code><?php echo h($a['usuario']); ?></code></td><td><code><?php echo h($a['clave']); ?></code></td></tr><?php } ?>
	</tbody></table>
	<a class="btn btn-success" target="_blank" rel="noopener" href="https://wa.me/<?php echo h($tel); ?>?text=<?php echo rawurlencode($texto); ?>"><i class="fab fa-whatsapp mr-1"></i> Mandar por WhatsApp</a>
	<?php echo $this->Html->link('Volver a ventas', array('action' => 'index'), array('class' => 'btn btn-default')); ?>
	<p class="text-muted small mt-3 mb-0">Página del comprador: <?php echo h($urlGracias); ?></p>
</div></section></div></div>
