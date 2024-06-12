<div class="container d-flex justify-content-center mt-5">
    <h2>Contact Administrator</h2>
</div>
<div class="container mt-2 d-flex justify-content-center">
    <form class="w-50" action="models/processContact.php" method="post" id="contactForm">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
            <div class="invalid-feedback">
                Please enter a subject.
            </div>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Your message" required></textarea>
            <div class="invalid-feedback">
                Please enter your message.
            </div>
        </div>
        <button type="submit" class="btn btn-secondary mb-3">Submit</button>
    </form>
</div>