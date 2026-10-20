<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'Espectaculos');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!doctype html>
<html class="fixed has-top-menu">

<head>
	<?php echo $this->Html->charset("UTF-8"); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<!-- Web Fonts  -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

	<?php
	echo $this->Html->meta('icon');

	#echo $this->Html->css('cake.generic');

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');

	echo $this->Html->css(array('../vendor/bootstrap/css/bootstrap', '../vendor/animate/animate', '../vendor/font-awesome/css/fontawesome-all.min', '../vendor/magnific-popup/magnific-popup', '../vendor/bootstrap-datepicker/css/bootstrap-datepicker3', '../vendor/jquery-ui/jquery-ui', '../vendor/jquery-ui/jquery-ui.theme', '../vendor/select2/css/select2', '../vendor/select2-bootstrap-theme/select2-bootstrap.min', '../vendor/bootstrap-multiselect/bootstrap-multiselect', '../vendor/morris/morris', 'theme'));

	echo $this->Html->css('styles');
	echo $this->Html->css('skins/default');
	echo $this->Html->css('custom');

	echo $this->Html->script(['../vendor/modernizr/modernizr', '../vendor/jquery/jquery']);
	?>
</head>

<body>
	<section class="body">

		<!-- start: header -->
		<?php
		if ($rol_label != 'Espectador') {
			echo $this->element('header');
		} else {
			echo $this->element('header-espectador');
		}

		?>
		<!-- end: header -->

		<div class="inner-wrapper">
			<section role="main" class="content-body card-margin">
				<?php echo $this->Session->flash(); ?>
				<?php echo  $this->Flash->render(); ?>
				<?php echo $this->fetch('content'); ?>
			</section>
		</div>

	</section>

	<!-- JS Vendor -->
	<?php echo $this->Html->script(array(

		'../vendor/jquery-browser-mobile/jquery.browser.mobile',
		'../vendor/popper/umd/popper.min',
		'../vendor/bootstrap/js/bootstrap',
		'../vendor/bootstrap-datepicker/js/bootstrap-datepicker',
		'../vendor/common/common',
		'../vendor/nanoscroller/nanoscroller',
		'../vendor/magnific-popup/jquery.magnific-popup',
		'../vendor/jquery-placeholder/jquery-placeholder'
	)); ?>

	<!-- Specific Page Vendor -->
	<?php echo $this->Html->script(array(
		'../vendor/jquery-ui/jquery-ui',
		'../vendor/jqueryui-touch-punch/jqueryui-touch-punch',
		'../vendor/jquery-appear/jquery-appear',
		'../vendor/select2/js/select2',
		'../vendor/bootstrap-multiselect/bootstrap-multiselect',
		'../vendor/jquery.easy-pie-chart/jquery.easy-pie-chart',
		'../vendor/flot/jquery.flot',
		'../vendor/flot.tooltip/flot.tooltip',
		'../vendor/flot/jquery.flot.pie',
		'../vendor/flot/jquery.flot.categories',
		'../vendor/flot/jquery.flot.resize',
		'../vendor/jquery-sparkline/jquery-sparkline',
		'../vendor/raphael/raphael',
		'../vendor/morris/morris',
		'../vendor/gauge/gauge',
		'../vendor/snap.svg/snap.svg',
		'../vendor/liquid-meter/liquid.meter'
	)); ?>

	<!-- Theme Base, Components and Settings -->
	<?php echo $this->Html->script("theme"); ?>

	<!-- Theme Custom -->
	<?php echo $this->Html->script("custom"); ?>

	<!-- Theme Initialization Files -->
	<?php echo $this->Html->script("theme.init"); ?>
	<?php echo $this->Html->script("examples.header.menu"); ?>
	<?php echo $this->Html->script("examples.dashboard"); ?>

</body>
<style>
	.success {
		background: #68e66c;
		text-align: center;
		color: white;
		padding: 20px;
		font-size: 18px;
		margin-top: 20px;
	}
</style>
<script>
	<?php

	if ($rol_label == 'Espectador') {
	?>
		function actualizar() {
			$.ajax({
				url: '<?php echo $this->Html->url(array(
							"controller" => "users",
							"action" => "checksession"
						)); ?>',
				type: 'get',
				async: false,
				success: function(response) {
					json = JSON.parse(response);
					if (json.code == 200) {

					} else {
						location.reload(true);
					}
				}
			});
		}
		//Función para actualizar cada 4 segundos(4000 milisegundos)
		setInterval("actualizar()", 60000);
	<?php
	}
	?>
</script>

</html>
