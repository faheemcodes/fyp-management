import re

with open('src/View/hod/dashboard.php', 'r', encoding='utf-8') as f:
    content = f.read()

# The corrupted part starts after '<i class="bi bi-hash me-1"></i><?php echo htmlspecialchars(['ref_no'] ?? 'N/A'); ?>'
# and ends before '.body-content'

pattern = re.compile(r'(<span class="fw-bold" style="font-family: monospace;font-size: 0\.75rem;color: var\(--text-secondary\);background: rgba\(0,0,0,0\.05\);padding: 4px 8px;border-radius: 6px">\s*<i class="bi bi-hash me-1"></i><\?php echo htmlspecialchars\(\\[\'ref_no\'\] \?\? \'N/A\'\); \?>\s*</span>\s*)(.*?)(    \.body-content {)', re.DOTALL)

replacement = r'''\1
                    </div>
                    <h6 class="fw-bold mb-3 lh-base" style="font-size: 0.85rem; color: var(--text-primary)">
                        <?php echo htmlspecialchars(['subject']); ?>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center pt-2" style="border-top: 1px solid var(--border-color)">
                        <span class="fw-semibold" style="font-size: 0.75rem;color: var(--text-secondary)">
                            <i class="bi bi-calendar3 me-2"></i><?php echo date('M d, Y', strtotime(['notice_date'])); ?>
                        </span>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#noticeModal<?php echo ['id']; ?>" class="btn btn-sm btn-primary rounded-pill px-4 py-1 fw-bold shadow-sm" style="font-size: 0.75rem; border: none;">View</button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if(empty()): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2 text-opacity-50"></i>
                        No recent notices found.
                    </div>
                <?php endif; ?>
            </div>
</div>

<!-- Notice Modals -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Great+Vibes&display=swap" rel="stylesheet">
<style>
@media (max-width: 768px) {
    .notice-modal-dialog { margin: 0.5rem; }
    .letterhead-container { padding: 30px 18px !important; min-height: auto !important; }
    .header-logo-section { gap: 8px !important; margin-bottom: 18px !important; padding-bottom: 10px !important; }
    .header-logo-section img { width: 48px !important; height: 48px !important; }
    .uni-title { font-size: 0.98rem !important; }
    .fac-title { font-size: 0.72rem !important; }
    .dept-title { font-size: 0.68rem !important; }
    .meta-section { font-size: 0.72rem !important; margin-bottom: 18px !important; padding-bottom: 6px !important; }
    .subject-line { font-size: 0.82rem !important; margin-bottom: 15px !important; padding-left: 6px !important; }
\3'''

new_content = re.sub(pattern, replacement, content)

with open('src/View/hod/dashboard.php', 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Repair completed.")
