<style>
        /* Text overlay styling */
        .carousel {
            padding-top: 14vh;
        }

        .carousel-caption {
            position: absolute;
            top: 55%;
            left: 35%;
            transform: translate(-50%, -50%);
            text-align: left;
        }

        .overlay-title {
            font-family: 'Poppins', sans-serif;
            font-size: 3em;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
        }

        .overlay-subtitle {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5em;
            color: white;
            margin-top: 0.5em;
        }

        /* Carousel adjustments */
        #carouselExampleFade {
            margin-top: 0; /* Remove top margin */
        }

        .carousel-item img {
            vertical-align: top; /* Ensure images align to the top */
            height: 86vh;
        }

        /* Adjust carousel margin for smaller devices */
        @media (max-width: 767px) {
            #carouselExampleFade {
                margin-top: 0px;
            }

            .carousel-item img {
                vertical-align: top; /* Ensure images align to the top */
                height: 25vh;
            }
        }
</style>

<!-- Slider -->
<div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="2000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="images/slideshow/slider1.jpg" class="d-block w-100" alt="image 1">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="overlay-title">PERPUSTAKAAN IBNU FIRNAS</h1>
                <p class="overlay-subtitle">KOLEJ VOKASIONAL KUALA SELANGOR, SELANGOR</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="images/slideshow/slider2.jpg" class="d-block w-100" alt="image 2">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="overlay-title">PERPUSTAKAAN IBNU FIRNAS</h1>
                <p class="overlay-subtitle">KOLEJ VOKASIONAL KUALA SELANGOR, SELANGOR</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="images/slideshow/slider3.jpg" class="d-block w-100" alt="image 3">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="overlay-title">PERPUSTAKAAN IBNU FIRNAS</h1>
                <p class="overlay-subtitle">KOLEJ VOKASIONAL KUALA SELANGOR, SELANGOR</p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
