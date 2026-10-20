<?php
echo $this->element('section-header', array('section' => 'Ventas', 'section_url' => 'Ventas'));
$badge = array('aprobado' => 'success', 'pendiente' => 'warning', 'procesando' => 'info', 'rechazado' => 'secondary', 'devuelto' => 'danger');
?>
<div class="row" style="margin-top:20px">
	<div class="col-lg-12">
		<?php if (!$mpListo) { ?><div class="alert alert-warning">Mercado Pago no está configurado: falta <code>MP_ACCESS_TOKEN</code> en Render → Environment.</div><?php } ?>
		<?php if (!$emailListo) { ?><div class="alert alert-info">Los emails no están configurados (faltan <code>BREVO_API_KEY</code> y <code>MAIL_FROM</code>). Los compradores igual ven sus accesos en pantalla.</div><?php } ?>
		<section class="card mb-3">
			<div class="card-body">
				<form method="get" class="form-inline" style="gap:8px">
					<?php echo $this->Form->select('evento', $eventos, array('name' => 'evento', 'value' => $evento, 'empty' => 'Todos los eventos', 'class' => 'form-control', 'id' => 'filtroEvento')); ?>
					<?php echo $this->Form->select('estado', array('aprobado' => 'Aprobadas', 'pendiente' => 'Pendientes', 'rechazado' => 'Rechazadas', 'devuelto' => 'Devueltas'), array('name' => 'estado', 'value' => $estado, 'empty' => 'Todos los estados', 'class' => 'form-control', 'id' => 'filtroEstado')); ?>
					<button class="btn btn-primary" type="submit">Filtrar</button>
				</form>
				<p class="mt-3 mb-0"><strong>Aprobadas:</strong> <?php echo (int)$totales['compras']; ?> compras · <?php echo (int)$totales['entradas']; ?> entradas · $<?php echo number_format((float)$totales['monto'], 0, ',', '.'); ?> <small class="text-muted">(antes de la comisión de Mercado Pago)</small></p>
			</div>
		</section>
		<section class="card">
			<div class="card-body">
				<table class="table table-responsive-md table-striped mb-0">
					<thead><tr><th>#</th><th>Fecha</th><th>Evento</th><th>Comprador</th><th>Cant.</th><th>Monto</th><th>Estado</th><th>Email</th><th></th></tr></thead>
					<tbody>
					<?php foreach ($orders as $o) { $x = $o['Order']; ?>
						<tr>
							<td><?php echo $x['id']; ?></td>
							<td><?php echo date('d/m H:i', strtotime($x['created'])); ?></td>
							<td><?php echo h($o['Event']['titulo']); ?></td>
							<td><?php echo h($x['nombre']); ?><br><small><?php echo h($x['email']); ?><?php echo $x['telefono'] ? ' · ' . h($x['telefono']) : ''; ?></small></td>
							<td><?php echo (int)$x['cantidad']; ?></td>
							<td>$<?php echo number_format((float)$x['monto'], 0, ',', '.'); ?></td>
							<td><span class="badge badge-<?php echo isset($badge[$x['estado']]) ? $badge[$x['estado']] : 'light'; ?>"><?php echo h($x['estado']); ?></span></td>
							<td><?php echo $x['email_enviado'] ? '<i class="fas fa-check text-success" title="Enviado"></i>' : '-'; ?></td>
							<td class="text-nowrap">
								<?php if ($x['estado'] === 'aprobado') {
									echo $this->Html->link('<i class="fas fa-key"></i> Ver', array('action' => 'ver', $x['id']), array('escape' => false)) . ' ';
									echo $this->Form->postLink('<i class="fas fa-envelope"></i> Reenviar', array('action' => 'reenviar', $x['id']), array('escape' => false));
								} elseif (in_array($x['estado'], array('pendiente', 'rechazado'))) {
									echo $this->Form->postLink('<i class="fas fa-sync"></i> Revisar pago', array('action' => 'revisar', $x['id']), array('escape' => false));
								} ?>
							</td>
						</tr>
					<?php } ?>
					<?php if (empty($orders)) { ?><tr><td colspan="9" class="text-muted">Todavía no hay ventas.</td></tr><?php } ?>
					</tbody>
				</table>
				<div class="paging mt-2"><?php echo $this->Paginator->prev('< anterior ', array(), null, array('class' => 'prev disabled')) . $this->Paginator->numbers(array('separator' => ' ')) . $this->Paginator->next(' siguiente >', array(), null, array('class' => 'next disabled')); ?></div>
			</div>
		</section>
	</div>
</div>
