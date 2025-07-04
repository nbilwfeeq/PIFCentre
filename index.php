<?php 
include 'database/config.php';
include 'session/security.php';

$page = 'PIF Centre | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Tempahan Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">

    <!--================Leaflet================-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

     <!--==============AOS CSS================-->
     <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!--==================CSS=================-->
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/index-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/banner-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <title><?php echo $page; ?></title>

    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            font-family: "Quicksand";
            flex-direction: column;
        }

        /* About Us */
        .container {
            font-family: 'Poppins', sans-serif;
        }

        .contact {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.4);
            background-color: white;
        }

        .about-title {
            font-family: "Quicksand";
            font-size: 3.0em;
            color: var(--cedar);
        }

        .about-subtitle {
            font-size: 1em;
            font-weight: 500;
            color: #666;
            text-transform: uppercase;
        }

        .about-description {
            font-size: 1em;
            color: #666;
            line-height: 1.5;
        }

        .read-more {
            font-size: 0.9em;
            font-weight: 600;
            color: var(--cedar);
            text-decoration: none;
            border-bottom: 1px solid #034638;
        }

        .read-more:hover {
            color: #666;
            border-bottom: 1px solid #666;
        }

        .about-info p {
            font-size: 0.9em;
            color: #666;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .about-info i {
            margin-right: 8px;
            color: #034638;
        }

        /* Contact Us */

        .contact-title {
            font-family: "Quicksand";
            font-size: 2.5em;
            color: var(--cedar);
        }

        .contact-subtitle {
            font-size: 1em;
            font-weight: 500;
            color: #666;
            text-transform: uppercase;
        }

        .contact-description {
            font-size: 1em;
            color: #666;
            line-height: 1.5;
        }

        /* About Slider */

        .custom-slide {
            background-color: transparent;
            padding: 15px;
            border-radius: 8px;
        }

        .custom-slide .img-fluid {
            width: 100%;
            height: 60%;
        }

        .custom-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--cedar);
            margin-top: 10px;
        }

        .custom-description {
            font-size: 0.9rem;
            color: #666;
            margin: 10px 0;
            line-height: 1.5;
        }

        .custom-link {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--cedar);
            text-decoration: none;
            border-bottom: 1px solid #034638;
        }

        .custom-link:hover {
            color: #666;
            border-bottom-color: #666;
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php' ?>
    
    <section id="home" data-aos="fade-in">
        <?php include 'includes/slider.php' ?>
    </section>

    <section id="catalog">
        <?php include 'includes/catalog.php' ?>
    </section>

    <br><br>

    <?php include 'includes/banner.php' ?>

    <br><br>

    <section id="collection">
        <?php include 'includes/collection.php' ?>
    </section>
    
    <section id="about">
        <div class="container mb-1">
            <div class="row align-items-center">
                <!-- Left Image Carousel Section -->
                <div class="col-md-6">
                    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/thumb/thumb1.jpg" class="d-block w-100 h-50" alt="Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="images/thumb/thumb2.jpg" class="d-block w-100" alt="Image 2">
                            </div>
                            <div class="carousel-item">
                                <img src="images/thumb/thumb3.jpg" class="d-block w-100" alt="Image 3">
                            </div>
                        </div>
                        <!-- Carousel Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        <!-- Thumbnail Navigation -->
                        <!-- <div class="d-flex justify-content-center mt-3">
                            <img src="images/thumb/thumb1.jpg" width="60" class="img-thumbnail me-2" data-bs-target="#carouselExample" data-bs-slide-to="0" alt="Thumbnail 1">
                            <img src="images/thumb/thumb2.jpg" width="60" class="img-thumbnail me-2" data-bs-target="#carouselExample" data-bs-slide-to="1" alt="Thumbnail 2">
                            <img src="images/thumb/thumb3.jpg" width="60" class="img-thumbnail" data-bs-target="#carouselExample" data-bs-slide-to="2" alt="Thumbnail 3">
                        </div> -->
                    </div>
                </div>
                <!-- Right Text Content Section -->
                <div class="col-md-6" data-aos="fade-left">
                    <h2 class="about-title">PERPUSTAKAAN IBNU FIRNAS</h2>
                    <p class="about-subtitle">KOLEJ VOKASIONAL KUALA SELANGOR</p>
                    <p class="about-description">
                        Perpustakaan Ibnu Firnas di Kolej Vokasional Kuala Selangor adalah pusat pembelajaran yang menyediakan pelbagai sumber seperti buku, media digital, dan jurnal akademik untuk menyokong pendidikan pelajar. Dengan ruang bacaan yang selesa dan akses teknologi, perpustakaan ini memupuk budaya ilmu dan pembelajaran. Ia juga menganjurkan pelbagai aktiviti untuk menggalakkan pembelajaran sepanjang hayat di kalangan komuniti kolej.
                    </p>
                    <a href="#" class="read-more">LEBIH LANJUT</a>
                    <div class="about-info mt-4">
                        <p><i class="bi bi-clock"></i> MASA DIBUKA : 8.00 pagi - 5.00 petang</p>
                        <hr>
                        <p><i class="bi bi-geo-alt"></i> Kolej Vokasional Kuala Selangor, Bestari Jaya, Kuala Selangor 46500 Selangor Darul Ehsan, Malaysia</p>
                        <hr>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel Card -->
        <div id="customCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="row text-center">

                        <div class="col-md-4">
                            <div class="custom-slide">
                                <img src="images/thumb/thumb1.jpg" class="img-fluid" alt="">
                                <div data-aos="fade-up" data-aos-delay="100">
                                    <h5 class="custom-title">PERPUSTAKAAN IBNU FIRNAS</h5>
                                    <p class="custom-description">
                                    Perpustakaan Ibnu Firnas Kolej Vokasional Kuala Selangor ialah sebuah pusat sumber pendidikan yang menyediakan pelbagai koleksi bahan bacaan, rujukan, dan sumber digital untuk pelajar dan tenaga pengajar. Perpustakaan ini bertujuan menyokong proses pembelajaran dan pengajaran melalui persekitaran yang kondusif serta kemudahan moden, termasuk ruang bacaan, akses internet, dan koleksi multimedia. Ia dinamakan sempena Ibnu Firnas, tokoh ilmuwan Islam yang terkenal dengan sumbangan dalam bidang sains dan teknologi.
                                    </p>
                                    <a href="#about" class="custom-link">LEBIH LANJUT</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="custom-slide">
                                <img src="images/thumb/thumb2.jpg" class="img-fluid" alt="">
                                <div data-aos="fade-up" data-aos-delay="500">
                                    <h5 class="custom-title">TEMPAT PERMAINAN DAN HIBURAN</h5>
                                    <p class="custom-description">
                                    Tempat Permainan dan Hiburan di Perpustakaan Ibnu Firnas menyediakan ruang santai untuk pelajar berehat sambil menikmati aktiviti interaktif. Kemudahan ini merangkumi permainan papan, permainan video pendidikan, dan sudut hiburan ringan yang bertujuan menggalakkan kreativiti serta mengurangkan tekanan. Ia direka untuk mencipta keseimbangan antara pembelajaran dan rekreasi dalam persekitaran yang menyeronokkan.
                                    </p>
                                    <a href="#about" class="custom-link">LEBIH LANJUT</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="custom-slide">
                                <img src="images/thumb/thumb3.jpg" class="img-fluid" alt="">
                                <div data-aos="fade-up" data-aos-delay="900">
                                    <h5 class="custom-title">KEMUDAHAN PEMBELAJARAN</h5>
                                    <p class="custom-description">
                                    Kemudahan Pembelajaran di Perpustakaan Ibnu Firnas dilengkapi dengan pelbagai sumber pendidikan seperti koleksi buku rujukan, jurnal, e-buku, dan akses kepada pangkalan data dalam talian. Perpustakaan ini juga menyediakan ruang belajar individu dan berkumpulan, komputer dengan sambungan internet, serta kemudahan pencetak dan pengimbas. Semua ini bertujuan menyokong proses pembelajaran pelajar dengan menyediakan persekitaran yang kondusif dan lengkap.
                                    </p>
                                    <a href="#about" class="custom-link">LEBIH LANJUT</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>
    
    <br>

    <section id="contact">
        <div class="contact mt-5">
            <div class="row align-item-center p-3" data-aos="fade-right">
                <h2 class="contact-title">HUBUNGI KAMI</h2>
                <p class="contact-subtitle">PERPUSTAKAAN IBNU FIRNAS <br>KOLEJ VOKASIONAL KUALA SELANGOR</p>
                <p class="contact-description">
                        "Experience The Vocational Learning With Us!"
                </p>
                <div class="col-md-3">
                    <p><i class="bx bx-phone"> +06 13-755 8636</i></p>
                    <p><i class="bx bx-envelope"> pifkvks@gmail.com</i></p>
                </div>
                <div class="col-md-3">
                    <p><i class='bx bxs-business'> +60 13-328 6664</i></p>
                </div>
            </div>

            <!-- Map Section -->
            <div id="map" style="height: 400px; margin-top: 20px;"></div>
        </div>
    </section>

    <?php include 'includes/detail-footer.php' ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000, 
            once: true      
        });
    </script>
    
    <script>
        // Function to disable AOS on small screens
        function handleAOS() {
            const isMobile = window.innerWidth <= 768; // Define mobile as screen width <= 768px

            if (isMobile) {
                AOS.init({
                    disable: true // Disable AOS on mobile
                });
            } else {
                AOS.init({
                    duration: 1000, // Animation duration
                    once: true      // Whether animation happens only once
                });
            }
        }

        // Call the function initially
        handleAOS();

        // Recheck on window resize
        window.addEventListener('resize', handleAOS);
    </script>

    <!-- Leaflet JS and CSS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        // Initialize the map and set its view to the address coordinates
        var map = L.map('map').setView([3.3764, 101.4151], 13); // Coordinates for Kuala Selangor

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add a marker with a popup for the address
        L.marker([3.3764, 101.4151]).addTo(map)
            .bindPopup('<b>Kolej Vokasional Kuala Selangor</b><br>45600 Bestari Jaya, Kuala Selangor, Selangor')
            .openPopup();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>