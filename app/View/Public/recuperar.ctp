<div class="panel card-sign auth-section">
  <div class="card-title-sign mt-3 text-right">
    <h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-key mr-1"></i> Recuperar acceso</h2>
  </div>
  <div class="card-body">
    <?php if (!empty($enviado)): ?>
      <div class="alert alert-success">
        <p class="m-0">Si <strong><?php echo h($email); ?></strong> tiene compras, te reenviamos tu usuario y clave a ese mail.
        Puede tardar unos minutos: <strong>revisá también la carpeta de spam o promociones</strong>.</p>
      </div>
      <p class="text-center mt-3"><a href="<?php echo $this->Html->url('/users/login'); ?>">Volver a ingresar</a></p>
    <?php else: ?>
      <p>Escribí el <strong>mail con el que compraste</strong> y te reenviamos tu usuario y clave.</p>
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo h($error); ?></div><?php endif; ?>
      <?php echo $this->Form->create('Recuperar', array('url' => '/recuperar-acceso')); ?>
        <div class="form-group mb-3">
          <?php echo $this->Form->input('email', array('type' => 'email', 'label' => false, 'required' => true, 'placeholder' => 'tu@mail.com', 'class' => 'form-control form-control-lg', 'div' => false, 'autocomplete' => 'email')); ?>
        </div>
        <?php echo $this->Form->submit('Reenviarme el acceso', array('div' => false, 'class' => 'btn btn-primary btn-lg btn-block')); ?>
      <?php echo $this->Form->end(); ?>
      <p class="text-center mt-3"><a href="<?php echo $this->Html->url('/users/login'); ?>">Volver a ingresar</a></p>
      <p class="text-center text-muted" style="font-size:13px">¿Te dieron el acceso a mano y no compraste online? Escribinos por WhatsApp.</p>
    <?php endif; ?>
  </div>
</div>
<style type="text/css">.auth-section .card-title-sign{background-color:transparent}</style>
