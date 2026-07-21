<!-- Coordinator External Assessment Generator View -->
<?php 
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']); 
?>

<style>
/* ─── Hero Section ─── */
.eval-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    border-radius: var(--border-radius-lg);
    padding: 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.eval-hero-icon {
    width: 56px;
    height: 56px;
    background: conic-gradient(from 0deg, #10b981, #059669, #34d399, #10b981);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #fff;
    flex-shrink: 0;
}

/* ─── Builder Panel ─── */
.builder-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    padding: 24px;
    margin-bottom: 20px;
}
.attr-row {
    background: var(--form-bg);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 12px;
    display: flex;
    gap: 16px;
    align-items: flex-end;
    transition: all 0.2s;
}
.attr-row:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
}
.total-tracker {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin-bottom: 24px;
}
.total-tracker h2 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0;
    color: #059669;
}
html.dark-theme .total-tracker h2 { color: #34d399; }
.total-tracker.error {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.2);
}
.total-tracker.error h2 {
    color: #dc2626;
}
html.dark-theme .total-tracker.error h2 { color: #f87171; }

/* ─── Form Select Fix for Dark Mode ─── */
html.dark-theme select option {
    background-color: var(--card-bg, #1e293b);
    color: var(--text-primary, #f1f5f9);
}
</style>

<!-- Hero Section -->
<div class="eval-hero">
    <div class="d-flex align-items-center gap-3 position-relative" style="z-index: 1;">
        <div class="eval-hero-icon shadow-sm">
            <i class="bi bi-file-earmark-excel-fill"></i>
        </div>
        <div>
            <h4 class="text-white fw-bold mb-1" style="font-size: 1.4rem; letter-spacing: -0.02em;">External Assessment Generator</h4>
            <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Build dynamic grading sheets for external evaluators (Max 50 Marks).</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <form action="<?php echo $bp; ?>/coordinator/assessment/generate" method="POST" id="assessmentForm">
            
            <div class="total-tracker" id="totalTracker">
                <p class="text-secondary fw-bold text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 0.05em;">Total Marks Allocated</p>
                <h2 id="totalMarksDisplay">0 / 50</h2>
                <small id="trackerMessage" class="fw-medium text-danger mt-2 d-block">You must allocate exactly 50 marks.</small>
            </div>

            <div class="builder-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0" style="color: var(--text-primary);">Assessment Configuration</h5>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-secondary fw-semibold" style="font-size: 0.8rem;">Select Shift</label>
                    <select class="form-select w-auto" name="shift" style="color: var(--text-primary);" required>
                        <option value="Combined">Combined (Morning & Evening)</option>
                        <option value="Morning">Morning Only</option>
                        <option value="Evening">Evening Only</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                    <h6 class="fw-bold m-0" style="color: var(--text-primary);">Assessment Attributes</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold shadow-sm" id="addAttrBtn">
                        <i class="bi bi-plus-lg me-1"></i> Add Attribute
                    </button>
                </div>

                <div id="attributesContainer">
                    <!-- Default initial row -->
                    <div class="attr-row">
                        <div class="flex-grow-1">
                            <label class="form-label text-secondary fw-semibold" style="font-size: 0.8rem;">Attribute Name</label>
                            <input type="text" class="form-control attr-name bg-transparent" name="attr_names[]" placeholder="e.g. Software Quality" required>
                        </div>
                        <div style="width: 150px;">
                            <label class="form-label text-secondary fw-semibold" style="font-size: 0.8rem;">Max Marks</label>
                            <input type="number" class="form-control attr-marks bg-transparent" name="attr_marks[]" min="1" max="50" placeholder="0" required>
                        </div>
                        <div style="width: 50px;" class="text-end">
                            <button type="button" class="btn btn-outline-danger btn-remove-attr px-2 py-1 border-0" disabled>
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-color);">
                
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted m-0" style="font-size: 0.85rem;"><i class="bi bi-info-circle me-1"></i>This will generate a CSV file containing all approved project groups in the <strong><?php echo htmlspecialchars($department); ?></strong> department.</p>
                    <button type="submit" class="btn btn-success rounded-pill px-5 py-2 fw-bold shadow" id="generateBtn" disabled>
                        <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i>Generate Sheet
                    </button>
                </div>
            </div>
        
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('attributesContainer');
    const addBtn = document.getElementById('addAttrBtn');
    const totalDisplay = document.getElementById('totalMarksDisplay');
    const tracker = document.getElementById('totalTracker');
    const trackerMsg = document.getElementById('trackerMessage');
    const generateBtn = document.getElementById('generateBtn');

    function calculateTotal() {
        let total = 0;
        const markInputs = document.querySelectorAll('.attr-marks');
        markInputs.forEach(input => {
            const val = parseInt(input.value);
            if (!isNaN(val) && val > 0) {
                total += val;
            }
        });

        totalDisplay.textContent = `${total} / 50`;

        if (total === 50) {
            tracker.classList.remove('error');
            trackerMsg.className = 'fw-bold text-success mt-2 d-block';
            trackerMsg.textContent = 'Perfect! You have allocated exactly 50 marks.';
            generateBtn.disabled = false;
        } else {
            tracker.classList.add('error');
            trackerMsg.className = 'fw-medium text-danger mt-2 d-block';
            generateBtn.disabled = true;
            if (total > 50) {
                trackerMsg.textContent = `You have exceeded the limit by ${total - 50} marks.`;
            } else {
                trackerMsg.textContent = `You need to allocate ${50 - total} more marks.`;
            }
        }
    }

    // Add new row
    addBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'attr-row';
        row.innerHTML = `
            <div class="flex-grow-1">
                <label class="form-label text-secondary fw-semibold" style="font-size: 0.8rem;">Attribute Name</label>
                <input type="text" class="form-control attr-name bg-transparent" name="attr_names[]" placeholder="e.g. Presentation Skills" required>
            </div>
            <div style="width: 150px;">
                <label class="form-label text-secondary fw-semibold" style="font-size: 0.8rem;">Max Marks</label>
                <input type="number" class="form-control attr-marks bg-transparent" name="attr_marks[]" min="1" max="50" placeholder="0" required>
            </div>
            <div style="width: 50px;" class="text-end">
                <button type="button" class="btn btn-outline-danger btn-remove-attr px-2 py-1 border-0">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        
        // Add event listeners to new elements
        row.querySelector('.attr-marks').addEventListener('input', calculateTotal);
        row.querySelector('.btn-remove-attr').addEventListener('click', function() {
            row.remove();
            calculateTotal();
            updateRemoveButtons();
        });
        
        updateRemoveButtons();
    });

    // Update initial elements
    document.querySelectorAll('.attr-marks').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.attr-row');
        const removeBtns = document.querySelectorAll('.btn-remove-attr');
        if (rows.length === 1) {
            removeBtns[0].disabled = true;
        } else {
            removeBtns.forEach(btn => btn.disabled = false);
        }
    }

    calculateTotal();
    updateRemoveButtons();
});
</script>
