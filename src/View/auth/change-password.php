<!-- User Change Password Form View -->
<?php
$basePath = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
?>
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">
        <div class="card border-0 shadow-sm rounded-3 bg-white p-4 p-sm-5">
            <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-4">
                <div class="avatar bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.5rem;">
                    <i class="bi bi-key-fill"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark m-0">Change Your Password</h4>
                    <small class="text-muted">Ensure your account remains secure by updating your credentials</small>
                </div>
            </div>

            <form action="<?php echo $basePath; ?>/change-password" method="POST">
                <!-- Current Password -->
                <div class="mb-3">
                    <label for="current_password" class="form-label small fw-semibold text-secondary">Current Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control bg-light border-start-0 border-end-0" id="current_password" name="current_password" required placeholder="Enter current password">
                        <button class="btn btn-outline-light border bg-light text-muted border-start-0" type="button" onclick="const el = document.getElementById('current_password'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';" style="border-color: #dee2e6 !important;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <label for="new_password" class="form-label small fw-semibold text-secondary">New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-lock"></i></span>
                        <input type="password" class="form-control bg-light border-start-0 border-end-0" id="new_password" name="new_password" required placeholder="Min 8, Max 50 characters" minlength="8" maxlength="50">
                        <button class="btn btn-outline-light border bg-light text-muted border-start-0" type="button" onclick="const el = document.getElementById('new_password'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';" style="border-color: #dee2e6 !important;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm New Password -->
                <div class="mb-4">
                    <label for="confirm_password" class="form-label small fw-semibold text-secondary">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-lock-fill"></i></span>
                        <input type="password" class="form-control bg-light border-start-0 border-end-0" id="confirm_password" name="confirm_password" required placeholder="Verify new password" minlength="8" maxlength="50">
                        <button class="btn btn-outline-light border bg-light text-muted border-start-0" type="button" onclick="const el = document.getElementById('confirm_password'); el.type = el.type === 'password' ? 'text' : 'password'; this.querySelector('i').className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';" style="border-color: #dee2e6 !important;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Requirements Card -->
                <div class="card border-0 rounded-3 bg-light p-3 mb-4">
                    <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-shield-exclamation text-primary me-1"></i> Password Requirements:</h6>
                    <ul class="list-unstyled m-0 text-muted" style="font-size: 0.8rem; line-height: 1.6;">
                        <li class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 0.75rem;"></i>
                            <span>At least 8 characters in length, but no more than 50</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 0.75rem;"></i>
                            <span>At least one uppercase character (A-Z)</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 0.75rem;"></i>
                            <span>At least one lowercase character (a-z)</span>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 0.75rem;"></i>
                            <span>At least one digit (0-9)</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 0.75rem;"></i>
                            <span>At least one special character [!,@,#,$,%,^,&,*,(,),.]</span>
                        </li>
                    </ul>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                    <a href="<?php echo $basePath; ?>/<?php echo htmlspecialchars($_SESSION['role']); ?>/dashboard" class="btn btn-outline-secondary rounded-pill px-4 py-2 small fw-semibold order-2 order-sm-1">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 small fw-semibold order-1 order-sm-2">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
