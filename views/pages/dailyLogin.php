<?php
    $loginCountToday = countLoginsToday($conn);
    $loginsToday = getLoginsToday($conn);
?>
<div class="container">
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                <h2>Daily Logins</h2>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <h3>Broj korisnika koji su se ulogovali danas: <?php echo $loginCountToday; ?></h3>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Last logged in</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loginsToday as $login): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($login['username']); ?></td>
                                <td><?php echo htmlspecialchars($login['email']); ?></td>
                                <td><?php echo htmlspecialchars($login['last_login']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

