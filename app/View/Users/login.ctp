<div class="panel card-sign auth-section">
  <div class="card-title-sign mt-3 text-right">
    <h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> Iniciar sesión</h2>
  </div>
  <div class="card-body">
    <?php echo $this->Form->create('User',
      array(
        'url'=>'login',
    )); ?>
      <div class="form-group mb-3">
        <label>Usuario o email</label>
        <div class="input-group">
          <?php echo $this->Form->input('username',array('type'=>'text', 'autocapitalize'=>'none', 'autocorrect'=>'off', 'label'=>false, 'required'=>true,'class'=>'form-control form-control-lg','div'=>false));?>
          <span class="input-group-append">
            <span class="input-group-text">
              <i class="fas fa-user"></i>
            </span>
          </span>
        </div>
      </div>

      <div class="form-group mb-3">
        <div class="clearfix">
          <label class="float-left">Contraseña</label>

        </div>
        <div class="input-group">
          <?php echo $this->Form->input('password',array('type'=>'password', 'label'=>false, 'required'=>true,'class'=>'form-control form-control-lg','div'=>false));?>
          <span class="input-group-append">
            <span class="input-group-text">
              <i class="fas fa-lock"></i>
            </span>
          </span>
        </div>
        <div class="input-group">
          <?php echo $this->Flash->render(); ?>
        </div>
      </div>

      <div class="row">

        <div class="col-sm-4 text-right">
          <?php
            echo $this->Form->submit('Iniciar sesión',array('div'=>false,'class'=>"btn btn-primary mt-2"));
            echo $this->Form->end();
          ?>
        </div>
      </div>

      <p class="text-center mt-3 mb-0"><a href="<?php echo $this->Html->url('/recuperar-acceso'); ?>">¿Olvidaste tu usuario o clave?</a></p>

      <!-- <span class="mt-3 mb-3 line-thru text-center text-uppercase">
        <span>or</span>
      </span>

      <p class="text-center">Don't have an account yet? <a href="pages-signup.html">Sign Up!</a></p> -->

    </form>
  </div>
</div>
<style type="text/css">
#UserUsername{
  font-size: 16px;
}
.auth-section .card-title-sign{
  background-color: transparent;
}
</style>
