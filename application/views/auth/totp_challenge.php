<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication — SecureAuth</title>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="<?= base_url('assets/css/fonts.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/totp.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/loader.css') ?>">
</head>

<body>

    <div class="loader-overlay hidden" id="loaderOverlay">
        <div class="loader-widget">
            <div class="loader-orbit">
                <div class="loader-ring loader-ring-2"></div>
                <div class="loader-ring loader-ring-1"></div>
                <div class="loader-core">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
            </div>
            <div class="loader-wave">
                <div class="loader-wave-bar"></div>
                <div class="loader-wave-bar"></div>
                <div class="loader-wave-bar"></div>
                <div class="loader-wave-bar"></div>
                <div class="loader-wave-bar"></div>
                <div class="loader-wave-bar"></div>
                <div class="loader-wave-bar"></div>
                <div class="loader-wave-bar"></div>
                <div class="loader-wave-bar"></div>
                <div class="loader-wave-bar"></div>
            </div>
            <div class="loader-text-group">
                <div class="loader-title">SecureAuth</div>
                <div class="loader-msg" id="loaderText">Securing connection...</div>
            </div>
            <div class="loader-steps" id="loaderSteps">
                <div class="loader-step">
                    <div class="loader-step-dot" id="lstep0"></div>
                </div>
                <div class="loader-step">
                    <div class="loader-step-line" id="lline0"></div>
                </div>
                <div class="loader-step">
                    <div class="loader-step-dot" id="lstep1"></div>
                </div>
                <div class="loader-step">
                    <div class="loader-step-line" id="lline1"></div>
                </div>
                <div class="loader-step">
                    <div class="loader-step-dot" id="lstep2"></div>
                </div>
                <div class="loader-step">
                    <div class="loader-step-line" id="lline2"></div>
                </div>
                <div class="loader-step">
                    <div class="loader-step-dot" id="lstep3"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="totp-stage">
        <div class="totp-card">

            <!-- HERO -->
            <div class="card-hero">
                <div class="hero-top">
                    <div class="brand">
                        <div class="brand-shield">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                        </div>
                        SecureAuth
                    </div>
                    <!-- Progress steps -->
                    <div class="hero-steps">
                        <div class="step-dot done">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="step-line done"></div>
                        <div class="step-dot active">2</div>
                        <div class="step-line"></div>
                        <div class="step-dot pending">3</div>
                    </div>
                </div>
                <div class="hero-body">
                    <p class="hero-label">Two-Factor Authentication</p>
                    <h1 class="hero-title">Verify your identity</h1>
                    <p class="hero-sub">Enter the 6-digit code from your authenticator app to complete sign-in.</p>
                </div>
            </div>

            <!-- BODY -->
            <div class="challenge-body">

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

                <!-- Timer -->
                <div class="timer-row">
                    <span class="timer-label">Code expires in</span>
                    <div class="timer-badge" id="timerBadge">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <span id="timerCount">30s</span>
                    </div>
                </div>
                <div class="timer-track">
                    <div class="timer-fill" id="timerBar" style="width:100%"></div>
                </div>

                <!-- Form -->
                <?= form_open('auth/verify_login_totp', ['id' => 'totpForm', 'novalidate' => 'novalidate']) ?>
                <input type="hidden" name="code" id="hiddenCode">
                <label class="code-label">Authentication Code</label>
                <div class="code-inputs" id="codeInputs">
                    <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="0">
                    <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="1">
                    <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="2">
                    <span class="sep">—</span>
                    <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="3">
                    <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="4">
                    <input type="text" class="digit-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off" data-idx="5">
                </div>
                <button type="submit" class="btn-totp" id="submitBtn" disabled>
                    <span class="btn-text">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;margin-right:4px;">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        Verify &amp; Sign In
                    </span>
                    <span class="spinner"></span>
                </button>
                <?= form_close() ?>

                <div class="totp-divider">or</div>

                <div class="card-footer-totp">
                    <a href="<?= base_url('auth') ?>" class="back-link" onclick="showLoader()">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                        Return to login
                    </a>
                    <div class="help-box">
                        <div class="help-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                        </div>
                        <p class="help-text">
                            <strong>Having trouble?</strong> Open your authenticator app and enter the current 6-digit code shown for this account. Contact your administrator if you've lost access.
                        </p>
                    </div>
                </div>

            </div><!-- /challenge-body -->
        </div><!-- /totp-card -->
    </div><!-- /totp-stage -->

    <script src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
    <script>
        (function() {
            'use strict';

            var inputs = Array.from(document.querySelectorAll('#codeInputs .digit-input'));
            var hiddenCode = document.getElementById('hiddenCode');
            var submitBtn = document.getElementById('submitBtn');
            var form = document.getElementById('totpForm');
            var timerBar = document.getElementById('timerBar');
            var timerCount = document.getElementById('timerCount');
            var timerBadge = document.getElementById('timerBadge');

            function getCode() {
                return inputs.map(function(i) {
                    return i.value;
                }).join('');
            }

            function updateHidden() {
                hiddenCode.value = getCode();
                submitBtn.disabled = !/^[0-9]{6}$/.test(getCode());
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

            inputs[0].focus();

            $(form).on('submit', function(e) {
                if (!/^[0-9]{6}$/.test(getCode())) {
                    e.preventDefault();
                    triggerShake();
                    inputs[0].focus();
                    return;
                }
                hiddenCode.value = getCode();
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });

            /* ── Timer — synced to Google Authenticator 30s window ── */
            function updateTimer() {
                var now = Math.floor(Date.now() / 1000);
                var remaining = 30 - (now % 30);
                var pct = (remaining / 30) * 100;

                timerCount.textContent = remaining + 's';
                timerBar.style.width = pct + '%';

                if (remaining <= 7) {
                    timerBar.classList.add('warn');
                    timerBadge.classList.add('warn');
                } else {
                    timerBar.classList.remove('warn');
                    timerBadge.classList.remove('warn');
                }

                /* Self-correcting: fires exactly on each second boundary */
                setTimeout(updateTimer, 1000 - (Date.now() % 1000));
            }

            updateTimer();

        }());
    </script>
</body>

</html>