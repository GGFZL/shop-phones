<?php
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$lock_message = isset($_SESSION['lock_message']) ? $_SESSION['lock_message'] : '';
$lock_timer = isset($_SESSION['lock_timer']) ? $_SESSION['lock_timer'] : '';
unset($_SESSION['error_message']);
unset($_SESSION['lock_message']);
unset($_SESSION['lock_timer']);
?>

<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="login-form">
        <h2 class="text-center">Login</h2>
        <?php if (!empty($lock_message)) : ?>
          <div class="alert alert-warning" role="alert">
            <?php echo $lock_message; ?> Remaining time: <span id="timer"><?php echo $lock_timer; ?></span> seconds.
          </div>
          <script>
            var timer = document.getElementById('timer').innerText;
            var interval = setInterval(function() {
              timer--;
              document.getElementById('timer').innerText = timer;
              if (timer <= 0) {
                clearInterval(interval);
                location.reload();
              }
            }, 1000);
          </script>
        <?php endif; ?>
        <?php if (!empty($error_message)) : ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
          </div>
        <?php endif; ?>
        <form action="models/login.php" method="POST">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-secondary btn-block">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
