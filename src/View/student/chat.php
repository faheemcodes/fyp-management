<?php
$title = 'Chat with Supervisor';
$bp = dirname($_SERVER['SCRIPT_NAME']) === '/' || dirname($_SERVER['SCRIPT_NAME']) === '\\' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$studentName = $_SESSION['name'] ?? 'Student';
$studentAvatar = $_SESSION['avatar'] ?? '';
?>

<style>
/* Modern Chat UI */
.chat-container {
    height: 85vh;
    min-height: 600px;
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    background: var(--surface-color);
    border-radius: var(--border-radius-xl);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.05);
}
.chat-header {
    padding: 12px 20px;
    background: var(--surface-color);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    gap: 12px;
}
.chat-header-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: conic-gradient(from 0deg, var(--primary-color), #3b82f6, #1d4ed8, var(--primary-color));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
.chat-messages {
    flex-grow: 1;
    padding: 16px 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: var(--body-bg);
}
.chat-message {
    max-width: 85%;
    clear: both;
}
.chat-message.sent { align-self: flex-end; }
.chat-message.received { align-self: flex-start; }

.message-bubble {
    display: inline-block;
    position: relative;
    padding: 7px 12px 22px 12px;
    border-radius: 16px;
    font-size: 0.88rem;
    line-height: 1.45;
    overflow-wrap: break-word;
    word-wrap: break-word;
    min-width: 120px;
}
.chat-message.sent .message-bubble {
    background: #2e3033; /* Dark grey from screenshot */
    color: #ffffff;
    border-bottom-right-radius: 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.15);
}
.chat-message.received .message-bubble {
    background: var(--card-bg, #ffffff);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.15);
}
.message-meta {
    position: absolute;
    right: 10px;
    bottom: 3px;
    font-size: 0.6rem;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 2px;
}
.chat-message.sent .message-meta { 
    color: rgba(255, 255, 255, 0.6); 
}
.chat-message.received .message-meta { 
    color: var(--text-secondary); 
}

.chat-input-area {
    padding: 0;
    background: transparent;
    border: none;
}
.chat-input-inner {
    max-width: 800px;
    margin: 0 auto;
    padding: 10px 16px;
}
.chat-input-row {
    display: flex;
    align-items: flex-end;
    gap: 8px;
}
.chat-input-row .btn-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 1.15rem;
    color: var(--text-secondary);
    background: var(--form-bg, #f0f2f5);
}
.chat-input-row .btn-icon:hover {
    background: var(--border-color, #e2e5e9);
    color: var(--text-primary);
}
.chat-input-row .btn-send {
    background: var(--primary-color);
    color: #fff;
}
.chat-input-row .btn-send:hover {
    opacity: 0.9;
}
.chat-textarea-wrap {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    background: var(--form-bg, #f0f2f5);
    border-radius: 22px;
    padding: 0;
    transition: border-color 0.2s;
    border: 2px solid transparent;
}
.chat-textarea-wrap.drag-over {
    border-color: var(--primary-color);
    background: rgba(79,124,247,0.06);
}
.chat-textarea-wrap textarea {
    border: none;
    background: transparent;
    resize: none;
    outline: none;
    padding: 10px 16px;
    font-size: 0.88rem;
    line-height: 1.5;
    min-height: 42px;
    max-height: 112px;
    overflow-y: hidden;
    width: 100%;
    box-sizing: border-box;
}
.chat-textarea-wrap textarea:focus {
    box-shadow: none;
}
.chat-textarea-wrap textarea {
    color: var(--text-primary);
}
.chat-textarea-wrap textarea::placeholder {
    color: var(--text-secondary);
}
/* File preview chip inside textarea wrap */
.file-chip {
    display: none;
    align-items: center;
    gap: 8px;
    margin: 6px 10px 4px 10px;
    padding: 6px 10px;
    background: var(--card-bg, #fff);
    border-radius: 12px;
    border: 1px solid var(--border-color, #e2e5e9);
    animation: chipSlideIn 0.2s ease;
}
.file-chip.active {
    display: flex;
}
@keyframes chipSlideIn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.file-chip-thumb {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
}
.file-chip-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.1rem;
}
.file-chip-icon.pdf  { background: #fde8e8; color: #e53e3e; }
.file-chip-icon.word { background: #dbeafe; color: #2563eb; }
.file-chip-icon.excel{ background: #d1fae5; color: #059669; }
.file-chip-icon.ppt  { background: #fef3c7; color: #d97706; }
.file-chip-icon.img  { background: #ede9fe; color: #7c3aed; }
.file-chip-icon.generic { background: #e5e7eb; color: #6b7280; }
.file-chip-info {
    flex: 1;
    min-width: 0;
    overflow: hidden;
}
.file-chip-name {
    font-size: 0.78rem;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    color: var(--text-primary);
}
.file-chip-size {
    font-size: 0.65rem;
    color: var(--text-secondary);
}
.file-chip-remove {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: none;
    background: rgba(128,128,128,0.1);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.7rem;
    flex-shrink: 0;
    transition: background 0.2s;
}
.file-chip-remove:hover {
    background: rgba(220,53,69,0.12);
    color: #dc3545;
}
/* Drag overlay */
.drag-overlay {
    display: none;
    position: absolute;
    inset: 0;
    background: rgba(79,124,247,0.08);
    border: 2px dashed var(--primary-color);
    border-radius: 22px;
    z-index: 5;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}
.drag-overlay.show {
    display: flex;
}
.drag-overlay span {
    background: var(--primary-color);
    color: #fff;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .chat-container {
        height: calc(100vh - 60px);
        height: calc(100dvh - 60px);
        min-height: auto;
        width: 100vw;
        max-width: 100vw;
        position: fixed;
        top: 60px;
        left: 0;
        z-index: 1000;
        border-radius: 0;
        border: none;
        margin: 0;
    }
    .chat-message {
        max-width: 90%;
    }
    .msg-actions-btn {
        opacity: 1 !important;
    }
}

.chat-message:has(.dropdown-menu.show) {
    z-index: 10;
}
.message-meta .dropdown-menu {
    z-index: 1050;
}

/* Message truncation - View more */
.msg-text {
    white-space: pre-wrap;
}
.msg-text.truncated {
    display: -webkit-box;
    -webkit-line-clamp: 5;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.msg-view-more {
    display: inline-block;
    margin-top: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
    opacity: 0.8;
}
.msg-view-more:hover { opacity: 1; }
.chat-message.sent .msg-view-more { color: rgba(255,255,255,0.85); }
.chat-message.received .msg-view-more { color: var(--primary-color); }
@media (max-width: 768px) {
    .msg-text.truncated {
        -webkit-line-clamp: 7;
    }
}

/* WhatsApp-style File Attachments */
.file-img-wrap {
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    position: relative;
    max-width: 280px;
}
.file-img-wrap img {
    display: block;
    width: 100%;
    max-height: 260px;
    object-fit: cover;
    border-radius: 10px;
    transition: filter 0.2s;
}
.file-img-wrap:hover img {
    filter: brightness(0.92);
}
.file-img-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 6px 10px;
    background: linear-gradient(transparent, rgba(0,0,0,0.45));
    border-radius: 0 0 10px 10px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.25s;
}
.file-img-wrap:hover .file-img-overlay {
    opacity: 1;
}
.file-img-overlay i {
    color: #fff;
    font-size: 0.75rem;
}

.file-doc-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 10px;
    text-decoration: none !important;
    cursor: pointer;
    transition: background 0.2s;
    min-width: 200px;
    max-width: 280px;
}
.chat-message.sent .file-doc-card {
    background: rgba(255,255,255,0.13);
}
.chat-message.sent .file-doc-card:hover {
    background: rgba(255,255,255,0.2);
}
.chat-message.received .file-doc-card {
    background: var(--form-bg, #f0f2f5);
}
.chat-message.received .file-doc-card:hover {
    background: var(--border-color, #e2e5e9);
}
.file-doc-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.2rem;
}
.file-doc-icon.pdf  { background: #fde8e8; color: #e53e3e; }
.file-doc-icon.word { background: #dbeafe; color: #2563eb; }
.file-doc-icon.excel { background: #d1fae5; color: #059669; }
.file-doc-icon.ppt  { background: #fef3c7; color: #d97706; }
.file-doc-icon.generic { background: #e5e7eb; color: #6b7280; }

.file-doc-info {
    flex: 1;
    min-width: 0;
    overflow: hidden;
}
.file-doc-name {
    font-size: 0.82rem;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
}
.chat-message.sent .file-doc-name { color: #fff; }
.chat-message.received .file-doc-name { color: var(--text-primary); }
.file-doc-size {
    font-size: 0.68rem;
    margin-top: 1px;
    display: block;
}
.chat-message.sent .file-doc-size { color: rgba(255,255,255,0.6); }
.chat-message.received .file-doc-size { color: var(--text-secondary); }

.file-doc-dl {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.9rem;
    transition: background 0.2s;
}
.chat-message.sent .file-doc-dl {
    background: rgba(255,255,255,0.15);
    color: #fff;
}
.chat-message.sent .file-doc-dl:hover {
    background: rgba(255,255,255,0.3);
}
.chat-message.received .file-doc-dl {
    background: rgba(0,0,0,0.06);
    color: var(--primary-color);
}
.chat-message.received .file-doc-dl:hover {
    background: rgba(0,0,0,0.1);
}

/* Responsive file sizes */
@media (max-width: 576px) {
    .file-img-wrap { max-width: 220px; }
    .file-img-wrap img { max-height: 180px; }
    .file-doc-card { min-width: 180px; max-width: 220px; padding: 7px 8px; gap: 8px; }
    .file-doc-icon { width: 34px; height: 34px; font-size: 1rem; }
    .file-doc-name { font-size: 0.76rem; }
    .file-doc-dl { width: 28px; height: 28px; font-size: 0.8rem; }
}
@media (min-width: 577px) and (max-width: 768px) {
    .file-img-wrap { max-width: 240px; }
    .file-doc-card { min-width: 190px; max-width: 250px; }
}

</style>



<?php if (!$isGroupLeader): ?>
    <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center gap-3 p-4">
        <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
        <div>
            <h6 class="fw-bold mb-1">Access Denied</h6>
            <p class="mb-0 text-muted" style="font-size: 0.9rem;">Only Group Leaders of approved projects can access the Supervisor chat. If your project is still pending or you are not the leader, this feature is disabled.</p>
        </div>
    </div>
<?php else: ?>

    <div class="chat-container">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-icon" style="overflow: hidden;">
                <?php 
                $supervisorInitial = strtoupper(substr($supervisor['name'], 0, 1));
                ?>
                <span class="fw-bold"><?php echo $supervisorInitial; ?></span>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($supervisor['name']); ?></h6>
                <small class="text-muted">Project Supervisor</small>
            </div>
        </div>

        <!-- Messages -->
        <div class="chat-messages" id="chatMessages">
            <div class="text-center text-muted" style="font-size: 0.85rem; margin-top: auto; margin-bottom: auto;" id="chatLoading">
                <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div><br>
                Loading messages...
            </div>
        </div>

        <!-- Input -->
        <div class="chat-input-area">
            <div class="chat-input-inner">
                <form id="chatForm" class="chat-input-row">
                    <label for="fileInput" class="btn-icon" title="Attach file">
                        <i class="bi bi-paperclip"></i>
                    </label>
                    <input type="file" id="fileInput" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" style="display: none;">
                    <div class="chat-textarea-wrap" id="textareaWrap" style="position: relative;">
                        <div class="drag-overlay" id="dragOverlay"><span><i class="bi bi-cloud-arrow-up me-1"></i>Drop file here</span></div>
                        <div class="file-chip" id="fileChip">
                            <div id="fileChipVisual"></div>
                            <div class="file-chip-info">
                                <span class="file-chip-name" id="fileChipName"></span>
                                <span class="file-chip-size" id="fileChipSize"></span>
                            </div>
                            <button type="button" class="file-chip-remove" id="removeFileBtn" title="Remove"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <textarea id="messageInput" rows="1" placeholder="Type a message..."></textarea>
                    </div>
                    <button type="submit" class="btn-icon btn-send" id="sendBtn" title="Send">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Firebase Integration -->
    <script type="module">
        import { db, storage, collection, addDoc, onSnapshot, query, orderBy, serverTimestamp, doc, setDoc, updateDoc, deleteDoc, ref, uploadBytes, getDownloadURL } from '<?php echo $bp; ?>/js/firebase-config.js';

        const studentId = "<?php echo $studentId; ?>";
        const supervisorId = "<?php echo $supervisor['id']; ?>";
        const chatId = `chat_${studentId}_${supervisorId}`;
        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const chatLoading = document.getElementById('chatLoading');
        const fileInput = document.getElementById('fileInput');
        const fileChip = document.getElementById('fileChip');
        const fileChipName = document.getElementById('fileChipName');
        const fileChipSize = document.getElementById('fileChipSize');
        const fileChipVisual = document.getElementById('fileChipVisual');
        const removeFileBtn = document.getElementById('removeFileBtn');
        const textareaWrap = document.getElementById('textareaWrap');
        const dragOverlay = document.getElementById('dragOverlay');

        let selectedFile = null;

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        function getChipClass(file) {
            const t = file.type || '';
            const n = file.name.toLowerCase();
            if (t.startsWith('image/')) return 'img';
            if (t.includes('pdf') || n.endsWith('.pdf')) return 'pdf';
            if (t.includes('word') || n.endsWith('.doc') || n.endsWith('.docx')) return 'word';
            if (t.includes('excel') || t.includes('spreadsheet') || n.endsWith('.xls') || n.endsWith('.xlsx')) return 'excel';
            if (t.includes('powerpoint') || t.includes('presentation') || n.endsWith('.ppt') || n.endsWith('.pptx')) return 'ppt';
            return 'generic';
        }

        function getChipIcon(cls) {
            const map = { img: 'bi-image', pdf: 'bi-file-earmark-pdf-fill', word: 'bi-file-earmark-word-fill', excel: 'bi-file-earmark-excel-fill', ppt: 'bi-file-earmark-ppt-fill', generic: 'bi-file-earmark-fill' };
            return map[cls] || map.generic;
        }

        function showFileChip(file) {
            selectedFile = file;
            fileChipName.textContent = file.name;
            fileChipSize.textContent = formatFileSize(file.size);
            const cls = getChipClass(file);

            if (cls === 'img') {
                const url = URL.createObjectURL(file);
                fileChipVisual.innerHTML = `<img src="${url}" class="file-chip-thumb" alt="preview">`;
            } else {
                fileChipVisual.innerHTML = `<div class="file-chip-icon ${cls}"><i class="bi ${getChipIcon(cls)}"></i></div>`;
            }
            fileChip.classList.add('active');
            messageInput.required = false;
        }

        function clearFileChip() {
            selectedFile = null;
            fileInput.value = '';
            fileChip.classList.remove('active');
            fileChipVisual.innerHTML = '';
            if (!messageInput.value.trim()) messageInput.required = true;
        }

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) showFileChip(e.target.files[0]);
        });

        removeFileBtn.addEventListener('click', clearFileChip);

        // Drag & Drop
        let dragCounter = 0;
        textareaWrap.addEventListener('dragenter', (e) => { e.preventDefault(); dragCounter++; textareaWrap.classList.add('drag-over'); dragOverlay.classList.add('show'); });
        textareaWrap.addEventListener('dragleave', (e) => { e.preventDefault(); dragCounter--; if (dragCounter <= 0) { dragCounter = 0; textareaWrap.classList.remove('drag-over'); dragOverlay.classList.remove('show'); } });
        textareaWrap.addEventListener('dragover', (e) => { e.preventDefault(); });
        textareaWrap.addEventListener('drop', (e) => {
            e.preventDefault();
            dragCounter = 0;
            textareaWrap.classList.remove('drag-over');
            dragOverlay.classList.remove('show');
            if (e.dataTransfer.files.length > 0) showFileChip(e.dataTransfer.files[0]);
        });

        messageInput.addEventListener('input', () => {
            if(messageInput.value.trim() || selectedFile) {
                messageInput.required = false;
            } else {
                messageInput.required = true;
            }
        });

        // Reference to the messages subcollection
        const messagesRef = collection(db, 'chats', chatId, 'messages');
        const q = query(messagesRef, orderBy('timestamp', 'asc'));

        // Listen for real-time updates
        let unsubscribeSnapshot = onSnapshot(q, (snapshot) => {
            if (chatLoading) chatLoading.remove();
            
            chatMessages.innerHTML = '';
            
            if (snapshot.empty) {
                chatMessages.innerHTML = '<div class="text-center text-muted my-auto" style="font-size: 0.85rem;">No messages yet. Send a message to start the conversation!</div>';
                return;
            }

            function getFileIconClass(fileType, fileName) {
                if (!fileType) fileType = '';
                const n = (fileName || '').toLowerCase();
                if (fileType.includes('pdf') || n.endsWith('.pdf')) return { icon: 'bi-file-earmark-pdf-fill', cls: 'pdf' };
                if (fileType.includes('word') || n.endsWith('.doc') || n.endsWith('.docx')) return { icon: 'bi-file-earmark-word-fill', cls: 'word' };
                if (fileType.includes('excel') || fileType.includes('spreadsheet') || n.endsWith('.xls') || n.endsWith('.xlsx')) return { icon: 'bi-file-earmark-excel-fill', cls: 'excel' };
                if (fileType.includes('powerpoint') || fileType.includes('presentation') || n.endsWith('.ppt') || n.endsWith('.pptx')) return { icon: 'bi-file-earmark-ppt-fill', cls: 'ppt' };
                return { icon: 'bi-file-earmark-fill', cls: 'generic' };
            }

            function getFileExt(fileName) {
                if (!fileName) return 'FILE';
                const parts = fileName.split('.');
                return parts.length > 1 ? parts.pop().toUpperCase() : 'FILE';
            }

            snapshot.forEach((doc) => {
                const data = doc.data();
                const isSentByMe = data.senderId == studentId;
                
                let timeStr = '';
                if (data.timestamp) {
                    const date = data.timestamp.toDate();
                    timeStr = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                }

                const msgDiv = document.createElement('div');
                msgDiv.className = `chat-message ${isSentByMe ? 'sent' : 'received'}`;
                const textContent = data.text ? data.text.replace(/</g, "&lt;").replace(/>/g, "&gt;") : '';
                const editedMark = data.isEdited ? '<span class="ms-1" style="font-size: 0.55rem; opacity: 0.8;">(edited)</span>' : '';
                
                let fileContent = '';
                if (data.fileUrl) {
                    if (data.fileType && data.fileType.startsWith('image/')) {
                        fileContent = `
                        <div class="file-img-wrap mb-1">
                            <a href="${data.fileUrl}" target="_blank" style="text-decoration:none">
                                <img src="${data.fileUrl}" alt="${data.fileName || 'Image'}" loading="lazy">
                                <div class="file-img-overlay">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </div>
                            </a>
                        </div>`;
                    } else {
                        const fi = getFileIconClass(data.fileType, data.fileName);
                        const ext = getFileExt(data.fileName);

                        fileContent = `
                        <div class="mb-1">
                            <a href="${data.fileUrl}" target="_blank" class="file-doc-card">
                                <div class="file-doc-icon ${fi.cls}">
                                    <i class="bi ${fi.icon}"></i>
                                </div>
                                <div class="file-doc-info">
                                    <span class="file-doc-name" title="${data.fileName || 'Attachment'}">${data.fileName || 'Attachment'}</span>
                                    <span class="file-doc-size">${ext} file</span>
                                </div>
                                <div class="file-doc-dl">
                                    <i class="bi bi-download"></i>
                                </div>
                            </a>
                        </div>`;
                    }
                }
                
                let actionsMenu = `
                    <div class="dropdown d-inline-block ms-1">
                        <button class="btn btn-sm p-0 border-0 msg-actions-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow: none; color: inherit; display: flex; align-items: center;">
                            <i class="bi bi-three-dots-vertical" style="font-size: 0.8rem;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="min-width: 120px; font-size: 0.85rem;">
                            ${textContent ? `<li><a class="dropdown-item copy-msg-btn" href="#" data-text="${textContent}"><i class="bi bi-clipboard me-2"></i>Copy</a></li>` : ''}
                            ${data.fileUrl ? `<li><a class="dropdown-item" href="${data.fileUrl}" target="_blank" download="${data.fileName || 'file'}"><i class="bi bi-download me-2"></i>Download</a></li>` : ''}
                            ${isSentByMe && !data.fileUrl ? `<li><a class="dropdown-item edit-msg-btn" href="#" data-id="${doc.id}" data-text="${textContent}"><i class="bi bi-pencil me-2"></i>Edit</a></li>` : ''}
                            ${isSentByMe ? `<li><a class="dropdown-item text-danger delete-msg-btn" href="#" data-id="${doc.id}"><i class="bi bi-trash me-2"></i>Delete</a></li>` : ''}
                        </ul>
                    </div>
                `;

                const needsTruncate = textContent && textContent.split('\n').length > 5 || textContent.length > 300;

                msgDiv.innerHTML = `
                    <div class="message-bubble">
                        ${fileContent}
                        ${textContent ? `<span class="msg-text${needsTruncate ? ' truncated' : ''}">${textContent}</span>${needsTruncate ? `<button class="msg-view-more" data-expanded="false">View more</button>` : ''}` : ''}
                        <span class="message-meta">${timeStr}${editedMark}${isSentByMe ? actionsMenu : ''}</span>
                    </div>
                `;
                chatMessages.appendChild(msgDiv);
            });
            
            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, (error) => {
            console.error("Firestore Listen Error:", error);
            chatMessages.innerHTML = '<div class="text-center text-danger my-auto">Error loading messages. Check Firebase rules.</div>';
        });

        // Handle Edit and Delete clicks
        let editingMsgId = null;

        chatMessages.addEventListener('click', async (e) => {
            const editBtn = e.target.closest('.edit-msg-btn');
            const deleteBtn = e.target.closest('.delete-msg-btn');
            const copyBtn = e.target.closest('.copy-msg-btn');
            const viewMoreBtn = e.target.closest('.msg-view-more');

            if (viewMoreBtn) {
                e.preventDefault();
                const textEl = viewMoreBtn.previousElementSibling;
                if (viewMoreBtn.dataset.expanded === 'false') {
                    textEl.classList.remove('truncated');
                    viewMoreBtn.textContent = 'View less';
                    viewMoreBtn.dataset.expanded = 'true';
                } else {
                    textEl.classList.add('truncated');
                    viewMoreBtn.textContent = 'View more';
                    viewMoreBtn.dataset.expanded = 'false';
                }
                return;
            }

            if (copyBtn) {
                e.preventDefault();
                const text = copyBtn.getAttribute('data-text');
                try {
                    await navigator.clipboard.writeText(text);
                    copyBtn.innerHTML = '<i class="bi bi-check2 me-2"></i>Copied!';
                    setTimeout(() => { copyBtn.innerHTML = '<i class="bi bi-clipboard me-2"></i>Copy'; }, 1500);
                } catch (err) {
                    console.error('Copy failed', err);
                }
                return;
            }

            if (editBtn) {
                e.preventDefault();
                editingMsgId = editBtn.getAttribute('data-id');
                const oldText = editBtn.getAttribute('data-text');
                
                messageInput.value = oldText;
                messageInput.focus();
                messageInput.placeholder = 'Editing message... (Esc to cancel)';
                sendBtn.innerHTML = '<i class="bi bi-check-lg"></i>';
            }

            if (deleteBtn) {
                e.preventDefault();
                const msgId = deleteBtn.getAttribute('data-id');
                
                if (confirm("Are you sure you want to delete this message?")) {
                    try {
                        const msgRef = doc(db, 'chats', chatId, 'messages', msgId);
                        await deleteDoc(msgRef);
                    } catch (error) {
                        console.error("Error deleting message:", error);
                        alert("Could not delete message. " + error.message);
                    }
                }
            }
        });

        // Cancel edit on Escape
        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && editingMsgId) {
                editingMsgId = null;
                messageInput.value = '';
                messageInput.placeholder = 'Type a message...';
                sendBtn.innerHTML = '<i class="bi bi-send-fill"></i>';
                autoResize();
            }
            // Submit on Enter (without Shift)
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });

        // Auto-resize textarea (properly shrinks back)
        function autoResize() {
            messageInput.style.height = '0px';
            const sh = messageInput.scrollHeight;
            const h = Math.max(42, Math.min(sh, 112));
            messageInput.style.height = h + 'px';
            messageInput.style.overflowY = sh > 112 ? 'auto' : 'hidden';
        }
        messageInput.addEventListener('input', autoResize);

        // Send message
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text && !selectedFile) return;

            messageInput.value = '';
            messageInput.placeholder = 'Type a message...';
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            sendBtn.disabled = true;

            try {
                const chatDocRef = doc(db, 'chats', chatId);

                if (editingMsgId) {
                    // Edit existing message
                    const msgRef = doc(db, 'chats', chatId, 'messages', editingMsgId);
                    await updateDoc(msgRef, {
                        text: text,
                        isEdited: true
                    });
                    await setDoc(chatDocRef, { lastUpdated: serverTimestamp() }, { merge: true });
                    editingMsgId = null;
                } else {
                    let fileUrl = null;
                    let fileName = null;
                    let fileType = null;
                    
                    if (selectedFile) {
                        const formData = new FormData();
                        formData.append('file', selectedFile);

                        const response = await fetch('<?php echo $bp; ?>/api/upload-chat-file', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();
                        
                        if (!data.success) {
                            throw new Error(data.error || 'Failed to upload file.');
                        }
                        
                        fileUrl = data.fileUrl;
                        fileName = data.fileName;
                        fileType = data.fileType;
                    }
                    
                    // Send new message
                    await setDoc(chatDocRef, {
                        participants: [studentId, supervisorId.toString()],
                        lastMessage: text || (selectedFile ? 'Attachment' : ''),
                        lastUpdated: serverTimestamp()
                    }, { merge: true });

                    await addDoc(messagesRef, {
                        senderId: studentId,
                        text: text,
                        fileUrl: fileUrl,
                        fileName: fileName,
                        fileType: fileType,
                        timestamp: serverTimestamp()
                    });
                    
                    // Reset file input
                    selectedFile = null;
                    fileInput.value = '';
                    fileChip.classList.remove('active');
                    fileChipVisual.innerHTML = '';
                    messageInput.required = true;
                }
            } catch (error) {
                console.error("Error sending message: ", error);
                alert("Failed to send message: " + error.message);
            } finally {
                sendBtn.innerHTML = '<i class="bi bi-send-fill"></i>';
                sendBtn.disabled = false;
                autoResize();
                messageInput.focus();
            }
        });
    </script>
<?php endif; ?>
