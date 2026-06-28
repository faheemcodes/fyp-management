<!-- AI Chatbot Widget -->
<div id="ai-chatbot-widget">
    <!-- Chat Window -->
    <div id="ai-chat-window" class="d-none">
        <!-- Header -->
        <div class="ai-chat-header">
            <div class="d-flex align-items-center gap-3">
                <div class="ai-avatar-ring">
                    <div class="ai-avatar">
                        <i class="bi bi-stars"></i>
                    </div>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">FYP AI Guide</h6>
                    <span class="ai-status-text"><span class="ai-status-dot"></span>Online</span>
                </div>
            </div>
            <div class="ai-header-actions">
                <button id="ai-chat-clear" class="ai-header-btn" title="Clear chat"><i class="bi bi-arrow-counterclockwise"></i></button>
                <button id="ai-chat-close" class="ai-header-btn" title="Close"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
        
        <!-- Body -->
        <div id="ai-chat-body">
            <!-- Welcome Card -->
            <div class="ai-welcome-card">
                <div class="ai-welcome-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <p class="ai-welcome-title">Hi there! 👋</p>
                <p class="ai-welcome-desc">I'm your FYP AI Guide. Ask me anything about proposals, deadlines, or navigating the portal.</p>
                <div class="ai-quick-actions">
                    <button class="ai-quick-btn" data-q="What are the FYP stages?"><i class="bi bi-signpost-split"></i> FYP Stages</button>
                    <button class="ai-quick-btn" data-q="How do I submit a proposal?"><i class="bi bi-file-earmark-plus"></i> Submit Proposal</button>
                    <button class="ai-quick-btn" data-q="Tell me about supervisor limits"><i class="bi bi-person-check"></i> Supervisor Limits</button>
                    <button class="ai-quick-btn" data-q="What deadlines should I know about?"><i class="bi bi-calendar-event"></i> Deadlines</button>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="ai-chat-footer">
            <form id="ai-chat-form">
                <div class="ai-input-wrapper">
                    <input type="text" id="ai-chat-input" placeholder="Type your message..." autocomplete="off">
                    <button type="submit" id="ai-send-btn" disabled>
                        <i class="bi bi-arrow-up"></i>
                    </button>
                </div>
            </form>
            <p class="ai-disclaimer">AI can make mistakes. Verify important info.</p>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button id="ai-chat-fab">
        <span class="ai-fab-icon ai-fab-open"><i class="bi bi-chat-dots-fill"></i></span>
        <span class="ai-fab-icon ai-fab-close d-none"><i class="bi bi-x-lg"></i></span>
        <span class="ai-fab-pulse"></span>
    </button>
</div>

<!-- marked.js for rendering markdown -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<style>
/* ═══════════════════════════════════════════
   AI CHATBOT - Premium Modern Design
   ═══════════════════════════════════════════ */

#ai-chatbot-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1060;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* ─── FAB Button ─── */
#ai-chat-fab {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    position: relative;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    font-size: 1.35rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(99, 102, 241, 0.45);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
#ai-chat-fab:hover {
    transform: scale(1.08);
    box-shadow: 0 6px 28px rgba(99, 102, 241, 0.55);
}
.ai-fab-icon { transition: transform 0.3s, opacity 0.3s; }
.ai-fab-pulse {
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 2px solid rgba(99, 102, 241, 0.5);
    animation: fabPulse 2.5s infinite;
    pointer-events: none;
}
#ai-chat-fab.active .ai-fab-pulse { display: none; }
@keyframes fabPulse {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(1.35); opacity: 0; }
}

/* ─── Chat Window ─── */
#ai-chat-window {
    position: absolute;
    bottom: 72px;
    right: 0;
    width: 380px;
    height: 540px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.06);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform-origin: bottom right;
    animation: chatSlideUp 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
