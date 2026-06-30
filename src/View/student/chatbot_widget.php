<!-- AI Chatbot Widget -->
<div id="ai-chatbot-widget">
    <!-- Chat Window -->
    <div id="ai-chat-window" style="display:none;">
        <!-- Header -->
        <div class="ai-chat-header">
            <div class="d-flex align-items-center gap-3">
                <div class="ai-avatar-ring">
                    <div class="ai-avatar">
                        <i class="bi bi-stars"></i>
                    </div>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold" style="font-size:0.95rem;">FYP Buddy</h6>
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
            <div class="ai-welcome-card">
                <div class="ai-welcome-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <p class="ai-welcome-title">Hi there! 👋</p>
                <p class="ai-welcome-desc">I'm your FYP Buddy! Ask me anything about proposals, deadlines, or navigating the portal.</p>
                <div class="ai-quick-actions">
                    <button class="ai-quick-btn" data-q="What are the FYP stages?"><i class="bi bi-signpost-split"></i> FYP Stages</button>
                    <button class="ai-quick-btn" data-q="How do I submit a proposal?"><i class="bi bi-file-earmark-plus"></i> Submit Proposal</button>
                    <button class="ai-quick-btn" data-q="How do I choose a supervisor?"><i class="bi bi-person-check"></i> Choose a Supervisor</button>
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
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
            <p class="ai-disclaimer">AI can make mistakes. Verify important info.</p>
        </div>
    </div>

    <!-- Floating Action Button - inline styles prevent FOUC -->
    <button id="ai-chat-fab" style="width:56px;height:56px;border-radius:50%;border:none;cursor:pointer;position:relative;background:linear-gradient(135deg,#2e3033,#1a1b1c);color:#fff;font-size:1.3rem;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 24px rgba(46,48,51,0.45);">
        <i class="bi bi-chat-dots-fill ai-fab-icon-open"></i>
        <i class="bi bi-x-lg ai-fab-icon-close" style="display:none;"></i>
        <span class="ai-fab-pulse"></span>
    </button>
</div>

<!-- marked.js for rendering markdown -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<style>
/* ═══════════════════════════════════════════
   AI CHATBOT - Glassmorphism Premium Design
   ═══════════════════════════════════════════ */

#ai-chatbot-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1060;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* ─── FAB ─── */
#ai-chat-fab {
    transition: transform 0.25s cubic-bezier(0.4,0,0.2,1), box-shadow 0.25s;
}
#ai-chat-fab:hover {
    transform: scale(1.08) translateY(-2px);
    box-shadow: 0 8px 32px rgba(46,48,51,0.55) !important;
}
.ai-fab-pulse {
    position: absolute;
    inset: -5px;
    border-radius: 50%;
    border: 2px solid rgba(46,48,51,0.4);
    animation: fabPulse 2.5s infinite;
    pointer-events: none;
}
#ai-chat-fab.open .ai-fab-pulse { display: none; }
@keyframes fabPulse {
    0% { transform: scale(1); opacity: 0.8; }
    100% { transform: scale(1.4); opacity: 0; }
}

/* ─── Chat Window (Glass) ─── */
#ai-chat-window {
    position: absolute;
    bottom: 72px;
    right: 0;
    width: 380px;
    height: 540px;
    background: var(--card-bg);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border-radius: 22px;
    box-shadow:
        0 24px 80px rgba(0,0,0,0.10),
        0 0 0 1px rgba(255,255,255,0.5) inset,
        0 0 0 1px rgba(0,0,0,0.04);
    flex-direction: column;
    overflow: hidden;
    transform-origin: bottom right;
    animation: chatOpen 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
#ai-chat-window[style*="flex"] {
    display: flex !important;
}
html.dark-theme #ai-chat-window {
    background: var(--card-bg);
    box-shadow:
        0 24px 80px rgba(0,0,0,0.35),
        0 0 0 1px rgba(255,255,255,0.06) inset;
}
@keyframes chatOpen {
    from { transform: translateY(12px) scale(0.95); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}

/* ─── Header (Glass) ─── */
.ai-chat-header {
    background: linear-gradient(135deg, rgba(46,48,51,0.92), rgba(37,99,235,0.92));
    backdrop-filter: blur(12px);
    color: white;
    padding: 10px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ai-avatar-ring {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    padding: 2px;
    background: linear-gradient(135deg, rgba(255,255,255,0.5), rgba(255,255,255,0.15));
}
.ai-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
.ai-status-text {
    font-size: 0.68rem;
    opacity: 0.85;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 1px;
}
.ai-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #4ade80;
    display: inline-block;
    box-shadow: 0 0 8px rgba(74, 222, 128, 0.7);
    animation: dotGlow 2s ease-in-out infinite;
}
@keyframes dotGlow {
    0%, 100% { box-shadow: 0 0 4px rgba(74,222,128,0.5); }
    50% { box-shadow: 0 0 10px rgba(74,222,128,0.9); }
}
.ai-header-actions { display: flex; gap: 4px; }
.ai-header-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: none;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(4px);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    transition: background 0.2s;
}
.ai-header-btn:hover { background: rgba(255,255,255,0.22); }

