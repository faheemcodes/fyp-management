<?php
if (!isset($basePath)) {
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($basePath === '/') {
        $basePath = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice: <?php echo htmlspecialchars($notice['subject']); ?> - University of Sindh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts for letterhead, serif body, and handwritten signatures -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a;
            background-image: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
            font-family: 'Lora', Georgia, serif;
            color: #1e293b;
            padding: 50px 0;
            min-height: 100vh;
        }
        .letterhead-container {
            background: #fdfcfb; /* Realistic warm paper color */
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            padding: 60px 70px;
            /* Layered shadow to simulate physical paper elevation */
            box-shadow: 0 0 0 1px rgba(0,0,0,0.08), 
                        0 2px 4px rgba(0,0,0,0.05), 
                        0 10px 20px rgba(0,0,0,0.15), 
                        0 20px 40px rgba(0,0,0,0.1);
            border-radius: 2px;
            position: relative;
            min-height: 1060px; /* Standard A4 proportions */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }
        /* Background Crest Watermark */
        .watermark {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 380px;
            height: 380px;
            opacity: 0.035;
            pointer-events: none;
            z-index: 0;
        }
        .letterhead-content {
            position: relative;
            z-index: 1;
        }
        .header-logo-section {
            border-bottom: 3px double #1e293b;
            padding-bottom: 20px;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        .header-text {
            text-align: left;
        }
        .uni-title {
            font-family: 'Cinzel', serif;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #0f172a;
            line-height: 1.2;
        }
        .fac-title {
            font-family: 'Cinzel', serif;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #334155;
            margin-top: 3px;
        }
        .dept-title {
            font-family: 'Lora', Georgia, serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: #475569;
            margin-top: 3px;
        }
        .meta-section {
            font-size: 0.95rem;
            margin-bottom: 40px;
            color: #334155;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 10px;
        }
        .subject-line {
            font-size: 1.15rem;
            font-weight: bold;
            margin-bottom: 30px;
            color: #0f172a;
            border-left: 3px solid #1e3a8a;
            padding-left: 12px;
        }
        .body-content {
            font-size: 1.05rem;
            line-height: 1.8;
            text-align: justify;
            white-space: pre-line;
            margin-bottom: 60px;
            color: #1e293b;
        }
        .signatures-section {
            position: relative;
            z-index: 1;
            margin-top: auto;
            padding-top: 50px;
        }
        .signature-box {
            position: relative;
            display: inline-block;
            text-align: left;
        }
        /* Handwritten Cursive Signature overlay */
        .signature-cursive {
            font-family: 'Great Vibes', cursive;
            font-size: 2.1rem;
            color: #1d4ed8; /* Blue fountain-pen ink color */
            position: absolute;
            top: -38px;
            left: 20px;
            transform: rotate(-3deg);
            opacity: 0.9;
            pointer-events: none;
            letter-spacing: 1px;
            text-shadow: 1px 1px 1px rgba(29, 78, 216, 0.15);
        }
        .signature-line {
            border-top: 1.5px solid #0f172a;
            width: 230px;
            padding-top: 8px;
            font-size: 0.9rem;
            font-weight: bold;
            color: #0f172a;
        }
        .sign-title {
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #475569;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px 5px !important;
            }
            .letterhead-container {
                padding: 30px 18px !important;
                min-height: auto !important; /* Manage height dynamically on mobile */
            }
            .header-logo-section {
                gap: 8px !important;
                margin-bottom: 18px !important;
                padding-bottom: 10px !important;
            }
            .header-logo-section img {
                width: 48px !important;
                height: 48px !important;
            }
            .uni-title {
                font-size: 0.98rem !important;
            }
            .fac-title {
                font-size: 0.72rem !important;
            }
            .dept-title {
                font-size: 0.68rem !important;
            }
            .meta-section {
                font-size: 0.72rem !important;
                margin-bottom: 18px !important;
                padding-bottom: 6px !important;
            }
            .subject-line {
                font-size: 0.82rem !important;
                margin-bottom: 15px !important;
                padding-left: 6px !important;
            }
            .body-content {
                font-size: 0.78rem !important;
                line-height: 1.55 !important;
                margin-bottom: 30px !important;
            }
            .watermark {
                width: 200px !important;
                height: 200px !important;
            }
            /* Keep signatures side-by-side but scale them nicely */
            .signature-line {
                width: 100% !important;
                max-width: 130px !important;
                font-size: 0.68rem !important;
            }
            .signature-line .small {
                font-size: 0.65rem !important;
            }
            .signature-line .x-small {
                font-size: 0.58rem !important;
            }
            .signature-cursive {
                font-size: 1.15rem !important;
                top: -22px !important;
                left: 5px !important;
            }
            .sign-title {
                font-size: 0.58rem !important;
            }
        }

        @page {
            size: A4 portrait;
            margin: 15mm 20mm;
        }
        @media print {
            html, body {
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .letterhead-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                height: 100% !important;
                min-height: 100% !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
            }
            .no-print {
                display: none !important;
            }
            .signature-cursive {
                color: #1d4ed8 !important; /* Ensure blue ink prints correctly */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<!-- Floating Control Bar -->
<div class="container text-center mb-4 no-print" style="max-width: 820px;">
    <div class="d-flex justify-content-between align-items-center bg-dark bg-opacity-75 rounded-pill p-2 px-3 shadow-lg border border-secondary border-opacity-25">
        <a href="javascript:void(0);" onclick="closeNotice()" class="btn btn-sm btn-outline-light rounded-pill px-3"><i class="bi bi-x-circle me-1"></i> Close Notice</a>
        <span class="text-light small fw-bold d-none d-sm-inline-block"><i class="bi bi-file-earmark-text-fill text-warning me-1"></i> Official Notice Letterhead</span>
        <button onclick="window.print()" class="btn btn-sm btn-primary rounded-pill px-4"><i class="bi bi-printer-fill me-1"></i> Print / Save PDF</button>
    </div>
</div>

<div class="letterhead-container">
    <!-- Watermark Seal -->
    <div class="watermark">
        <img src="<?php echo $basePath; ?>/images/logo.png" alt="FET Watermark" style="width: 100%; height: 100%; object-fit: contain; filter: grayscale(100%);">
    </div>

    <div class="letterhead-content">
        <!-- Faculty Header -->
        <div class="header-logo-section">
            <!-- Colored Crest Emblem -->
            <img src="<?php echo $basePath; ?>/images/logo.png" alt="FET Logo" width="80" height="80" class="d-inline-block align-top" style="object-fit: contain;">
            <div class="header-text">
                <h3 class="uni-title m-0">University of Sindh</h3>
                <h5 class="fac-title m-0">Faculty of Engineering & Technology</h5>
                <h6 class="dept-title m-0">Department of <?php echo htmlspecialchars($coordDept); ?></h6>
                <small class="text-muted" style="font-size: 0.78rem; display: block; margin-top: 3px; font-family: sans-serif; letter-spacing: 0.3px;">Jamshoro, Sindh, Pakistan</small>
            </div>
        </div>

        <!-- Ref & Date Section -->
        <div class="meta-section d-flex justify-content-between align-items-center">
            <div>
                <strong>Ref No:</strong> 
                <span class="font-monospace text-uppercase" style="letter-spacing: 0.5px;"><?php echo htmlspecialchars(!empty($notice['ref_no']) ? $notice['ref_no'] : '--'); ?></span>
            </div>
            <div>
                <strong>Date:</strong> 
                <span><?php echo date('F d, Y', strtotime($notice['notice_date'])); ?></span>
            </div>
        </div>

        <!-- Subject Line -->
        <div class="subject-line">
            <strong>SUBJECT: <?php echo htmlspecialchars($notice['subject']); ?></strong>
        </div>

        <!-- Body -->
        <div class="body-content">
            <?php echo htmlspecialchars($notice['body']); ?>
        </div>
    </div>

    <!-- Signatures Section -->
    <div class="signatures-section row">
        <div class="col-6 text-start">
            <div class="signature-box">
                <!-- Cursive Handwriting Signature Overlay -->
                <div class="signature-cursive">
                    <?php 
                        // Strip prefixes like "Dr." or "Prof." for realistic handwritten look
                        $cleanCoordName = preg_replace('/^(Dr\.|Prof\.|Mr\.|Ms\.)\s+/i', '', $coordName);
                        echo htmlspecialchars($cleanCoordName); 
                    ?>
                </div>
                <div class="signature-line">
                    <div class="sign-title">Coordinator FYP</div>
                    <div class="small text-dark font-weight-normal mt-1"><?php echo htmlspecialchars($coordName); ?></div>
                    <div class="x-small text-muted" style="font-size: 0.72rem; font-weight: normal; font-family: sans-serif;">Dept. of <?php echo htmlspecialchars($coordDept); ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 text-end">
            <div class="signature-box text-start">
                <!-- Cursive Handwriting Signature Overlay -->
                <div class="signature-cursive">
                    <?php 
                        $cleanHodName = preg_replace('/^(Dr\.|Prof\.|Mr\.|Ms\.)\s+/i', '', $hodName);
                        echo htmlspecialchars($cleanHodName); 
                    ?>
                </div>
                <div class="signature-line">
                    <div class="sign-title">Head of Department (HOD)</div>
                    <div class="small text-dark font-weight-normal mt-1"><?php echo htmlspecialchars($hodName); ?></div>
                    <div class="x-small text-muted" style="font-size: 0.72rem; font-weight: normal; font-family: sans-serif;">Faculty of Engineering & Tech</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function closeNotice() {
    window.close();
    setTimeout(function() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = "<?php echo $basePath; ?>/<?php echo $_SESSION['role'] ?? ''; ?>/dashboard";
        }
    }, 150);
}
</script>
</body>
</html>
