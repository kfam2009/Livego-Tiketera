<!-- start: page -->
<div class="row">
	<div class="col">
		<section class="card">
			<header class="card-header">
				<div class="card-actions">
					<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
				</div>

				<h2 class="card-title">Modificar contraseña</h2>
			</header>
			<div class="card-body">
        <?php echo $this->Form->create('User',
          array(
            'url'=>'change_password',
            'class'=>'form-horizontal form-bordered'
        )); ?>

          <div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="nombre">Contraseña actual</label>
						<div class="col-lg-6">
              <?php echo $this->Form->input('current_password',array('type'=>'password', 'label'=>false, 'required'=>true,'class'=>'form-control','div'=>false));?>
						</div>
          </div>

          <div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="nombre">Nueva contraseña</label>
						<div class="col-lg-6">
              <?php echo $this->Form->input('new_password',array('type'=>'password', 'label'=>false, 'required'=>true,'class'=>'form-control','div'=>false));?>
						</div>
          </div>

          <div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="nombre">Repita la nueva contraseña</label>
						<div class="col-lg-6">
              <?php echo $this->Form->input('repeat_password',array('type'=>'password', 'label'=>false, 'required'=>true,'class'=>'form-control','div'=>false));?>
						</div>
          </div>

          <?php
            echo $this->Form->submit('Actualizar',array('div'=>false,'class'=>"btn btn-primary"));
            echo $this->Form->end();
          ?>
			</div>
		</section>
	</div>
</div>