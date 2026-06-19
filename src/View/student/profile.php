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
if ($basePath === '/') {
    $basePath = '';
}
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
            
            <!-- Profile Header with avatar centered & placed above name -->
            <div class="text-center border-bottom pb-4 mb-4 position-relative">
                <span class="badge bg-secondary text-uppercase py-2 px-3 position-absolute end-0 top-0 d-none d-sm-inline-block">Student Portal</span>
                <div class="d-inline-block mb-3">
                    <?php $avatarFile = !empty($student['avatar']) ? $student['avatar'] : 'default_avatar.svg'; ?>
                    <img src="<?php echo $basePath; ?>/uploads/avatars/<?php echo htmlspecialchars($avatarFile); ?>" class="rounded-circle border border-3 border-primary shadow-sm" style="width: 100px; height: 100px; object-fit: cover;" alt="Profile photo">
                </div>
                <h4 class="fw-bold text-dark m-0"><?php echo htmlspecialchars($student['name']); ?></h4>
                <p class="text-muted small m-0 mt-1">Roll No: <?php echo htmlspecialchars($student['student_id'] ?? 'N/A'); ?> &bull; Student Portal</p>
            </div>

            <!-- Lock and Warning Alert Banners -->
            <?php if ($isLocked): ?>
                <div class="alert alert-info rounded-3 shadow-sm d-flex align-items-center mb-4 border-0 bg-info bg-opacity-10 text-info" role="alert">
                    <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                    <div>
                        <strong class="d-block">Profile Details Locked</strong>
                        Your personal profile details have been submitted and are locked. If you need to make any corrections, please contact the Academic Registry.
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning rounded-3 shadow-sm d-flex align-items-center mb-4 border-0 bg-warning bg-opacity-10 text-warning" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                    <div>
                        <strong class="d-block">Important Notice: One-Time Submission Only!</strong>
                        Some fields have been made permanently locked from your registration form. Please fill in the remaining fields. Once saved, **all remaining details will also be locked permanently**.
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo $basePath; ?>/student/profile" method="POST">
                
                <!-- Section 1: Basic Identity (Registration Permanent Fields + Editable Prefix/DOB/CNIC Expiry) -->
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-person-badge me-2"></i>Personal Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-4 col-md-2">
                        <label class="form-label small fw-semibold text-secondary">Prefix <span class="text-danger">*</span></label>
                        <select class="form-select bg-light" name="prefix" required <?php echo $isLocked ? 'disabled' : ''; ?>>
                            <option value="" disabled <?php echo empty($prefixVal) ? 'selected' : ''; ?>>Select</option>
                            <option value="Mr." <?php echo $prefixVal === 'Mr.' ? 'selected' : ''; ?>>Mr.</option>
                            <option value="Ms." <?php echo $prefixVal === 'Ms.' ? 'selected' : ''; ?>>Ms.</option>
                            <option value="Mrs." <?php echo $prefixVal === 'Mrs.' ? 'selected' : ''; ?>>Mrs.</option>
                            <option value="Dr." <?php echo $prefixVal === 'Dr.' ? 'selected' : ''; ?>>Dr.</option>
                            <option value="Prof." <?php echo $prefixVal === 'Prof.' ? 'selected' : ''; ?>>Prof.</option>
                        </select>
                    </div>
                    
                    <div class="col-8 col-md-5">
                        <label class="form-label small fw-semibold text-secondary">Full Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>
                    
                    <div class="col-12 col-md-5">
                        <label class="form-label small fw-semibold text-secondary">Surname</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($surnameVal); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Father's Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($fatherNameVal); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="dob" class="form-label small fw-semibold text-secondary">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control bg-light" id="dob" name="dob" value="<?php echo htmlspecialchars($dobVal !== '2000-01-01' ? $dobVal : ''); ?>" required <?php echo $isLocked ? 'disabled' : ''; ?>>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Gender</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($genderVal); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">CNIC / Form-B</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($cnicVal); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">CNIC Expiry Date</label>
                        <input type="date" class="form-control bg-light" id="cnic_expiry" name="cnic_expiry" value="<?php echo htmlspecialchars($cnicExpiryVal); ?>" <?php echo $isLocked ? 'disabled' : ''; ?>>
                    </div>

                    <div class="col-md-4">
                        <label for="blood_group" class="form-label small fw-semibold text-secondary">Blood Group</label>
                        <select class="form-select bg-light" id="blood_group" name="blood_group" <?php echo $isLocked ? 'disabled' : ''; ?>>
                            <option value="" <?php echo empty($bloodGroupVal) ? 'selected' : ''; ?>>Select Group</option>
                            <option value="A+" <?php echo $bloodGroupVal === 'A+' ? 'selected' : ''; ?>>A+</option>
                            <option value="A-" <?php echo $bloodGroupVal === 'A-' ? 'selected' : ''; ?>>A-</option>
                            <option value="B+" <?php echo $bloodGroupVal === 'B+' ? 'selected' : ''; ?>>B+</option>
                            <option value="B-" <?php echo $bloodGroupVal === 'B-' ? 'selected' : ''; ?>>B-</option>
                            <option value="AB+" <?php echo $bloodGroupVal === 'AB+' ? 'selected' : ''; ?>>AB+</option>
                            <option value="AB-" <?php echo $bloodGroupVal === 'AB-' ? 'selected' : ''; ?>>AB-</option>
                            <option value="O+" <?php echo $bloodGroupVal === 'O+' ? 'selected' : ''; ?>>O+</option>
                            <option value="O-" <?php echo $bloodGroupVal === 'O-' ? 'selected' : ''; ?>>O-</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <!-- Section 2: Contact & Location (Registration Permanent Fields + Editable Place of Birth/City/Zip) -->
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-telephone me-2"></i>Contact & Place of Birth</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>

                    <div class="col-3 col-md-2">
                        <label class="form-label small fw-semibold text-secondary">Code</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($mobileCodeVal); ?>" disabled readonly>
                    </div>

                    <div class="col-9 col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Mobile Number</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($mobileNoVal); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="place_of_birth" class="form-label small fw-semibold text-secondary">Place of Birth</label>
                        <input type="text" class="form-control bg-light" id="place_of_birth" name="place_of_birth" value="<?php echo htmlspecialchars($placeOfBirthVal); ?>" placeholder="e.g. Lahore" <?php echo $isLocked ? 'disabled' : ''; ?>>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Country</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($countryVal); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Domicile Province / State</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($provinceStateVal); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>

                    <div class="col-6 col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Domicile District</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($districtVal); ?>" disabled readonly>
                        <div class="form-text small text-muted">Permanent (Registration-locked).</div>
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="city" class="form-label small fw-semibold text-secondary">City</label>
                        <input type="text" class="form-control bg-light" id="city" name="city" value="<?php echo htmlspecialchars($cityVal); ?>" placeholder="e.g. Lahore" <?php echo $isLocked ? 'disabled' : ''; ?>>
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="zip_code" class="form-label small fw-semibold text-secondary">Zip / Postal Code</label>
                        <input type="text" class="form-control bg-light" id="zip_code" name="zip_code" value="<?php echo htmlspecialchars($zipCodeVal); ?>" placeholder="e.g. 54000" <?php echo $isLocked ? 'disabled' : ''; ?>>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <!-- Section 3: Addresses (Editable) -->
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-geo-alt me-2"></i>Address Information</h5>
                
                <div class="mb-4">
                    <label for="home_address" class="form-label small fw-semibold text-secondary">Home Address / Postal Address <span class="text-danger">*</span></label>
                    <textarea class="form-control bg-light" id="home_address" name="home_address" rows="3" required placeholder="Enter your full home or mailing address..." <?php echo $isLocked ? 'disabled' : ''; ?>><?php echo htmlspecialchars($homeAddressVal !== 'Not Provided Yet' ? $homeAddressVal : ''); ?></textarea>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label for="permanent_address" class="form-label small fw-semibold text-secondary m-0">Permanent Address</label>
                        <?php if (!$isLocked): ?>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="same_address_cb">
                                <label class="form-check-label small text-muted" for="same_address_cb">Same as Home Address</label>
                            </div>
                        <?php endif; ?>
                    </div>
                    <textarea class="form-control bg-light" id="permanent_address" name="permanent_address" rows="3" placeholder="Enter permanent address if different..." <?php echo $isLocked ? 'disabled' : ''; ?>><?php echo htmlspecialchars($permanentAddressVal); ?></textarea>
                </div>

                <!-- Action Button / Submitted lock badge -->
                <?php if (!$isLocked): ?>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="bi bi-save me-2"></i>Save Profile Data
                        </button>
                    </div>
                <?php else: ?>
                    <div class="text-end mt-4">
                        <span class="badge bg-secondary px-4 py-2.5 rounded-pill fs-6 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-lock-fill"></i> Submitted & Locked
                        </span>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

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
