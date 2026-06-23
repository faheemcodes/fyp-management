<!-- Student Profile View -->
<?php
$prefixVal = $profile['prefix'] ?? '';
$surnameVal = $profile['surname'] ?? '';
$cnicVal = $profile['cnic'] ?? '';
$cnicExpiryVal = $profile['cnic_expiry'] ?? '';
$fatherNameVal = $profile['father_name'] ?? '';
$dobVal = $profile['dob'] ?? '';
$mobileCodeVal = $profile['mobile_code'] ?? '';
$mobileNoVal = $profile['mobile_no'] ?? '';
$placeOfBirthVal = $profile['place_of_birth'] ?? '';
$countryVal = $profile['country'] ?? '';
$provinceStateVal = $profile['province_state'] ?? '';
$districtVal = $profile['district'] ?? '';
$cityVal = $profile['city'] ?? '';
$homeAddressVal = $profile['home_address'] ?? '';
$permanentAddressVal = $profile['permanent_address'] ?? '';
$zipCodeVal = $profile['zip_code'] ?? '';
$bloodGroupVal = $profile['blood_group'] ?? '';
$genderVal = $profile['gender'] ?? '';

$isLocked = !empty($profile) && !empty($profile['home_address']) && $profile['home_address'] !== 'Not Provided Yet';
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($basePath === '/') { $basePath = ''; }

$avatarFile = !empty($student['avatar']) ? $student['avatar'] : 'default_avatar.svg';

// Count filled vs total editable fields
$editableFields = [$prefixVal, $dobVal, $cnicExpiryVal, $bloodGroupVal, $placeOfBirthVal, $cityVal, $zipCodeVal, $homeAddressVal];
$filledCount = 0;
foreach ($editableFields as $f) { if (!empty($f) && $f !== 'Not Provided Yet' && $f !== '2000-01-01') $filledCount++; }
$totalEditable = count($editableFields);
$completionPct = $totalEditable > 0 ? round(($filledCount / $totalEditable) * 100) : 0;
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
.profile-avatar-ring img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #0f172a;
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
            <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" alt="Profile Photo">
        </div>

        <!-- Info -->
        <div class="flex-grow-1 text-center text-md-start">
            <h4 class="text-white fw-bold mb-1" style="font-size: 1.3rem; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars($student['name']); ?>
            </h4>
            <p class="mb-2" style="color: rgba(255,255,255,0.5); font-size: 0.82rem;">
                <i class="bi bi-mortarboard me-1"></i><?php echo htmlspecialchars($student['student_id'] ?? 'N/A'); ?>
                &nbsp;·&nbsp;
                <i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($student['email']); ?>
            </p>

        </div>

        <!-- Quick Stats -->
        <div class="d-none d-lg-flex gap-2">
            <div class="profile-quick-stat">
                <i class="bi bi-person-vcard"></i>
                <?php echo !empty($cnicVal) ? substr($cnicVal, 0, 5) . '...' : 'Not set'; ?>
            </div>
            <div class="profile-quick-stat">
                <i class="bi bi-droplet-fill"></i>
                <?php echo !empty($bloodGroupVal) ? $bloodGroupVal : '—'; ?>
            </div>
            <div class="profile-quick-stat">
                <i class="bi bi-geo-alt-fill"></i>
                <?php echo !empty($cityVal) ? htmlspecialchars($cityVal) : '—'; ?>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════ Status Alert ═══════════════ -->
<?php if ($isLocked): ?>
    <div class="profile-alert" style="background: rgba(59,130,246,0.06); color: #2563eb;">
        <div class="profile-alert-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div>
            <h6>Profile Locked</h6>
            <p>Your details have been submitted and permanently locked. Contact the Academic Registry for corrections.</p>
        </div>
    </div>
