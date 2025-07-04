<style>
    body {
        background-image: url('images/bg.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed; /* Keeps the background image static */
    }
    
    .navbar-custom {
        background-color: white;
        color: black;
        height: 100px;
        margin-bottom: 0;
        transition: background-color 0.3s ease, color 0.3s ease;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    /* Style for navbar-brand logo */
    .navbar-brand img {
        height: 90px;
    }

    /* Default nav link style */
    .nav-link {
        color: black;
        font-family: "Quicksand";
        transition: color 0.3s ease;
    }

    .nav-link:hover {
        color: var(--cedar);
    }

    .nav-link.active {
        color: var(--cedar);
        font-weight: bold;
    }

    .navbar-custom.scrolled .nav-link.active {
        color: var(--cedar);
        font-weight: bold;
    }

    .nav-item a {
        font-size: 14px;
    }

    /* Scrolled state for the navbar */
    .navbar-custom.scrolled {
        background-color: rgba(0, 0, 0, 0.8); 
        color: white;
    }

    /* Change nav-link color when scrolled */
    .navbar-custom.scrolled .nav-link {
        color: white;
    }

    .navbar-custom.scrolled:hover .nav-link:hover {
        color: var(--cedar);
    }

    /* Change logo image color to white, if applicable */
    .navbar-custom.scrolled .navbar-brand img {
        filter: brightness(0) invert(1); 
    }

    /* Button style */
    .btn-custom {
        background-color: var(--cedar); 
        color: white; 
        border: none; 
        border-radius: 0; 
        padding: 12px 20px; 
        font-size: 1rem; 
        width: 21vh; 
        transition: all 0.4s ease;
        letter-spacing: 0.07em; 
    }

    .btn-custom:hover {
        background-color: transparent;
        color: var(--cedar); 
        border: 2px solid var(--cedar); 
        transform: scale(1.05); 
    }

    video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }

    /* Navbar styles remain the same */

    /* Change navbar background when toggle is active */
    .navbar-custom.toggled {
        background-color: white;
    }

    /* Change toggler icon appearance */
    .navbar-toggler {
        border: none;
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(0, 0, 0, 0.5)' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        transition: transform 0.3s ease;
    }

    .navbar-toggler.toggled .navbar-toggler-icon {
        transform: rotate(90deg);
    }

    @media (max-width: 992px) {
        .nav-item {
            text-align: center;
            margin-bottom: 10px;
        }

        .btn-custom {
            width: 100%;
        }

        .navbar-nav {
            background-color: white;
            color: black;
        }

        .navbar-custom.scrolled {
            background-color: rgba(0, 0, 0, 0.8); 
            color: black;
        }

        /* Change nav-link color when scrolled */
        .navbar-custom.scrolled .nav-link {
            color: black;
        }
    }

</style>

<?php include 'loader.php'; ?>

<!-- Background Video -->
<!-- <video autoplay loop muted>
    <source src="images/bg.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video> -->

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/pif-logo.png" alt="Logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Utama</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#catalog">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#collection">Koleksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Hubungi Kami</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-custom ms-3" href="login-page.php"><b>LOG MASUK</b></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <script>
        // Detect scroll event
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {  // Change the background when scrolling past 50px
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('section'); // Ensure sections have matching IDs to nav-link hrefs.

            // Function to highlight the active nav link
            function updateActiveLink() {
                const scrollPos = window.scrollY + 100; // Offset for navbar height

                sections.forEach((section, index) => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;

                    if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                        navLinks.forEach(link => link.classList.remove('active'));
                        navLinks[index].classList.add('active');
                    }
                });
            }

            // Add click event listener to nav links
            navLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);

                    // Smooth scroll to the section
                    window.scrollTo({
                        top: targetSection.offsetTop - 80, // Adjust for navbar height
                        behavior: 'smooth'
                    });

                    // Manually update the active link based on the section clicked
                    navLinks.forEach(link => link.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Add scroll event listener
            window.addEventListener('scroll', updateActiveLink);

            // Call updateActiveLink once to set the initial active link on page load
            updateActiveLink();
        });

        // New functionality: Navbar toggler background change and close behavior
        document.addEventListener('DOMContentLoaded', function () {
            const navbar = document.querySelector('.navbar-custom');
            const toggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.getElementById('navbarNav');
            const navLinks = document.querySelectorAll('.nav-link');

            // Toggle navbar background when toggler is active
            toggler.addEventListener('click', function () {
                navbar.classList.toggle('toggled');
            });

            // Close navbar when a nav-link is clicked (on mobile)
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 992) {
                        navbarCollapse.classList.remove('show');
                        navbar.classList.remove('toggled');
                    }
                });
            });

            // Change navbar background on scroll
            window.addEventListener('scroll', function () {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        });

    </script>
