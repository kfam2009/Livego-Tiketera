<header class="header header-nav-menu header-nav-stripe">
	<div class="logo-container">
		<?php echo $this->Html->link(
			$this->Html->image('logo.jpg', array('alt' => 'Eventos', 'width' => 110, 'height' => 35)),
			array('controller' => 'events', 'action' => 'index'),
			array('class' => 'logo', 'escape' => false)
		); ?>
		<button class="btn header-btn-collapse-nav d-lg-none" data-toggle="collapse" data-target=".header-nav">
			<i class="fas fa-bars"></i>
		</button>


	</div>
	<!-- start: header nav menu -->
	<div class="header-nav collapse">
		<div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 header-nav-main-square">
			<nav>
				<ul class="nav nav-pills" id="mainNav">

					<li class="dropdown">
						<?php echo $this->Html->link('Eventos', array('controller' => 'Events', 'action' => 'index'), array('class' => 'nav-link dropdown-toggle')); ?>
						<ul class="dropdown-menu">
							<li>
								<?php echo $this->Html->link('Listar Eventos', array('controller' => 'Events', 'action' => 'index'), array('class' => 'nav-link')); ?>
							</li>
							<li>
								<?php echo $this->Html->link('Agregar Evento', array('controller' => 'Events', 'action' => 'add'), array('class' => 'nav-link')); ?>
							</li>
						</ul>

					</li>
					<li>
						<?php echo $this->Html->link('Ventas', array('controller' => 'Orders', 'action' => 'index'), array('class' => 'nav-link')); ?>
					</li>
					<li>
						<?php echo $this->Html->link('Ver cartelera pública <i class="fas fa-external-link-alt"></i>', '/', array('class' => 'nav-link', 'target' => '_blank', 'escape' => false)); ?>
					</li>
					<li class="dropdown">
						<?php echo $this->Html->link('Usuarios', array('controller' => 'Users', 'action' => 'index'), array('class' => 'nav-link dropdown-toggle')); ?>
						<ul class="dropdown-menu">
							<li>
								<?php echo $this->Html->link('Listar Usuarios', array('controller' => 'Users', 'action' => 'index'), array('class' => 'nav-link')); ?>
							</li>
							<li>
								<?php echo $this->Html->link('Agregar Usuario', array('controller' => 'Users', 'action' => 'add'), array('class' => 'nav-link')); ?>
							</li>
						</ul>

					</li>

				</ul>
			</nav>
		</div>
	</div>

	<!-- start: search & user box -->
	<div class="header-right">
		<span class="separator"></span>

		<div id="userbox" class="userbox">
			<a href="#" data-toggle="dropdown">
				<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
					<span class="name"><?php echo $username_label; ?></span>
					<span class="role"><?php echo $rol_label; ?></span>
				</div>

				<i class="fa custom-caret"></i>
			</a>

			<div class="dropdown-menu">
				<ul class="list-unstyled">
					<li class="divider"></li>

					<li>
						<?php echo $this->Html->link('<i class="fas fa-power-off"></i> Logout</a>', array('controller' => 'users', 'action' => 'logout'), array('role' => 'menuitem', 'tabindex' => '-1', 'escape' => false)); ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- end: search & user box -->
</header>
