<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$query = "SELECT * FROM surveys WHERE status = 'active'";
$stmt = $conn->prepare($query);
$stmt->execute();
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the surveys the user has voted on (if user is logged in)
$userVotes = [];
if ($user_id) {
    $query = "SELECT survey_id FROM votes WHERE user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $userVotes = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}

$featuredPhones = getFeatured();
?>

<div class="container">
    <div class="col-12 justify-content-center d-flex font-weight-bold mt-5">
        <?php if(isset($_SESSION['logged_in'])): ?>
            <div class="h3 border border-dark p-2 helloUser">Hello <?php echo $_SESSION['username']; ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="row align-items-center d-flex justify-content-center mt-5 gy-5 ">
        <div class="col-12 d-flex justify-content-center mb-3 text-center">
            <h1 id="welcomTo">Welcome to SignalSphere</h1>
        </div>
        <div class="col-12 d-flex justify-content-center text-center mb-3">
            <p>
                "Step into the future with our stunning array of smartphones, meticulously crafted for the modern explorer. Embrace innovation, seize the moment, and redefine your mobile experience. Your next adventure starts here."
            </p>
        </div>
        <div class="col-12 d-flex justify-content-center mb-3">
            <a href="index.php?page=shop" class="btn buttons font-weight-bold m-2">Browse collection</a>
            <a href="index.php?page=contact" class="btn buttons font-weight-bold m-2">Contant Us</a>
        </div>
    </div>
</div>

<!-- Featured Phones Carousel -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Looking for featured phones:</h2>
    <div id="featuredPhonesCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?php $i = 0; ?>
            <?php foreach(array_chunk($featuredPhones, 4) as $item): ?>
                <div class="carousel-item <?= ($i === 0) ? 'active' : ''; ?>">
                    <div class="row">
                        <?php foreach($item as $f): ?>
                            <div class="col-md-3">
                                <div class="card mb-4">
                                    <img src="assets/thumbnails/thumb_<?= htmlspecialchars($f->Image); ?>" class="card-img-top" alt="<?= htmlspecialchars($f->name); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title text-center"><?= htmlspecialchars($f->name); ?></h5>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php $i++; ?>
            <?php endforeach; ?>
        </div>
        <a class="carousel-control-prev" href="#featuredPhonesCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#featuredPhonesCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<!-- End Featured Phones Carousel -->

<!-- Survey -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Available Surveys</h2>
    <div class="row">
        <?php if ($user_id): ?>
            <?php foreach ($surveys as $survey): ?>
                <?php if (!in_array($survey['survey_id'], $userVotes)): ?>
                    <?php
                    $query = "SELECT * FROM survey_answers WHERE survey_id = :survey_id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(":survey_id", $survey['survey_id']);
                    $stmt->execute();
                    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($survey['title']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($survey['question']); ?></p>
                                <?php foreach ($answers as $answer): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="vote" id="vote_<?= htmlspecialchars($answer['answer_id']); ?>" value="<?= htmlspecialchars($answer['answer_id']); ?>">
                                        <label class="form-check-label" for="vote_<?= htmlspecialchars($answer['answer_id']); ?>"><?= htmlspecialchars($answer['answer']); ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <form method="post" action="logic/vote.php" class="mt-auto">
                                    <input type="hidden" name="survey_id" value="<?= htmlspecialchars($survey['survey_id']); ?>">
                                    <button type="submit" class="btn btn-primary mt-3 w-25">Vote</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-md-12">
                <p class="text-center">Please log in to participate in the surveys.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- End Survey -->

<!-- trusted brands -->
<div class="container mt-5 mb-3">
    <div class="row align-items-center justify-content-center">
        <div class="col-md-12 text-center">
            <h1>Trusted Partners</h1>
            <p>We partner with top brands to bring you the best in quality and technology. Explore our prestigious partners.</p>
            <p class="font-weight-bold"><span class="spacing">Iphone</span> <span class="spacing">Huawei</span> <span class="spacing">Samsung</span> <span class="spacing">Nokia</span> <span class="spacing">Motorola</span> <span class="spacing">Xiaomi</span> <span class="spacing">LG</span> <span class="spacing">Honor</span> <span class="spacing">Sony</span></p>
        </div>
    </div>
</div>
<!-- end trusted brands -->
