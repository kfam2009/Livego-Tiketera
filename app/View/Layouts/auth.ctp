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
	<title>Ingresar · LiveGo! Video Streaming</title>

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
	<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<style>
		/* Estética Panel Go */
		:root { --bg:#121317; --bg-2:#16171c; --card:#1d1f25; --card-2:#23262d; --line:#33363e; --line-2:#444955; --text:#f2f3f5; --muted:#a3a7b0; --red:#ed383e; --red-2:#c9252b; --cyan:#4cc9f0; --green:#3ccf7e; color-scheme: dark; }
		html, body { background: var(--bg) !important; }
		body { background: radial-gradient(900px 420px at 90% -10%, rgba(237,56,62,.18), transparent 60%), radial-gradient(700px 360px at -10% 100%, rgba(76,201,240,.1), transparent 60%), var(--bg) !important;
			font-family: 'Inter', 'Segoe UI', Arial, sans-serif; color: var(--text); min-height: 100vh; }
		.body-sign { padding-left: max(16px, env(safe-area-inset-left, 0px)); padding-right: max(16px, env(safe-area-inset-right, 0px)); }
		.body-sign .center-sign { max-width: 440px; }
		.logo-auth { background: #fff; border-radius: 10px; padding: 6px 10px; margin-bottom: 10px; box-shadow: 0 0 0 1px var(--line); }
		.logo-auth img { height: 40px; width: auto; }
		.body-sign .card-sign .card-title-sign .title { background: var(--red) !important; color: #fff; border-radius: 10px 10px 0 0; font-family: 'Barlow Condensed', Arial, sans-serif; letter-spacing: .06em; font-size: 1.05rem; padding: 12px 18px; }
		.body-sign .card-sign .card-body { background: linear-gradient(180deg, var(--card-2), var(--card)) !important; border: 1px solid var(--line) !important; border-top: 3px solid var(--red) !important; border-radius: 14px 0 14px 14px; box-shadow: 0 16px 40px rgba(0,0,0,.45); color: var(--text); }
		.body-sign label { color: var(--text); font-weight: 600; }
		.body-sign .form-control { background: var(--bg-2) !important; border: 1px solid var(--line-2) !important; color: var(--text) !important; border-radius: 10px 0 0 10px; }
		.body-sign .form-control:focus { border-color: var(--cyan) !important; box-shadow: none; }
		.body-sign .input-group-text { background: var(--card-2) !important; border: 1px solid var(--line-2) !important; color: var(--muted) !important; border-radius: 0 10px 10px 0; }
		.body-sign .btn-primary { background: linear-gradient(180deg, #f24b51, var(--red-2)) !important; border: 1px solid var(--red) !important; border-radius: 10px; font-weight: 600; padding: 9px 20px; box-shadow: 0 6px 18px rgba(237,56,62,.3); }
		.body-sign .alert, .body-sign .message, .body-sign #flashMessage { background: rgba(237,56,62,.1); border: 1px solid rgba(237,56,62,.5); color: var(--text); border-radius: 10px; padding: 8px 12px; margin-top: 10px; }
		.body-sign a { color: var(--cyan); }
		.lg-auth-pie { color: var(--muted); font-size: .88rem; text-align: center; margin: 18px 0 24px; }
		.lg-auth-pie a.lg-wa { display: inline-flex; align-items: center; gap: 6px; color: var(--text); border: 1px solid var(--line-2); background: var(--card); padding: 5px 10px; border-radius: 8px; margin: 4px 2px; font-weight: 600; }
		.lg-auth-pie a.lg-wa i { color: #25d366; }
	</style>
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

				<div class="lg-auth-pie">
					<div class="mb-2"><a href="/">&larr; Ver los próximos eventos</a></div>
					¿Problemas para entrar? Escribinos:<br>
					<a class="lg-wa" href="https://wa.me/5492914254860" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> 291 425-4860</a>
					<a class="lg-wa" href="https://wa.me/5492915162426" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> 291 516-2426</a>
					<div class="mt-2">LiveGo! Video Streaming · &copy; <?php echo date('Y'); ?></div>
				</div>
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
