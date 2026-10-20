<div class="panel card-sign">
  <div class="card-title-sign mt-3 text-right">
    <h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> Recuperar contraseña</h2>
  </div>
  <div class="card-body">
    <div class="alert alert-info">
      <p class="m-0">Ingrese su email y le enviaremos una nueva contraseña.</p>
    </div>

    <form>
    <?php echo $this->Form->create('User',
      array(
        'url'=>'forgot',
    )); ?>
      <div class="form-group mb-0">
        <div class="input-group">
        <?php echo $this->Form->input('username',array('placeholder'=>"E-mail", 'type'=>'email', 'label'=>false, 'required'=>true,'class'=>'form-control form-control-lg','div'=>false));?>
          <span class="input-group-append">
          <?php
            echo $this->Form->submit('Solicitar',array('div'=>false,'class'=>"btn btn-primary btn-lg"));
            echo $this->Form->end();
          ?>
          </span>
        </div>
      </div>

      <p class="text-center mt-3"><?php echo $this->Html->link('Volver al login', array('action'=>'login'));?></p>
    </form>
  </div>
</div>
