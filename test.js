
        import { db, storage, collection, addDoc, onSnapshot, query, orderBy, serverTimestamp, doc, setDoc, updateDoc, deleteDoc, ref, uploadBytes, getDownloadURL, getDocs, where } from '"DUMMY"/js/firebase-config.js';

    const supervisorId = ""DUMMY"";
    let currentLeaderId = null;
    let unsubscribeSnapshot = null;
    let editingMsgId = null;

    const allContactItems = document.querySelectorAll('.contact-item');
    const studentContactItems = document.querySelectorAll('.contact-item:not([data-leader-id="broadcast"])');
    const allLeaderIds = Array.from(studentContactItems).map(item => item.getAttribute('data-leader-id'));
    const emptyState = document.getElementById('emptyState');
    const activeChat = document.getElementById('activeChat');
    const chatHeaderName = document.getElementById('chatHeaderName');
    const chatHeaderAvatar = document.getElementById('chatHeaderAvatar');
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
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

    allContactItems.forEach(item => {
        item.addEventListener('click', () => {
            // UI Selection
            allContactItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');

            // Mobile toggle
            document.querySelector('.chat-wrapper').classList.add('chat-active');

            // Setup chat
            currentLeaderId = item.getAttribute('data-leader-id');
            const leaderName = item.getAttribute('data-leader-name');
            const avatarUrl = item.getAttribute('data-avatar');
            const initial = item.getAttribute('data-initial');
            
            chatHeaderName.textContent = leaderName;
            
            if (avatarUrl) {
                chatHeaderAvatar.innerHTML = `<img src="${avatarUrl}" alt="Profile" style="width: 100%;height: 100%;object-fit: cover">`;
            } else {
                chatHeaderAvatar.innerHTML = `<span class="fw-bold">${initial}</span>`;
            }
            
            emptyState.style.display = 'none';
            activeChat.style.display = 'flex';
            
            loadChat(currentLeaderId);
        });
    });

    document.getElementById('backToContacts').addEventListener('click', () => {
        document.querySelector('.chat-wrapper').classList.remove('chat-active');
    });

    function loadChat(leaderId) {
        if (unsubscribeSnapshot) {
            unsubscribeSnapshot();
        }

        const chatId = `chat_${leaderId}_${supervisorId}`;
        const messagesRef = collection(db, 'chats', chatId, 'messages');
        const q = query(messagesRef, orderBy('timestamp', 'asc'));

        chatMessages.innerHTML = '<div class="text-center w-100 my-auto"><div class="spinner-border spinner-border-sm text-primary"></div></div>';

        unsubscribeSnapshot = onSnapshot(q, (snapshot) => {
            chatMessages.innerHTML = '';
            
            if (snapshot.empty) {
                chatMessages.innerHTML = '<div class="text-center text-muted my-auto" style="font-size: 0.85rem">No messages yet. Send a message to start the conversation!</div>';
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
                const isSentByMe = data.senderId == supervisorId;
                
                let timeStr = '';
                if (data.timestamp) {
                    const date = data.timestamp.toDate();
                    timeStr = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                }

                const msgDiv = document.createElement('div');
                msgDiv.className = `chat-message ${isSentByMe ? 'sent' : 'received'}`;
                const textContent = data.text ? data.text.replace(/</g, "&lt;").replace(/>/g, "&gt;") : '';
                const editedMark = data.isEdited ? '<span class="ms-1" style="font-size: 0.55rem;opacity: 0.8">(edited)</span>' : '';
                
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
                        <button class="btn btn-sm p-0 border-0 msg-actions-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow: none;color: inherit;display: flex;align-items: center">
                            <i class="bi bi-three-dots-vertical" style="font-size: 0.8rem"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="min-width: 120px;font-size: 0.85rem">
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
            
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, (error) => {
            console.error("Firestore Listen Error:", error);
            chatMessages.innerHTML = '<div class="text-center text-danger my-auto">Error loading messages. Check Firebase rules.</div>';
        });

        // Handle Edit and Delete clicks
        editingMsgId = null;

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
                const chatId = `chat_${currentLeaderId}_${supervisorId}`;
                
                if (confirm("Are you sure you want to delete this message?")) {
                    try {
                        if (currentLeaderId === 'broadcast') {
                            const bMsgRef = doc(db, 'chats', `chat_broadcast_${supervisorId}`, 'messages', msgId);
                            await deleteDoc(bMsgRef);
                            // Fan-out delete
                            for (const lId of allLeaderIds) {
                                const cId = `chat_${lId}_${supervisorId}`;
                                const cMsgsRef = collection(db, 'chats', cId, 'messages');
                                const q = query(cMsgsRef, where('originalBroadcastId', '==', msgId));
                                const qSnap = await getDocs(q);
                                qSnap.forEach(async (d) => {
                                    await deleteDoc(d.ref);
                                });
                            }
                        } else {
                            const msgRef = doc(db, 'chats', chatId, 'messages', msgId);
                            await deleteDoc(msgRef);
                        }
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
    }

    // Send message
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!currentLeaderId) return;

        const text = messageInput.value.trim();
        if (!text && !selectedFile) return;

        messageInput.value = '';
        messageInput.placeholder = 'Type a message...';
        sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        sendBtn.disabled = true;

        try {
            let fileUrl = null;
            let fileName = null;
            let fileType = null;
            
            if (selectedFile) {
                const formData = new FormData();
                formData.append('file', selectedFile);

                const response = await fetch('"DUMMY"/api/upload-chat-file', {
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

            if (currentLeaderId === 'broadcast') {
                if (editingMsgId) {
                    const bMsgRef = doc(db, 'chats', `chat_broadcast_${supervisorId}`, 'messages', editingMsgId);
                    await updateDoc(bMsgRef, { text: text, isEdited: true });
                    
                    for (const lId of allLeaderIds) {
                        const cId = `chat_${lId}_${supervisorId}`;
                        const cMsgsRef = collection(db, 'chats', cId, 'messages');
                        const q = query(cMsgsRef, where('originalBroadcastId', '==', editingMsgId));
                        const qSnap = await getDocs(q);
                        qSnap.forEach(async (d) => {
                            await updateDoc(d.ref, { text: text, isEdited: true });
                        });
                    }
                    
                    editingMsgId = null;
                    clearFileChip();
                    return;
                }
                // Save to broadcast history
                const broadcastChatId = `chat_broadcast_${supervisorId}`;
                const broadcastDocRef = doc(db, 'chats', broadcastChatId);
                await setDoc(broadcastDocRef, {
                    lastMessage: text || (selectedFile ? 'Attachment' : ''),
                    lastUpdated: serverTimestamp()
                }, { merge: true });
                const broadcastMsgsRef = collection(db, 'chats', broadcastChatId, 'messages');
                const docRef = await addDoc(broadcastMsgsRef, {
                    senderId: supervisorId,
                    text: text,
                    fileUrl: fileUrl,
                    fileName: fileName,
                    fileType: fileType,
                    timestamp: serverTimestamp(),
                    isEdited: false
                });
                
                // Fan-out to all leaders
                for (const lId of allLeaderIds) {
                    const cId = `chat_${lId}_${supervisorId}`;
                    const cDocRef = doc(db, 'chats', cId);
                    await setDoc(cDocRef, {
                        participants: [lId.toString(), supervisorId.toString()],
                        lastMessage: text || (selectedFile ? 'Attachment' : ''),
                        lastUpdated: serverTimestamp()
                    }, { merge: true });
                    const cMsgsRef = collection(db, 'chats', cId, 'messages');
                    await addDoc(cMsgsRef, {
                        senderId: supervisorId,
                        text: text,
                        fileUrl: fileUrl,
                        fileName: fileName,
                        fileType: fileType,
                        timestamp: serverTimestamp(),
                        isEdited: false,
                        originalBroadcastId: docRef.id
                    });
                    
                    fetch('"DUMMY"/api/chat/notify', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ chat_id: cId, sender_id: supervisorId, message: text || 'Sent a file' })
                    }).catch(console.error);
                }
                
                clearFileChip();
                return;
            }

            const chatId = `chat_${currentLeaderId}_${supervisorId}`;
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
                // Send new message
                await setDoc(chatDocRef, {
                    participants: [currentLeaderId.toString(), supervisorId.toString()],
                    lastMessage: text || (selectedFile ? 'Attachment' : ''),
                    lastUpdated: serverTimestamp()
                }, { merge: true });

                const messagesRef = collection(db, 'chats', chatId, 'messages');
                await addDoc(messagesRef, {
                    senderId: supervisorId,
                    text: text,
                    fileUrl: fileUrl,
                    fileName: fileName,
                    fileType: fileType,
                    timestamp: serverTimestamp()
                });

                // Trigger notification and email
                fetch('"DUMMY"/api/chat/notify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '"DUMMY"'
                    },
                    body: JSON.stringify({
                        recipient_id: currentLeaderId,
                        chat_id: chatId,
                        message_preview: text || (selectedFile ? '[Attachment]' : '')
                    })
                }).catch(e => console.error("Notification failed", e));
                
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