[data-bs-theme="dark"] #ai-chat-window {
    background: #1a1a2e;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.06);
}
@keyframes chatSlideUp {
    from { transform: translateY(16px) scale(0.96); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}

/* ─── Header ─── */
.ai-chat-header {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a78bfa 100%);
    color: white;
    padding: 16px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ai-avatar-ring {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    padding: 2px;
    background: linear-gradient(135deg, rgba(255,255,255,0.4), rgba(255,255,255,0.1));
}
.ai-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    backdrop-filter: blur(4px);
}
.ai-status-text {
    font-size: 0.7rem;
    opacity: 0.85;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 2px;
}
.ai-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #4ade80;
    display: inline-block;
    box-shadow: 0 0 6px rgba(74, 222, 128, 0.6);
}
.ai-header-actions { display: flex; gap: 4px; }
.ai-header-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: rgba(255,255,255,0.15);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    transition: background 0.2s;
}
.ai-header-btn:hover { background: rgba(255,255,255,0.25); }

/* ─── Body ─── */
#ai-chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: #f9fafb;
    scroll-behavior: smooth;
}
[data-bs-theme="dark"] #ai-chat-body {
    background: #0f0f23;
}
#ai-chat-body::-webkit-scrollbar { width: 4px; }
#ai-chat-body::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.12); border-radius: 4px; }
[data-bs-theme="dark"] #ai-chat-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }

/* ─── Welcome Card ─── */
.ai-welcome-card {
    text-align: center;
    padding: 20px 12px;
    animation: fadeInUp 0.5s ease;
}
.ai-welcome-icon {
    width: 52px;
    height: 52px;
    margin: 0 auto 12px;
    border-radius: 16px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.ai-welcome-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}
