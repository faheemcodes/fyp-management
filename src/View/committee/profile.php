<!-- Committee Member Profile View -->
<?php
$prefixVal = $profile['prefix'] ?? '';
$surnameVal = $profile['surname'] ?? '';
$cnicVal = $committee['cnic'] ?? $profile['cnic'] ?? '';
$mobileCodeVal = $profile['mobile_code'] ?? '';
$mobileNoVal = $profile['mobile_no'] ?? '';
$homeAddressVal = $profile['home_address'] ?? '';
$departmentVal = $committee['department'] ?? '';

$isLocked = !empty($profile) && !empty($profile['home_address']) && $profile['home_address'] !== 'Not Provided Yet';

$editableFields = [$prefixVal, $surnameVal, $cnicVal, $mobileNoVal, $homeAddressVal];
$filledCount = 0;
foreach ($editableFields as $f) { if (!empty($f) && $f !== 'Not Provided Yet') $filledCount++; }
$totalEditable = count($editableFields);
$completionPct = $totalEditable > 0 ? round(($filledCount / $totalEditable) * 100) : 0;

$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($basePath === '/') {
    $basePath = '';
}
?>

<style>
/* ─── Profile Page Scoped Styles ─── */
.profile-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: var(--border-radius-lg);
    padding: 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.profile-hero::before {
    content: '';
    position: absolute;
    top: -60%;
    right: -15%;
    width: 340px;
    height: 340px;
    background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.profile-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -10%;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.profile-avatar-ring {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    background: conic-gradient(from 0deg, #3b82f6, #6366f1, #8b5cf6, #3b82f6);
    padding: 3px;
    flex-shrink: 0;
}
.profile-avatar-ring .avatar-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
    font-weight: bold;
    border: 3px solid #0f172a;
}
.profile-quick-stat {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background: rgba(255,255,255,0.06);
    border-radius: 10px;
    color: rgba(255,255,255,0.7);
    font-size: 0.78rem;
    white-space: nowrap;
}
.profile-quick-stat i {
    color: #60a5fa;
    font-size: 0.9rem;
}
.profile-completion {
    height: 4px;
    background: rgba(255,255,255,0.1);
    border-radius: 99px;
    overflow: hidden;
    width: 140px;
}
.profile-completion-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #3b82f6, #6366f1);
    transition: width 0.6s ease;
}

/* ─── Section Panel ─── */
.profile-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    margin-bottom: 20px;
    overflow: hidden;
    transition: box-shadow 0.25s ease;
}
.profile-section:hover {
    box-shadow: 0 4px 20px rgba(59,130,246,0.06);
}
.profile-section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-color);
    background: var(--form-bg);
}
.profile-section-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.profile-section-header h6 {
    font-size: 0.85rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
    letter-spacing: -0.01em;
}
.profile-section-header small {
    font-size: 0.72rem;
    color: var(--text-secondary);
    margin: 0;
}
.profile-section-body {
    padding: 24px;
}

/* ─── Modern Form Group ─── */
.pf-group {
    position: relative;
}
.pf-group .form-label {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--text-secondary);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.pf-group .form-control,
.pf-group .form-select {
    padding: 10px 14px;
    font-size: 0.85rem;
    border-radius: 10px;
}
.pf-group .form-control:disabled,
.pf-group .form-control[readonly],
.pf-group .form-select:disabled {
    background-color: var(--form-bg);
    opacity: 0.65;
    cursor: not-allowed;
}
.pf-locked-tag {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 0.62rem;
    font-weight: 500;
    color: #9ca3af;
    margin-top: 4px;
}
.pf-locked-tag i {
    font-size: 0.58rem;
}

/* ─── Floating Save Footer ─── */
.profile-save-footer {
    position: sticky;
    bottom: 0;
    background: var(--card-bg);
    border-top: 1px solid var(--border-color);
    border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 10;
    backdrop-filter: blur(12px);
}
.profile-save-footer .btn {
    padding: 10px 32px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 10px;
}

/* ─── Alert Banner ─── */
.profile-alert {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 20px;
    border-radius: var(--border-radius-md);
    margin-bottom: 20px;
    border: none;
}
.profile-alert-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.profile-alert h6 {
    font-size: 0.82rem;
    font-weight: 700;
    margin: 0 0 3px 0;
}
.profile-alert p {
    font-size: 0.78rem;
    margin: 0;
    line-height: 1.5;
    opacity: 0.85;
}

/* ─── Address card ─── */
.address-card {
    background: var(--form-bg);
    border: 1.5px solid var(--border-color);
    border-radius: var(--border-radius-md);
    padding: 16px;
    transition: border-color 0.2s ease;
}
.address-card:focus-within {
    border-color: var(--primary-color);
}
.address-card label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--text-secondary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.address-card textarea {
    border: none;
    background: transparent;
    padding: 0;
    resize: vertical;
    font-size: 0.85rem;
    color: var(--text-primary);
}
.address-card textarea:focus {
    box-shadow: none;
    border: none;
}

