(function () {

  const KEY = 'device_id_v1';

  if (!localStorage.getItem(KEY)) {
    const arr = new Uint8Array(32);
    crypto.getRandomValues(arr);

    const id = Array.from(arr)
      .map(b => b.toString(16).padStart(2, '0'))
      .join('');

    localStorage.setItem(KEY, id);
  }
  window.getDeviceId = function () {
    return localStorage.getItem(KEY);
  };

  initLogin()

})();


var loaderMessages = ['Loading...', 'Almost there...'];
var msgIndex = 0;
var msgInterval = null;
var loaderShownAt = 0;          // timestamp when loader appeared
var MIN_SHOW_MS = 2500;         // guaranteed minimum display time

function showLoader(text) {
  clearInterval(msgInterval);
  msgInterval = null;
  loaderShownAt = Date.now();
  msgIndex = 0;
  var $text = document.getElementById('loaderText');
  var $overlay = document.getElementById('loaderOverlay');

  // Show first message immediately
  $text.textContent = text || loaderMessages[0];
  $overlay.classList.remove('hidden');

  // Switch to second message at exactly halfway (1250ms)
  // then stop — no loop, no flicker back to "Loading..."
  msgInterval = setTimeout(function () {
    msgIndex = 1;
    $text.textContent = loaderMessages[1];
  }, MIN_SHOW_MS / loaderMessages.length); // 2500 / 2 = 1250ms
}


function hideLoader() {
  clearInterval(msgInterval);

  var elapsed = Date.now() - loaderShownAt;
  var remaining = Math.max(0, MIN_SHOW_MS - elapsed);

  // wait for however much of the 2.5s is left before hiding
  setTimeout(function () {
    document.getElementById('loaderOverlay').classList.add('hidden');
  }, remaining);
}


$(document).on('click', '#login', initLogin);
$(document).on('click', '#register', initRegister);


const container = document.getElementById("containers");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");
let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn");

if (registerBtn && loginBtn && container) {
  registerBtn.addEventListener("click", function () {
    container.classList.add("active");
  });

  loginBtn.addEventListener("click", function () {
    container.classList.remove("active");
  });
}

function initFormValidation(formSelector, rules, messages, submitHandler) {
  $(formSelector).validate({
    rules: rules,
    messages: messages,
    errorElement: "div",
    errorClass: "invalid-feedback",

    highlight: function (element) {
      $(element).addClass("is-invalid");
    },

    unhighlight: function (element) {
      $(element).removeClass("is-invalid");
    },

    errorPlacement: function (error, element) {
      error.insertAfter(element);
    },
    submitHandler: submitHandler
  });
}

function floatResponse(message, type = 'success') {
  var toast = Toastify({
    text: message,
    duration: 3000,
    gravity: "top",
    position: "right",
    offset: {
      x: 20,
      y: 20
    },
    close: true,
    stopOnFocus: true,
    className: `bg-${type}`
  })

  toast.showToast();
  return toast;
}

function initRegister() {

  // Add custom rule
  $.validator.addMethod("strongPassword", function (value, element) {
    return this.optional(element) || /^(?=.*[A-Za-z])(?=.*\d).+$/.test(value);
  }, "Password must contain at least one letter and one number");

  // Use it
  const registerRules = {
    name: {
      required: true,
      minlength: 3,
      maxlength: 64,
    },
    email: {
      required: true,
      maxlength: 200,
      email: true,
      remote: {
        url: BASE_URL + "auth/check_email",
        type: "post",
        delay: 700,
        dataType: "json",
        data: {
          email: function () {
            return $('#register-form input[name="email"]').val();
          },
          csrf_token: function () {
            return $('#register-form input[name="csrf_token"]').val()
          },
        },
        dataFilter: function (response) {
          const json = JSON.parse(response);

          // 🔄 Update CSRF token
          if (json.csrf) {
            $('input[name="' + json.csrf.name + '"]').val(json.csrf.hash);
          }

          // jQuery Validate expects TRUE or FALSE only
          return json.available === true ? "true" : "false";
        }
      }
    },
    password: {
      required: true,
      minlength: 8,
      maxlength: 64,
      strongPassword: true
    },
    confirm_password: {
      required: true,
      equalTo: "#reg-password"
    }
  };

  const registerMessages = {
    name: {
      required: "Name is required",
      minlength: "Name must be at least 3 characters",
      maxlength: "Name cannot exceed 100 characters"

    },
    email: {
      required: "Email is required",
      email: "Enter a valid email",
      remote: "This email is already registered",
      maxlength: "Email is too long"

    },
    password: {
      required: "Password is required",
      minlength: "Password must be at least 8 characters",
      maxlength: "Password must not exceed 64 characters"

    },
    confirm_password: {
      required: "Confirm password is required",
      equalTo: "Passwords do not match"
    }
  };

  initFormValidation(
    '#register-form',
    registerRules,
    registerMessages,
    registersubmitHandler
  );
}

function registersubmitHandler(form) {
  $.ajax({
    url: $(form).attr("action"),
    type: "POST",
    data: $(form).serialize(),
    dataType: "json",
    success: function (res) {
      // Reset form first
      if (res.success) {
        form.reset();
      }

      if (res.success) {
        floatResponse(res.message, "success");
      } else {
        floatResponse(res.message, "danger");
      }
    },

    error: function (xhr) {
      floatResponse("Error " + xhr.status, "danger");
    }
  });
}

