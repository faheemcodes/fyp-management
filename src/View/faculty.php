<?php
$pageTitle = 'Faculty & Staff - FYP Management Portal';
include __DIR__ . '/layout/auth_header.php';
?>

<style>
    body {
        background-color: #0f172a;
        color: #f1f5f9;
        font-family: 'Inter', sans-serif;
    }
    .faculty-page-header {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .faculty-page-header h1 {
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: white;
    }
    .faculty-page-header p {
        color: #94a3b8;
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }
    .faculty-section {
        padding: 50px 20px;
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 30px;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 10px;
    }
    .section-title i {
        color: #8b5cf6;
    }
    
    /* ─── Cards from Landing Page ─── */
    .faculty-card {
        background: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 24px 18px 20px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }
    .faculty-card:hover {
        transform: translateY(-5px);
        border-color: rgba(139,92,246,0.2);
        box-shadow: 0 12px 28px rgba(0,0,0,0.25);
    }
    .faculty-avatar {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(59,130,246,0.15));
        color: #a78bfa;
        font-size: 1rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 12px;
        border: 2px solid rgba(255, 255, 255, 0.05);
    }
    .faculty-card h5 { font-weight: 700; margin: 0 0 6px; font-size: 0.88rem; color: white; }
    .faculty-designation {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 5px;
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
    }
    .faculty-designation.professor { background: rgba(245,158,11,0.15); color: #fbbf24; }
    .faculty-designation.associate { background: rgba(139,92,246,0.15); color: #a78bfa; }
    .faculty-designation.assistant { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .faculty-designation.lecturer { background: rgba(16,185,129,0.15); color: #34d399; }
    .faculty-designation.hod { background: rgba(239,68,68,0.15); color: #f87171; }
    .faculty-designation.coordinator { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .faculty-designation.committee { background: rgba(16,185,129,0.15); color: #34d399; }
    .faculty-designation.other { background: rgba(6,182,212,0.15); color: #22d3ee; }
    .faculty-dept { color: #94a3b8; font-size: 0.75rem; margin: 0; }
    .faculty-email {
        display: inline-flex; align-items: center; gap: 6px;
        color: #94a3b8; font-size: 0.75rem; font-weight: 500;
        text-decoration: none; transition: all 0.2s;
        margin-top: 10px;
    }
    .faculty-email:hover { color: #a78bfa; }
    .role-description {
        background: rgba(139,92,246,0.05);
        border: 1px solid rgba(139,92,246,0.1);
        border-left: 3px solid #8b5cf6;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-size: 0.9rem;
        color: #cbd5e1;
    }
</style>

<div class="faculty-page-header">
    <div class="container">
        <h1>Faculty & Staff</h1>
        <p>Meet the dedicated team responsible for guiding, managing, and evaluating Final Year Projects.</p>
    </div>
</div>

<div class="container faculty-section">
    
    <!-- Heads of Department -->
    <?php if (!empty($hods)): ?>
    <h2 class="section-title"><i class="bi bi-bank"></i> Heads of Department (HOD)</h2>
    <div class="role-description">
        HODs oversee the entire FYP process within their respective departments. They manage faculty allocations, ensure standards are maintained, and approve final project grades.
    </div>
    <div class="row g-4 mb-5">
        <?php foreach ($hods as $hod): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="faculty-card">
                    <div class="faculty-avatar">
                        <?php echo strtoupper(substr($hod['name'], 0, 1)); ?>
                    </div>
                    <h5><?php echo htmlspecialchars($hod['name']); ?></h5>
                    <span class="faculty-designation hod">Head of Department</span>
                    <p class="faculty-dept"><?php echo htmlspecialchars(ucfirst($hod['department']) ?? 'Department'); ?></p>
                    <a href="mailto:<?php echo htmlspecialchars($hod['email']); ?>" class="faculty-email"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($hod['email']); ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Coordinators -->
    <?php if (!empty($coordinators)): ?>
    <h2 class="section-title"><i class="bi bi-diagram-3"></i> FYP Coordinators</h2>
    <div class="role-description">
        Coordinators are responsible for the day-to-day administration of the FYP portal. They verify student accounts, manage deadlines, organize defense schedules, and handle official notices.
    </div>
    <div class="row g-4 mb-5">
        <?php foreach ($coordinators as $coord): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="faculty-card">
                    <div class="faculty-avatar">
                        <?php echo strtoupper(substr($coord['name'], 0, 1)); ?>
                    </div>
                    <h5><?php echo htmlspecialchars($coord['name']); ?></h5>
                    <span class="faculty-designation coordinator">FYP Coordinator</span>
                    <a href="mailto:<?php echo htmlspecialchars($coord['email']); ?>" class="faculty-email"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($coord['email']); ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Committee Members -->
    <?php if (!empty($committee)): ?>
    <h2 class="section-title"><i class="bi bi-clipboard-check"></i> Evaluation Committee</h2>
    <div class="role-description">
        Committee Members form the examination panels. They review project proposals, conduct mid-year and final defenses, and evaluate the overall quality and presentation of the projects.
    </div>
    <div class="row g-4 mb-5">
        <?php foreach ($committee as $member): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="faculty-card">
                    <div class="faculty-avatar">
                        <?php echo strtoupper(substr($member['name'], 0, 1)); ?>
                    </div>
                    <h5><?php echo htmlspecialchars($member['name']); ?></h5>
                    <span class="faculty-designation committee">Committee Member</span>
                    <p class="faculty-dept"><?php echo htmlspecialchars(ucfirst($member['department']) ?? 'Department'); ?></p>
                    <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="faculty-email"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($member['email']); ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Supervisors -->
    <?php if (!empty($supervisors)): ?>
    <h2 class="section-title"><i class="bi bi-person-workspace"></i> Project Supervisors</h2>
    <div class="role-description">
        Supervisors directly mentor student groups. They provide technical guidance, track bi-weekly progress, approve documentation, and help students overcome challenges throughout their project journey.
    </div>
    <div class="row g-4 mb-5">
        <?php foreach ($supervisors as $supervisor): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="faculty-card">
                    <div class="faculty-avatar">
                        <?php
                        $initials = '';
                        $nameParts = explode(' ', $supervisor['name']);
                        foreach (array_slice($nameParts, 0, 2) as $part) {
                            $initials .= strtoupper(substr($part, 0, 1));
                        }
                        echo htmlspecialchars($initials);
                        ?>
                    </div>
                    <h5><?php echo htmlspecialchars($supervisor['name']); ?></h5>
                    <?php
                    $desig = $supervisor['designation'] ?? '';
                    $desigClass = 'other';
                    if (stripos($desig, 'Associate') !== false) $desigClass = 'associate';
                    elseif (stripos($desig, 'Assistant') !== false) $desigClass = 'assistant';
                    elseif (stripos($desig, 'Professor') !== false) $desigClass = 'professor';
                    elseif (stripos($desig, 'Lecturer') !== false) $desigClass = 'lecturer';
                    ?>
                    <span class="faculty-designation <?php echo $desigClass; ?>"><?php echo htmlspecialchars($desig); ?></span>
                    <p class="faculty-dept"><?php echo htmlspecialchars($supervisor['department']); ?></p>
                    <a href="mailto:<?php echo htmlspecialchars($supervisor['email']); ?>" class="faculty-email"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($supervisor['email']); ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/layout/auth_footer.php'; ?>
