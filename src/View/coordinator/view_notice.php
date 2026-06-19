<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice: <?php echo htmlspecialchars($notice['subject']); ?> - University of Sindh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
            padding: 40px 0;
        }
        .letterhead-container {
            background: #ffffff;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 50px 60px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
            position: relative;
            min-height: 1000px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .header-logo-section {
            border-bottom: 3px double #000000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .uni-title {
            font-size: 1.55rem;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #0f172a;
        }
        .fac-title {
            font-size: 1.15rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            text-transform: uppercase;
            color: #334155;
            margin-top: 2px;
        }
        .dept-title {
            font-size: 1.05rem;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            margin-top: 2px;
        }
        .meta-section {
            font-size: 1rem;
            margin-bottom: 35px;
        }
        .subject-line {
            font-size: 1.15rem;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 30px;
            text-transform: uppercase;
        }
        .body-content {
            font-size: 1.1rem;
            line-height: 1.7;
            text-align: justify;
            white-space: pre-line;
            margin-bottom: 50px;
            flex-grow: 1;
        }
        .signatures-section {
            margin-top: auto;
            padding-top: 40px;
        }
        .signature-line {
            border-top: 1.5px solid #000000;
            width: 220px;
            margin-top: 60px;
            padding-top: 8px;
            font-size: 0.95rem;
            font-weight: bold;
            color: #1e293b;
        }
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .letterhead-container {
                box-shadow: none;
                border: none;
                padding: 20px 30px;
                width: 100%;
                max-width: 100%;
                min-height: auto;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<!-- Floating Print Button -->
<div class="container text-center mb-4 no-print" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center">
        <a href="javascript:window.close();" class="btn btn-sm btn-secondary rounded-pill px-3 shadow-sm"><i class="bi bi-arrow-left me-1"></i> Close Window</a>
        <button onclick="window.print()" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-printer-fill me-1"></i> Print Notice Letter</button>
    </div>
</div>

<div class="letterhead-container">
    <div>
        <!-- Faculty Header -->
        <div class="header-logo-section text-center">
            <h3 class="uni-title m-0">University of Sindh</h3>
            <h5 class="fac-title m-0">Faculty of Engineering & Technology</h5>
            <h6 class="dept-title m-0">Department of <?php echo htmlspecialchars($coordDept); ?></h6>
            <small class="text-muted" style="font-size: 0.8rem; display: block; margin-top: 5px;">Jamshoro, Sindh, Pakistan</small>
        </div>

        <!-- Ref & Date Section -->
        <div class="meta-section d-flex justify-content-between align-items-center">
            <div>
                <strong>Ref No:</strong> 
                <span class="font-monospace"><?php echo htmlspecialchars($notice['ref_no'] ?? 'SU/FET/FYP/' . date('Y', strtotime($notice['notice_date'])) . '/---'); ?></span>
            </div>
            <div>
                <strong>Date:</strong> 
                <span><?php echo date('F d, Y', strtotime($notice['notice_date'])); ?></span>
            </div>
        </div>

        <!-- Subject Line -->
        <div class="subject-line">
            Subject: <?php echo htmlspecialchars($notice['subject']); ?>
        </div>

        <!-- Body -->
        <div class="body-content">
            <?php echo htmlspecialchars($notice['body']); ?>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signatures-section row">
        <div class="col-6 text-start">
            <div class="signature-line">
                Coordinator
                <div class="small text-muted font-weight-normal mt-1"><?php echo htmlspecialchars($coordName); ?></div>
                <div class="x-small text-muted" style="font-size: 0.72rem; font-weight: normal;">Dept. of <?php echo htmlspecialchars($coordDept); ?></div>
            </div>
        </div>
        <div class="col-6 text-end">
            <div class="d-inline-block text-start">
                <div class="signature-line">
                    Head of Department (HOD)
                    <div class="small text-muted font-weight-normal mt-1"><?php echo htmlspecialchars($hodName); ?></div>
                    <div class="x-small text-muted" style="font-size: 0.72rem; font-weight: normal;">Faculty of Engineering & Tech</div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
