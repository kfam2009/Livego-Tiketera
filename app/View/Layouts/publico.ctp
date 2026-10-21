<!doctype html>
<html lang="es">
<head>
	<?php echo $this->Html->charset('UTF-8'); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="theme-color" content="#121317">
	<title><?php echo h($this->fetch('title') ?: 'LiveGo'); ?> · LiveGo! Video Streaming</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css(array('../vendor/bootstrap/css/bootstrap', '../vendor/font-awesome/css/fontawesome-all.min'));
	echo $this->fetch('meta');
	?>
	<style>
		/* Estética Panel Go: grafito oscuro, rojo de acento, celeste de reloj, verde de OK */
		:root {
			--bg: #121317; --bg-2: #16171c; --card: #1d1f25; --card-2: #23262d;
			--line: #33363e; --line-2: #444955;
			--text: #f2f3f5; --muted: #a3a7b0; --dim: #7c818c;
			--red: #ed383e; --red-2: #c9252b; --cyan: #4cc9f0; --green: #3ccf7e; --warn: #f2b632;
			--display: 'Barlow Condensed', 'Arial Narrow', Arial, sans-serif;
			--body: 'Inter', 'Segoe UI', system-ui, Arial, sans-serif;
			color-scheme: dark;
		}
		html { background: var(--bg); }
		body {
			background:
				radial-gradient(1200px 500px at 85% -10%, rgba(237, 56, 62, .14), transparent 60%),
				radial-gradient(900px 420px at -10% 10%, rgba(76, 201, 240, .08), transparent 60%),
				var(--bg);
			color: var(--text); font-family: var(--body); font-size: 15px; line-height: 1.55;
			min-height: 100vh; display: flex; flex-direction: column;
		}
		a { color: var(--cyan); }
		a:hover { color: #8adcf6; text-decoration: none; }
		h1, h2, h3, h4, .lg-display { font-family: var(--display); font-weight: 700; letter-spacing: .01em; text-wrap: balance; color: var(--text); }
		.text-muted { color: var(--muted) !important; }
		::selection { background: var(--red); color: #fff; }

		/* Barra superior */
		.lg-top { position: sticky; top: 0; z-index: 20; background: rgba(18, 19, 23, .88); backdrop-filter: blur(10px); border-bottom: 1px solid var(--line); }
		.lg-top::after { content: ""; display: block; height: 3px; background: linear-gradient(90deg, var(--red) 0 40%, var(--cyan) 40% 70%, var(--green) 70%); opacity: .9; }
		.lg-top .inner { display: flex; align-items: center; justify-content: space-between; gap: 12px; max-width: 1180px; margin: auto; flex-wrap: wrap;
			padding-block: 10px; padding-left: max(16px, env(safe-area-inset-left, 0px)); padding-right: max(16px, env(safe-area-inset-right, 0px)); }
		.lg-logo { display: inline-flex; align-items: center; background: #fff; border-radius: 10px; padding: 5px 10px; box-shadow: 0 0 0 1px var(--line); }
		.lg-logo img { height: 34px; width: auto; display: block; }
		.lg-nav { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
		.lg-nav a { color: var(--muted); font-weight: 600; font-size: .92rem; padding: 7px 12px; border-radius: 8px; border: 1px solid transparent; }
		.lg-nav a:hover, .lg-nav a.activo { color: var(--text); border-color: var(--line); background: var(--card); }
		.lg-nav a.lg-ingresar { color: #fff; background: var(--red); border-color: var(--red); }
		.lg-nav a.lg-ingresar:hover { background: var(--red-2); border-color: var(--red-2); }

		/* Contenido */
		.lg-main { flex: 1; width: 100%; max-width: 1180px; margin: auto; padding-block: 28px 56px; padding-left: max(16px, env(safe-area-inset-left, 0px)); padding-right: max(16px, env(safe-area-inset-right, 0px)); }
		.lg-eyebrow { display: inline-flex; align-items: center; gap: 8px; font-size: .74rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--cyan); margin-bottom: 6px; }
		.lg-eyebrow::before { content: ""; width: 18px; height: 2px; background: var(--red); }
		.lg-titulo { font-size: clamp(2.1rem, 4.5vw, 3.1rem); line-height: 1; margin: 0 0 8px; }
		.lg-sub { color: var(--muted); max-width: 62ch; margin: 0 0 26px; }

		/* Tarjetas */
		.lg-card { background: linear-gradient(180deg, var(--card-2), var(--card)); border: 1px solid var(--line); border-radius: 16px; overflow: hidden; height: 100%;
			display: flex; flex-direction: column; box-shadow: 0 10px 30px rgba(0,0,0,.35); transition: border-color .2s, transform .2s; }
		a.lg-card-link { color: inherit; display: block; height: 100%; }
		.lg-card.interactiva:hover { border-color: rgba(237, 56, 62, .7); transform: translateY(-2px); }
		.lg-media { position: relative; background: #0d0e11; }
		.lg-media img { width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block; }
		.lg-media::after { content: ""; position: absolute; inset: auto 0 0 0; height: 45%; background: linear-gradient(transparent, rgba(18,19,23,.85)); pointer-events: none; }
		.lg-media .lg-badges { position: absolute; left: 12px; top: 12px; display: flex; gap: 6px; z-index: 1; }
		.lg-card .body { padding: 16px 18px 18px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
		.lg-card h3 { font-size: 1.65rem; margin: 0; line-height: 1.05; }
		.lg-chip { display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; font-weight: 600; color: var(--cyan); background: rgba(76, 201, 240, .1); border: 1px solid rgba(76, 201, 240, .35); padding: 3px 10px; border-radius: 999px; width: fit-content; }
		.lg-vivo { display: inline-flex; align-items: center; gap: 6px; background: var(--red); color: #fff; font-size: .72rem; font-weight: 800; padding: 3px 9px; border-radius: 6px; letter-spacing: .1em; }
		.lg-vivo::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: #fff; animation: lgpulse 1.4s infinite; }
		.lg-pronto { display: inline-flex; background: rgba(18,19,23,.8); color: var(--text); border: 1px solid var(--line-2); font-size: .72rem; font-weight: 700; padding: 3px 9px; border-radius: 6px; letter-spacing: .08em; text-transform: uppercase; }
		@keyframes lgpulse { 50% { opacity: .25; } }
		@media (prefers-reduced-motion: reduce) { .lg-vivo::before { animation: none; } .lg-card { transition: none; } }
		.lg-precio { font-family: var(--display); font-size: 1.9rem; font-weight: 700; color: var(--text); line-height: 1; font-variant-numeric: tabular-nums; }
		.lg-precio small { font-family: var(--body); font-size: .8rem; color: var(--muted); font-weight: 500; }
		.lg-card .acciones { margin-top: auto; display: flex; gap: 10px; align-items: center; justify-content: space-between; flex-wrap: wrap; padding-top: 12px; border-top: 1px dashed var(--line); }
		.lg-fecha { color: var(--muted); font-size: .9rem; }

		/* Botones */
		.btn { border-radius: 10px; font-weight: 600; }
		.btn-lg-comprar, .btn-primary { background: linear-gradient(180deg, #f24b51, var(--red-2)); border: 1px solid var(--red); color: #fff; box-shadow: 0 6px 18px rgba(237, 56, 62, .28); }
		.btn-lg-comprar:hover, .btn-primary:hover, .btn-primary:focus { background: linear-gradient(180deg, var(--red), #b31f24); border-color: var(--red); color: #fff; }
		.btn-success { background: #1fa855; border-color: #1fa855; }
		.btn-secondary, .btn-default { background: var(--card-2); border: 1px solid var(--line-2); color: var(--text); }
		.btn:focus-visible, a:focus-visible, .form-control:focus { outline: 2px solid var(--cyan); outline-offset: 2px; box-shadow: none; }

		/* Formularios, alertas, tablas */
		.form-control, .custom-select { background: var(--bg-2); border: 1px solid var(--line-2); color: var(--text); border-radius: 10px; height: auto; padding: 10px 12px; }
		.form-control:focus { background: var(--bg-2); color: var(--text); border-color: var(--cyan); }
		label { font-weight: 600; font-size: .92rem; color: var(--text); }
		.alert { border-radius: 12px; border: 1px solid var(--line); background: var(--card); color: var(--text); }
		.alert-danger { border-color: rgba(237, 56, 62, .6); background: rgba(237, 56, 62, .1); }
		.alert-warning { border-color: rgba(242, 182, 50, .6); background: rgba(242, 182, 50, .1); }
		.alert-success { border-color: rgba(60, 207, 126, .6); background: rgba(60, 207, 126, .1); }
		.alert-light, .alert-secondary { color: var(--muted); }
		.table { color: var(--text); }
		.table-bordered, .table-bordered td, .table-bordered th { border-color: var(--line); }
		.table thead th { background: var(--bg-2); color: var(--muted); font-size: .8rem; letter-spacing: .06em; text-transform: uppercase; border-bottom-color: var(--line); }
		.lg-codigo { font-family: ui-monospace, Consolas, monospace; font-size: 1.2rem; color: var(--green); background: rgba(60, 207, 126, .1); border: 1px solid rgba(60, 207, 126, .35); padding: 2px 10px; border-radius: 8px; }

		/* Pie */
		.lg-foot { border-top: 1px solid var(--line); background: var(--bg-2); }
		.lg-foot .inner { max-width: 1180px; margin: auto; display: flex; flex-wrap: wrap; gap: 18px 32px; justify-content: space-between; align-items: center;
			padding-block: 22px; padding-left: max(16px, env(safe-area-inset-left, 0px)); padding-right: max(16px, env(safe-area-inset-right, 0px)); color: var(--muted); font-size: .88rem; }
		.lg-foot strong { color: var(--text); font-family: var(--display); font-size: 1.15rem; letter-spacing: .02em; }
		.lg-contactos { display: flex; flex-wrap: wrap; gap: 8px; }
		.lg-wa { display: inline-flex; align-items: center; gap: 8px; color: var(--text); border: 1px solid var(--line-2); background: var(--card); padding: 7px 12px; border-radius: 10px; font-weight: 600; font-variant-numeric: tabular-nums; }
		.lg-wa i { color: #25d366; font-size: 1.1rem; }
		.lg-wa:hover { color: var(--text); border-color: #25d366; }
		.lg-wa-flotante { position: fixed; right: max(16px, env(safe-area-inset-right, 0px)); bottom: calc(16px + env(safe-area-inset-bottom, 0px)); z-index: 30;
			width: 54px; height: 54px; border-radius: 50%; background: #25d366; color: #fff; display: grid; place-items: center; font-size: 1.7rem; box-shadow: 0 8px 24px rgba(0,0,0,.45); }
		.lg-wa-flotante:hover { color: #fff; transform: scale(1.05); }
	</style>
</head>
<body>
	<?php
	$aqui = $this->request->here;
	$contactos = array('2914254860' => '291 425-4860', '2915162426' => '291 516-2426');
	?>
	<header class="lg-top">
		<div class="inner">
			<?php echo $this->Html->link($this->Html->image('logo.png', array('alt' => 'LiveGo! Video Streaming')), '/', array('escape' => false, 'class' => 'lg-logo')); ?>
			<nav class="lg-nav" aria-label="Principal">
				<?php echo $this->Html->link('Próximos eventos', '/', array('class' => ($aqui === '/' ? 'activo' : ''))); ?>
				<?php echo $this->Html->link('Eventos anteriores', '/eventos-anteriores', array('class' => (strpos($aqui, 'anteriores') !== false ? 'activo' : ''))); ?>
				<?php echo $this->Html->link('<i class="fas fa-user mr-1"></i> Ingresar', '/users/login', array('class' => 'lg-ingresar', 'escape' => false)); ?>
			</nav>
		</div>
	</header>
	<main class="lg-main">
		<?php echo $this->Flash->render(); ?>
		<?php echo $this->fetch('content'); ?>
	</main>
	<footer class="lg-foot">
		<div class="inner">
			<div>
				<strong>LiveGo! Video Streaming</strong><br>
				Transmisiones en vivo · © <?php echo date('Y'); ?>
			</div>
			<div class="lg-contactos">
				<?php foreach ($contactos as $num => $txt) { ?>
					<a class="lg-wa" href="https://wa.me/549<?php echo $num; ?>" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> <?php echo $txt; ?></a>
				<?php } ?>
			</div>
		</div>
	</footer>
	<a class="lg-wa-flotante" href="https://wa.me/5492914254860?text=<?php echo rawurlencode('Hola! Quería consultar por los eventos de LiveGo'); ?>" target="_blank" rel="noopener" aria-label="Escribinos por WhatsApp"><i class="fab fa-whatsapp"></i></a>
</body>
</html>