@media (max-width: 768px) {
    .profile-hero { padding: 24px 16px; }
    .profile-section-body { padding: 16px; }
    .profile-avatar-ring { width: 68px; height: 68px; }
    .profile-quick-stat { display: none; }
}
</style>

<!-- ═══════════════ Hero Banner ═══════════════ -->
<div class="profile-hero">
    <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <!-- Avatar -->
        <div class="profile-avatar-ring">
            <div class="avatar-inner">
                <?php echo strtoupper(substr($committee['name'], 0, 1)); ?>
            </div>
        </div>

        <!-- Info -->
        <div class="flex-grow-1 text-center text-md-start">
            <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                <h4 class="text-white fw-bold m-0" style="font-size: 1.3rem; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars(($prefixVal ? $prefixVal . ' ' : '') . $committee['name']); ?>
                </h4>
                <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25 rounded-pill px-2 py-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Committee</span>
            </div>
            <p class="mb-2" style="color: rgba(255,255,255,0.7); font-size: 0.82rem;">
                <i class="bi bi-shield-lock me-1"></i>Evaluation Committee Member
                <span class="d-none d-md-inline">
                    &nbsp;·&nbsp;
                    <i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($committee['email']); ?>
                </span>
            </p>

            <div class="d-none d-md-flex align-items-center justify-content-center justify-content-md-start gap-3 mt-3">
                <span style="font-size: 0.75rem; color: rgba(255,255,255,0.6); font-weight: 600;">PROFILE SETUP</span>
                <div class="profile-completion">
                    <div class="profile-completion-fill" style="width: <?php echo $completionPct; ?>%;"></div>
                </div>
                <span class="text-white fw-bold" style="font-size: 0.75rem;"><?php echo $completionPct; ?>%</span>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="d-none d-lg-flex gap-2">
            <div class="profile-quick-stat">
                <i class="bi bi-building"></i>
                <?php echo !empty($departmentVal) ? htmlspecialchars($departmentVal) : 'N/A'; ?>
            </div>
            <div class="profile-quick-stat">
                <i class="bi bi-person-vcard"></i>
                <?php echo !empty($cnicVal) ? substr($cnicVal, 0, 5) . '...' : 'Not set'; ?>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Status Alert ═══════════════ -->
<?php if ($isLocked): ?>
<div class="profile-alert" style="background: rgba(59,130,246,0.06); color: #2563eb;">
    <div class="profile-alert-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
        <i class="bi bi-shield-check"></i>
    </div>
    <div>
        <h6>Profile Submitted</h6>
        <p>Your profile is up to date. Official registration details remain locked.</p>
    </div>
</div>
<?php else: ?>
<div class="profile-alert" style="background: rgba(239,68,68,0.06); color: #dc2626;">
    <div class="profile-alert-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>
    <div>
        <h6>Action Required</h6>
        <p>Please complete your profile details below. Official details are locked for integrity.</p>
    </div>
</div>
<?php endif; ?>

<?php
// Display flash messages
if (isset($_SESSION['flash']['success'])) {
    echo '<div class="profile-alert" style="background: rgba(16,185,129,0.06); color: #059669; border: 1px solid rgba(16,185,129,0.2);"><div class="profile-alert-icon" style="background: rgba(16,185,129,0.1); color: #10b981;"><i class="bi bi-check-circle-fill"></i></div><div><h6>Success</h6><p>' . htmlspecialchars($_SESSION['flash']['success']) . '</p></div></div>';
    unset($_SESSION['flash']['success']);
}
if (isset($_SESSION['flash']['error'])) {
    echo '<div class="profile-alert" style="background: rgba(239,68,68,0.06); color: #dc2626; border: 1px solid rgba(239,68,68,0.2);"><div class="profile-alert-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;"><i class="bi bi-x-circle-fill"></i></div><div><h6>Error</h6><p>' . htmlspecialchars($_SESSION['flash']['error']) . '</p></div></div>';
    unset($_SESSION['flash']['error']);
}
?>

