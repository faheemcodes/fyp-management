<!-- AI Chatbot Widget -->
<div id="ai-chatbot-widget">
    <!-- Chat Window -->
    <div id="ai-chat-window" class="d-none">
        <div class="ai-chat-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-robot fs-5"></i>
                <h6 class="mb-0 fw-bold">FYP AI Guide</h6>
            </div>
            <button id="ai-chat-close" class="btn btn-sm text-white"><i class="bi bi-x-lg"></i></button>
        </div>
        
        <div id="ai-chat-body">
            <div class="ai-message ai-system">
                Hi there! I'm your AI guide for the FYP system. Ask me about proposals, deadlines, or how to navigate the portal!
            </div>
        </div>
        
        <div class="ai-chat-footer">
            <form id="ai-chat-form" class="d-flex gap-2 w-100 m-0">
                <input type="text" id="ai-chat-input" class="form-control form-control-sm" placeholder="Ask a question..." autocomplete="off">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send-fill"></i></button>
            </form>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button id="ai-chat-fab" class="btn btn-primary shadow-lg rounded-circle">
        <i class="bi bi-chat-dots-fill fs-4"></i>
    </button>
</div>

<!-- marked.js for rendering markdown -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<style>
/* Chatbot Styles */
#ai-chatbot-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1050;
    font-family: 'Inter', sans-serif;
}

#ai-chat-fab {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
#ai-chat-fab:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(37,99,235,0.4) !important;
}

#ai-chat-window {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background: var(--card-bg, #ffffff);
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid var(--border-color, #e5e7eb);
    transform-origin: bottom right;
    animation: scaleIn 0.2s ease-out;
}
[data-bs-theme="dark"] #ai-chat-window {
    background: #1e293b;
    border-color: #334155;
}

@keyframes scaleIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.ai-chat-header {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    padding: 16px;
}

#ai-chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: var(--body-bg, #f8fafc);
}
[data-bs-theme="dark"] #ai-chat-body {
    background: #0f172a;
}

.ai-chat-footer {
    padding: 12px;
    background: var(--card-bg, #ffffff);
    border-top: 1px solid var(--border-color, #e5e7eb);
}
[data-bs-theme="dark"] .ai-chat-footer {
    background: #1e293b;
    border-color: #334155;
}

.ai-message {
    max-width: 85%;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 0.85rem;
    line-height: 1.4;
    word-break: break-word;
}

.ai-message.ai-user {
    align-self: flex-end;
    background: #3b82f6;
    color: white;
    border-bottom-right-radius: 2px;
}

.ai-message.ai-system {
    align-self: flex-start;
    background: white;
    color: #334155;
    border: 1px solid #e2e8f0;
    border-bottom-left-radius: 2px;
}
[data-bs-theme="dark"] .ai-message.ai-system {
    background: #334155;
    color: #f1f5f9;
    border-color: #475569;
}

/* Markdown styling inside AI messages */
.ai-message.ai-system p { margin-bottom: 0.5rem; }
.ai-message.ai-system p:last-child { margin-bottom: 0; }
.ai-message.ai-system ul, .ai-message.ai-system ol { margin-bottom: 0.5rem; padding-left: 1.2rem; }
.ai-message.ai-system code { background: rgba(0,0,0,0.05); padding: 2px 4px; border-radius: 4px; font-family: monospace; }
[data-bs-theme="dark"] .ai-message.ai-system code { background: rgba(255,255,255,0.1); }
.ai-message.ai-system pre { background: rgba(0,0,0,0.05); padding: 8px; border-radius: 6px; overflow-x: auto; margin-top: 0.5rem; }
[data-bs-theme="dark"] .ai-message.ai-system pre { background: rgba(255,255,255,0.05); }

.ai-typing {
    display: flex;
    gap: 4px;
    padding: 12px 14px;
    align-items: center;
    width: fit-content;
}
.ai-typing .dot {
    width: 6px;
    height: 6px;
    background: #94a3b8;
    border-radius: 50%;
    animation: typingBounce 1.4s infinite ease-in-out both;
}
.ai-typing .dot:nth-child(1) { animation-delay: -0.32s; }
.ai-typing .dot:nth-child(2) { animation-delay: -0.16s; }
@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

@media (max-width: 576px) {
    #ai-chatbot-widget {
        bottom: 16px;
        right: 16px;
    }
    #ai-chat-window {
        width: calc(100vw - 32px);
        height: 60vh;
        bottom: 70px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const fab = document.getElementById('ai-chat-fab');
    const chatWindow = document.getElementById('ai-chat-window');
    const closeBtn = document.getElementById('ai-chat-close');
    const form = document.getElementById('ai-chat-form');
    const input = document.getElementById('ai-chat-input');
    const body = document.getElementById('ai-chat-body');
    
    // Conversation history
    let messages = [];

    // Toggle window
    fab.addEventListener('click', () => {
        chatWindow.classList.toggle('d-none');
        if (!chatWindow.classList.contains('d-none')) {
            input.focus();
        }
    });

    closeBtn.addEventListener('click', () => {
        chatWindow.classList.add('d-none');
    });

    function addMessage(text, role) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `ai-message ai-${role}`;
        
        if (role === 'system') {
            msgDiv.innerHTML = marked.parse(text);
        } else {
            msgDiv.textContent = text;
        }
        
        body.appendChild(msgDiv);
        body.scrollTop = body.scrollHeight;
    }

    function showTyping() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'ai-message ai-system ai-typing';
        typingDiv.id = 'ai-typing-indicator';
        typingDiv.innerHTML = '<div class="dot"></div><div class="dot"></div><div class="dot"></div>';
        body.appendChild(typingDiv);
        body.scrollTop = body.scrollHeight;
    }

    function hideTyping() {
        const typingDiv = document.getElementById('ai-typing-indicator');
        if (typingDiv) {
            typingDiv.remove();
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        // User message
        addMessage(text, 'user');
        input.value = '';
        input.disabled = true;

        messages.push({ role: 'user', content: text });

        // Limit history to last 10 messages to save context limit and bandwidth
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
                    addMessage(data.reply, 'system');
                    messages.push({ role: 'model', content: data.reply });
                } else {
                    addMessage("I'm sorry, I couldn't generate a response.", 'system');
                }
            } else {
                console.error(await response.text());
                addMessage("I'm having trouble connecting to the server. Please try again later.", 'system');
            }
        } catch (error) {
            hideTyping();
            addMessage("Network error. Please check your connection.", 'system');
        }

        input.disabled = false;
        input.focus();
    });
});
</script>
