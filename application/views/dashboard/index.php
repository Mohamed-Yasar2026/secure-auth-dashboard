<?php initHeader(); ?>

<section class="home_section">

    <!-- ── TOPBAR ── -->
    <div class="topbar">
        <div class="heading">
            <h3>Secure Authentication Dashboard</h3>
        </div>
        <div class="search_wrapper">
            <span><i class='bx bx-search'></i></span>
            <input type="search" placeholder="Search...">
        </div>
        <div class="user_wrapper">
            <div class="dropdown">
                <a href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= base_url('assets/img/person-circle.svg') ?>" alt="User" class="user-avatar">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a href="<?= base_url('logout') ?>" onclick="showLoader();" class="dropdown-item text-danger">
                            <svg class="ms-2" width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M16 17l5-5-5-5" stroke="currentColor" stroke-width="2" />
                                <path d="M21 12H9" stroke="currentColor" stroke-width="2" />
                                <path d="M13 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="2" />
                            </svg>
                            <span class="ms-2">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Topbar -->

    <!-- ── STAT CARDS ── -->
    <div class="card-boxes">

        <div class="box">
            <div class="right_side">
                <div class="numbers"><?= $total_attempts ?></div>
                <div class="box_topic">Total Login Attempts</div>
            </div>
            <i class="mt-3 fa-solid fa-right-to-bracket"></i>
        </div>

        <div class="box">
            <div class="right_side">
                <div class="numbers"><?= $successful_login ?></div>
                <div class="box_topic">Successful Logins</div>
            </div>
            <i class="mt-3 text-success bx bx-check-shield"></i>
        </div>

        <div class="box">
            <div class="right_side">
                <div class="numbers"><?= $failed_login ?></div>
                <div class="box_topic">Failed Login Attempts</div>
            </div>
            <i class="mt-3 text-warning fa-solid fa-circle-exclamation"></i>
        </div>

        <div class="box">
            <div class="right_side">
                <div class="numbers"><?= $status ?></div>
                <div class="box_topic">Account Status</div>
            </div>
            <i class="mt-3 text-success fa-solid fa-lock"></i>
        </div>

        <div class="box">
            <div class="right_side">
                <div class="numbers"><?= $ip ?></div>
                <div class="box_topic">Last Login IP</div>
            </div>
            <i class="mt-3 text-primary fa-solid fa-globe"></i>
        </div>

        <div class="box">
            <div class="right_side">
                <div class="numbers">MFA Status</div>
                <div class="box_topic">
                    <?php if ($mfa_status) : ?>
                        <span class="text-success fw-bold">Activated</span>
                    <?php else : ?>
                        <span class="text-danger fw-bold">Not Activated</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($mfa_status) : ?>
                <i class="mt-3 text-success bx bx-check-shield"></i>
            <?php else : ?>
                <i class="mt-3 text-danger bx bx-shield-x"></i>
            <?php endif; ?>
        </div>

    </div>
    <!-- End Stat Cards -->

    <!-- ── MAIN DETAILS ── -->
    <div class="details">

        <!-- Login Activity -->
        <div class="recent_project">
            <div class="card_header">
                <h2>Login Activity</h2>
                <span class="activity-count" id="activityCount">—</span>
            </div>
            <div class="activity-table-wrap">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>IP Address</th>
                            <th>Device</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody id="activityTableBody">
                        <tr>
                            <td colspan="5">
                                <div class="activity-empty">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span>Loading activity...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Login Summary -->
        <div class="recent_customers">
            <div class="card_header">
                <h2>Login Summary</h2>
            </div>
            <div class="summary-inner">
                <div class="summary-rates">
                    <div class="summary-rate-item">
                        <span class="summary-rate-value"><?= $success_login_rate ?>%</span>
                        <span class="summary-rate-label success">Login Success Rate</span>
                    </div>
                    <div class="summary-rate-item">
                        <span class="summary-rate-value"><?= $failed_login_rate ?>%</span>
                        <span class="summary-rate-label failed">Login Failed Rate</span>
                    </div>
                </div>
                <div class="chart-box">
                    <canvas id="loginChart"></canvas>
                </div>
            </div>
        </div>

    </div>
    <!-- End Details -->

</section>

<?php initFooter(); ?>

<script>
    initDashboard();
</script>