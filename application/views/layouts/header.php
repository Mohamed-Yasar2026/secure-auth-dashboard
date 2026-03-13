<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Icon Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- App CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/toastify.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/totp.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/loader.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard_activity.css') ?>">

    <title><?= isset($title) ? htmlspecialchars($title) : 'SecureAuth' ?></title>
</head>

<body>

    <!-- Loader Overlay -->
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