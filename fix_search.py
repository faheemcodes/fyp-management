import re

with open('src/View/committee/grading_sheet.php', 'r') as f:
    content = f.read()

# 1. Add Search Bar
search_html = """<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold"><?php echo htmlspecialchars($stage); ?> Grading</h1>
    <div class="d-flex gap-3 align-items-center">
        <div class="input-group shadow-sm" style="max-width: 350px;">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" id="gradingSearch" class="form-control border-start-0 ps-0" placeholder="Search ID, Name, Supervisor...">
        </div>
        <a href="<?php echo $bp; ?>/committee/evaluations/print?stage=<?php echo urlencode($stage); ?>" class="btn btn-outline-primary shadow-sm" target="_blank" style="white-space: nowrap;">
            <i class="bi bi-printer me-1"></i> Print Blank Sheet
        </a>
    </div>
</div>"""

content = re.sub(
    r'<div class="d-flex justify-content-between align-items-center mb-4">\s*<h1 class="h3 mb-0 text-gray-800 fw-bold"><\?php echo htmlspecialchars\(\$stage\); \?> Grading</h1>\s*<a href="[^"]*" class="btn btn-outline-primary shadow-sm" target="_blank">\s*<i class="bi bi-printer me-1"></i> Print Blank Sheet\s*</a>\s*</div>',
    search_html,
    content
)

# 2. Convert single tbody to multiple tbodys in both IF and ELSEIF blocks
# For the first block:
content = content.replace(
    '                <tbody>\n                    <?php \n                    $srNo = 1;\n                    foreach ($grouped as $groupId => $members): \n                        $numMembers = count($members);\n                        $firstMember = $members[0];\n                    ?>',
    '                <?php \n                $srNo = 1;\n                foreach ($grouped as $groupId => $members): \n                    $numMembers = count($members);\n                    $firstMember = $members[0];\n                ?>\n                <tbody class="eval-group-tbody">'
)
# For the second block:
content = content.replace(
    '                <tbody>\n                    <?php \n                    $srNo = 1;\n                    foreach ($grouped as $groupId => $members): \n                        $numMembers = count($members);\n                        $firstMember = $members[0];\n                    ?>',
    '                <?php \n                $srNo = 1;\n                foreach ($grouped as $groupId => $members): \n                    $numMembers = count($members);\n                    $firstMember = $members[0];\n                ?>\n                <tbody class="eval-group-tbody">'
)

content = content.replace(
    '                    <?php endforeach; ?>\n                    <?php if (empty($grouped)): ?>',
    '                </tbody>\n                <?php endforeach; ?>\n                <tbody>\n                    <?php if (empty($grouped)): ?>'
)

# 3. Add JS Script at the end
js_script = """
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('gradingSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const tbodies = document.querySelectorAll('.eval-group-tbody');
            
            tbodies.forEach(tbody => {
                const text = tbody.innerText.toLowerCase();
                if (text.includes(term)) {
                    tbody.style.display = '';
                } else {
                    tbody.style.display = 'none';
                }
            });
        });
    }
});
</script>
"""

if "gradingSearch" not in content:
    content += js_script

with open('src/View/committee/grading_sheet.php', 'w') as f:
    f.write(content)

print("Updated grading_sheet.php")
