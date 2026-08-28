<script>
function viewThesisOffcanvas(path) {
    if (window.innerWidth <= 768) {
        window.open('<?php echo $basePath ?? ''; ?>/' + path, '_blank');
        return;
    }
    
    var offcanvasEl = document.getElementById('thesisOffcanvas');
    var iframe = document.getElementById('thesisIframe');
    iframe.src = '<?php echo $basePath ?? ''; ?>/' + path;
    var offcanvas = new bootstrap.Offcanvas(offcanvasEl);
    
    const openModal = document.querySelector('.modal.show');
    if(openModal) {
        const modalInstance = bootstrap.Modal.getInstance(openModal);
        if(modalInstance) modalInstance.hide();
    }
    
    offcanvas.show();
}
</script>

<!-- Thesis Document Offcanvas Viewer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="thesisOffcanvas" aria-labelledby="thesisOffcanvasLabel" style="width: 70vw; max-width: 100vw;">
    <div class="offcanvas-header border-bottom py-3" style="background: var(--card-bg);">
        <h6 class="offcanvas-title fw-bold mb-0" style="color: var(--text-primary); font-size: 1.1rem; letter-spacing: -0.01em;">Final Thesis Document</h6>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>
    <div class="offcanvas-body p-0" style="background: #f8fafc;">
        <iframe id="thesisIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
    </div>
</div>
