<!-- Coordinator Meetings Verification View -->
<style>
/* ─── Hero overrides ─── */
.page-hero-icon i {
    font-size: 2rem;
}
</style>

<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-4 text-center text-md-start">
            <div class="page-hero-icon" style="background: rgba(255,255,255,0.15); color: #fff;">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1" style="font-size: 1.25rem; letter-spacing: -0.02em">Meetings Audit</h4>
                <p class="mb-0" style="font-size: 0.85rem; color: rgba(255,255,255,0.7)">Verify completed meetings between students and supervisors in your department</p>
            </div>
        </div>
        <div class="position-relative">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--text-muted);"></i>
            <input type="text" id="meetingSearch" class="form-control search-hero" placeholder="Search meetings..." style="background: var(--form-bg); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 20px; padding-left: 2.5rem; width: 250px; box-shadow: none;">
            <style>
                .search-hero::placeholder { color: var(--text-muted); }
                .search-hero:focus { background: var(--bg-surface); border-color: #3b82f6; outline: none; box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25) !important; }
            </style>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Completed & Waiting for Verification -->
    <div class="col-lg-8">
        <div class="card border-0 p-4 h-100">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(16,185,129,0.1);color: #10b981">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <h6>Pending Verification</h6>
                    <small>Completed meetings waiting for your review</small>
                </div>
            </div>
            
            <?php 
            $pendingMeetings = array_filter($meetings, function($m) {
                return $m['status'] === 'Completed';
            });
            
            if (empty($pendingMeetings)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-check2-circle fs-3 d-block mb-2 text-opacity-50 text-success"></i>
                    <p class="text-muted mb-0 mt-2" style="font-size: 0.85rem">All completed meetings have been verified.</p>
                </div>
            <?php else: ?>
                <ul class="list-unstyled m-0 p-0">
                    <?php foreach ($pendingMeetings as $i => $meeting):
                        $isLast = ($i === array_key_last($pendingMeetings));
                    ?>
                        <li class="meeting-item <?php echo !$isLast ? 'pb-4 mb-4 border-bottom' : ''; ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 1rem;"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                        <span class="badge" style="background: rgba(59,130,246,0.1); color: #3b82f6; font-size: 0.7rem;"><i class="bi bi-folder-fill me-1"></i> <?php echo htmlspecialchars($meeting['project_title']); ?></span>
                                        <span class="badge" style="background: rgba(16,185,129,0.1); color: #10b981; font-size: 0.7rem;"><i class="bi bi-people-fill me-1"></i> <?php echo htmlspecialchars($meeting['group_code'] ?? 'ID PENDING'); ?></span>
                                        <span class="badge" style="background: rgba(139,92,246,0.1); color: #8b5cf6; font-size: 0.7rem;"><i class="bi bi-person-badge-fill me-1"></i> Sup. <?php echo htmlspecialchars(explode(' ', trim($meeting['supervisor_name'] ?? ''))[0]); ?></span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="color: var(--text-primary); font-size: 0.85rem">
                                        <?php echo date('M d, Y', strtotime($meeting['meeting_date'])); ?>
                                    </div>
                                    <div class="text-muted fw-medium" style="font-size: 0.75rem">
                                        <?php echo date('h:i A', strtotime($meeting['meeting_date'])); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($meeting['supervisor_notes'])): ?>
                                <div class="mb-3 p-3 rounded" style="background: var(--form-bg); border-left: 3px solid #10b981;">
                                    <div class="fw-bold mb-1 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em; color: var(--text-secondary)">Supervisor Notes</div>
                                    <p class="small mb-0" style="color: var(--text-secondary); line-height: 1.5; font-size: 0.8rem;"><?php echo nl2br(htmlspecialchars($meeting['supervisor_notes'])); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center gap-1 fw-semibold" style="color: var(--text-secondary); font-size: 0.8rem;">
                                        <i class="bi <?php echo $meeting['type'] === 'Online' ? 'bi-link-45deg fs-5' : 'bi-geo-alt-fill'; ?>"></i>
                                        <?php echo $meeting['type']; ?>
                                    </div>
                                </div>
                                
                                <form action="<?php echo $basePath; ?>/coordinator/meetings/verify" method="POST" class="m-0">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                                    <input type="hidden" name="status" value="Verified">
                                    <button type="submit" class="btn btn-sm btn-success fw-bold px-4" style="font-size: 0.75rem;"><i class="bi bi-shield-check me-1"></i> Verify Meeting</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Verified History -->
    <div class="col-lg-4">
        <div class="card border-0 p-4 h-100">
            <div class="page-section-header mb-4">
                <div class="page-section-icon" style="background: rgba(107,114,128,0.1);color: #6b7280">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h6>Verified History</h6>
                    <small>Past verified meetings</small>
                </div>
            </div>
            
            <?php 
            $verifiedMeetings = array_filter($meetings, function($m) {
                return $m['status'] === 'Verified';
            });
            
            if (empty($verifiedMeetings)): ?>
                <div class="text-center py-4">
                    <p class="text-muted small mb-0 fw-medium">No verified meetings yet.</p>
                </div>
            <?php else: ?>
                <ul class="list-unstyled m-0 p-0">
                    <?php foreach ($verifiedMeetings as $i => $meeting):
                        $isLast = ($i === array_key_last($verifiedMeetings));
                    ?>
                        <li class="meeting-item <?php echo !$isLast ? 'pb-3 mb-3 border-bottom' : ''; ?>">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold" style="font-size: 0.8rem; color: var(--text-primary)"><?php echo date('M d, Y', strtotime($meeting['meeting_date'])); ?></span>
                                <span class="badge" style="background: rgba(16,185,129,0.1); color: #10b981; font-size: 0.65rem;"><i class="bi bi-shield-check"></i> Verified</span>
                            </div>
                            <h6 class="mb-1 fw-bold" style="font-size: 0.85rem; color: var(--text-primary)"><?php echo htmlspecialchars($meeting['subject']); ?></h6>
                            <span class="text-muted d-block mb-2" style="font-size: 0.75rem;">
                                <?php echo htmlspecialchars($meeting['project_title']); ?> • <span class="fw-semibold text-success"><?php echo htmlspecialchars($meeting['group_code'] ?? 'ID PENDING'); ?></span>
                            </span>
                            
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('meetingSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const listItems = document.querySelectorAll('li.meeting-item');
            
            listItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>
</div>
