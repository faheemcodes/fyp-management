<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - University of Sindh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <?php
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($basePath === '/') {
        $basePath = '';
    }
    
    // Retrieve old form data and clear it from session safely
    $old = $_SESSION['old'] ?? [];
    if (!isset($_SESSION['has_old_data'])) {
        unset($_SESSION['old']);
    } else {
        unset($_SESSION['has_old_data']);
    }
    ?>
    <link href="<?php echo $basePath; ?>/css/style.css" rel="stylesheet">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-theme');
        }
    </script>
    <style>
        body {
            background: var(--bg-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 10px;
            font-family: 'Outfit', sans-serif;
            color: var(--text-primary);
        }
        .register-card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            background: var(--card-bg);
            overflow: hidden;
            width: 100%;
            max-width: 850px;
        }
        .register-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .btn-primary {
            background-color: #2E5BFF;
            border-color: #2E5BFF;
            box-shadow: 0 4px 12px rgba(46,91,255,0.25);
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #1A40D4;
            border-color: #1A40D4;
        }
        .info-note {
            background: rgba(46, 91, 255, 0.08);
            border-left: 4px solid #2E5BFF;
        }
        .avatar-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            background: var(--card-bg);
            cursor: pointer;
            position: relative;
            transition: all 0.25s ease-in-out;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .avatar-dropzone:hover {
            border-color: #2E5BFF !important;
            background: rgba(46, 91, 255, 0.03);
        }
        .avatar-dropzone.dragover {
            border-color: #1A40D4 !important;
            background: rgba(46, 91, 255, 0.08);
            transform: scale(1.01);
        }
        .avatar-dropzone.has-file {
            border-style: solid;
            border-color: #2E5BFF;
        }
        .avatar-preview-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #2E5BFF;
            box-shadow: 0 4px 12px rgba(46,91,255,0.15);
        }
        .btn-remove-preview {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #EF4444;
            color: white;
            border: none;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(239,68,68,0.3);
            transition: all 0.2s;
        }
        .btn-remove-preview:hover {
            background: #DC2626;
            transform: scale(1.1);
        }
        .form-section-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            background: var(--form-bg);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        }
        .form-section-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #2E5BFF;
            margin-bottom: 18px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-label-sm {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }
    </style>
</head>
<body>

