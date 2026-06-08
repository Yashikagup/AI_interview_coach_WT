<?php

session_start();
include 'db.php';

require('tcpdf/tcpdf.php');

if(!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

/* =========================
   FETCH DATA FROM DB
========================= */
$stmt = $pdo->prepare("
    SELECT * FROM interview_results
    WHERE user_id = ?
    ORDER BY id DESC
");

$stmt->execute([$user_id]);

$data = $stmt->fetch(PDO::FETCH_ASSOC);

$pdf = new TCPDF();
$pdf->AddPage();

$pdf->SetFont('helvetica','B',18);
$pdf->Cell(0,10,'AI Interview Report',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('helvetica','',12);

$pdf->Cell(0,10,'User ID: '.$user_id,0,1);

/* =========================
   CHECK DATA
========================= */
if(!$data)
{
    $pdf->Cell(0,10,'No Data Found',0,1);
    $pdf->Output();
    exit();
}

/* =========================
   APTITUDE
========================= */
$pdf->Ln(5);
$pdf->SetFont('helvetica','B',14);
$pdf->Cell(0,10,'Aptitude Score: '.$data['aptitude_score'],0,1);

/* =========================
   TECHNICAL
========================= */
$pdf->Ln(5);
$pdf->SetFont('helvetica','B',14);
$pdf->Cell(0,10,'Technical & HR Details',0,1);

$pdf->SetFont('helvetica','',11);

$pdf->MultiCell(0,8,"Technical:\n".$data['technical']);
$pdf->Ln(3);
$pdf->MultiCell(0,8,"HR:\n".$data['hr']);

$pdf->Output('Interview_Report.pdf','D');

?>