<?php include __DIR__ . '/../layout/auth_header.php'; ?>

<style>
    .notice-header {
        background: linear-gradient(135deg, #0f172a, #020617);
        padding: 80px 0 40px;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .notice-header h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 3rem;
        color: white;
        margin-bottom: 15px;
    }
    .notice-header p {
        color: #94a3b8;
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .notice-list {
        max-width: 800px;
        margin: 40px auto;
    }
    .notice-item {
        background: #1e293b;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .notice-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        border-color: rgba(59,130,246,0.3);
    }
    .notice-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-size: 0.85rem;
    }
    .notice-date {
        color: #94a3b8;
    }
    .notice-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(16,185,129,0.15);
        color: #34d399;
    }
    .notice-ref {
        background: rgba(255,255,255,0.1);
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        color: #cbd5e1;
    }
    .notice-subject {
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .notice-body {
        color: #cbd5e1;
        font-size: 0.95rem;
        line-height: 1.6;
        white-space: pre-wrap;
    }
    
    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 40px;
    }
    .page-link {
        background: #1e293b;
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.2s;
    }
    .page-link:hover {
        background: #334155;
    }
    .page-link.active {
        background: #3b82f6;
        border-color: #3b82f6;
    }
</style>

<div class="notice-header">
    <div class="container">
        <h1>Public Notice Board</h1>
        <p>Stay updated with the latest official announcements, circulars, and notifications from the FYP Coordination Office.</p>
    </div>
</div>

<div class="container" style="padding-bottom: 80px;">
    <div class="notice-list">
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php elseif (empty($notices)): ?>
            <div class="text-center" style="padding: 60px 20px; color: #94a3b8;">
                <i class="bi bi-inbox fs-1 d-block mb-3" style="opacity:0.3"></i>
                <p>No notices available at this time.</p>
            </div>
        <?php else: ?>
            <?php foreach ($notices as $notice): ?>
                <div class="notice-item">
                    <div class="notice-meta">
                        <span class="notice-badge">Public Notice</span>
                        <div class="d-flex align-items-center gap-3">
                            <?php if (!empty($notice['ref_no'])): ?>
                                <span class="notice-ref">Ref: <?php echo htmlspecialchars($notice['ref_no']); ?></span>
                            <?php endif; ?>
                            <span class="notice-date"><i class="bi bi-calendar3"></i> <?php echo date('F d, Y', strtotime($notice['notice_date'])); ?></span>
                        </div>
                    </div>
                    <h3 class="notice-subject"><?php echo htmlspecialchars($notice['subject']); ?></h3>
                    <div class="notice-body"><?php echo htmlspecialchars(strip_tags($notice['body'])); ?></div>
                </div>
            <?php endforeach; ?>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?>" class="page-link">&laquo; Prev</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i === $currentPage ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>" class="page-link">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
        
    </div>
</div>

<?php include __DIR__ . '/../layout/auth_footer.php'; ?>
