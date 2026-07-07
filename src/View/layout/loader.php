<!-- Global Page Loader -->
<div id="global-loader" class="global-loader">
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
        backdrop-filter: blur(5px);           /* Subtle glassmorphism blur effect */
        -webkit-backdrop-filter: blur(5px);
        z-index: 99999;                       /* Highest z-index to stay on top */
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 1;
        transition: opacity 0.4s ease, visibility 0.4s ease;
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
        --color-2: #3b82f6; /* Back to primary blue */
        --size: 1.2px;      

        width: calc(48 * var(--size));
        height: calc(48 * var(--size));
        /* Increased from 3 to 5 for a thicker ring */
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
        /* Increased from 3 to 5 for a thicker ring */
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
    // Hide the loader once the page has fully loaded
    window.addEventListener('load', function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            setTimeout(() => {
                loader.classList.add('hidden');
                setTimeout(() => loader.remove(), 400); 
            }, 200); 
        }
    });

    // Show the loader if user clicks a link 
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
</script>
