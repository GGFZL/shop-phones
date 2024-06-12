<?php
session_start();
require_once '../../config/connection.php';

$title = $question = "";
$answers = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $question = $_POST["question"];
    $answers = $_POST["answers"];

    if (!empty($title) && !empty($question) && count($answers) >= 2) {
        try {
            $conn->beginTransaction();

            $query1 = "INSERT INTO surveys (title, question, status) VALUES (:title, :question, 'active')";
            $stmt = $conn->prepare($query1);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":question", $question);
            $stmt->execute();
            $survey_id = $conn->lastInsertId();

            $query2 = "INSERT INTO survey_answers (survey_id, answer) VALUES (:id, :answer)";
            $stmt = $conn->prepare($query2);

            foreach ($answers as $answer) {
                $stmt->bindParam(":id", $survey_id);
                $stmt->bindParam(":answer", $answer);
                $stmt->execute();
            }

            $conn->commit();
            header("Location: ../../index.php?page=survey");
            exit();
        } catch (PDOException $e) {
            $conn->rollBack();
            $error_message = "Error: " . $e->getMessage();
        }
    } else {
        $error_message = "Please fill in all required fields and provide at least 2 answers.";
    }
}
?>
