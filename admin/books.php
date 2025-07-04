<?php 
    include 'database/config.php';
    $page = 'Data Buku | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';

    // Get user info
    $stmt = $connect->prepare("SELECT * FROM user WHERE nokp = ?");
    $stmt->bind_param("s", $nokp);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Get jenis buku info
    $jenisStmt = $connect->prepare("SELECT id_jenisBuku, jenisBuku FROM jenis");
    $jenisStmt->execute();
    $jenisResult = $jenisStmt->get_result();
    $jenisBuku = $jenisResult->fetch_all(MYSQLI_ASSOC);

    // Get kategori buku info
    $kategoriStmt = $connect->prepare("SELECT id_kategoriBuku, kategoriBuku FROM kategori");
    $kategoriStmt->execute();
    $kategoriResult = $kategoriStmt->get_result();
    $kategoriBuku = $kategoriResult->fetch_all(MYSQLI_ASSOC);

    // Get bahasa buku info (if stored separately in a table)
    $bahasaStmt = $connect->prepare("SELECT DISTINCT bahasa_buku FROM books");
    $bahasaStmt->execute();
    $bahasaResult = $bahasaStmt->get_result();
    $bahasaBuku = $bahasaResult->fetch_all(MYSQLI_ASSOC);

    // Fetch book data, grouped by ISBN, and count the quantities
    $bookStmt = $connect->prepare("SELECT isbn, judul_buku, id_jenisBuku, id_kategoriBuku, noPanggil_buku, lokasi_buku, bahasa_buku, gambar_buku, SUM(kuantiti_buku) AS total_kuantiti FROM books GROUP BY isbn");
    $bookStmt->execute();
    $bookResult = $bookStmt->get_result();

    // Modified SQL Query to include id_buku
    $bookStmt = $connect->prepare("
    SELECT b.id_buku, b.isbn, b.judul_buku, jb.jenisBuku, kb.kategoriBuku, b.noPanggil_buku, 
        b.lokasi_buku, b.bahasa_buku, b.gambar_buku, 
        COUNT(*) AS total_kuantiti 
    FROM books b
    LEFT JOIN jenis jb ON b.id_jenisBuku = jb.id_jenisBuku
    LEFT JOIN kategori kb ON b.id_kategoriBuku = kb.id_kategoriBuku
    GROUP BY b.isbn
    ");
    $bookStmt->execute();
    $bookResult = $bookStmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Tempahan Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">
    
    <!-- ==================CSS=================-->
    <link rel="stylesheet" href="styles/dashboard-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <!--=============== DataTables CSS ===============-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <title><?php echo $page; ?></title>

    <style>
        .btn-custom {
            background-color: transparent;
            border: 2px solid black; 
            color: black;
            transition: all 0.1s ease; 
        }

        .btn-custom:hover {
            color: #fff;
            background-color: var(--cedar); 
            border: none;
        }

        /* Custom Pagination Buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: black !important; /* Default button color */
            background-color: transparent; /* Transparent background */
            border: 1px solid #ddd; /* Border for pagination buttons */
            border-radius: 4px; /* Rounded corners */
            margin: 2px; /* Space between buttons */
            padding: 5px 10px; /* Padding for a comfortable click area */
            transition: all 0.3s ease; /* Smooth hover effect */
        }

        /* Hover and Active State */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: white !important; /* Text color on hover */
            background-color: var(--cedar); /* Change this to match your theme color */
            border: none; /* Remove border on hover */
        }

        /* Current Page Button */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            color: white !important; /* White text */
            background-color: var(--cedar); /* Bootstrap primary color or your preferred color */
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Optional shadow for active button */
        }
    </style>

</head>

