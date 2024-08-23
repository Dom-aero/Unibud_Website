<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('/home/u629519980/domains/quest2blueprint.com/public_html/fpdf186/fpdf.php'); 

if (isset($_POST['generate_pdf']) && isset($_POST['selected_questions'])) {
    $selectedQuestions = $_POST['selected_questions'];

    
    $pdfFilename = 'temp_pdf_' . uniqid() . '.pdf';
    $pdfFilePath = '/temp' . $pdfFilename; 

    
    $pdf = new FPDF();

  
    $pdf->AddPage();

   
    $pdf->SetFont('Arial', '', 12);

  
    $servername = "localhost";
    $username = "u629519980_root";
    $password = "b1239potK";
    $database = "u629519980_credentials";

    
    $conn = new mysqli($servername, $username, $password, $database);

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

  
    $selectedQuestionIds = implode(',', $selectedQuestions);
    $query = "SELECT question_id, question_up, assessment_type FROM question WHERE question_id IN ($selectedQuestionIds)";
    $result = $conn->query($query);

    
    while ($row = $result->fetch_assoc()) {
        $question_id = $row['question_id'];
        $question = $row['question_up'];
        $assessment_type = $row['assessment_type'];

       
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Question ID: $question_id", 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, "Question: $question", 0, 'J');
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, "Assessment Type: $assessment_type", 0, 1);
        $pdf->Cell(0, 10, "Module: $module", 0, 1); 
        $pdf->Ln(5);
    }

    $pdf->SetX(-15);
    $footerY = $pdf->GetY();

   
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, "www.unibud.in", 0, 0, 'C');

 
    $conn->close();

    
    $pdf->Output($pdfFilePath, 'F'); 
    
    $_SESSION['pdf_filename'] = $pdfFilename;
}
?>

<!DOCTYPE html>
<html>
<head>
  
</head>
<body>
   
    <?php if (isset($_SESSION['pdf_filename'])) : ?>
        <a href="download.php">Download PDF</a> 
    <?php endif; ?>
</body>
</html>