[data-bs-theme="dark"] .ai-welcome-title { color: #f1f5f9; }
.ai-welcome-desc {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 16px;
    line-height: 1.5;
}
[data-bs-theme="dark"] .ai-welcome-desc { color: #94a3b8; }
.ai-quick-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.ai-quick-btn {
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 10px;
    padding: 10px 8px;
    font-size: 0.73rem;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
    text-align: left;
}
.ai-quick-btn i { color: #6366f1; font-size: 0.85rem; flex-shrink: 0; }
.ai-quick-btn:hover {
    border-color: #6366f1;
    background: #f5f3ff;
    color: #6366f1;
}
[data-bs-theme="dark"] .ai-quick-btn {
    background: #1e293b;
    border-color: #334155;
    color: #cbd5e1;
}
[data-bs-theme="dark"] .ai-quick-btn:hover {
    background: rgba(99, 102, 241, 0.1);
    border-color: #6366f1;
    color: #a5b4fc;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ─── Messages ─── */
.ai-msg-row {
    display: flex;
    gap: 8px;
    align-items: flex-end;
    animation: msgPop 0.25s ease;
}
.ai-msg-row.user { flex-direction: row-reverse; }
@keyframes msgPop {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

.ai-msg-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
}
.ai-msg-avatar.bot {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
}
.ai-msg-avatar.user-av {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.ai-message {
    max-width: 80%;
    padding: 10px 14px;
    font-size: 0.82rem;
    line-height: 1.55;
    word-break: break-word;
}
.ai-message.ai-user {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    border-radius: 16px 16px 4px 16px;
}
.ai-message.ai-bot {
    background: white;
    color: #334155;
    border: 1px solid #e9ecef;
    border-radius: 16px 16px 16px 4px;
}
[data-bs-theme="dark"] .ai-message.ai-bot {
    background: #1e293b;
    color: #e2e8f0;
    border-color: #334155;
}

/* Markdown in bot messages */
.ai-message.ai-bot p { margin-bottom: 0.4rem; }
.ai-message.ai-bot p:last-child { margin-bottom: 0; }
.ai-message.ai-bot ul, .ai-message.ai-bot ol { margin: 0.3rem 0; padding-left: 1.1rem; }
.ai-message.ai-bot li { margin-bottom: 0.15rem; }
.ai-message.ai-bot strong { font-weight: 600; color: #1e293b; }
[data-bs-theme="dark"] .ai-message.ai-bot strong { color: #f1f5f9; }
.ai-message.ai-bot code {
    background: rgba(99, 102, 241, 0.08);
    padding: 1px 5px;
    border-radius: 4px;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.78rem;
    color: #6366f1;
}
[data-bs-theme="dark"] .ai-message.ai-bot code {
    background: rgba(99, 102, 241, 0.15);
    color: #a5b4fc;
}
.ai-message.ai-bot pre {
    background: #1e293b;
    color: #e2e8f0;
    padding: 10px 12px;
    border-radius: 8px;
    overflow-x: auto;
    margin: 0.4rem 0;
    font-size: 0.76rem;
}
[data-bs-theme="dark"] .ai-message.ai-bot pre { background: #0f172a; }

/* ─── Typing Indicator ─── */
.ai-typing-row {
    display: flex;
    gap: 8px;
    align-items: flex-end;
    animation: msgPop 0.25s ease;
}
.ai-typing-bubble {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 16px 16px 16px 4px;
    padding: 14px 18px;
    display: flex;
    gap: 5px;
    align-items: center;
}
[data-bs-theme="dark"] .ai-typing-bubble {
    background: #1e293b;
    border-color: #334155;
}
.ai-typing-bubble .dot {
    width: 7px;
    height: 7px;
    background: #94a3b8;
    border-radius: 50%;
    animation: typingBounce 1.4s infinite ease-in-out both;
}
.ai-typing-bubble .dot:nth-child(1) { animation-delay: -0.32s; }
.ai-typing-bubble .dot:nth-child(2) { animation-delay: -0.16s; }
@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

/* ─── Footer ─── */
.ai-chat-footer {
    padding: 12px 14px 10px;
    background: white;
    border-top: 1px solid #f1f5f9;
}
[data-bs-theme="dark"] .ai-chat-footer {
    background: #1a1a2e;
    border-color: #1e293b;
}
#ai-chat-form { margin: 0; }
.ai-input-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f1f5f9;
    border-radius: 14px;
    padding: 4px 4px 4px 14px;
    border: 1.5px solid transparent;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.ai-input-wrapper:focus-within {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    background: white;
}
[data-bs-theme="dark"] .ai-input-wrapper {
    background: #1e293b;
}
[data-bs-theme="dark"] .ai-input-wrapper:focus-within {
    background: #0f172a;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}
.ai-input-wrapper input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 0.84rem;
    color: #1e293b;
    padding: 6px 0;
}
[data-bs-theme="dark"] .ai-input-wrapper input { color: #e2e8f0; }
.ai-input-wrapper input::placeholder { color: #94a3b8; }
#ai-send-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: none;
    background: #6366f1;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    transition: all 0.2s;
    opacity: 0.5;
}
#ai-send-btn:not(:disabled) {
    opacity: 1;
}
#ai-send-btn:not(:disabled):hover {
    background: #4f46e5;
    transform: scale(1.05);
}
.ai-disclaimer {
    text-align: center;
    font-size: 0.65rem;
    color: #94a3b8;
    margin: 6px 0 0 0;
}

/* ─── Responsive ─── */
@media (max-width: 576px) {
    #ai-chatbot-widget { bottom: 16px; right: 16px; }
    #ai-chat-fab { width: 52px; height: 52px; font-size: 1.2rem; }
    #ai-chat-window {
        width: calc(100vw - 32px);
        height: 65vh;
        bottom: 66px;
        border-radius: 16px;
    }
    .ai-quick-actions { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const fab = document.getElementById('ai-chat-fab');
    const chatWindow = document.getElementById('ai-chat-window');
    const closeBtn = document.getElementById('ai-chat-close');
    const clearBtn = document.getElementById('ai-chat-clear');
    const form = document.getElementById('ai-chat-form');
    const input = document.getElementById('ai-chat-input');
    const sendBtn = document.getElementById('ai-send-btn');
    const body = document.getElementById('ai-chat-body');
    
    let messages = [];

    // Toggle send button state
    input.addEventListener('input', () => {
        sendBtn.disabled = !input.value.trim();
    });

    // Toggle chat window
    fab.addEventListener('click', () => {
        const isOpen = !chatWindow.classList.contains('d-none');
        chatWindow.classList.toggle('d-none');
        fab.classList.toggle('active');
        fab.querySelector('.ai-fab-open').classList.toggle('d-none');
        fab.querySelector('.ai-fab-close').classList.toggle('d-none');
        if (!isOpen) input.focus();
    });

    closeBtn.addEventListener('click', () => {
        chatWindow.classList.add('d-none');
        fab.classList.remove('active');
        fab.querySelector('.ai-fab-open').classList.remove('d-none');
        fab.querySelector('.ai-fab-close').classList.add('d-none');
    });

    // Clear conversation
    clearBtn.addEventListener('click', () => {
        messages = [];
        body.innerHTML = '';
        showWelcome();
    });

    function showWelcome() {
        const welcome = document.createElement('div');
        welcome.className = 'ai-welcome-card';
        welcome.innerHTML = `
            <div class="ai-welcome-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <p class="ai-welcome-title">Hi there! 👋</p>
            <p class="ai-welcome-desc">I'm your FYP AI Guide. Ask me anything about proposals, deadlines, or navigating the portal.</p>
            <div class="ai-quick-actions">
                <button class="ai-quick-btn" data-q="What are the FYP stages?"><i class="bi bi-signpost-split"></i> FYP Stages</button>
                <button class="ai-quick-btn" data-q="How do I submit a proposal?"><i class="bi bi-file-earmark-plus"></i> Submit Proposal</button>
                <button class="ai-quick-btn" data-q="Tell me about supervisor limits"><i class="bi bi-person-check"></i> Supervisor Limits</button>
                <button class="ai-quick-btn" data-q="What deadlines should I know about?"><i class="bi bi-calendar-event"></i> Deadlines</button>
            </div>`;
        body.appendChild(welcome);
        attachQuickBtns();
    }

    function attachQuickBtns() {
        document.querySelectorAll('.ai-quick-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                input.value = btn.dataset.q;
                sendBtn.disabled = false;
                form.dispatchEvent(new Event('submit'));
            });
        });
    }
    attachQuickBtns();

    function addMessage(text, role) {
        // Remove welcome card if present
        const welcome = body.querySelector('.ai-welcome-card');
        if (welcome) welcome.remove();

        const row = document.createElement('div');
        row.className = `ai-msg-row ${role}`;

        const avatar = document.createElement('div');
        avatar.className = `ai-msg-avatar ${role === 'user' ? 'user-av' : 'bot'}`;
        avatar.innerHTML = role === 'user' ? '<i class="bi bi-person-fill"></i>' : '<i class="bi bi-stars"></i>';

        const msgDiv = document.createElement('div');
        msgDiv.className = `ai-message ai-${role === 'user' ? 'user' : 'bot'}`;
        
        if (role === 'bot') {
            msgDiv.innerHTML = marked.parse(text);
        } else {
            msgDiv.textContent = text;
        }

        row.appendChild(avatar);
        row.appendChild(msgDiv);
        body.appendChild(row);
        body.scrollTop = body.scrollHeight;
    }

    function showTyping() {
        const row = document.createElement('div');
        row.className = 'ai-typing-row';
        row.id = 'ai-typing-indicator';

        const avatar = document.createElement('div');
        avatar.className = 'ai-msg-avatar bot';
        avatar.innerHTML = '<i class="bi bi-stars"></i>';

        const bubble = document.createElement('div');
        bubble.className = 'ai-typing-bubble';
        bubble.innerHTML = '<div class="dot"></div><div class="dot"></div><div class="dot"></div>';

        row.appendChild(avatar);
        row.appendChild(bubble);
        body.appendChild(row);
        body.scrollTop = body.scrollHeight;
    }

    function hideTyping() {
        const el = document.getElementById('ai-typing-indicator');
        if (el) el.remove();
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        addMessage(text, 'user');
        input.value = '';
        sendBtn.disabled = true;
        input.disabled = true;

        messages.push({ role: 'user', content: text });
        if (messages.length > 10) {
            messages = messages.slice(messages.length - 10);
        }

        showTyping();

        try {
            const response = await fetch(window.appBasePath + '/api/chatbot', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ messages: messages })
            });
            
            hideTyping();
            
            if (response.ok) {
                const data = await response.json();
                if (data.reply) {
                    addMessage(data.reply, 'bot');
                    messages.push({ role: 'model', content: data.reply });
                } else {
                    addMessage("I'm sorry, I couldn't generate a response.", 'bot');
                }
            } else {
                addMessage("I'm having trouble connecting to the server. Please try again.", 'bot');
            }
        } catch (error) {
            hideTyping();
            addMessage("Network error. Please check your connection.", 'bot');
        }

        input.disabled = false;
        input.focus();
    });
});
</script>
