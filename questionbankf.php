<!DOCTYPE html>
<html>
<head>
    <title>Display Questions</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
       
        .container {
            margin-top: 30px;
        }

        .button {
            padding: 10px 20px;
            background-color: rgb(1, 130, 197);
            color: #fff;
            border: 1px solid #0074cc;
            border-radius: 3px;
            cursor: pointer;
            margin-top:20px;
        }

        .button:hover {
            background-color: #0074cc;
        }

        .questions-container {
            border: 1px solid #0074cc;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
        }
        
        .question-card {
            margin-bottom: 20px;
        }
        
        .question-card.shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .question-container {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }

        .select-question {
            margin-top: 10px;
        }
        .question-card:hover {
        background-color: #f5f5f5; 
        transition: background-color 0.3s; 
    }
    </style>
</head>
<body>
    <nav id="myNavbar" class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a href="index.php"> 
                <img src="Screenshot_2023-09-29_at_3.37.41_AM-removebg-preview.png" alt="Image description" width="100">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="text-center">Display Questions</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="my-4">
            <div class="form-group">
               
                <label for="subject_category">Select Subject Category:</label
                ><span style="color: red;">*</span> 
                
                <select id="subject_category" name="subject_category" required class="form-control">
                    <option value="" disabled>Select Subject</option>
                    <option value="Compiler Design" <?php if(isset($_POST['subject_category']) && $_POST['subject_category'] == 'Compiler Design') echo 'selected'; ?>>Compiler Design</option>
                    <option value="Database Management" <?php if(isset($_POST['subject_category']) && $_POST['subject_category'] == 'Database Management') echo 'selected'; ?>>Database Management</option>
                    <option value="Operating Systems" <?php if(isset($_POST['subject_category']) && $_POST['subject_category'] == 'Operating Systems') echo 'selected'; ?>>Operating Systems</option>
                    <option value="Computer Networks" <?php if(isset($_POST['subject_category']) && $_POST['subject_category'] == 'Computer Networks') echo 'selected'; ?>>Computer Networks</option>
                    <option value="Engineering Physics" <?php if(isset($_POST['subject_category']) && $_POST['subject_category'] == 'Engineering Physics') echo 'selected'; ?>>Engineering Physics</option>
                    <option value="Microprocessors and Microcontrollers" <?php if(isset($_POST['subject_category']) && $_POST['subject_category'] == 'Microprocessors and Microcontrollers') echo 'selected'; ?>>Microprocessors and Microcontrollers</option>
                    <option value="Discrete Mathematics and Graph Theory" <?php if(isset($_POST['subject_category']) && $_POST['subject_category'] == 'Discrete Mathematics and Graph Theory') echo 'selected'; ?>>Discrete Mathematics and Graph Theory</option>
                    <option value="Complex Variable and Linear Algebra" <?php if(isset($_POST['subject_category']) && $_POST['subject_category'] == 'Complex Variable and Linear Algebra') echo 'selected'; ?>>Complex Variable and Linear Algebra</option>

                </select>
            </div>
            <div class="form-group my-4">
                <label for="search_keyword">Search by Keyword:</label>
                <input type="text" id="search_keyword" name="search_keyword" class="form-control" placeholder="Enter Keyword" value="<?php if(isset($_POST['search_keyword'])) echo $_POST['search_keyword']; ?>">
            </div>
            <div class="form-group my-4">
                <label for="search_module">Search by Module:</label>
                <input type="text" id="search_module" name="search_module" class="form-control" placeholder="Enter Module" value="<?php if(isset($_POST['search_module'])) echo $_POST['search_module']; ?>">
            </div>
            <button type="submit" value="Submit" class="btn btn-primary button">Submit</button>
        </form>
       
        <?php
      
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

      
        $servername = "localhost";
        $username = "u6295199_root";
        $password = "b1239potK";
        $database = "u6295199_credentials";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

       
        echo '<script>';
        echo 'let selectedQuestionIds = JSON.parse(localStorage.getItem("selectedQuestionIds")) || [];';
        echo '</script>';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $subjectCategory = $_POST["subject_category"];
                $searchKeyword = $_POST["search_keyword"];
                $searchModule = $_POST["search_module"];

                $stmt = $conn->prepare("SELECT question_id, question_up, assessment_type, module FROM question WHERE subject_category = ? AND question_up LIKE ? AND module LIKE ? ORDER BY module ASC");
                $searchKeyword = "%{$searchKeyword}%"; 
                $searchModule = "%{$searchModule}%"; 
                $stmt->bind_param("sss", $subjectCategory, $searchKeyword, $searchModule);
                $stmt->execute();

                $stmt->bind_result($question_id, $question, $assessmentType, $module);

                
                while ($stmt->fetch()) {
                    echo '<div class="question-card question-card card shadow">';
                    echo '<div class="card-body">';
                    echo '<input type="checkbox" name="selected_questions[]" value="' . $question_id . '" onclick="updateSelectedOrder(this)">';
                    echo "<p class='card-text'>Question: $question</p>";
                    echo "<p class='card-text'>Assessment Type: $assessmentType</p>";
                    echo "<p class='card-text'>Module: $module</p>";
                    echo '</div>';
                    echo '</div>';
                }

                $stmt->close();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
        
        <form action="generate_pdf.php" method="post">
            <div class="selected-questions-container">
                
            </div>
            
          
            <input type="hidden" name="selected_question_order" id="selected_question_order">
           
            <button type="submit" id="generate_pdf_button" name="generate_pdf" class="button my-4">Generate PDF</button>
            <span style="color: red;">Note : </span> 
            <span class="generate-pdf-text">To generate the PDF, select your questions by checking the checkboxes and then click on "Generate PDF."</span>
           
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      
        function updateSelectedOrder(checkbox) {
            const questionId = checkbox.value;

            if (checkbox.checked) {
                
                selectedQuestionIds.push(questionId);
            } else {
               
                const index = selectedQuestionIds.indexOf(questionId);
                if (index > -1) {
                    selectedQuestionIds.splice(index, 1);
                }
            }

            
            document.querySelector("#selected_question_order").value = selectedQuestionIds.join(',');

            
            localStorage.setItem("selectedQuestionIds", JSON.stringify(selectedQuestionIds));
        }
        
        
        document.querySelector("#subject_category").addEventListener("change", function() {
            
            selectedQuestionIds = [];
            localStorage.removeItem("selectedQuestionIds");
        });
    </script>
</body>
</html>
