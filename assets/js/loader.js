/* ============================================================
   loader.js — Global loader + dashboard init
   ============================================================ */

var loaderMessages = [
    'Securing connection...',
    'Verifying credentials...',
    'Loading dashboard...',
    'Almost there...'
];
var msgIndex = 0;
var msgInterval = null;
var loaderShownAt = 0;
var MIN_SHOW_MS = 2500;

/* ── Cache elements once DOM is ready ── */
var $loaderOverlay = null;
var $loaderText = null;

$(function () {
    $loaderOverlay = $('#loaderOverlay');
    $loaderText = $('#loaderText');
});

/* ============================================================
   showLoader(text)
   ============================================================ */
function showLoader(text) {
    if (!$loaderOverlay || !$loaderOverlay.length) {
        console.warn('[Loader] #loaderOverlay not found in DOM');
        return;
    }

    clearInterval(msgInterval);
    msgInterval = null;

    loaderShownAt = Date.now();
    msgIndex = 0;

    $loaderText.text(text || loaderMessages[0]);
    $loaderOverlay.removeClass('hidden');

    msgInterval = setInterval(function () {
        msgIndex = (msgIndex + 1) % loaderMessages.length;
        $loaderText.text(loaderMessages[msgIndex]);
    }, 700);
}

/* ============================================================
   hideLoader()
   Guarantees loader shows for at least MIN_SHOW_MS total.
   ============================================================ */
function hideLoader() {
    clearInterval(msgInterval);
    msgInterval = null;

    var elapsed = Date.now() - loaderShownAt;
    var remaining = Math.max(0, MIN_SHOW_MS - elapsed);

    setTimeout(function () {
        if ($loaderOverlay) $loaderOverlay.addClass('hidden');
    }, remaining);
}