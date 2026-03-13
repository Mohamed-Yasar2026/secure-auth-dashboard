<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>404 — Page Not Found</title>
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
	<style>
		*,
		*::before,
		*::after {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		:root {
			--bg: #eef1f8;
			--white: #ffffff;
			--teal: #2bb8a0;
			--teal-dark: #1e9e88;
			--teal-deep: #197a6a;
			--teal-soft: rgba(43, 184, 160, .10);
			--teal-mid: rgba(43, 184, 160, .18);
			--gray-100: #f4f6fb;
			--gray-200: #e4e9f2;
			--gray-300: #c8d0df;
			--gray-500: #8896af;
			--gray-700: #4a5568;
			--text: #1a2236;
			--danger: #e05555;
			--danger-soft: rgba(224, 85, 85, .08);
		}

		html,
		body {
			height: 100%;
			background: var(--bg);
			color: var(--text);
			font-family: 'Nunito', sans-serif;
			overflow: hidden;
		}

		/* ── BACKGROUND ── */
		body::before {
			content: '';
			position: fixed;
			inset: 0;
			z-index: 0;
			background:
				radial-gradient(ellipse 650px 500px at 85% 5%, rgba(43, 184, 160, .11) 0%, transparent 65%),
				radial-gradient(ellipse 500px 400px at -5% 95%, rgba(43, 184, 160, .07) 0%, transparent 65%),
				radial-gradient(ellipse 350px 300px at 40% 110%, rgba(43, 184, 160, .05) 0%, transparent 60%);
			pointer-events: none;
		}

		body::after {
			content: '';
			position: fixed;
			inset: 0;
			z-index: 0;
			background-image: radial-gradient(circle, rgba(43, 184, 160, .09) 1px, transparent 1px);
			background-size: 28px 28px;
			animation: gridDrift 35s linear infinite;
			pointer-events: none;
		}

		@keyframes gridDrift {
			to {
				background-position: 28px 28px;
			}
		}

		/* Floating orbs */
		.orb {
			position: fixed;
			border-radius: 50%;
			filter: blur(90px);
			pointer-events: none;
			z-index: 0;
		}

		.orb-1 {
			width: 320px;
			height: 320px;
			background: rgba(43, 184, 160, .09);
			top: -80px;
			right: 5%;
			animation: orbF 13s ease-in-out infinite;
		}

		.orb-2 {
			width: 240px;
			height: 240px;
			background: rgba(43, 184, 160, .06);
			bottom: -60px;
			left: 3%;
			animation: orbF 10s ease-in-out infinite 3s reverse;
		}

		.orb-3 {
			width: 180px;
			height: 180px;
			background: rgba(100, 180, 255, .05);
			top: 40%;
			left: 12%;
			animation: orbF 8s ease-in-out infinite 1s;
		}

		@keyframes orbF {

			0%,
			100% {
				transform: translate(0, 0);
			}

			50% {
				transform: translate(16px, -16px);
			}
		}

		/* ── TOPBAR ── */
		.topbar {
			position: relative;
			z-index: 10;
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 18px 40px;
			background: var(--white);
			border-bottom: 1px solid var(--gray-200);
			box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
		}

		.logo {
			display: flex;
			align-items: center;
			gap: 10px;
			text-decoration: none;
		}

		.logo-icon {
			width: 34px;
			height: 34px;
			background: linear-gradient(135deg, var(--teal), var(--teal-dark));
			border-radius: 10px;
			display: grid;
			place-items: center;
			color: #fff;
			box-shadow: 0 4px 12px rgba(43, 184, 160, .3);
		}

		.logo-name {
			font-size: 1.05rem;
			font-weight: 800;
			color: var(--text);
		}

		/* ── STAGE ── */
		.stage {
			position: relative;
			z-index: 1;
			height: calc(100vh - 65px);
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			padding: 24px;
			gap: 0;
		}

		/* ── BIG 404 ── */
		.four-wrap {
			position: relative;
			margin-bottom: -18px;
			animation: numIn .8s cubic-bezier(.16, 1, .3, 1) .05s both;
			z-index: 1;
		}

		@keyframes numIn {
			from {
				opacity: 0;
				transform: translateY(-20px) scale(.94);
			}

			to {
				opacity: 1;
				transform: translateY(0) scale(1);
			}
		}

		.four-oh-four {
			font-family: 'Nunito', sans-serif;
			font-size: clamp(90px, 20vw, 180px);
			font-weight: 900;
			line-height: 1;
			letter-spacing: -.04em;
			color: transparent;
			background: linear-gradient(135deg, var(--teal) 0%, #5dd6c3 40%, var(--teal-dark) 70%, var(--teal-deep) 100%);
			background-size: 200% 200%;
			-webkit-background-clip: text;
			background-clip: text;
			animation: tealShift 5s ease-in-out infinite alternate;
			user-select: none;
			position: relative;
		}

		@keyframes tealShift {
			from {
				background-position: 0% 50%;
			}

			to {
				background-position: 100% 50%;
			}
		}

		/* Ghost shadow */
		.four-oh-four::before {
			content: '404';
			position: absolute;
			inset: 0;
			font-family: 'Nunito', sans-serif;
			font-weight: 900;
			color: transparent;
			-webkit-text-stroke: 1.5px rgba(43, 184, 160, .15);
			letter-spacing: inherit;
			transform: translate(4px, 4px);
			z-index: -1;
		}

		/* Orbit ring */
		.orbit-ring {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: clamp(120px, 25vw, 240px);
			height: clamp(120px, 25vw, 240px);
			border-radius: 50%;
			border: 1.5px dashed rgba(43, 184, 160, .22);
			animation: orbitSpin 16s linear infinite;
			pointer-events: none;
		}

		.orbit-ring::after {
			content: '';
			position: absolute;
			top: -5px;
			left: 50%;
			width: 10px;
			height: 10px;
			border-radius: 50%;
			background: var(--teal);
			box-shadow: 0 0 14px rgba(43, 184, 160, .5);
			transform: translateX(-50%);
		}

		@keyframes orbitSpin {
			to {
				transform: translate(-50%, -50%) rotate(360deg);
			}
		}

		/* ── CARD ── */
		.card {
			background: var(--white);
			border: 1px solid var(--gray-200);
			border-radius: 24px;
			box-shadow: 0 20px 60px rgba(0, 0, 0, .07), 0 4px 16px rgba(0, 0, 0, .04);
			overflow: hidden;
			width: 100%;
			max-width: 460px;
			position: relative;
			animation: cardIn .8s cubic-bezier(.16, 1, .3, 1) .25s both;
			transition: transform .4s ease;
		}

		@keyframes cardIn {
			from {
				opacity: 0;
				transform: translateY(22px) scale(.97);
			}

			to {
				opacity: 1;
				transform: translateY(0) scale(1);
			}
		}

		/* Shimmer top bar */
		.card::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 3px;
			z-index: 2;
			background: linear-gradient(90deg, transparent, var(--teal), #5dd6c3, var(--teal), transparent);
			background-size: 300% 100%;
			animation: shimmerBar 3s linear infinite;
		}

		@keyframes shimmerBar {
			from {
				background-position: 300%;
			}

			to {
				background-position: -300%;
			}
		}

		.card-body {
			padding: 32px 40px 28px;
			text-align: center;
		}

		/* Icon badge */
		.icon-badge {
			width: 64px;
			height: 64px;
			background: linear-gradient(135deg, var(--teal-soft), var(--teal-mid));
			border: 1.5px solid rgba(43, 184, 160, .25);
			border-radius: 18px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 20px;
			box-shadow: 0 6px 24px rgba(43, 184, 160, .18), 0 0 0 6px rgba(43, 184, 160, .05);
			animation: iconBob 4s ease-in-out infinite;
		}

		@keyframes iconBob {

			0%,
			100% {
				transform: translateY(0);
			}

			50% {
				transform: translateY(-6px);
			}
		}

		/* Eyebrow */
		.eyebrow {
			font-size: .68rem;
			font-weight: 700;
			letter-spacing: .2em;
			text-transform: uppercase;
			color: var(--teal);
			margin-bottom: 8px;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
		}

		.eyebrow::before,
		.eyebrow::after {
			content: '';
			width: 24px;
			height: 1.5px;
		}

		.eyebrow::before {
			background: linear-gradient(90deg, transparent, var(--teal));
		}

		.eyebrow::after {
			background: linear-gradient(90deg, var(--teal), transparent);
		}

		.card-title {
			font-size: 1.5rem;
			font-weight: 800;
			color: var(--text);
			margin-bottom: 10px;
			line-height: 1.2;
		}

		.card-title em {
			font-style: italic;
			color: var(--teal);
		}

		.card-sub {
			font-size: .82rem;
			color: var(--gray-500);
			line-height: 1.8;
			margin-bottom: 22px;
		}

		/* URL chip */
		.url-chip {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 8px 16px;
			background: var(--gray-100);
			border: 1px solid var(--gray-200);
			border-radius: 8px;
			font-family: 'DM Mono', monospace;
			font-size: .72rem;
			color: var(--gray-500);
			margin-bottom: 24px;
			max-width: 100%;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.url-chip-icon {
			color: var(--danger);
			flex-shrink: 0;
		}

		/* Buttons */
		.btn-row {
			display: flex;
			gap: 10px;
			justify-content: center;
			flex-wrap: wrap;
		}

		.btn-primary {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 13px 26px;
			background: linear-gradient(135deg, var(--teal), var(--teal-dark));
			border: none;
			border-radius: 12px;
			color: #fff;
			font-family: 'Nunito', sans-serif;
			font-size: .82rem;
			font-weight: 700;
			letter-spacing: .05em;
			text-transform: uppercase;
			cursor: pointer;
			text-decoration: none;
			box-shadow: 0 6px 20px rgba(43, 184, 160, .32);
			transition: transform .18s, box-shadow .2s;
		}

		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 28px rgba(43, 184, 160, .42);
		}

		.btn-primary:active {
			transform: scale(.99);
		}

		.btn-ghost {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 12px 22px;
			background: var(--gray-100);
			border: 1.5px solid var(--gray-200);
			border-radius: 12px;
			color: var(--gray-700);
			font-family: 'Nunito', sans-serif;
			font-size: .82rem;
			font-weight: 700;
			letter-spacing: .04em;
			text-transform: uppercase;
			cursor: pointer;
			text-decoration: none;
			transition: border-color .2s, background .2s;
		}

		.btn-ghost:hover {
			border-color: var(--teal);
			color: var(--teal);
			background: var(--teal-soft);
		}

		/* Card footer */
		.card-foot {
			padding: 14px 40px 18px;
			border-top: 1px solid var(--gray-200);
			background: var(--gray-100);
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			gap: 10px;
		}

		.foot-info {
			font-family: 'DM Mono', monospace;
			font-size: .68rem;
			color: var(--gray-500);
			display: flex;
			align-items: center;
			gap: 6px;
		}

		.foot-dot {
			width: 6px;
			height: 6px;
			border-radius: 50%;
			background: var(--teal);
			animation: blink 2s ease-in-out infinite;
		}

		@keyframes blink {

			0%,
			100% {
				opacity: 1;
			}

			50% {
				opacity: .2;
			}
		}

		.foot-code {
			font-family: 'DM Mono', monospace;
			font-size: .68rem;
			color: var(--gray-500);
		}

		.foot-code span {
			color: var(--teal);
			font-weight: 600;
		}

		/* Canvas */
		#particleCanvas {
			position: fixed;
			inset: 0;
			z-index: 0;
			pointer-events: none;
			opacity: .45;
		}

		@media (max-width: 500px) {
			.topbar {
				padding: 14px 20px;
			}

			.card-body {
				padding: 26px 22px 22px;
			}

			.card-foot {
				padding: 12px 22px 16px;
			}

			.btn-row {
				flex-direction: column;
				align-items: stretch;
			}

			.btn-primary,
			.btn-ghost {
				justify-content: center;
			}
		}
	</style>
</head>

<body>

	<canvas id="particleCanvas"></canvas>
	<div class="orb orb-1"></div>
	<div class="orb orb-2"></div>
	<div class="orb orb-3"></div>

	<!-- TOPBAR -->
	<nav class="topbar">
		<a href="#" class="logo">
			<div class="logo-icon">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
					<rect x="3" y="11" width="18" height="11" rx="2" />
					<path d="M7 11V7a5 5 0 0 1 10 0v4" />
				</svg>
			</div>
			<span class="logo-name">SecureAuth</span>
		</a>
	</nav>

	<div class="stage">

		<!-- Big 404 -->
		<div class="four-wrap">
			<div class="orbit-ring"></div>
			<div class="four-oh-four" id="bigNum">000</div>
		</div>

		<!-- Card -->
		<div class="card" id="mainCard">

			<div class="card-body">

				<!-- Icon -->
				<div class="icon-badge">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2bb8a0" stroke-width="1.7">
						<circle cx="11" cy="11" r="8" />
						<line x1="21" y1="21" x2="16.65" y2="16.65" />
						<line x1="11" y1="8" x2="11" y2="11" />
						<circle cx="11" cy="14" r=".8" fill="#2bb8a0" stroke="none" />
					</svg>
				</div>

				<div class="eyebrow">Error 404</div>
				<h1 class="card-title">Page <em>not found</em></h1>
				<p class="card-sub">
					The page you're looking for doesn't exist, has been moved,<br>
					or you may have followed a broken link.
				</p>

				<!-- URL chip -->
				<div class="url-chip">
					<span class="url-chip-icon">
						<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
							<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
							<line x1="4" y1="4" x2="20" y2="20" />
						</svg>
					</span>
					<span id="currentUrl"></span>
				</div>

				<!-- Buttons -->
				<div class="btn-row">
					<!-- <a href="#" class="btn-primary">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<rect x="3" y="3" width="7" height="7" rx="1" />
							<rect x="14" y="3" width="7" height="7" rx="1" />
							<rect x="3" y="14" width="7" height="7" rx="1" />
							<rect x="14" y="14" width="7" height="7" rx="1" />
						</svg>
						Go to Dashboard
					</a> -->
					<a href="javascript:history.back()" class="btn-primary">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<polyline points="15 18 9 12 15 6" />
						</svg>
						Go Back
					</a>
				</div>

			</div>

			<div class="card-foot">
				<div class="foot-info">
					<span class="foot-dot"></span>
					Page not found
				</div>
				<div class="foot-code">HTTP <span>404</span></div>
			</div>

		</div><!-- /card -->

	</div><!-- /stage -->

	<script>
		(function() {
			'use strict';

			/* ── Current URL ── */
			var urlEl = document.getElementById('currentUrl');
			var href = window.location.href;
			urlEl.textContent = href.length > 50 ? '...' + href.slice(-48) : href;

			/* ── Count up to 404 ── */
			var bigNum = document.getElementById('bigNum');
			var target = 404,
				current = 0;
			var counter = setInterval(function() {
				current = Math.min(current + 8, target);
				bigNum.textContent = String(current).padStart(3, '0');
				if (current >= target) {
					clearInterval(counter);
					bigNum.textContent = '404';
				}
			}, 18);

			/* ── Particle system ── */
			var canvas = document.getElementById('particleCanvas');
			var ctx = canvas.getContext('2d');
			var W, H, particles = [];

			function resize() {
				W = canvas.width = window.innerWidth;
				H = canvas.height = window.innerHeight;
			}
			resize();
			window.addEventListener('resize', resize);

			for (var i = 0; i < 50; i++) {
				particles.push({
					x: Math.random() * 1400,
					y: Math.random() * 900,
					r: Math.random() * 2 + 0.5,
					vx: (Math.random() - .5) * 0.35,
					vy: (Math.random() - .5) * 0.3,
					alpha: Math.random() * 0.4 + 0.08,
					teal: Math.random() > 0.4
				});
			}

			function drawParticles() {
				ctx.clearRect(0, 0, W, H);
				particles.forEach(function(p) {
					ctx.beginPath();
					ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
					ctx.fillStyle = p.teal ?
						'rgba(43,184,160,' + p.alpha + ')' :
						'rgba(30,158,136,' + (p.alpha * .5) + ')';
					ctx.fill();
					p.x += p.vx;
					p.y += p.vy;
					if (p.x < -10) p.x = W + 10;
					if (p.x > W + 10) p.x = -10;
					if (p.y < -10) p.y = H + 10;
					if (p.y > H + 10) p.y = -10;
				});
				requestAnimationFrame(drawParticles);
			}
			drawParticles();

			/* ── Mouse parallax on card ── */
			var card = document.getElementById('mainCard');
			document.addEventListener('mousemove', function(e) {
				var cx = window.innerWidth / 2;
				var cy = window.innerHeight / 2;
				var dx = (e.clientX - cx) / cx;
				var dy = (e.clientY - cy) / cy;
				card.style.transform = 'perspective(1200px) rotateY(' + (dx * 3) + 'deg) rotateX(' + (-dy * 2) + 'deg)';
			});
			document.addEventListener('mouseleave', function() {
				card.style.transition = 'transform .5s ease';
				card.style.transform = '';
				setTimeout(function() {
					card.style.transition = '';
				}, 500);
			});

		})();
	</script>
</body>

</html>