function initLogin() {

  /* ── Show/hide password toggle visibility ── */
  $('body').on('input', '#login-form input[name="password"]', function () {
    var $toggle = $('#togglePassword');
    if ($(this).val().length > 0) {
      $toggle.removeClass('d-none');
    } else {
      $toggle.addClass('d-none');
      $(this).attr('type', 'password');
      $toggle.find('i')
        .removeClass('fa-eye-slash')
        .addClass('fa-eye');
    }
  });

  /* ── Password visibility toggle click ── */
  $('body').on('click', '#togglePassword', function () {
    var $password = $('#login-form input[name="password"]');
    var $icon = $(this).find('i');
    var isPassword = $password.attr('type') === 'password';

    $password.attr('type', isPassword ? 'text' : 'password');
    $icon.toggleClass('fa-eye fa-eye-slash');
  });

  /* ── Validation rules ── */
  var loginRules = {
    email: {
      required: true,
      email: true
    },
    password: {
      required: true
    }
  };

  var loginMessages = {
    email: {
      required: 'Email is required',
      email: 'Enter a valid email'
    },
    password: {
      required: 'Password is required'
    }
  };

  initFormValidation(
    '#login-form',
    loginRules,
    loginMessages,
    loginSubmitHandler
  );
}

function loginSubmitHandler(form) {
  var $form = $(form);
  var $btn = $form.find('#loginBtn');
  var $toggle = $('#togglePassword');

  /* ── Show spinner, disable button ── */
  $btn.addClass('loading');

  /* ── Attach device fingerprint ── */
  $form.find('#device_id').val(getDeviceId());

  $.ajax({
    url: $form.attr('action'),
    type: 'POST',
    data: $form.serialize(),
    dataType: 'json',

    success: function (res) {
      if (res.success) {
        /* Keep spinner — page is navigating, no need to reset */
        showLoader();
        var elapsed = Date.now() - loaderShownAt;
        var remaining = Math.max(0, MIN_SHOW_MS - elapsed);

        setTimeout(function () {
          window.location.href = res.redirect;
        }, remaining);
      } else {
        /* Reset button so user can try again */
        $btn.removeClass('loading');

        /* Clear password field + hide toggle on failure */
        $form.find('input[name="password"]').val('');
        $toggle.addClass('d-none');

        floatResponse(res.message, 'danger');
      }
    },

    error: function () {
      $btn.removeClass('loading');
      floatResponse('Server Unreachable', 'danger');
    }
  });
}

let loginChart = null;

function initDashboard() {
  $.ajax({
    url: BASE_URL + 'dashboard/get_dashboard_data',
    type: "GET",
    dataType: "json",

    beforeSend: function () {
      showLoader();
    },

    success: function (res) {
      // ── Chart ──
      var chartData = {
        labels: ['Total Logins', 'Successful Logins', 'Failed Logins'],
        datasets: [{
          data: [res.chart.total, res.chart.success, res.chart.failed],
          backgroundColor: ['#0d6efd', '#4caf50', 'rgb(255, 60, 0)'],
        }]
      };

      if (loginChart) loginChart.destroy();

      loginChart = new Chart(document.getElementById('loginChart'), {
        type: 'doughnut',
        data: chartData,
        options: {
          responsive: true,
          cutout: '70%',
          plugins: { legend: { position: 'bottom' } }
        }
      });

      // ── Activity table ──
      renderActivityTable(res.activity || []);

      hideLoader();
    },

    error: function () {
      hideLoader();
      floatResponse("Server Unreachable", "danger");
    }
  });
}

function renderActivityTable(rows) {
  var $tbody = $('#activityTableBody');
  var $count = $('#activityCount');

  if (!rows || rows.length === 0) {
    $tbody.html(
      '<tr><td colspan="5">' +
      '<div class="activity-empty">' +
      '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>' +
      '<span>No login activity found.</span>' +
      '</div></td></tr>'
    );
    $count.text('0 records');
    return;
  }

  $count.text(rows.length + ' record' + (rows.length !== 1 ? 's' : ''));

  var html = '';
  $.each(rows, function (i, row) {
    var isSuccess = row.status === 'success';

    var statusBadge = isSuccess
      ? '<span class="badge-status success">' +
      '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>' +
      ' Success</span>'
      : '<span class="badge-status failed">' +
      '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
      ' Failed</span>';

    // Display name if available, fall back to email
    var displayName = row.user_name
      ? row.user_name + '<br><small>' + $('<s>').text(row.email || '').html() + '</small>'
      : $('<s>').text(row.email || '—').html();

    html +=
      '<tr>' +
      '<td><span class="activity-email">' + displayName + '</span></td>' +
      '<td><span class="activity-ip">' + $('<s>').text(row.ip_address || '—').html() + '</span></td>' +
      '<td><span class="activity-device">' + $('<s>').text(row.device_name || row.device || '—').html() + '</span></td>' +
      '<td>' + statusBadge + '</td>' +
      '<td><span class="activity-time">' + $('<s>').text(row.created_at || '—').html() + '</span></td>' +
      '</tr>';
  });

  $tbody.html(html);
}