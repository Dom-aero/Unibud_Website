<?php
require_once('/home/u629519980/domains/quest2blueprint.com/public_html/fpdf186/fpdf.php');

if (isset($_POST['generate_pdf']) && isset($_POST['selected_question_order'])) {
   
    $selectedQuestionOrder = explode(',', $_POST['selected_question_order']);

    
    $pdf = new FPDF();

    $pdf->AddPage();

   
    $pdf->SetFont('Arial', '', 12);

  
    $servername = "localhost";
    $username = "u629519_root";
    $password = "b1239potK";
    $database = "u629519_credentials";

    
    $conn = new mysqli($servername, $username, $password, $database);

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

   
    foreach ($selectedQuestionOrder as $question_id) {
       
        $query = "SELECT question_id, question_up, assessment_type, module FROM question WHERE question_id = $question_id";
        $result = $conn->query($query);

        if ($result && $row = $result->fetch_assoc()) {
            $question_id = $row['question_id'];
            $question = $row['question_up'];
            $assessment_type = $row['assessment_type'];
            $module = $row['module'];

            
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, "Question ID: $question_id", 0, 1);
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCell(0, 10, "Question: $question", 0, 'J');
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, "Assessment Type: $assessment_type", 0, 1);
            $pdf->Cell(0, 10, "Module: $module", 0, 1); 
            $pdf->Ln(5);
            $pdf->Ln(20); 
        }
    }

    $pdf->SetX(-15);
    $footerY = $pdf->GetY();

  
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, "www.unibud.in", 0, 0, 'C');

  
    $conn->close();

   
    $pdfContent = $pdf->Output('', 'S');

    
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="selected_questions.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($pdfContent));

    
    echo $pdfContent;

   
    exit;
}
?>