<body>

    <?php include('includes/topbar.php'); ?>

    <div class="content container p-3">
        <h1>Pangkalan Data Buku</h1>
        <div class="hr">
            <hr>
        </div>

        <div class='p-3'>
            <button class="btn btn-custom" onclick="window.location='create-book.php'"><i class='bx bxs-user-account'></i>&nbspTambah Buku</button>
            <button class="btn btn-secondary" onclick="window.location='books-import.php'"><i class='bx bx-cloud-upload'></i>&nbspMuat Naik Data .CSV</button>
        </div>
        <br>

        <div class="table-responsive">
            <table class="table" id="bookData">
                <thead>
                    <tr>
                        <th>Bil</th>
                        <th>Judul</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>No.Panggil</th>
                        <th>No. ISBN</th>
                        <th>Lokasi</th>
                        <!-- <th>Bahasa</th> -->
                        <th>Jumlah Kuantiti</th>
                        <th>Gambar</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;
                        while($book = $bookResult->fetch_assoc()) { 
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $book['judul_buku']; ?></td>
                        <td><?php echo $book['jenisBuku']; ?></td>
                        <td><?php echo $book['kategoriBuku']; ?></td>
                        <td><?php echo $book['noPanggil_buku']; ?></td>
                        <td><?php echo $book['isbn']; ?></td>
                        <td><?php echo $book['lokasi_buku']; ?></td>
                        <td>
                            <form method="POST" action="backend/delete-book-quantity.php" style="display:inline;">
                                <input type="hidden" name="isbn" value="<?php echo $book['isbn']; ?>">
                                <button type="submit" class="btn btn-sm btn-secondary"><i class="bx bx-minus" ></i></button>
                            </form>
                            <?php echo $book['total_kuantiti']; ?>
                            <form method="POST" action="backend/add-book-quantity.php" style="display:inline;">
                                <input type="hidden" name="isbn" value="<?php echo $book['isbn']; ?>">
                                <button type="submit" class="btn btn-sm btn-secondary"><i class="bx bx-plus"></i></button>
                            </form>
                        </td>
                        <td><img src="../images/books/<?php echo $book['gambar_buku']; ?>" alt="<?php echo $book['isbn']; ?>" width="50"></td>
                        <td>
                            <a href="javascript:void(0);" onclick="editBook(<?php echo $book['isbn']; ?>)" class="btn btn-warning btn-sm" title="Kemaskini"><i class="bx bx-edit"></i></a>
                            <a href="javascript:void(0);" onclick="deleteBook('<?php echo $book['isbn']; ?>')" class="btn btn-danger btn-sm" title="Padam"><i class="bx bx-trash"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
    </div>

    <!--=============== Bootstrap 5.2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!--=============== jQuery ===============-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!--=============== DataTables JS ===============-->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <!--=============== SweetAlert2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#bookData').DataTable({
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Paparkan _MENU_ buku per halaman",
                    info: "Paparkan _START_ hingga _END_ dari _TOTAL_ buku",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Seterusnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>

    <script>
        //==========Alert=============//

        function editBook(id_buku) {
            const editURL = 'update-book.php?id=' + encodeURIComponent(id_buku);
            window.location.href = editURL;
        }

        function deleteBook(isbn) {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: 'This will delete all books with the same ISBN!',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send the ISBN to the back-end for deletion
                    const deleteURL = 'backend/delete-book.php';

                    fetch(deleteURL, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `isbn=${encodeURIComponent(isbn)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', 'All books with this ISBN have been deleted.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error!', 'Unable to delete books.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'There was an issue with the request.', 'error');
                    });
                }
            });
        }
        
        const parameter = new URLSearchParams(window.location.search);
        const getURL = parameter.get('status');

        if (getURL == 'deleted') {
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: 'Semua data buku berjaya dipadam!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#28a745',
                iconColor: '#fff',
                color: '#fff',
            }).then(() => {
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        } else if (getURL == 'updated') {
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: 'Data buku berjaya dikemaskini!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#28a745',
                iconColor: '#fff',
                color: '#fff',
            }).then(() => {
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        } else if (getURL == 'added') {
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: 'Data berjaya ditambah!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#28a745',
                iconColor: '#fff',
                color: '#fff',
            }).then(() => {
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        } else if (getURL == 'addedQuantity') {
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: 'Kuantiti berjaya ditambah!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#28a745',
                iconColor: '#fff',
                color: '#fff',
            }).then(() => {
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        }else if (getURL == 'deletedQuantity') {
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: 'Kuantiti berjaya ditolak!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#c8504e',
                iconColor: '#fff',
                color: '#fff',
            }).then(() => {
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        }
    </script>

</body>
</html>
