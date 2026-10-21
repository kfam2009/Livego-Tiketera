<?php $ev = $event['Event']; $max = min(10, $quedan); ?>
<div class="row">
	<div class="col-lg-7 mb-4">
		<div class="lg-card">
			<div class="lg-media"><?php echo $this->Html->image(livego_imagen_url($ev), array('alt' => h($ev['titulo']))); ?></div>
			<div class="body">
				<div class="lg-eyebrow mb-0">Evento</div>
				<h1 class="lg-titulo mb-0" style="font-size:2.4rem"><?php echo h($ev['titulo']); ?></h1>
				<span class="lg-chip"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y · H:i', strtotime($ev['fecha'])); ?> hs</span>
				<?php if (!empty($ev['descripcion'])) { ?><p class="mb-0"><?php echo nl2br(h($ev['descripcion'])); ?></p><?php } ?>
				<?php if (!empty($ev['horario'])) { ?><p class="mb-0 text-muted"><?php echo nl2br(h($ev['horario'])); ?></p><?php } ?>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<div class="lg-card">
			<div class="body">
				<h2 class="mb-0"><i class="fas fa-ticket-alt mr-1" style="color:var(--red)"></i> Comprar entrada</h2>
				<div class="lg-precio">$<?php echo number_format((float)$ev['precio'], 0, ',', '.'); ?> <small class="text-muted" style="font-size:1rem">por entrada</small></div>
				<?php if (!empty($errores)) { ?>
					<div class="alert alert-danger mb-0"><?php echo implode('<br>', array_map('h', $errores)); ?></div>
				<?php } ?>
				<?php if (!$ventaLista) { ?>
					<div class="alert alert-warning mb-0">La venta online todavía no está habilitada.</div>
				<?php } elseif ($quedan < 1) { ?>
					<div class="alert alert-secondary mb-0">Entradas agotadas.</div>
				<?php } else { ?>
				<?php echo $this->Form->create('Compra', array('url' => '/comprar/' . $ev['id'], 'novalidate' => false)); ?>
					<div class="form-group">
						<label for="CompraEmail">Email <small class="text-muted">(ahí te llegan el usuario y la clave)</small></label>
						<?php echo $this->Form->input('email', array('type' => 'email', 'label' => false, 'div' => false, 'class' => 'form-control', 'required' => true, 'autocomplete' => 'email', 'id' => 'CompraEmail')); ?>
					</div>
					<div class="form-group">
						<label for="CompraNombre">Nombre y apellido</label>
						<?php echo $this->Form->input('nombre', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'form-control', 'autocomplete' => 'name', 'maxlength' => 120, 'id' => 'CompraNombre')); ?>
					</div>
					<div class="form-group">
						<label for="CompraTelefono">WhatsApp <small class="text-muted">(opcional)</small></label>
						<?php echo $this->Form->input('telefono', array('type' => 'tel', 'label' => false, 'div' => false, 'class' => 'form-control', 'autocomplete' => 'tel', 'maxlength' => 30, 'id' => 'CompraTelefono')); ?>
					</div>
					<div class="form-group">
						<label for="CompraCantidad">Cantidad de entradas</label>
						<?php echo $this->Form->input('cantidad', array('type' => 'select', 'options' => array_combine(range(1, $max), range(1, $max)), 'label' => false, 'div' => false, 'class' => 'form-control', 'id' => 'CompraCantidad')); ?>
						<small class="form-text text-muted">Cada entrada es un usuario distinto (uno por dispositivo).</small>
					</div>
					<button type="submit" class="btn btn-lg-comprar btn-block btn-lg"><i class="fas fa-lock mr-1"></i> Pagar con Mercado Pago</button>
					<p class="text-muted small mt-2 mb-0">Pagás en Mercado Pago (tarjeta, débito, dinero en cuenta o efectivo). Apenas se aprueba el pago, te mostramos tu usuario y clave y te los mandamos por email.</p>
				<?php echo $this->Form->end(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
