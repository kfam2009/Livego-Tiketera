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

$cakeDescription = __d('cake_dev', 'Eventos');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!doctype html>
<html class="fixed">
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

		echo $this->Html->css(array('../vendor/bootstrap/css/bootstrap', '../vendor/animate/animate','../vendor/font-awesome/css/fontawesome-all.min','../vendor/magnific-popup/magnific-popup','../vendor/bootstrap-datepicker/css/bootstrap-datepicker3'));

		echo $this->Html->css('styles');
		echo $this->Html->css('skins/default');
		echo $this->Html->css('custom');

		echo $this->Html->script('../vendor/modernizr/modernizr');
	?>
</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<?php echo $this->Html->link(
					$this->Html->image('logo.png',array('alt'=>'Eventos', 'width'=> 180, 'height'=> 54)),
					'/',
					array('class'=> 'logo float-left logo-auth', 'escape'=> false)
				);?>

				<?php echo $this->Flash->render(); ?>
				<?php echo $this->fetch('content'); ?>

				<p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2021. Todos los derechos reservados.</p>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<?php echo $this->Html->script(array(
			'../vendor/jquery/jquery',
			'../vendor/jquery-browser-mobile/jquery.browser.mobile',
			'../vendor/popper/umd/popper.min',
			'../vendor/bootstrap/js/bootstrap',
			'../vendor/bootstrap-datepicker/js/bootstrap-datepicker',
			'../vendor/common/common',
			'../vendor/nanoscroller/nanoscroller',
			'../vendor/magnific-popup/jquery.magnific-popup',
			'../vendor/jquery-placeholder/jquery-placeholder'
		));?>

		<!-- Theme Base, Components and Settings -->
		<?php echo $this->Html->script("theme");?>

		<!-- Theme Custom -->
		<?php echo $this->Html->script("custom");?>

		<!-- Theme Initialization Files -->
		<?php echo $this->Html->script("theme.init");?>

	</body>
</html>
