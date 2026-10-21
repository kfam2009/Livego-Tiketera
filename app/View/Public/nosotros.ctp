<?php
$this->assign('title', 'Quiénes somos');
$anios = (int)date('Y') - 2018;
$wa = 'https://wa.me/5492914254860?text=' . rawurlencode('Hola LiveGo! Tengo un evento y quiero transmitirlo con entrada online.');
?>
<style>
	.qs-hero { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(0, 1fr); gap: 28px; align-items: center; margin-bottom: 44px; }
	@media (max-width: 860px) { .qs-hero { grid-template-columns: 1fr; } }
	.qs-hero h1 { font-size: clamp(2.6rem, 6vw, 4.4rem); line-height: .95; margin: 0 0 16px; }
	.qs-hero h1 em { font-style: normal; color: var(--red); }
	.qs-lead { font-size: 1.12rem; color: var(--muted); max-width: 58ch; margin: 0 0 22px; }
	.qs-lead strong { color: var(--text); }
	.qs-cta { display: flex; gap: 10px; flex-wrap: wrap; }
	.qs-cta .btn { padding: 11px 18px; }
	.qs-sello { position: relative; border: 1px solid var(--line); border-radius: 20px; padding: 28px; background: linear-gradient(160deg, #26292f, #17181d); overflow: hidden; }
	.qs-sello::before { content: ""; position: absolute; inset: 0 0 auto 0; height: 4px; background: linear-gradient(90deg, var(--red) 0 40%, var(--cyan) 40% 70%, var(--green) 70%); }
	.qs-sello .num { font-family: var(--display); font-weight: 700; font-size: clamp(5rem, 12vw, 7.5rem); line-height: .85; color: var(--text); font-variant-numeric: tabular-nums; }
	.qs-sello .num span { color: var(--red); }
	.qs-sello .rot { font-family: var(--display); font-size: 1.5rem; letter-spacing: .04em; margin-top: 6px; }
	.qs-sello p { color: var(--muted); margin: 10px 0 0; }
	.qs-rec { position: absolute; right: 22px; top: 22px; display: inline-flex; align-items: center; gap: 6px; font-size: .72rem; font-weight: 800; letter-spacing: .12em; color: var(--red); }
	.qs-rec::before { content: ""; width: 9px; height: 9px; border-radius: 50%; background: var(--red); animation: lgpulse 1.4s infinite; }

	.qs-seccion { margin: 0 0 48px; }
	.qs-seccion > h2 { font-size: clamp(1.9rem, 3.6vw, 2.6rem); margin: 0 0 6px; }
	.qs-seccion > p { color: var(--muted); max-width: 64ch; margin: 0 0 22px; }
	.qs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 16px; }
	.qs-item { border: 1px solid var(--line); border-radius: 16px; padding: 20px; background: linear-gradient(180deg, var(--card-2), var(--card)); transition: border-color .2s, transform .2s; }
	.qs-item:hover { border-color: var(--line-2); transform: translateY(-2px); }
	.qs-ico { width: 46px; height: 46px; border-radius: 12px; display: grid; place-items: center; font-size: 1.25rem; margin-bottom: 14px; border: 1px solid; }
	.qs-ico.r { color: var(--red); background: rgba(237,56,62,.1); border-color: rgba(237,56,62,.4); }
	.qs-ico.c { color: var(--cyan); background: rgba(76,201,240,.1); border-color: rgba(76,201,240,.4); }
	.qs-ico.g { color: var(--green); background: rgba(60,207,126,.1); border-color: rgba(60,207,126,.4); }
	.qs-ico.y { color: var(--warn); background: rgba(242,182,50,.1); border-color: rgba(242,182,50,.4); }
	.qs-item h3 { font-size: 1.4rem; margin: 0 0 6px; }
	.qs-item p { color: var(--muted); margin: 0; font-size: .95rem; }

	.qs-ticketera { position: relative; border-radius: 22px; padding: clamp(22px, 4vw, 40px); border: 1px solid rgba(237,56,62,.55);
		background: radial-gradient(700px 300px at 100% 0%, rgba(237,56,62,.22), transparent 70%), linear-gradient(180deg, #23262d, #17181d);
		box-shadow: 0 20px 50px rgba(0,0,0,.4); overflow: hidden; }
	.qs-nuevo { display: inline-block; background: var(--red); color: #fff; font-weight: 800; font-size: .72rem; letter-spacing: .14em; padding: 4px 10px; border-radius: 6px; margin-bottom: 12px; }
	.qs-ticketera h2 { font-size: clamp(2rem, 4.4vw, 3.2rem); line-height: 1; margin: 0 0 12px; }
	.qs-ticketera h2 em { font-style: normal; color: var(--cyan); }
	.qs-ticketera > p { color: var(--muted); max-width: 62ch; font-size: 1.05rem; margin: 0 0 22px; }
	.qs-lista { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px 22px; list-style: none; padding: 0; margin: 0 0 24px; }
	.qs-lista li { display: flex; gap: 10px; align-items: flex-start; }
	.qs-lista i { color: var(--green); margin-top: 4px; }
	.qs-lista strong { display: block; color: var(--text); }
	.qs-lista span { color: var(--muted); font-size: .92rem; }

	.qs-pasos { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; counter-reset: paso; }
	.qs-paso { position: relative; border-left: 3px solid var(--red); padding: 4px 0 4px 18px; }
	.qs-paso::before { counter-increment: paso; content: "0" counter(paso); font-family: var(--display); font-weight: 700; font-size: 2.4rem; line-height: 1; color: var(--line-2); display: block; margin-bottom: 4px; }
	.qs-paso h3 { font-size: 1.35rem; margin: 0 0 4px; }
	.qs-paso p { color: var(--muted); margin: 0; font-size: .95rem; }

	.qs-final { text-align: center; border: 1px dashed var(--line-2); border-radius: 20px; padding: clamp(24px, 4vw, 40px) 18px; background: var(--bg-2); }
	.qs-final h2 { font-size: clamp(2rem, 4vw, 2.8rem); margin: 0 0 8px; }
	.qs-final p { color: var(--muted); margin: 0 auto 20px; max-width: 52ch; }
	.qs-final .qs-cta { justify-content: center; }
	.btn-wa { background: #25d366; border: 1px solid #25d366; color: #0b2b17; font-weight: 700; }
	.btn-wa:hover { background: #1fbe5b; color: #0b2b17; }
	@media (prefers-reduced-motion: reduce) { .qs-item { transition: none; } .qs-rec::before { animation: none; } }
</style>

<section class="qs-hero">
	<div>
		<div class="lg-eyebrow">Quiénes somos</div>
		<h1>Ponemos tus eventos <em>en vivo</em>. Desde 2018.</h1>
		<p class="qs-lead"><strong>LiveGo! Video Streaming</strong> nació para que lo que pasa en la cancha, el escenario o el salón llegue a todos, esté donde esté. Desde 2018 hacemos transmisiones con un <strong>equipo técnico propio</strong>, armamos cámaras, gráficos y señal, y nos ocupamos de que salga al aire bien de punta a punta.</p>
		<div class="qs-cta">
			<a class="btn btn-lg-comprar" href="<?php echo h($wa); ?>" target="_blank" rel="noopener"><i class="fab fa-whatsapp mr-1"></i> Contanos tu evento</a>
			<?php echo $this->Html->link('Ver próximos eventos', '/', array('class' => 'btn btn-secondary')); ?>
		</div>
	</div>
	<div class="qs-sello" aria-label="Años transmitiendo">
		<span class="qs-rec">REC</span>
		<div class="num"><?php echo $anios; ?><span>+</span></div>
		<div class="rot">años transmitiendo en vivo</div>
		<p>Mucho rodaje, muchas transmisiones y la tranquilidad de un equipo que sabe resolver cuando el directo aprieta.</p>
	</div>
</section>

<section class="qs-seccion">
	<div class="lg-eyebrow">Lo que hacemos</div>
	<h2>Producción completa, sin vueltas</h2>
	<p>Llegamos, armamos y salimos al aire. Vos te ocupás del evento; nosotros, de que se vea y se escuche como corresponde.</p>
	<div class="qs-grid">
		<div class="qs-item">
			<div class="qs-ico r"><i class="fas fa-video"></i></div>
			<h3>Transmisiones en vivo</h3>
			<p>Producción multicámara con dirección en vivo para partidos, shows y eventos de todo tipo.</p>
		</div>
		<div class="qs-item">
			<div class="qs-ico c"><i class="fas fa-sliders-h"></i></div>
			<h3>Gráficos y tableros</h3>
			<p>Marcadores, reloj, zócalos y repeticiones en pantalla con nuestras herramientas Panel Go para vMix y OBS.</p>
		</div>
		<div class="qs-item">
			<div class="qs-ico g"><i class="fas fa-broadcast-tower"></i></div>
			<h3>Señal a donde la necesites</h3>
			<p>YouTube, Facebook, Twitch o una transmisión privada y protegida para tu público.</p>
		</div>
		<div class="qs-item">
			<div class="qs-ico y"><i class="fas fa-users-cog"></i></div>
			<h3>Equipo técnico</h3>
			<p>Gente con experiencia en el lugar y del otro lado de la señal, para que el directo no dependa de la suerte.</p>
		</div>
	</div>
</section>

<section class="qs-seccion qs-ticketera">
	<span class="qs-nuevo">NUEVO</span>
	<h2>Tu producción, ahora <em>con entrada</em>.</h2>
	<p>Sumamos la <strong style="color:var(--text)">ticketera LiveGo</strong>: un plus para que tus transmisiones también generen ingresos. Tu público compra online, recibe su acceso al instante y mira el evento en vivo desde cualquier pantalla. Vos te enfocás en el show.</p>
	<ul class="qs-lista">
		<li><i class="fas fa-check-circle"></i><div><strong>Cobro con Mercado Pago</strong><span>Tarjeta, débito, dinero en cuenta o efectivo.</span></div></li>
		<li><i class="fas fa-check-circle"></i><div><strong>Usuario y clave automáticos</strong><span>Apenas se aprueba el pago, por pantalla y por email.</span></div></li>
		<li><i class="fas fa-check-circle"></i><div><strong>Video protegido</strong><span>Un acceso por persona: si se comparte, se corta en el otro dispositivo.</span></div></li>
		<li><i class="fas fa-check-circle"></i><div><strong>Repetición incluida</strong><span>Los que compraron pueden volver a verlo durante los días que elijas.</span></div></li>
		<li><i class="fas fa-check-circle"></i><div><strong>Ventas en tiempo real</strong><span>Sabés cuántas entradas se vendieron y a quién, en todo momento.</span></div></li>
		<li><i class="fas fa-check-circle"></i><div><strong>Cupos y cortesías</strong><span>Limitá las entradas y generá accesos de invitados cuando quieras.</span></div></li>
	</ul>
	<div class="qs-cta">
		<a class="btn btn-lg-comprar" href="<?php echo h($wa); ?>" target="_blank" rel="noopener"><i class="fas fa-ticket-alt mr-1"></i> Quiero vender entradas</a>
	</div>
</section>

<section class="qs-seccion">
	<div class="lg-eyebrow">Cómo trabajamos</div>
	<h2>De la idea al aire en tres pasos</h2>
	<p>Simple para vos, prolijo para tu público.</p>
	<div class="qs-pasos">
		<div class="qs-paso">
			<h3>Nos contás el evento</h3>
			<p>Lugar, fecha, cámaras, gráficos y si querés vender entradas. Te armamos la propuesta.</p>
		</div>
		<div class="qs-paso">
			<h3>Armamos todo</h3>
			<p>Producción técnica, señal y la página de venta con tu evento listo para compartir.</p>
		</div>
		<div class="qs-paso">
			<h3>Salís en vivo</h3>
			<p>Transmitimos, tu público mira con su acceso y vos seguís las ventas en tiempo real.</p>
		</div>
	</div>
</section>

<section class="qs-final">
	<h2>¿Tenés un evento? Hagámoslo en vivo.</h2>
	<p>Escribinos y lo charlamos. Te respondemos por WhatsApp.</p>
	<div class="qs-cta">
		<a class="btn btn-wa" href="https://wa.me/5492914254860?text=<?php echo rawurlencode('Hola LiveGo! Quiero consultar por una transmisión.'); ?>" target="_blank" rel="noopener"><i class="fab fa-whatsapp mr-1"></i> 291 425-4860</a>
		<a class="btn btn-wa" href="https://wa.me/5492915162426?text=<?php echo rawurlencode('Hola LiveGo! Quiero consultar por una transmisión.'); ?>" target="_blank" rel="noopener"><i class="fab fa-whatsapp mr-1"></i> 291 516-2426</a>
	</div>
</section>
