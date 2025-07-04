<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="../styles/main-style.css">

<!-- Scoped CSS for footer only -->
<style>
    footer {
        margin-top: auto;
        font-family: "Quicksand";
    }

    footer h5 {
        color: var(--light-cedar);
        margin-bottom: 15px;
        font-weight: bold;
    }

    footer img {
        width: 40vh;
    }

    footer p {
        color: white;
        margin: 5px 0;
    }

    .logo-pif {
        width: 15vh;
        filter: brightness(0) invert(1);
    }

    .signup-button {
        background-color: white; 
        color: var(--cedar); 
        border: none; 
        border-radius: 0; 
        padding: 12px 20px; 
        font-size: 1rem; 
        width: 45vh; 
        transition: all 0.4s ease;
        letter-spacing: 0.07em; 
    }

    .signup-button:hover {
        background-color: transparent;
        color: white; 
        border: 2px solid var(--light-cedar); 
        transform: scale(1.05); 
    }

    .social-icons {
        margin-top: 20px;
    }

    .social-icons i {
        font-size: 24px;
        color: var(--white);
        margin-right: 10px;
        transition: color 0.3s;
    }

    .social-icons i:hover {
        color: var(--light-cedar);
    }

    @media screen and (max-width: 767px) {
        .signup-button {
            background-color: white; 
            color: var(--cedar); 
            border: none; 
            border-radius: 0; 
            padding: 12px 20px; 
            font-size: 1rem; 
            width: 25vh; 
            transition: all 0.4s ease;
            letter-spacing: 0.07em; 
        }

        .logo-kv {
            width: 200px;
        }
    }

</style>

<footer style="background-color: var(--cedar); color: white; padding: 40px;">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>PERPUSTAKAAN IBNU FIRNAS KNOWLEDGE CENTRE</h5>
                <a href="admin/index.php"><img src="images/pif-logo.png" class="logo-pif" title="Admin PifCentre"></a>
            </div>
            <div class="col-md-4">
                <h5>KOLEJ VOKASIONAL KUALA SELANGOR</h5>
                <p>"Shine With Skills"</p>
                <br>
                <img src="images/kvks.png" alt="" class="logo-kv">
            </div>
            <div class="col-md-4">
                <h5>BERHUBUNG DENGAN KAMI</h5>
                <p>"Experience The Vocational Learning With Us!"</p>
                <br>
                <a class="btn signup-button ms-3" href="register.php"><b>DAFTAR MASUK SEKARANG</b></a>
                <div class="social-icons">
                    <i class="bx bxl-facebook"></i>
                    <i class="bx bxl-instagram"></i>
                    <i class='bx bxl-tiktok'></i>
                    <i class='bx bxl-gmail' ></i>
                    <i class='bx bxl-whatsapp'></i>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="text-center mt-4">
        <p>&copy; 2024 PIF Centre. All Rights Reserved. Managed by Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor.</p>
    </div>
</footer>


<!-- Scoped JS for footer only -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
