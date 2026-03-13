<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Error — SecureAuth</title>
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
			--teal-soft: rgba(43, 184, 160, .10);
			--teal-mid: rgba(43, 184, 160, .18);
			--gray-100: #f4f6fb;
			--gray-200: #e4e9f2;
			--gray-300: #c8d0df;
			--gray-500: #8896af;
			--gray-700: #4a5568;
			--text: #1a2236;
			--danger: #e05555;
			--danger-dark: #c03d3d;
			--danger-soft: rgba(224, 85, 85, .08);
			--danger-mid: rgba(224, 85, 85, .18);
			--warn: #f7a035;
		}

		html,
		body {
			min-height: 100%;
			background: var(--bg);
			color: var(--text);
			font-family: 'Nunito', sans-serif;
			font-size: 14px;
		}

		/* ── BACKGROUND ── */
		body::before {
			content: '';
			position: fixed;
			inset: 0;
			z-index: 0;
			background:
				radial-gradient(ellipse 700px 500px at 80% -10%, rgba(224, 85, 85, .07) 0%, transparent 65%),
				radial-gradient(ellipse 500px 400px at -5% 100%, rgba(43, 184, 160, .06) 0%, transparent 65%),
				radial-gradient(ellipse 400px 300px at 50% 110%, rgba(224, 85, 85, .04) 0%, transparent 60%);
			pointer-events: none;
		}

		body::after {
			content: '';
			position: fixed;
			inset: 0;
			z-index: 0;
			background-image: radial-gradient(circle, rgba(43, 184, 160, .08) 1px, transparent 1px);
			background-size: 28px 28px;
			pointer-events: none;
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
			min-height: calc(100vh - 65px);
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 40px 24px;
		}

		/* ── CARD ── */
		.card {
			width: 100%;
			max-width: 560px;
			background: var(--white);
			border-radius: 24px;
			border: 1px solid var(--gray-200);
			overflow: hidden;
			box-shadow: 0 20px 60px rgba(0, 0, 0, .07), 0 4px 16px rgba(0, 0, 0, .04);
			opacity: 0;
			animation: cardIn .65s cubic-bezier(.22, .61, .36, 1) .1s forwards;
		}

		@keyframes cardIn {
			from {
				opacity: 0;
				transform: translateY(24px) scale(.97);
			}

			to {
				opacity: 1;
				transform: translateY(0) scale(1);
			}
		}

		/* ── CARD HERO ── */
		.card-hero {
			padding: 40px 40px 36px;
			background: linear-gradient(135deg, #fff8f8 0%, #fff 60%);
			border-bottom: 1px solid var(--gray-200);
			position: relative;
			overflow: hidden;
			text-align: center;
		}

		/* Decorative blob */
		.card-hero::before {
			content: '';
			position: absolute;
			width: 260px;
			height: 260px;
			border-radius: 50%;
			background: radial-gradient(circle, rgba(224, 85, 85, .06) 0%, transparent 70%);
			top: -80px;
			right: -80px;
			pointer-events: none;
		}

		.card-hero::after {
			content: '';
			position: absolute;
			width: 160px;
			height: 160px;
			border-radius: 50%;
			background: radial-gradient(circle, rgba(247, 160, 53, .05) 0%, transparent 70%);
			bottom: -60px;
			left: -40px;
			pointer-events: none;
		}

		/* Error code big display */
		.error-code {
			font-family: 'Nunito', sans-serif;
			font-size: 6rem;
			font-weight: 900;
			line-height: 1;
			letter-spacing: -.04em;
			background: linear-gradient(135deg, var(--danger) 0%, var(--warn) 100%);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
			margin-bottom: 6px;
			position: relative;
			z-index: 1;
			animation: fadeUp .5s ease .3s both;
		}

		/* Icon circle */
		.error-icon-wrap {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 72px;
			height: 72px;
			border-radius: 50%;
			background: var(--danger-soft);
			border: 2px solid rgba(224, 85, 85, .18);
			margin-bottom: 20px;
			position: relative;
			z-index: 1;
			animation: popIn .5s cubic-bezier(.34, 1.56, .64, 1) .4s both;
		}

		@keyframes popIn {
			from {
				opacity: 0;
				transform: scale(.5);
			}

			to {
				opacity: 1;
				transform: scale(1);
			}
		}

		/* Pulsing ring */
		.error-icon-wrap::before {
			content: '';
			position: absolute;
			inset: -8px;
			border-radius: 50%;
			border: 1.5px solid rgba(224, 85, 85, .15);
			animation: ringPulse 2.5s ease-in-out infinite;
		}

		@keyframes ringPulse {

			0%,
			100% {
				transform: scale(1);
				opacity: .6;
			}

			50% {
				transform: scale(1.08);
				opacity: 1;
			}
		}

		.error-title {
			font-size: 1.4rem;
			font-weight: 800;
			color: var(--text);
			margin-bottom: 8px;
			position: relative;
			z-index: 1;
			animation: fadeUp .5s ease .5s both;
		}

		.error-subtitle {
			font-size: .82rem;
			color: var(--gray-500);
			line-height: 1.65;
			position: relative;
			z-index: 1;
			animation: fadeUp .5s ease .6s both;
		}

		/* ── CARD BODY ── */
		.card-body {
			padding: 28px 40px 32px;
		}

		/* Message box */
		.message-label {
			font-size: .67rem;
			font-weight: 700;
			letter-spacing: .16em;
			text-transform: uppercase;
			color: var(--gray-500);
			margin-bottom: 10px;
			animation: fadeUp .5s ease .7s both;
		}

		.message-box {
			background: var(--gray-100);
			border: 1px solid var(--gray-200);
			border-left: 3px solid var(--danger);
			border-radius: 0 12px 12px 0;
			padding: 16px 18px;
			font-family: 'DM Mono', monospace;
			font-size: .82rem;
			color: var(--gray-700);
			line-height: 1.7;
			margin-bottom: 24px;
			animation: fadeUp .5s ease .75s both;
		}

		/* Meta row */
		.meta-row {
			display: flex;
			gap: 12px;
			margin-bottom: 26px;
			flex-wrap: wrap;
			animation: fadeUp .5s ease .8s both;
		}

		.meta-chip {
			display: flex;
			align-items: center;
			gap: 6px;
			padding: 5px 12px;
			border-radius: 20px;
			font-size: .69rem;
			font-weight: 700;
			letter-spacing: .06em;
			border: 1px solid;
		}

		.chip-danger {
			background: var(--danger-soft);
			border-color: rgba(224, 85, 85, .25);
			color: var(--danger);
		}

		.chip-warn {
			background: rgba(247, 160, 53, .08);
			border-color: rgba(247, 160, 53, .25);
			color: var(--warn);
		}

		.chip-gray {
			background: var(--gray-100);
			border-color: var(--gray-200);
			color: var(--gray-500);
		}

		.chip-dot {
			width: 6px;
			height: 6px;
			border-radius: 50%;
			background: currentColor;
			animation: blinkDot 1.8s ease-in-out infinite;
		}

		@keyframes blinkDot {

			0%,
			100% {
				opacity: 1;
			}

			50% {
				opacity: .2;
			}
		}

		/* Divider */
		.divider {
			height: 1px;
			background: var(--gray-200);
			margin-bottom: 22px;
		}

		/* Info box */
		.info-box {
			display: flex;
			gap: 12px;
			align-items: flex-start;
			padding: 14px 16px;
			background: var(--teal-soft);
			border: 1px solid rgba(43, 184, 160, .2);
			border-radius: 12px;
			margin-bottom: 24px;
			animation: fadeUp .5s ease .85s both;
		}

		.info-box-icon {
			width: 30px;
			height: 30px;
			background: rgba(43, 184, 160, .15);
			border-radius: 8px;
			display: grid;
			place-items: center;
			color: var(--teal);
			flex-shrink: 0;
		}

		.info-box-text {
			font-size: .76rem;
			color: var(--gray-700);
			line-height: 1.65;
		}

		.info-box-text strong {
			color: var(--text);
			font-weight: 700;
		}

		/* ── ACTIONS ── */
		.actions {
			display: flex;
			gap: 10px;
			animation: fadeUp .5s ease .9s both;
		}

		.btn-primary {
			flex: 1;
			padding: 13px;
			background: linear-gradient(135deg, var(--teal), var(--teal-dark));
			color: #fff;
			border: none;
			border-radius: 12px;
			font-family: 'Nunito', sans-serif;
			font-size: .82rem;
			font-weight: 700;
			letter-spacing: .05em;
			cursor: pointer;
			text-decoration: none;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			box-shadow: 0 6px 18px rgba(43, 184, 160, .32);
			transition: transform .15s, box-shadow .2s;
		}

		.btn-primary:hover {
			transform: translateY(-1px);
			box-shadow: 0 10px 26px rgba(43, 184, 160, .40);
		}

		.btn-primary:active {
			transform: scale(.99);
		}

		.btn-ghost {
			padding: 13px 18px;
			background: var(--gray-100);
			border: 1.5px solid var(--gray-200);
			border-radius: 12px;
			color: var(--gray-700);
			font-family: 'Nunito', sans-serif;
			font-size: .82rem;
			font-weight: 700;
			cursor: pointer;
			text-decoration: none;
			display: flex;
			align-items: center;
			gap: 8px;
			transition: border-color .2s, background .2s;
			white-space: nowrap;
		}

		.btn-ghost:hover {
			border-color: var(--gray-300);
			background: var(--gray-200);
		}

		/* ── FOOTER ── */
		.card-footer {
			padding: 16px 40px;
			border-top: 1px solid var(--gray-200);
			background: var(--gray-100);
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			gap: 10px;
			animation: fadeUp .5s ease .95s both;
		}

		.footer-brand {
			display: flex;
			align-items: center;
			gap: 7px;
			font-size: .72rem;
			font-weight: 700;
			color: var(--gray-500);
		}

		.footer-brand .dot {
			color: var(--teal);
		}

		.footer-ts {
			font-family: 'DM Mono', monospace;
			font-size: .68rem;
			color: var(--gray-400, #b0bbcc);
		}

		@keyframes fadeUp {
			from {
				opacity: 0;
				transform: translateY(10px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@media (max-width: 480px) {
			.topbar {
				padding: 14px 20px;
			}

			.card-hero {
				padding: 32px 24px 28px;
			}

			.card-body {
				padding: 24px 24px 28px;
			}

			.card-footer {
				padding: 14px 24px;
			}

			.error-code {
				font-size: 4.5rem;
			}

			.actions {
				flex-direction: column;
			}
		}
	</style>
</head>

<body>

	<!-- TOPBAR -->
	<nav class="topbar">
		<a href="<?= base_url() ?>" class="logo">
			<div class="logo-icon">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
					<rect x="3" y="11" width="18" height="11" rx="2" />
					<path d="M7 11V7a5 5 0 0 1 10 0v4" />
				</svg>
			</div>
			<span class="logo-name">SecureAuth</span>
		</a>
	</nav>

	<!-- STAGE -->
	<div class="stage">
		<div class="card">

			<!-- HERO -->
			<div class="card-hero">
				<div class="error-icon-wrap">
					<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#e05555" stroke-width="1.8">
						<circle cx="12" cy="12" r="10" />
						<line x1="12" y1="8" x2="12" y2="12" />
						<circle cx="12" cy="16" r="1" fill="#e05555" stroke="none" />
					</svg>
				</div>
				<div class="error-code">405</div>
				<h1 class="error-title"><?= $heading ?? 'Something went wrong' ?></h1>
				<p class="error-subtitle">We hit an unexpected error while processing your request.</p>
			</div>

			<!-- BODY -->
			<div class="card-body">

				<p class="message-label">// Exception Message</p>
				<div class="message-box">
					<?= $message ?? 'An unexpected error occurred. Please try again.' ?>
				</div>

				<!-- Meta chips -->
				<div class="meta-row">
					<div class="meta-chip chip-danger">
						<div class="chip-dot"></div>
						Runtime Error
					</div>
					<div class="meta-chip chip-warn">
						<div class="chip-dot"></div>
						405 Method Not Allowed
					</div>
					<div class="meta-chip chip-gray" id="tsChip">
						<!-- filled by JS -->
					</div>
				</div>

				<div class="divider"></div>

				<!-- Info -->
				<div class="info-box">
					<div class="info-box-icon">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<circle cx="12" cy="12" r="10" />
							<line x1="12" y1="16" x2="12" y2="12" />
							<line x1="12" y1="8" x2="12.01" y2="8" />
						</svg>
					</div>
					<p class="info-box-text">
						<strong>What can you do?</strong><br>
						Try going back to the previous page or return to the dashboard. If this keeps happening, please contact your system administrator.
					</p>
				</div>

				<!-- Actions -->
				<div class="actions">
					<a href="<?= base_url('dashboard') ?>" class="btn-primary">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<rect x="3" y="3" width="7" height="7" rx="1" />
							<rect x="14" y="3" width="7" height="7" rx="1" />
							<rect x="3" y="14" width="7" height="7" rx="1" />
							<rect x="14" y="14" width="7" height="7" rx="1" />
						</svg>
						Go to Dashboard
					</a>
					<a href="javascript:history.back()" class="btn-ghost">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<polyline points="15 18 9 12 15 6" />
						</svg>
						Go Back
					</a>
				</div>

			</div>

			<!-- FOOTER -->
			<div class="card-footer">
				<div class="footer-brand">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
					</svg>
					SecureAuth <span class="dot">·</span> Error Report
				</div>
				<span class="footer-ts" id="footerTs"></span>
			</div>

		</div>
	</div>

	<script>
		// Timestamp
		const now = new Date();
		const ts = now.toISOString().replace('T', ' ').slice(0, 19) + ' UTC';
		document.getElementById('tsChip').textContent = ts;
		document.getElementById('footerTs').textContent = ts;
	</script>
</body>

</html>