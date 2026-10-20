<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.theme.css" />
<link rel="stylesheet" href="vendor/morris/morris.css" />
<link rel="stylesheet" href="vendor/owl.carousel/assets/owl.carousel.css" />
<link rel="stylesheet" href="vendor/owl.carousel/assets/owl.theme.default.css" />
<?php
echo $this->Html->css(array('../vendor/jquery-ui/jquery-ui', '../vendor/jquery-ui/jquery-ui.theme', '../vendor/morris/morris', '../vendor/owl.carousel/assets/owl.carousel', '../vendor/owl.carousel/assets/owl.theme.default'));
?>

<div class="row">
	<div class="col">
		<section class="card card-info">
			<header class="card-header">

				<h2 class="card-title">Mis Eventos </h2>
			</header>
			<div class="card-body">
				<div class="row">
					<?php foreach ($events as $event) { ?>

						<div class="col-md-3">
							<section class="card mb-4" style="border: 1px solid #CCC;">
								<div class="card-body card-body-nopadding">
									<div class="owl-carousel owl-theme mb-0" data-plugin-carousel data-plugin-options='{ "dots": true, "items": 1 }'>
										<div class="item">
											<?php echo $this->Html->image(livego_imagen_url($event['Events']), array('class' => "img-fluid rounded", 'alt' => $event['Events']['titulo'])); ?>
										</div>

									</div>
									<div class="p-3">
										<h4 class="font-weight-semibold mt-0"><?php echo $event['Events']['titulo']; ?></h4>
										<spam><i class="fas fa-clock mr-1"></i> <?php echo 'Fecha: ' . (date_format(date_create($event['Events']['fecha']), 'd-m-Y')); ?> </spam></br>
										<p>
											<?php echo $event['Events']['descripcion']; ?></p>
									</div>
								</div>
								<div class="card-footer card-footer-btn-group">
									<?php
									if ($event['Events']['fecha']  < date("Y-m-d H:i",time())){
									echo $this->Html->link('<i class="fas fa-user mr-1"></i> Ingresar al Show', array('controller' => 'Events', 'action' => 'view', $event['Events']['id']), array('escape' => false));
									}
									?>
									<a href="#"><i class="fas fa-clock mr-1"></i> Ver Horarios</a>

								</div>
							</section>
						</div>

					<?php
					}
					?>
				</div>
			</div>
		</section>
	</div>
</div>
<?php echo $this->Html->script(array(
	'vendor/jquery-ui/jquery-ui',
	'vendor/jqueryui-touch-punch/jqueryui-touch-punch',
	'vendor/jquery-appear/jquery-appear',
	'vendor/jquery.easy-pie-chart/jquery.easy-pie-chart',
	'vendor/flot/jquery.flot',
	'vendor/raphael/raphael',
	'vendor/morris/morris',
	'vendor/owl.carousel/owl.carousel',
	'examples.widgets'
));

?>
