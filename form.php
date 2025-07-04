<?php

require 'vendor/autoload.php'; // Autoload Composer packages

use Dompdf\Dompdf;
use Dompdf\Options;

include 'database/config.php';
include 'session/security.php';

if (isset($_GET['id_loan'])) {
    $id_loan = $_GET['id_loan'];

    // Query the loan details using $id_loan
    $stmt = $connect->prepare("SELECT * FROM loans WHERE id_loan = ?");
    $stmt->bind_param("s", $id_loan);
    $stmt->execute();
    $loanDetails = $stmt->get_result()->fetch_assoc();

    if ($loanDetails) {
        // Load the template file and pass the data
        ob_start();
        $loan = $loanDetails; // Rename for clarity when passed to template
        include 'pdfhtml/template.php'; // Ensure the file path is correct
        $html = ob_get_clean();

        // Configure DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Enable for external images
        $dompdf = new Dompdf($options);

        // Load HTML content into DOMPDF
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML to PDF
        $dompdf->render();

       // Generate the filename dynamically
        $filename = "SLIP PINJAMAN BUKU - " . htmlspecialchars($loan['nama_penuh']) . ".pdf";

        // Send the PDF to the browser
        $dompdf->stream($filename, ["Attachment" => true]);
    } else {
        echo "Loan not found.";
    }
} else {
    echo "No loan ID provided.";
}
