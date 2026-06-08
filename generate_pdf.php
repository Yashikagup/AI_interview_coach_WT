<?php

session_start();

error_reporting(0);
ob_start();

require('tcpdf/tcpdf.php');

$pdf = new TCPDF();

$pdf->AddPage();

$pdf->SetFont('helvetica','B',20);

$pdf->Cell(0,10,'AI Interview Report',0,1,'C');

$pdf->Ln(10);

$pdf->SetFont('helvetica','',12);

$username = $_SESSION['username'] ?? 'Candidate';

$pdf->Cell(0,10,'Candidate: '.$username,0,1);

$pdf->Ln(5);

/* =========================
   APTITUDE
========================= */

$pdf->SetFont('helvetica','B',16);

$pdf->Cell(0,10,'Aptitude Round',0,1);

$aptitude = $_SESSION['aptitude'] ?? [];

foreach($aptitude as $i => $row){

    $pdf->SetFont('helvetica','B',12);

    $pdf->MultiCell(
        0,
        8,
        "Q".($i+1).": ".($row['question'] ?? 'No question')
    );

    $pdf->SetFont('helvetica','',11);

    $pdf->MultiCell(
        0,
        8,
        "Answer: ".($row['answer'] ?? 'No answer')
    );

    $pdf->MultiCell(
        0,
        8,
        "Feedback: ".($row['feedback'] ?? 'No feedback available')
    );

    if(isset($row['score'])){
        $pdf->MultiCell(
            0,
            8,
            "Score: ".$row['score']."/10"
        );
    }

    $pdf->Ln(3);
}

/* =========================
   TECHNICAL
========================= */

$pdf->Ln(5);

$pdf->SetFont('helvetica','B',16);

$pdf->Cell(0,10,'Technical Round',0,1);

$technical = $_SESSION['technical'] ?? [];

foreach($technical as $i => $row){

    $pdf->SetFont('helvetica','B',12);

    $pdf->MultiCell(
        0,
        8,
        "Q".($i+1).": ".($row['question'] ?? 'No question')
    );

    $pdf->SetFont('helvetica','',11);

    $pdf->MultiCell(
        0,
        8,
        "Answer: ".($row['answer'] ?? 'No answer')
    );

    $pdf->MultiCell(
        0,
        8,
        "Feedback: ".($row['feedback'] ?? 'No feedback available')
    );

    if(isset($row['score'])){
        $pdf->MultiCell(
            0,
            8,
            "Score: ".$row['score']."/10"
        );
    }

    $pdf->Ln(3);
}

/* =========================
   HR ROUND
========================= */

$pdf->Ln(5);

$pdf->SetFont('helvetica','B',16);

$pdf->Cell(0,10,'HR Round',0,1);

$hr = $_SESSION['hr'] ?? [];

foreach($hr as $i => $row){

    $pdf->SetFont('helvetica','B',12);

    $pdf->MultiCell(
        0,
        8,
        "Q".($i+1).": ".($row['question'] ?? 'No question')
    );

    $pdf->SetFont('helvetica','',11);

    $pdf->MultiCell(
        0,
        8,
        "Answer: ".($row['answer'] ?? 'No answer')
    );

    $pdf->MultiCell(
        0,
        8,
        "Feedback: ".($row['feedback'] ?? 'No feedback available')
    );

    if(isset($row['score'])){
        $pdf->MultiCell(
            0,
            8,
            "Score: ".$row['score']."/10"
        );
    }

    $pdf->Ln(3);
}

ob_end_clean();

$pdf->Output(
    'Interview_Report.pdf',
    'D'
);

?>