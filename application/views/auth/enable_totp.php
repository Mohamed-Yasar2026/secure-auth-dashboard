<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication — SecureAuth</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/totp.css') ?>">
</head>

<body>
    <main class="totp-main">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-badge">
                <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                Security Setup
            </div>
            <h1 class="page-title">Enable Two-Factor Authentication</h1>
            <p class="page-sub">Add a second layer of security to your account using an authenticator app.</p>
        </div>

        <!-- Setup Grid -->
        <div class="setup-grid">

            <!-- Card 1: QR Code -->
            <div class="totp-card">
                <div class="card-accent"></div>
                <div class="card-body">
                    <p class="card-section-label">Step 1</p>
                    <h2 class="card-section-title">Scan QR Code</h2>
                    <div class="qr-wrapper">
                        <div class="qr-frame">
                            <img src="<?= $qrCode ?>" alt="TOTP QR Code">
                            <div class="qr-corner-bl"></div>
                            <div class="qr-corner-br"></div>
                        </div>
                    </div>
                    <p class="qr-hint">Point your authenticator app at this code</p>
                    <div class="notice">
                        <span class="notice-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                        </span>
                        <span class="notice-text">Keep this QR code private. Anyone with access to it can generate valid codes for your account.</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Instructions -->
            <div class="totp-card">
                <div class="card-accent"></div>
                <div class="card-body">
                    <p class="card-section-label">Step 2</p>
                    <h2 class="card-section-title">Install &amp; Configure</h2>
                    <div class="install-steps">
                        <div class="step">
                            <div class="step-num">1</div>
                            <div class="step-content">
                                <p class="step-title">Install an authenticator app</p>
                                <p class="step-desc">Download one of these apps to your mobile device:</p>
                                <div class="app-badges">
                                    <span class="app-badge">Google Authenticator</span>
                                    <span class="app-badge">Authy</span>
                                    <span class="app-badge">1Password</span>
                                </div>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-num">2</div>
                            <div class="step-content">
                                <p class="step-title">Add a new account</p>
                                <p class="step-desc">Open the app, tap the "+" icon, then choose "Scan QR code".</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-num">3</div>
                            <div class="step-content">
                                <p class="step-title">Point your camera</p>
                                <p class="step-desc">Scan the QR code on the left. A 6-digit code will appear and refresh every 30 seconds.</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-num">4</div>
                            <div class="step-content">
                                <p class="step-title">Verify below</p>
                                <p class="step-desc">Enter the current code to confirm the setup is working correctly.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Verify (full width) -->
            <div class="totp-card full-width">
                <div class="card-accent"></div>
                <div class="card-body">
                    <p class="card-section-label">Step 3</p>
                    <h2 class="card-section-title">Verify &amp; Activate</h2>

                    <?php if ($this->session->flashdata('error')) : ?>
                        <div class="flash error">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <?= htmlspecialchars($this->session->flashdata('error')) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('success')) : ?>
                        <div class="flash success">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <?= htmlspecialchars($this->session->flashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <div class="verify-layout">
                        <!-- Left: form -->
                        <div>
                            <label class="code-label">Enter 6-digit code from your app</label>
                            <?= form_open('auth/verify_totp', ['id' => 'verifyForm', 'novalidate' => 'novalidate']) ?>
                            <input type="hidden" name="code" id="hiddenCode">
                            <div class="code-inputs" id="codeInputs">
                                <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="0">
                                <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="1">
                                <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="2">
                                <span class="sep">—</span>
                                <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="3">
                                <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="4">
                                <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="5">
                            </div>
                            <div class="verify-actions">
                                <button type="submit" class="btn-totp" id="verifyBtn">
                                    <span class="btn-text">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;margin-right:4px;">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                        </svg>
                                        Activate 2FA
                                    </span>
                                    <span class="spinner"></span>
                                </button>
                                <button type="submit" form="skipForm" class="btn-totp-ghost" id="skipBtn">
                                    Skip for now
                                </button>
                            </div>
                            <?= form_close() ?>
                            <?= form_open('auth/skip_totp', ['id' => 'skipForm', 'style' => 'display:none']) ?>
                            <?= form_close() ?>
                        </div>

                        <!-- Right: info -->
                        <div>
                            <div class="info-row">
                                <div class="info-row-icon teal">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                </div>
                                <p class="info-row-text"><strong>What happens next?</strong><br>Every login will require your email, password, and a time-based code from your authenticator app.</p>
                            </div>
                            <div class="info-row">
                                <div class="info-row-icon warn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                </div>
                                <p class="info-row-text"><strong>Lost access to your app?</strong><br>Contact your administrator to reset 2FA. Always keep a backup of your recovery codes.</p>
                            </div>
                            <div class="notice">
                                <span class="notice-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                </span>
                                <span class="notice-text">Codes are time-sensitive and expire every 30 seconds. Enter the code immediately after it appears in your app.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /setup-grid -->
    </main>

    <script>
        (function() {
            'use strict';
            var inputs = Array.from(document.querySelectorAll('#codeInputs .digit-input'));
            var hiddenCode = document.getElementById('hiddenCode');
            var verifyBtn = document.getElementById('verifyBtn');
            var form = document.getElementById('verifyForm');

            function getCode() {
                return inputs.map(function(i) {
                    return i.value;
                }).join('');
            }

            function updateHidden() {
                hiddenCode.value = getCode();
            }

            function markFilled(el) {
                el.value ? el.classList.add('filled') : el.classList.remove('filled');
            }

            function clearInvalid() {
                inputs.forEach(function(i) {
                    i.classList.remove('invalid');
                });
            }

            function triggerShake() {
                inputs.forEach(function(i) {
                    i.classList.remove('invalid');
                    void i.offsetWidth;
                    i.classList.add('invalid');
                });
            }

            inputs.forEach(function(input, idx) {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(-1);
                    markFilled(this);
                    clearInvalid();
                    if (this.value && idx < inputs.length - 1) inputs[idx + 1].focus();
                    updateHidden();
                });
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace') {
                        if (!this.value && idx > 0) {
                            inputs[idx - 1].value = '';
                            inputs[idx - 1].classList.remove('filled');
                            inputs[idx - 1].focus();
                        } else {
                            this.value = '';
                            markFilled(this);
                        }
                        updateHidden();
                    }
                    if (e.key === 'ArrowLeft' && idx > 0) {
                        inputs[idx - 1].focus();
                        e.preventDefault();
                    }
                    if (e.key === 'ArrowRight' && idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                        e.preventDefault();
                    }
                });
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    var p = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                    p.split('').forEach(function(ch, i) {
                        if (inputs[i]) {
                            inputs[i].value = ch;
                            markFilled(inputs[i]);
                        }
                    });
                    var nx = inputs.findIndex(function(i) {
                        return !i.value;
                    });
                    (inputs[nx] || inputs[inputs.length - 1]).focus();
                    clearInvalid();
                    updateHidden();
                });
            });

            // inputs[0].focus();
            inputs[0].focus({
                preventScroll: true
            });
            
            form.addEventListener('submit', function(e) {
                if (!/^[0-9]{6}$/.test(getCode())) {
                    e.preventDefault();
                    triggerShake();
                    inputs[0].focus();
                    return;
                }
                hiddenCode.value = getCode();
                verifyBtn.classList.add('loading');
                verifyBtn.disabled = true;
            });
        }());
    </script>

    <?php initFooter() ?>