/* ─── Body ─── */
#ai-chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: var(--bg-color);
    scroll-behavior: smooth;
}

#ai-chat-body::-webkit-scrollbar { width: 4px; }
#ai-chat-body::-webkit-scrollbar-track { background: transparent; }
#ai-chat-body::-webkit-scrollbar-thumb { background: rgba(46,48,51,0.2); border-radius: 4px; }
#ai-chat-body::-webkit-scrollbar-thumb:hover { background: rgba(46,48,51,0.35); }

/* ─── Welcome Card ─── */
.ai-welcome-card {
    text-align: center;
    padding: 18px 12px;
    animation: fadeUp 0.4s ease;
}
.ai-welcome-icon {
    width: 50px;
    height: 50px;
    margin: 0 auto 12px;
    border-radius: 14px;
    background: linear-gradient(135deg, #2e3033, #1a1b1c);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    box-shadow: 0 6px 20px rgba(46,48,51,0.3);
}
.ai-welcome-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.ai-welcome-desc {
    font-size: 0.78rem;
    color: var(--text-secondary);
    margin-bottom: 16px;
    line-height: 1.5;
}

.ai-quick-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.ai-quick-btn {
    border: 1px solid rgba(46,48,51,0.15);
    background: var(--card-bg);
    backdrop-filter: blur(8px);
    border-radius: 10px;
    padding: 9px 8px;
    font-size: 0.72rem;
    font-weight: 500;
    color: var(--text-primary);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
    text-align: left;
}
.ai-quick-btn i { color: #2e3033; font-size: 0.85rem; flex-shrink: 0; }
html.dark-theme .ai-quick-btn i { color: var(--text-primary); }
.ai-quick-btn:hover {
    border-color: #2e3033;
    background: rgba(46,48,51,0.08);
    color: #2e3033;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(46,48,51,0.12);
}
html.dark-theme .ai-quick-btn {
    background: var(--card-bg);
    border-color: rgba(46,48,51,0.2);
    color: var(--text-primary);
}
html.dark-theme .ai-quick-btn:hover {
    background: rgba(46,48,51,0.12);
    color: var(--text-primary);
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(8px); }
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
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

.ai-msg-avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}
.ai-msg-avatar.bot {
    background: linear-gradient(135deg, #2e3033, #1a1b1c);
    color: white;
    box-shadow: 0 2px 8px rgba(46,48,51,0.3);
}
.ai-msg-avatar.user-av {
    background: linear-gradient(135deg, #2e3033, #1a1b1c);
    color: white;
    box-shadow: 0 2px 8px rgba(37,99,235,0.3);
}

.ai-message {
    max-width: 80%;
    padding: 10px 14px;
    font-size: 0.8rem;
    line-height: 1.55;
    word-break: break-word;
}

/* User bubble (glass) */
.ai-message.ai-user {
    background: linear-gradient(135deg, #2e3033, #1a1b1c);
    color: white;
    border-radius: 16px 16px 4px 16px;
    box-shadow: 0 2px 10px rgba(46,48,51,0.25);
}

/* Bot bubble (glass) */
.ai-message.ai-bot {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    color: var(--text-primary);
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 16px 16px 16px 4px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
html.dark-theme .ai-message.ai-bot {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    color: var(--text-primary);
    border-color: rgba(255,255,255,0.06);
}

/* Markdown in bot messages */
.ai-message.ai-bot p { margin-bottom: 0.4rem; }
.ai-message.ai-bot p:last-child { margin-bottom: 0; }
.ai-message.ai-bot ul, .ai-message.ai-bot ol { margin: 0.3rem 0; padding-left: 1.1rem; }
.ai-message.ai-bot li { margin-bottom: 0.15rem; }
.ai-message.ai-bot strong { font-weight: 600; color: var(--text-primary); }

.ai-message.ai-bot code {
    background: rgba(46,48,51,0.08);
    padding: 1px 5px;
    border-radius: 4px;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.76rem;
    color: #2e3033;
}
html.dark-theme .ai-message.ai-bot code {
    background: rgba(46,48,51,0.15);
    color: var(--text-primary);
}
.ai-message.ai-bot pre {
    background: var(--text-primary);
    color: var(--text-primary);
    padding: 10px 12px;
    border-radius: 8px;
    overflow-x: auto;
    margin: 0.4rem 0;
    font-size: 0.74rem;
}
html.dark-theme .ai-message.ai-bot pre { background: #0f172a; }

/* ─── Typing Indicator ─── */
.ai-typing-row {
    display: flex;
    gap: 8px;
    align-items: flex-end;
    animation: msgPop 0.25s ease;
}
.ai-typing-bubble {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 16px 16px 16px 4px;
    padding: 12px 16px;
    display: flex;
    gap: 5px;
    align-items: center;
}
html.dark-theme .ai-typing-bubble {
    background: var(--card-bg);
    border-color: rgba(255,255,255,0.06);
}
.ai-typing-bubble .dot {
    width: 7px;
    height: 7px;
    background: #2e3033;
    border-radius: 50%;
    animation: typingBounce 1.4s infinite ease-in-out both;
    opacity: 0.6;
}
.ai-typing-bubble .dot:nth-child(1) { animation-delay: -0.32s; }
.ai-typing-bubble .dot:nth-child(2) { animation-delay: -0.16s; }
@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0.4); opacity: 0.3; }
    40% { transform: scale(1); opacity: 0.8; }
}

/* ─── Footer (Glass) ─── */
.ai-chat-footer {
    padding: 10px 14px 8px;
    background: var(--surface-color);
    backdrop-filter: blur(16px);
    border-top: 1px solid rgba(0,0,0,0.05);
}
html.dark-theme .ai-chat-footer {
    background: var(--form-bg);
    border-color: rgba(255,255,255,0.05);
}
#ai-chat-form { margin: 0; }
.ai-input-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--form-bg);
    backdrop-filter: blur(8px);
    border-radius: 30px;
    padding: 4px 4px 4px 14px;
    border: 1.5px solid transparent;
    transition: all 0.25s;
}
.ai-input-wrapper:focus-within {
    border-color: rgba(46,48,51,0.5);
    box-shadow: 0 0 0 3px rgba(46,48,51,0.08);
    background: var(--card-bg);
}
html.dark-theme .ai-input-wrapper {
    background: var(--form-bg);
}
html.dark-theme .ai-input-wrapper:focus-within {
    background: var(--form-bg);
    box-shadow: 0 0 0 3px rgba(46,48,51,0.12);
}
.ai-input-wrapper input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 0.82rem;
    color: var(--text-primary);
    padding: 6px 0;
}

