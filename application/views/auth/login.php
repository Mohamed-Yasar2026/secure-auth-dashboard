<?php $this->load->view('layouts/header') ?>

<div class="containers" id="containers">

  <!-- ── REGISTER PANEL ── -->
  <div class="form-container sign-up">
    <?= form_open('auth/register', ['id' => 'register-form', 'autocomplete' => 'off']) ?>
    <h2>Create Account</h2>

    <input type="text" class="form-control" name="name" placeholder="Name" />
    <input type="email" class="form-control" name="email" placeholder="Email" />
    <input type="password" class="form-control" name="password" placeholder="Password" id="reg-password" />
    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" />

    <button type="submit" class="btn-auth" id="registerBtn">
      <span class="btn-auth-text">Sign Up</span>
      <span class="btn-auth-spinner">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        &nbsp;Please wait...
      </span>
    </button>
    <?= form_close() ?>
  </div>

  <!-- ── LOGIN PANEL ── -->
  <div class="form-container sign-in">
    <?= form_open('auth/login', ['id' => 'login-form', 'autocomplete' => 'off']) ?>
    <h1>Sign In</h1>

    <!-- Email -->
    <input type="email" class="form-control mb-3" name="email" placeholder="Email">

    <!-- Password with toggle -->
    <div class="position-relative mb-3 w-100">
      <input type="password" class="form-control pe-5" name="password" id="password" placeholder="Password">
      <span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" style="cursor:pointer;z-index:5;" id="togglePassword">
        <i class="fa-solid fa-eye"></i>
      </span>
    </div>

    <input type="hidden" name="device_id" id="device_id">

    <!-- Submit with spinner -->
    <button type="submit" class="btn btn-primary w-100 btn-auth" id="loginBtn">
      <span class="btn-auth-text">
        Sign In
      </span>
      <span class="btn-auth-spinner">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        &nbsp;Signing in...
      </span>
    </button>

    <?= form_close() ?>
  </div>

  <!-- ── TOGGLE PANEL ── -->
  <div class="toggle-container">
    <div class="toggle">
      <div class="toggle-panel toggle-left">
        <h1>Welcome Back!</h1>
        <p>Enter your personal details to use all of site features</p>
        <button class="hidden" id="login">Sign In</button>
      </div>
      <div class="toggle-panel toggle-right">
        <h1>Hello, Friend!</h1>
        <p>Register with your personal details to use all of site features</p>
        <button class="hidden" id="register">Sign Up</button>
      </div>
    </div>
  </div>

</div>

<style>
  /* ── Spinner state — hidden by default, shown when .loading added ── */
  .btn-auth .btn-auth-spinner {
    display: none;
  }

  .btn-auth .btn-auth-text {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .btn-auth.loading .btn-auth-text {
    display: none;
  }

  .btn-auth.loading .btn-auth-spinner {
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .btn-auth.loading {
    opacity: .8;
    pointer-events: none;
    cursor: not-allowed;
  }
</style>

<?php $this->load->view('layouts/footer') ?>