<?php else: ?>
    <div class="profile-alert" style="background: rgba(239,68,68,0.06); color: #dc2626;">
        <div class="profile-alert-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
            <h6>One-Time Submission — Please Read!</h6>
            <p>Some fields are permanently locked from registration. Fill in the remaining fields carefully. Once saved, <strong>all details will be locked permanently</strong>.</p>
        </div>
    </div>
<?php endif; ?>

<form action="<?php echo $basePath; ?>/student/profile" method="POST">
    <div class="row g-4">

        <!-- ═══════════════ COLUMN 1: Personal + Identity ═══════════════ -->
        <div class="col-lg-6">

            <!-- Personal Information -->
            <div class="profile-section">
                <div class="profile-section-header">
                    <div class="profile-section-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <h6>Personal Information</h6>
                        <small>Your basic identity details</small>
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
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" disabled readonly>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">Surname</label>
                            <input type="text" class="form-control" name="surname" value="<?php echo htmlspecialchars($surnameVal); ?>" <?php echo !empty($surnameVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($surnameVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">Father's Name</label>
                            <input type="text" class="form-control" name="father_name" value="<?php echo htmlspecialchars($fatherNameVal); ?>" <?php echo !empty($fatherNameVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($fatherNameVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($dobVal !== '2000-01-01' ? $dobVal : ''); ?>" required <?php echo $isLocked ? 'disabled' : ''; ?>>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">Gender</label>
                            <?php if(!empty($genderVal)): ?>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($genderVal); ?>" disabled readonly>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php else: ?>
                            <select class="form-select" name="gender" <?php echo $isLocked ? 'disabled' : ''; ?>>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identity Documents -->
            <div class="profile-section">
                <div class="profile-section-header">
                    <div class="profile-section-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>
                    <div>
                        <h6>Identity &amp; Health</h6>
                        <small>CNIC and health information</small>
                    </div>
                </div>
                <div class="profile-section-body">
                    <div class="row g-3">
                        <div class="col-6 pf-group">
                            <label class="form-label">CNIC / Form-B</label>
                            <input type="text" class="form-control" name="cnic" value="<?php echo htmlspecialchars($cnicVal); ?>" <?php echo !empty($cnicVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($cnicVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">CNIC Expiry</label>
                            <input type="date" class="form-control" id="cnic_expiry" name="cnic_expiry" value="<?php echo htmlspecialchars($cnicExpiryVal); ?>" <?php echo $isLocked ? 'disabled' : ''; ?>>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">Blood Group</label>
                            <select class="form-select" id="blood_group" name="blood_group" <?php echo $isLocked ? 'disabled' : ''; ?>>
                                <option value="" <?php echo empty($bloodGroupVal) ? 'selected' : ''; ?>>Select</option>
                                <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg): ?>
                                    <option value="<?php echo $bg; ?>" <?php echo $bloodGroupVal === $bg ? 'selected' : ''; ?>><?php echo $bg; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ═══════════════ COLUMN 2: Contact + Address ═══════════════ -->
        <div class="col-lg-6">

            <!-- Contact & Location -->
            <div class="profile-section">
                <div class="profile-section-header">
                    <div class="profile-section-icon" style="background: rgba(13,148,136,0.1); color: #0d9488;">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div>
                        <h6>Contact &amp; Location</h6>
                        <small>Email, phone, and geographic details</small>
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
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" disabled readonly style="border-radius: 0 10px 10px 0;">
                            </div>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                        </div>
                        <div class="col-4 pf-group">
                            <label class="form-label">Code</label>
                            <input type="text" class="form-control" name="mobile_code" value="<?php echo htmlspecialchars($mobileCodeVal); ?>" <?php echo !empty($mobileCodeVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($mobileCodeVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-8 pf-group">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" name="mobile_no" value="<?php echo htmlspecialchars($mobileNoVal); ?>" <?php echo !empty($mobileNoVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($mobileNoVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                        </div>
                        <hr style="border-color: var(--border-color); opacity: 0.5; margin: 4px 0;">
                        <div class="col-6 pf-group">
                            <label class="form-label">Place of Birth</label>
                            <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="<?php echo htmlspecialchars($placeOfBirthVal); ?>" placeholder="e.g. Lahore" <?php echo $isLocked ? 'disabled' : ''; ?>>
                        </div>
                        <div class="col-6 pf-group">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control" name="country" value="<?php echo htmlspecialchars($countryVal); ?>" <?php echo !empty($countryVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($countryVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-4 pf-group">
                            <label class="form-label">Province</label>
                            <input type="text" class="form-control" name="province_state" value="<?php echo htmlspecialchars($provinceStateVal); ?>" <?php echo !empty($provinceStateVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($provinceStateVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-4 pf-group">
                            <label class="form-label">District</label>
                            <input type="text" class="form-control" name="district" value="<?php echo htmlspecialchars($districtVal); ?>" <?php echo !empty($districtVal) ? 'disabled readonly' : ($isLocked ? 'disabled readonly' : ''); ?>>
                            <?php if(!empty($districtVal)): ?>
                            <span class="pf-locked-tag"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-4 pf-group">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($cityVal); ?>" placeholder="e.g. Hyderabad" <?php echo $isLocked ? 'disabled' : ''; ?>>
                        </div>
                        <div class="col-4 pf-group">
                            <label class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo htmlspecialchars($zipCodeVal); ?>" placeholder="e.g. 71000" <?php echo $isLocked ? 'disabled' : ''; ?>>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="profile-section">
                <div class="profile-section-header">
                    <div class="profile-section-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h6>Address Information</h6>
                        <small>Home and permanent addresses</small>
                    </div>
                </div>
                <div class="profile-section-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="address-card">
                                <label><i class="bi bi-house-door-fill"></i> Home / Postal Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="home_address" name="home_address" rows="3" required placeholder="Enter your full home or mailing address..." <?php echo $isLocked ? 'disabled' : ''; ?>><?php echo htmlspecialchars($homeAddressVal !== 'Not Provided Yet' ? $homeAddressVal : ''); ?></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="address-card">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="m-0"><i class="bi bi-building"></i> Permanent Address</label>
                                    <?php if (!$isLocked): ?>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="same_address_cb">
                                            <label class="form-check-label" for="same_address_cb" style="font-size: 0.72rem; color: var(--text-secondary);">Same as home</label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <textarea class="form-control" id="permanent_address" name="permanent_address" rows="3" placeholder="Enter permanent address if different..." <?php echo $isLocked ? 'disabled' : ''; ?>><?php echo htmlspecialchars($permanentAddressVal); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticky Save Footer -->
                <?php if (!$isLocked): ?>
                    <div class="profile-save-footer">
                        <span style="font-size: 0.78rem; color: var(--text-secondary);">
                            <i class="bi bi-info-circle me-1"></i>Review all fields before saving
                        </span>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-2"></i>Save Profile
                        </button>
                    </div>
                <?php else: ?>
                    <div class="profile-save-footer" style="justify-content: center;">
                        <span class="d-inline-flex align-items-center gap-2" style="background: rgba(107,114,128,0.08); color: #6b7280; font-size: 0.82rem; padding: 8px 20px; border-radius: 10px; font-weight: 600;">
                            <i class="bi bi-lock-fill"></i> Profile Submitted & Locked
                        </span>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const homeAddress = document.getElementById('home_address');
    const permAddress = document.getElementById('permanent_address');
    const sameAddressCb = document.getElementById('same_address_cb');

    if (sameAddressCb) {
        sameAddressCb.addEventListener('change', function() {
            if (this.checked) {
                permAddress.value = homeAddress.value;
                permAddress.setAttribute('readonly', 'readonly');
            } else {
                permAddress.removeAttribute('readonly');
            }
        });
        homeAddress.addEventListener('input', function() {
            if (sameAddressCb.checked) {
                permAddress.value = this.value;
            }
        });
    }
});
</script>
