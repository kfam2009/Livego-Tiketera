<!doctype html>
<html lang="es">
<head>
	<?php echo $this->Html->charset('UTF-8'); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo h($this->fetch('title') ?: 'LiveGo'); ?> · LiveGo</title>
	<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css(array('../vendor/bootstrap/css/bootstrap', '../vendor/font-awesome/css/fontawesome-all.min'));
	echo $this->fetch('meta');
	?>
	<style>
		:root { --azul: #0b5fa5; --azul-osc: #08243f; --rojo: #e2232a; --fondo: #f3f5f8; --texto: #1b2430; --gris: #5b6675; }
		body { background: var(--fondo); color: var(--texto); font-family: 'Open Sans', Arial, sans-serif; }
		.lg-top { background: #fff; border-bottom: 3px solid var(--azul); }
		.lg-top .inner { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 16px; max-width: 1140px; margin: auto; flex-wrap: wrap; }
		.lg-top img { height: 40px; width: auto; }
		.lg-top nav a { color: var(--texto); font-weight: 600; margin-left: 16px; }
		.lg-top nav a.btn { color: #fff; }
		h1, h2, h3, .lg-display { font-family: 'Barlow Condensed', 'Arial Narrow', Arial, sans-serif; letter-spacing: .01em; }
		.lg-main { max-width: 1140px; margin: auto; padding: 24px 16px 48px; }
		.lg-card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(8,36,63,.12); overflow: hidden; height: 100%; display: flex; flex-direction: column; }
		.lg-card img { width: 100%; aspect-ratio: 16/9; object-fit: cover; background: #dde3ea; }
		.lg-card .body { padding: 14px 16px 16px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
		.lg-card h3 { font-size: 1.5rem; margin: 0; line-height: 1.1; }
		.lg-fecha { color: var(--gris); font-size: .92rem; }
		.lg-precio { font-family: 'Barlow Condensed', Arial, sans-serif; font-size: 1.6rem; font-weight: 700; color: var(--azul-osc); }
		.lg-card .acciones { margin-top: auto; display: flex; gap: 8px; align-items: center; justify-content: space-between; flex-wrap: wrap; }
		.btn-lg-comprar { background: var(--rojo); border-color: var(--rojo); color: #fff; font-weight: 700; }
		.btn-lg-comprar:hover { background: #c41c22; color: #fff; }
		.lg-vivo { display: inline-block; background: var(--rojo); color: #fff; font-size: .75rem; font-weight: 700; padding: 2px 8px; border-radius: 3px; letter-spacing: .06em; }
		.lg-foot { text-align: center; color: var(--gris); font-size: .85rem; padding: 24px 16px; }
		.lg-codigo { font-family: Menlo, Consolas, monospace; font-size: 1.25rem; background: #eef3f8; padding: 2px 8px; border-radius: 4px; }
	</style>
</head>
<body>
	<header class="lg-top">
		<div class="inner">
			<?php echo $this->Html->link($this->Html->image('logo.png', array('alt' => 'LiveGo')), '/', array('escape' => false)); ?>
			<nav>
				<?php echo $this->Html->link('Próximos partidos', '/'); ?>
				<?php echo $this->Html->link('Partidos anteriores', '/partidos-anteriores'); ?>
				<?php echo $this->Html->link('<i class="fas fa-user mr-1"></i> Ingresar', '/users/login', array('class' => 'btn btn-sm btn-primary', 'escape' => false)); ?>
			</nav>
		</div>
	</header>
	<main class="lg-main">
		<?php echo $this->Flash->render(); ?>
		<?php echo $this->fetch('content'); ?>
	</main>
	<footer class="lg-foot">LiveGo · Transmisiones en vivo</footer>
</body>
</html>