<div class="register-card shadow-lg">
    <div class="register-header">
        <h3 class="fw-bold m-0"><i class="bi bi-mortarboard-fill text-primary"></i> University of Sindh</h3>
        <small class="text-uppercase text-white-50 d-block mt-1 fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Faculty of Engineering and Technology</small>
        <p class="text-muted mb-0 mt-3 text-white-50 small">Final Year Project Portal Registration</p>
    </div>
    
    <div class="p-4 p-sm-5">
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show small rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($old)): ?>
            <div class="alert alert-warning alert-dismissible fade show small rounded-3 shadow-sm" role="alert">
                <i class="bi bi-shield-lock-fill me-2 text-warning fs-5"></i> 
                <strong>Attention Required:</strong> For your security and browser privacy limits, please re-type your <strong>Password</strong> and re-select your <strong>Profile Image / Avatar</strong> file. Other fields have been preserved.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo $basePath; ?>/register" method="POST" enctype="multipart/form-data" id="registerForm">
            
            <!-- Role Selection -->
            <div class="mb-4">
                <label for="role" class="form-label small fw-semibold text-secondary">Choose Account Type</label>
                <select class="form-select bg-light" id="role" name="role" required onchange="toggleFields()">
                    <option value="student" <?php echo (!isset($old['role']) || $old['role'] === 'student') ? 'selected' : ''; ?>>Student</option>
                    <option value="supervisor" <?php echo (isset($old['role']) && $old['role'] === 'supervisor') ? 'selected' : ''; ?>>Teacher / Supervisor</option>
                    <option value="dean" <?php echo (isset($old['role']) && $old['role'] === 'dean') ? 'selected' : ''; ?>>HOD / Dean</option>
                    <option value="admin" <?php echo (isset($old['role']) && $old['role'] === 'admin') ? 'selected' : ''; ?>>System Administrator</option>
                </select>
            </div>

            <!-- Student Form Fields -->
            <div id="studentFields">
                <!-- Academic details card -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <i class="bi bi-mortarboard-fill"></i> Academic Details
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="student_id" class="form-label-sm">Roll No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="student_id" name="student_id" placeholder="e.g. 2k23/SWE/001" pattern="[0-9][Kk][0-9]{2}/[A-Za-z]{2,5}/[0-9]{1,4}" title="Format must match 2k23/SWE/001 or 2K23/SWE/001" value="<?php echo htmlspecialchars($old['student_id'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="student_email" class="form-label-sm">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm bg-light" id="student_email" name="email" placeholder="student@university.edu" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="student_department" class="form-label-sm">Department <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm bg-light" id="student_department" name="student_department">
                                <option value="" disabled <?php echo empty($old['student_department']) ? 'selected' : ''; ?>>Select Department</option>
                                <option value="Information Technology" <?php echo (isset($old['student_department']) && $old['student_department'] === 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                                <option value="Software Engineering" <?php echo (isset($old['student_department']) && $old['student_department'] === 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                                <option value="Data Science" <?php echo (isset($old['student_department']) && $old['student_department'] === 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                                <option value="Electronic Engineering" <?php echo (isset($old['student_department']) && $old['student_department'] === 'Electronic Engineering') ? 'selected' : ''; ?>>Electronic Engineering</option>
                                <option value="Telecommunication Engineering" <?php echo (isset($old['student_department']) && $old['student_department'] === 'Telecommunication Engineering') ? 'selected' : ''; ?>>Telecommunication Engineering</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="student_shift" class="form-label-sm">Shift <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm bg-light" id="student_shift" name="shift" required>
                                <option value="" disabled <?php echo empty($old['shift']) ? 'selected' : ''; ?>>Select Shift</option>
                                <option value="Morning" <?php echo (isset($old['shift']) && $old['shift'] === 'Morning') ? 'selected' : ''; ?>>Morning</option>
                                <option value="Evening" <?php echo (isset($old['shift']) && $old['shift'] === 'Evening') ? 'selected' : ''; ?>>Evening</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Personal Identity card -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <i class="bi bi-person-badge-fill"></i> Personal Identity
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label for="cnic" class="form-label-sm">CNIC No. / B-Form No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="cnic" name="cnic" placeholder="Numbers only (e.g. 3520112345671)" pattern="[0-9]+" title="Enter numbers only, without dashes" value="<?php echo htmlspecialchars($old['cnic'] ?? ''); ?>">
                            <div class="form-text small text-muted mt-0" style="font-size: 0.65rem;">Cannot be changed after registration.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_cnic" class="form-label-sm">Re-Type CNIC No. / B-Form No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="confirm_cnic" name="confirm_cnic" placeholder="Confirm your CNIC No." value="<?php echo htmlspecialchars($old['confirm_cnic'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label for="name" class="form-label-sm">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="name" name="name" placeholder="As in Matric Certificate" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="father_name" class="form-label-sm">Father's Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="father_name" name="father_name" placeholder="As in Matric Certificate" value="<?php echo htmlspecialchars($old['father_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="surname" class="form-label-sm">Surname</label>
                            <input type="text" class="form-control form-control-sm bg-light" id="surname" name="surname" placeholder="e.g. Khan" value="<?php echo htmlspecialchars($old['surname'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Contact & Location card -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <i class="bi bi-geo-alt-fill"></i> Contact & Domicile Location
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-2">
                            <label for="mobile_code" class="form-label-sm">Code <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm bg-light" id="mobile_code" name="mobile_code">
                                <option value="+92" <?php echo (!isset($old['mobile_code']) || $old['mobile_code'] === '+92') ? 'selected' : ''; ?>>+92</option>
                                <option value="+1" <?php echo (isset($old['mobile_code']) && $old['mobile_code'] === '+1') ? 'selected' : ''; ?>>+1</option>
                                <option value="+44" <?php echo (isset($old['mobile_code']) && $old['mobile_code'] === '+44') ? 'selected' : ''; ?>>+44</option>
                                <option value="+971" <?php echo (isset($old['mobile_code']) && $old['mobile_code'] === '+971') ? 'selected' : ''; ?>>+971</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="mobile_no" class="form-label-sm">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control form-control-sm bg-light" id="mobile_no" name="mobile_no" placeholder="3001234567" value="<?php echo htmlspecialchars($old['mobile_no'] ?? ''); ?>">
                        </div>
                        <div class="col-md-5">
                            <label for="gender" class="form-label-sm">Gender <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm bg-light" id="gender" name="gender">
                                <option value="" disabled <?php echo empty($old['gender']) ? 'selected' : ''; ?>>Select Gender</option>
                                <option value="Male" <?php echo (isset($old['gender']) && $old['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($old['gender']) && $old['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo (isset($old['gender']) && $old['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label for="country" class="form-label-sm">Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="country" name="country" placeholder="e.g. Pakistan" value="<?php echo htmlspecialchars($old['country'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="province_state" class="form-label-sm">Province / State <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="province_state" name="province_state" placeholder="e.g. Sindh" value="<?php echo htmlspecialchars($old['province_state'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="district" class="form-label-sm">Domicile District <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="district" name="district" placeholder="e.g. Jamshoro" value="<?php echo htmlspecialchars($old['district'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Account Security card -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <i class="bi bi-shield-lock-fill"></i> Account Security
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="student_password" class="form-label-sm">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm bg-light" id="student_password" name="password" placeholder="Min 8 characters/digits">
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label-sm">Re-Type Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm bg-light" id="confirm_password" name="confirm_password" placeholder="Confirm your password">
                        </div>
                    </div>
                </div>

                <!-- Profile Image card -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <i class="bi ::bi-image-fill"></i> Profile Image Upload
                    </div>
                    <div class="mb-2">
                        <div id="avatar-dropzone" class="avatar-dropzone">
                            <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg, image/jpg" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;">
                            
                            <!-- Default placeholder -->
                            <div id="dropzone-placeholder" class="d-flex flex-column align-items-center">
                                <i class="bi bi-cloud-arrow-up text-primary fs-1 mb-2"></i>
                                <span class="fw-semibold small text-dark mb-1">Drag & drop your photo here, or <span class="text-primary text-decoration-underline">Browse</span></span>
                                <span class="text-muted small" style="font-size: 0.75rem;">Supports JPG, JPEG, PNG (Max 500KB)</span>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle small mt-3" style="font-size: 0.65rem;"><i class="bi bi-clipboard-check me-1"></i> You can also paste (Ctrl+V) image here</span>
                            </div>
                            
                            <!-- Preview State -->
                            <div id="dropzone-preview" class="d-none flex-column align-items-center">
                                <div class="position-relative d-inline-block">
                                    <img id="preview-image" src="#" class="avatar-preview-img" alt="Avatar Preview">
                                    <button type="button" id="btn-remove-avatar" class="btn-remove-preview" title="Remove photo">
                                        <i class="bi bi-x fs-5"></i>
                                    </button>
                                </div>
                                <span id="preview-filename" class="d-block small text-success fw-semibold mt-2">filename.png</span>
                            </div>
                        </div>
                        <div class="form-text small text-muted mt-1" style="font-size: 0.75rem;">Must be a passport-size photo with a **white background** in JPG, JPEG, or PNG format. Maximum size **500KB**. This **cannot be changed** after upload.</div>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="p-3 mb-3 rounded-3 info-note border-0">
                    <p class="small text-primary fw-medium mb-1"><i class="bi bi-info-circle-fill"></i> Important Note</p>
                    <p class="small text-muted m-0" style="font-size: 0.75rem;">If you have already registered with your CNIC or B-Form number, you do not need to register again. You can simply log in to access your existing application form.</p>
                </div>
            </div>

            <!-- Staff/Faculty Form Fields (Supervisor, HOD/Dean, Admin) -->
            <div id="staffFields" class="d-none">
                <!-- Personal details card -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <i class="bi bi-person-fill"></i> Personal Details
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="staff_name" class="form-label-sm">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="staff_name" name="staff_first_name" placeholder="John" value="<?php echo htmlspecialchars($old['staff_first_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="staff_surname" class="form-label-sm">Last Name / Surname <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="staff_surname" name="staff_last_name" placeholder="Doe" value="<?php echo htmlspecialchars($old['staff_last_name'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Professional info card -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <i class="bi bi-briefcase-fill"></i> Professional Information
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label for="staff_email" class="form-label-sm">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm bg-light" id="staff_email" name="staff_email" placeholder="faculty@university.edu" value="<?php echo htmlspecialchars($old['staff_email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="staff_cnic" class="form-label-sm">CNIC No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="staff_cnic" name="staff_cnic" placeholder="Must be entered without dashes" pattern="[0-9]+" title="Enter numbers only, without dashes" value="<?php echo htmlspecialchars($old['staff_cnic'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label for="department" class="form-label-sm">Department <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm bg-light" id="department" name="staff_department">
                                <option value="" disabled <?php echo empty($old['staff_department']) ? 'selected' : ''; ?>>Select Department</option>
                                <option value="Information Technology" <?php echo (isset($old['staff_department']) && $old['staff_department'] === 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                                <option value="Software Engineering" <?php echo (isset($old['staff_department']) && $old['staff_department'] === 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                                <option value="Data Science" <?php echo (isset($old['staff_department']) && $old['staff_department'] === 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                                <option value="Electronic Engineering" <?php echo (isset($old['staff_department']) && $old['staff_department'] === 'Electronic Engineering') ? 'selected' : ''; ?>>Electronic Engineering</option>
                                <option value="Telecommunication Engineering" <?php echo (isset($old['staff_department']) && $old['staff_department'] === 'Telecommunication Engineering') ? 'selected' : ''; ?>>Telecommunication Engineering</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="designation" class="form-label-sm">Designation <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm bg-light" id="designation" name="designation">
                                <option value="" disabled <?php echo empty($old['designation']) ? 'selected' : ''; ?>>Select Designation</option>
                                <option value="Lecturer" <?php echo (isset($old['designation']) && $old['designation'] === 'Lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                                <option value="Assistant Professor" <?php echo (isset($old['designation']) && $old['designation'] === 'Assistant Professor') ? 'selected' : ''; ?>>Assistant Professor</option>
                                <option value="Associate Professor" <?php echo (isset($old['designation']) && $old['designation'] === 'Associate Professor') ? 'selected' : ''; ?>>Associate Professor</option>
                                <option value="Professor" <?php echo (isset($old['designation']) && $old['designation'] === 'Professor') ? 'selected' : ''; ?>>Professor</option>
                                <option value="HOD / Dean" <?php echo (isset($old['designation']) && $old['designation'] === 'HOD / Dean') ? 'selected' : ''; ?>>HOD / Dean</option>
                                <option value="System Admin" <?php echo (isset($old['designation']) && $old['designation'] === 'System Admin') ? 'selected' : ''; ?>>System Admin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label-sm">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm bg-light" id="phone" name="phone" placeholder="e.g. 03001234567" value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Account Security card -->
                <div class="form-section-card">
                    <div class="form-section-title">
                        <i class="bi bi-shield-lock-fill"></i> Account Security
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="staff_password" class="form-label-sm">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm bg-light" id="staff_password" name="staff_password" placeholder="Min 8 characters/digits">
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_staff_password" class="form-label-sm">Re-Type Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm bg-light" id="confirm_staff_password" name="confirm_staff_password" placeholder="Confirm your password">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill mt-4 mb-3">Submit Registration</button>
            
            <div class="text-center small mt-3">
                <span class="text-muted">Already registered?</span> 
                <a href="<?php echo $basePath; ?>/login" class="text-decoration-none fw-semibold">Sign In Here</a>
            </div>

            <!-- Footer Help Text -->
            <div class="text-center mt-5 pt-3 border-top text-muted small">
                <span>Would you like help with any other steps on the admission portal?</span>
                <br>
                <a href="mailto:support@university.edu" class="text-decoration-none text-primary fw-medium"><i class="bi bi-chat-left-dots-fill me-1"></i> Contact Support Helpdesk</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFields() {
    const role = document.getElementById('role').value;
    const studentFields = document.getElementById('studentFields');
    const staffFields = document.getElementById('staffFields');
    
    // Get all input/select elements inside the containers to toggle the 'required' attribute
    const studentInputs = studentFields.querySelectorAll('input, select');
    const staffInputs = staffFields.querySelectorAll('input, select');

    if (role === 'student') {
        studentFields.classList.remove('d-none');
        staffFields.classList.add('d-none');
        
        // Make student fields required (except optional surname)
        studentInputs.forEach(input => {
            if (input.id !== 'surname') {
                input.setAttribute('required', 'required');
            }
        });
        // Remove required from staff fields
        staffInputs.forEach(input => {
            input.removeAttribute('required');
        });
    } else {
        studentFields.classList.add('d-none');
        staffFields.classList.remove('d-none');
        
        // Remove required from student fields
        studentInputs.forEach(input => {
            input.removeAttribute('required');
        });
        // Make staff fields required
        staffInputs.forEach(input => {
            input.setAttribute('required', 'required');
        });
    }
}

// Dropzone and Pastezone implementation
document.addEventListener('DOMContentLoaded', function() {
    toggleFields();
    
    const dropzone = document.getElementById('avatar-dropzone');
    const fileInput = document.getElementById('avatar');
    
    if (dropzone && fileInput) {
        function showAvatarPreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-filename').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                document.getElementById('dropzone-placeholder').classList.add('d-none');
                document.getElementById('dropzone-preview').classList.remove('d-none');
                dropzone.classList.add('has-file');
            };
            reader.readAsDataURL(file);
        }

        function clearAvatarPreview() {
            fileInput.value = '';
            document.getElementById('dropzone-placeholder').classList.remove('d-none');
            document.getElementById('dropzone-preview').classList.add('d-none');
            dropzone.classList.remove('has-file');
        }

        // Drag events
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            
            if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                const file = e.dataTransfer.files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Only image files (JPG, JPEG, PNG) are allowed.');
                    return;
                }
                if (file.size > 500 * 1024) {
                    alert('Profile image size cannot exceed 500KB.');
                    return;
                }
                fileInput.files = e.dataTransfer.files;
                showAvatarPreview(file);
            }
        });

        // Input change event
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Only image files (JPG, JPEG, PNG) are allowed.');
                    this.value = '';
                    return;
                }
                if (file.size > 500 * 1024) {
                    alert('Profile image size cannot exceed 500KB.');
                    this.value = '';
                    return;
                }
                showAvatarPreview(file);
            }
        });

        // Remove button event
        document.getElementById('btn-remove-avatar').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearAvatarPreview();
        });

        // Document-level paste event to support copy-pasting images directly
        document.addEventListener('paste', function(e) {
            const role = document.getElementById('role').value;
            if (role !== 'student') return; // only student has avatar upload
            
            const clipboardItems = (e.clipboardData || e.originalEvent.clipboardData).items;
            for (let i = 0; i < clipboardItems.length; i++) {
                const item = clipboardItems[i];
                if (item.kind === 'file') {
                    const blob = item.getAsFile();
                    if (blob && blob.type.startsWith('image/')) {
                        if (blob.size > 500 * 1024) {
                            alert('Pasted image size cannot exceed 500KB.');
                            return;
                        }
                        
                        const extension = blob.type.split('/')[1] || 'png';
                        const filename = 'pasted_image_' + Math.round(new Date().getTime()/1000) + '.' + extension;
                        const file = new File([blob], filename, { type: blob.type });
                        
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                        
                        showAvatarPreview(file);
                        
                        // Notify user of successful paste
                        const toastMsg = document.createElement('div');
                        toastMsg.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x m-3';
                        toastMsg.style.zIndex = '9999';
                        toastMsg.innerHTML = '<i class="bi bi-clipboard-check-fill me-2"></i> Image pasted successfully!';
                        document.body.appendChild(toastMsg);
                        setTimeout(() => toastMsg.remove(), 2500);
                        
                        e.preventDefault();
                        break;
                    }
                }
            }
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
