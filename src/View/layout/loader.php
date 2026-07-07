<!-- Global Page Loader -->
<div id="global-loader" class="global-loader hidden">
    <div class="loader-content">
        <div class="loader"></div>
    </div>
</div>

<style>
    /* Loader Overlay - Dim light gray background */
    .global-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.3);       /* Dim light gray overlay */
        backdrop-filter: blur(2px);           /* Slighter blur effect */
        -webkit-backdrop-filter: blur(2px);
        z-index: 99999;                       /* Highest z-index to stay on top */
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 1;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    /* Hidden State */
    .global-loader.hidden {
        opacity: 0;
        visibility: hidden;
    }

    /* Container for centering */
    .loader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Custom Loader Animation */
    .loader {
        --color-1: #fff;
        --color-2: #3b82f6; 
        --size: 1.2px;      

        width: calc(48 * var(--size));
        height: calc(48 * var(--size));
        border: calc(5 * var(--size)) solid var(--color-1); 
        border-radius: 50%;
        display: inline-block;
        position: relative;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }
    .loader::after {
        content: '';
        box-sizing: border-box;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: calc(56 * var(--size));
        height: calc(56 * var(--size));
        border-radius: 50%;
        border: calc(5 * var(--size)) solid;
        border-color: var(--color-2) transparent;
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    // Ensure loader is hidden when coming back via browser back button (bfcache)
    window.addEventListener('pageshow', function(event) {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.classList.add('hidden');
        }
    });

    // Show the loader if user clicks a standard link (waiting for next page)
    document.addEventListener('click', function(e) {
        const target = e.target.closest('a');
        if (target 
            && target.href 
            && !target.href.startsWith('#') 
            && !target.href.startsWith('javascript:')
            && !target.href.includes(window.location.hash || '#')
            && target.target !== '_blank'
            && !target.hasAttribute('download')) {
            
            const loader = document.getElementById('global-loader');
            if (loader) {
                document.body.appendChild(loader); 
                loader.classList.remove('hidden');
            }
        }
    });

    // Show loader on form submissions
    document.addEventListener('submit', function(e) {
        // Explicitly ignore chatbot and message forms
        if (e.target && (e.target.id === 'ai-chat-form' || e.target.id === 'chatForm')) {
            return;
        }
        
        // Use a slight timeout to see if another script called preventDefault() (e.g. for AJAX)
        setTimeout(() => {
            if (!e.defaultPrevented) {
                const loader = document.getElementById('global-loader');
                if (loader) {
                    document.body.appendChild(loader);
                    loader.classList.remove('hidden');
                }
            }
        }, 10);
    });
</script>
