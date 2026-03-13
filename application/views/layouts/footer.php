<script>
    const BASE_URL = "<?= base_url() ?>";
    const CSRF_NAME = "<?= $this->security->get_csrf_token_name() ?>";
</script>

<!-- Vendor JS -->
<script src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/toastify.js') ?>"></script>
<script src="<?= base_url('assets/js/chart.min.js') ?>"></script>

<!-- App JS -->
<script src="<?= base_url('assets/js/script.js') ?>"></script>
<script src="<?= base_url('assets/js/auth.js') ?>"></script>

<!-- Loader JS (global showLoader / hideLoader) -->
<script>
    // (function($) {
    //     'use strict';

    //     var loaderSteps = [{
    //             msg: 'Establishing connection...',
    //             dot: 0
    //         },
    //         {
    //             msg: 'Verifying session...',
    //             dot: 1
    //         },
    //         {
    //             msg: 'Loading dashboard...',
    //             dot: 2
    //         },
    //         {
    //             msg: 'Almost ready...',
    //             dot: 3
    //         }
    //     ];
    //     var stepTimer = null;
    //     var currentStep = 0;

    //     function resetSteps() {
    //         for (var i = 0; i < 4; i++) {
    //             $('#lstep' + i).attr('class', 'loader-step-dot');
    //             if (i < 3) $('#lline' + i).attr('class', 'loader-step-line');
    //         }
    //     }

    //     function activateStep(n) {
    //         resetSteps();
    //         for (var i = 0; i < n; i++) {
    //             $('#lstep' + i).addClass('done');
    //             if (i < 3) $('#lline' + i).addClass('done');
    //         }
    //         $('#lstep' + n).addClass('active');
    //     }

    //     function runSteps() {
    //         currentStep = 0;
    //         activateStep(0);
    //         $('#loaderText').text(loaderSteps[0].msg);

    //         stepTimer = setInterval(function() {
    //             currentStep++;
    //             if (currentStep >= loaderSteps.length) {
    //                 clearInterval(stepTimer);
    //                 return;
    //             }
    //             activateStep(currentStep);
    //             $('#loaderText').text(loaderSteps[currentStep].msg);
    //         }, 900);
    //     }

    //     /* ── Public API ── */
    //     window.showLoader = function(msg) {
    //         clearInterval(stepTimer);
    //         resetSteps();
    //         $('#loaderOverlay').removeClass('hidden');
    //         runSteps();
    //         if (msg) $('#loaderText').text(msg);
    //     };

    //     window.hideLoader = function() {
    //         clearInterval(stepTimer);
    //         $('#loaderOverlay').addClass('hidden');
    //     };

    //     /* ── Auto-hide on DOM ready ── */
    //     $(function() {
    //         setTimeout(window.hideLoader, 1600);
    //     });

    // }(jQuery));
</script>

</body>

</html>