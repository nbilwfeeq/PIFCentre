<style>

/* Preloader Styles */
    .preload {
        font-family: "Quicksand";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: transparent; /* Background color */
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        z-index: 9999;
        transition: opacity 0.5s ease-in-out;
    }

    /* Circle Animation */
    .circle {
        width: 100px;
        height: 100px;
        border: 5px solid #fff;
        border-top: 5px solid var(--cedar);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Text Styles */
    .text {
        color: var(--cedar);
        font-size: 24px;
        margin-top: 10px;
        }

    /* Circle Spin Animation */
    @keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
    }
</style>

<div class="preload" id="preload">
  <div class="circle"></div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        window.onload = function () {
            setTimeout(function () {
            document.getElementById('preload').style.opacity = '0';
            document.getElementById('preload').style.pointerEvents = 'none';
            setTimeout(function () {
                document.getElementById('preload').style.display = 'none';
            }, 500); // Matches the CSS transition time
            }, 150); // Optional: delay before hiding
        };
        });
</script>