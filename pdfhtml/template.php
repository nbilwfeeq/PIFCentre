<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>PIF CENTRE - SLIP PINJAMAN BUKU</title>
</head>
<style>
    tr.border-bottom td {
        border-bottom: 1pt solid #969696;
    }
</style>
<body style="font-family: 'Arial', sans-serif;">
    <div class="container">
        <!-- Header -->
        <table cellpadding="3" class="table">
            <tr>
                <td style="width: 20%;">
                    <img src="<?php echo 'dashboard\PIF CENTER\images\kvks.png'; ?>" alt="KVKS" width="50%">
                </td>
                <td style="width: 10%;">
                    <img src="<?php echo 'dashboard\PIF CENTER\images\pif-logo.png'; ?>" alt="PIF" width="35%">
                </td>
                <td style="width: 50%;">
                    <p class="h6" style="font-size:10px;">
                        <b>PERPUSTAKAAN IBNU FIRNAS</b><br>
                        <b>KOLEJ VOKASIONAL KUALA SELANGOR</b><br>
                        45600 Bestari Jaya,<br>
                        Selangor Darul Ehsan
                    </p>
                </td>
                <td>
                    <p class="h6" style="font-size:11px;">
                        Email : BHA3001@moe.edu.my<br>
                        Tel : +60332718370<br>
                        Fax : +60332718371<br>
                        Portal : kvkualaselangor.moe.edu.my
                    </p>
                </td>
            </tr>
        </table>
        <!-- Main Content -->
        <div style="font-weight:bold; font-size:16px; background-color: #997950; padding:1rem; text-align:center; color: white;">
            SLIP PINJAMAN BUKU
        </div>
        <div style="padding:1rem;">
            <strong>Maklumat Pinjaman</strong>
            <table cellpadding="10" cellspacing="0" style="margin-top:10px; width:100%;">
                <tr class="border-bottom">
                    <td><strong>Nama Peminjam</strong></td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($loan['nama_penuh']); ?></td>
                </tr>
                <tr class="border-bottom">
                    <td><strong>No. Kad Pengenalan</strong></td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($loan['nokp']); ?></td>
                </tr>
                <tr class="border-bottom">
                    <td><strong>ID Tempahan</strong></td>
                    <td>:</td>
                    <td>ID<?php echo htmlspecialchars($loan['id_reservation']); ?></td>
                </tr>
                <tr class="border-bottom">
                    <td><strong>Judul Buku</strong></td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($loan['judul_buku']); ?></td>
                </tr>
                <tr class="border-bottom">
                    <td><strong>ISBN</strong></td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($loan['isbn']); ?></td>
                </tr>
                <tr class="border-bottom">
                    <td><strong>Tarikh Pinjam</strong></td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($loan['reserve_date']); ?></td>
                </tr>
                <tr class="border-bottom">
                    <td><strong>Tarikh Pulang</strong></td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($loan['return_date']); ?></td>
                </tr>
            </table>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