.ai-input-wrapper input::placeholder { color: var(--text-secondary); }
#ai-send-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #2e3033, #1a1b1c);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
    transition: all 0.2s;
    opacity: 0.4;
}
#ai-send-btn:not(:disabled) { opacity: 1; }
#ai-send-btn:not(:disabled):hover {
    transform: scale(1.06);
    box-shadow: 0 2px 10px rgba(46,48,51,0.35);
}
.ai-disclaimer {
    text-align: center;
    font-size: 0.62rem;
    color: var(--text-secondary);
    margin: 5px 0 0 0;
    letter-spacing: 0.01em;
}

/* ─── Responsive ─── */
@media (max-width: 576px) {
    #ai-chatbot-widget { bottom: 16px; right: 16px; }
    #ai-chat-fab { width: 50px !important; height: 50px !important; font-size: 1.15rem !important; }
    #ai-chat-window {
        width: calc(100vw - 32px);
        height: 65vh;
        bottom: 62px;
        border-radius: 18px;
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
    const fabOpen = fab.querySelector('.ai-fab-icon-open');
    const fabClose = fab.querySelector('.ai-fab-icon-close');

    let messages = [];
    let isOpen = false;

    // Enable/disable send button
    input.addEventListener('input', () => {
        sendBtn.disabled = !input.value.trim();
    });

    // Toggle chat
    function openChat() {
        chatWindow.style.display = 'flex';
        fab.classList.add('open');
        fabOpen.style.display = 'none';
        fabClose.style.display = 'inline';
        isOpen = true;
        setTimeout(() => input.focus(), 100);
    }
    function closeChat() {
        chatWindow.style.display = 'none';
        fab.classList.remove('open');
        fabOpen.style.display = 'inline';
        fabClose.style.display = 'none';
        isOpen = false;
    }

    fab.addEventListener('click', () => isOpen ? closeChat() : openChat());
    closeBtn.addEventListener('click', closeChat);

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
            <p class="ai-welcome-desc">I'm your FYP Buddy! Ask me anything about proposals, deadlines, or navigating the portal.</p>
            <div class="ai-quick-actions">
                <button class="ai-quick-btn" data-q="What are the FYP stages?"><i class="bi bi-signpost-split"></i> FYP Stages</button>
                <button class="ai-quick-btn" data-q="How do I submit a proposal?"><i class="bi bi-file-earmark-plus"></i> Submit Proposal</button>
                <button class="ai-quick-btn" data-q="How do I choose a supervisor?"><i class="bi bi-person-check"></i> Choose a Supervisor</button>
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
                addMessage("I'm having trouble connecting. Please try again.", 'bot');
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