<form action="<?php echo $basePath; ?>/committee/profile" method="POST">
    <div class="row g-4">

        <!-- ═══════════════ COLUMN 1: Professional Identity ═══════════════ -->
        <div class="col-lg-6">
            <div class="profile-section h-100">
                <div class="profile-section-header">
                    <div class="profile-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <h6>Professional Identity</h6>
                        <small>Your official academic identity details</small>
                    </div>
                </div>
                <div class="profile-section-body">
                    <div class="row g-3">
                        <div class="col-4 pf-group">
                            <label class="form-label">Prefix <span class="text-danger">*</span></label>
                            <select class="form-select" name="prefix" required <?php echo $isLocked ? 'disabled' : ''; ?>>
                                <option value="" disabled <?php echo empty($prefixVal) ? 'selected' : ''; ?>>Select</option>
                                <option value="Mr."  <?php echo $prefixVal === 'Mr.'   ? 'selected' : ''; ?>>Mr.</option>
                                <option value="Ms."  <?php echo $prefixVal === 'Ms.'   ? 'selected' : ''; ?>>Ms.</option>
                                <option value="Mrs." <?php echo $prefixVal === 'Mrs.'  ? 'selected' : ''; ?>>Mrs.</option>
                                <option value="Dr."  <?php echo $prefixVal === 'Dr.'   ? 'selected' : ''; ?>>Dr.</option>
                                <option value="Prof."<?php echo $prefixVal === 'Prof.' ? 'selected' : ''; ?>>Prof.</option>
                            </select>
                        </div>
                        <div class="col-8 pf-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($committee['name']); ?>" disabled readonly>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">Surname</label>
                            <?php if (empty($surnameVal)): ?>
                                <input type="text" class="form-control border border-warning" name="surname" placeholder="Enter Surname" required>
                                <div class="form-text small text-warning"><i class="bi bi-exclamation-triangle-fill"></i> Missing data</div>
                            <?php else: ?>
                                <input type="text" class="form-control" name="surname" value="<?php echo htmlspecialchars($surnameVal); ?>" <?php echo !empty($surnameVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($surnameVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">CNIC</label>
                            <?php if (empty($cnicVal)): ?>
                                <input type="text" class="form-control border border-warning" name="cnic" placeholder="No dashes" required>
                                <div class="form-text small text-warning"><i class="bi bi-exclamation-triangle-fill"></i> Missing data</div>
                            <?php else: ?>
                                <input type="text" class="form-control" name="cnic" value="<?php echo htmlspecialchars($cnicVal); ?>" <?php echo !empty($cnicVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($cnicVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($departmentVal); ?>" disabled readonly>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">Designation</label>
                            <input type="text" class="form-control" value="Committee Member" disabled readonly>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════ COLUMN 2: Contact & Research ═══════════════ -->
        <div class="col-lg-6">
            <div class="profile-section h-100">
                <div class="profile-section-header">
                    <div class="profile-section-icon" style="background: rgba(13,148,136,0.1); color: #0d9488;">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div>
                        <h6>Contact &amp; Details</h6>
                        <small>Email, phone, and office details</small>
                    </div>
                </div>
                <div class="profile-section-body">
                    <div class="row g-3">
                        <div class="col-12 pf-group">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text" style="border-radius: 10px 0 0 10px; border: 1.5px solid var(--border-color); border-right: 0; background: var(--form-bg); color: var(--text-secondary); font-size: 0.85rem;">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($committee['email']); ?>" disabled readonly style="border-radius: 0 10px 10px 0;">
                            </div>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                        </div>
                        <div class="col-4 pf-group">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <select class="form-select" name="mobile_code" required <?php echo $isLocked ? 'disabled' : ''; ?>>
                                <option value="+92" <?php echo $mobileCodeVal === '+92' || empty($mobileCodeVal) ? 'selected' : ''; ?>>+92</option>
                                <option value="+1" <?php echo $mobileCodeVal === '+1' ? 'selected' : ''; ?>>+1</option>
                                <option value="+44" <?php echo $mobileCodeVal === '+44' ? 'selected' : ''; ?>>+44</option>
                                <option value="+971" <?php echo $mobileCodeVal === '+971' ? 'selected' : ''; ?>>+971</option>
                            </select>
                        </div>
                        <div class="col-8 pf-group">
                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="mobile_no" value="<?php echo htmlspecialchars($mobileNoVal); ?>" placeholder="e.g. 3001234567" required <?php echo $isLocked ? 'disabled readonly' : ''; ?>>
                        </div>
                        <hr style="border-color: var(--border-color); opacity: 0.5; margin: 12px 0;">
                        <div class="col-12">
                            <div class="address-card">
                                <label><i class="bi bi-house-door-fill"></i> Office / Home Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="home_address" name="home_address" rows="3" required placeholder="Enter your office or mailing address..." <?php echo $isLocked ? 'disabled readonly' : ''; ?>><?php echo htmlspecialchars($homeAddressVal !== 'Not Provided Yet' ? $homeAddressVal : ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticky Save Footer -->
                <div class="profile-save-footer">
                    <span style="font-size: 0.78rem; color: var(--text-secondary);">
                        <i class="bi bi-info-circle me-1"></i>Review editable fields
                    </span>
                    <?php if ($isLocked): ?>
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class="bi bi-lock-fill me-2"></i>Submitted
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-2"></i>Save Profile
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>
