<?php
// includes/background.php
?>

<style>
    /* Animated Background Effect */
    .animated-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(130deg, rgb(123, 122, 122), rgb(167, 165, 165), rgb(161, 161, 161), rgb(255, 255, 255), rgb(255, 255, 255),  rgb(255, 255, 255), rgb(167, 165, 165), rgb(123, 122, 122));
        background-size: 300% 300%; /* Allow room for animation */
        animation: gradientMove 6s ease infinite; /* Animate gradient */
        z-index: -1; /* Ensure it stays in the background */
    }

    @keyframes gradientMove {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
</style>

<div class="animated-background"></div>
