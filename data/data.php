<?php

$servername = "localhost";
$username = "u629519_root";
$password = "b1239potK";
$database = "u629519_credentials";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    
    if (isset($_GET['subject_category'])) {
      
        $selectedCategory = $_GET['subject_category'];

     
        $stmt = $conn->prepare("SELECT question_id, question_up, assessment_type, module, subject_category FROM question WHERE subject_category = ?");
        $stmt->bind_param("s", $selectedCategory);

        $stmt->execute();

        $stmt->bind_result($question_id, $question, $assessmentType, $module, $subjectCategory);

        $questions = array();

        
        while ($stmt->fetch()) {
            $questions[] = array(
                "question_id" => $question_id,
                "question" => $question,
                "assessment_type" => $assessmentType,
                "module" => $module,
                "subject_category" => $subjectCategory,
            );
        }

        $stmt->close();

        // Returning questions in JSON format
        header("Content-Type: application/json");
        echo json_encode($questions);
    }
}

$conn->close();
?>
