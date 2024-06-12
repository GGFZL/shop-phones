<?php
include "../../config/connection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You need to be logged in to vote.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["survey_id"]) && isset($_POST["vote"])) {
        $survey_id = $_POST["survey_id"];
        $vote = $_POST["vote"];
        $user_id = $_SESSION["user_id"];

        echo "Survey ID: $survey_id, Vote: $vote, User ID: $user_id";

        if (!empty($survey_id) && !empty($vote)) {
            $query = "SELECT * FROM votes WHERE survey_id = :survey_id AND user_id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":survey_id", $survey_id);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo "User has already voted for this survey.";
                exit;
            }

            $query = "INSERT INTO votes (survey_id, answer_id, user_id) VALUES (:survey_id, :vote, :user_id)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":survey_id", $survey_id);
            $stmt->bindParam(":vote", $vote);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();

            header("Location: ../../index.php?page=survey");
            exit;
        } else {
            echo "Invalid survey ID or vote.";
            exit;
        }
    } else {
        echo "Survey ID or vote not set.";
        exit;
    }
} else {
    echo "Invalid request method.";
    exit;
}
?>
