<?php
$title = '';
$question = '';

try {
    $query = "SELECT * FROM surveys WHERE status = 'active'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $surveyMap = [];
    foreach ($surveys as $survey) {
        $surveyMap[$survey['survey_id']] = $survey;
    }

    $statistics = [];
    foreach ($surveyMap as $survey_id => $survey) {
        $query = "SELECT survey_answers.answer, COUNT(votes.answer_id) AS count 
                  FROM survey_answers 
                  LEFT JOIN votes ON survey_answers.answer_id = votes.answer_id AND votes.survey_id = survey_answers.survey_id
                  WHERE survey_answers.survey_id = :survey_id
                  GROUP BY survey_answers.answer_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":survey_id", $survey_id);
        $stmt->execute();
        $statistics[$survey_id] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container mb-5 mt-5">
    <div class="row">
        <div class="col-md-6">
            <h2 class="mb-4">Survey Statistics</h2>
            <?php foreach ($statistics as $survey_id => $stats): ?>
                <?php if (isset($surveyMap[$survey_id])): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($surveyMap[$survey_id]['title']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($surveyMap[$survey_id]['question']); ?></p>
                            <ul class="list-group">
                                <?php if (empty($stats)): ?>
                                    <li class="list-group-item">No votes yet</li>
                                <?php else: ?>
                                    <?php foreach ($stats as $stat): ?>
                                        <li class="list-group-item"><?= htmlspecialchars($stat['answer']); ?>: <?= $stat['count']; ?> votes</li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="col-md-6">
            <h2 class="mt-5">Create New Survey</h2>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="models/surveys/add_survey.php" class="mt-3">
                <div class="form-group">
                    <label for="title">Survey Title:</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
                </div>
                <div class="form-group">
                    <label for="question">Survey Question:</label>
                    <textarea id="question" name="question" class="form-control" required><?php echo htmlspecialchars($question); ?></textarea>
                </div>
                <div id="answers-container">
                    <div class="form-group">
                        <label for="answer1">Answer 1:</label>
                        <input type="text" id="answer1" name="answers[]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="answer2">Answer 2:</label>
                        <input type="text" id="answer2" name="answers[]" class="form-control" required>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" id="add-answer-btn">Add Answer</button>
                <button type="submit" class="btn btn-primary">Create Survey</button>
            </form>
        </div>
    </div>
</div>
