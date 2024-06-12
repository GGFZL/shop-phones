<?php

$sql = "
    SELECT cm.id, u.username, u.email, cm.subject, cm.message, cm.created_at
    FROM contact_messages cm
    JOIN users u ON cm.user_id = u.user_id";
$stmt = $conn->query($sql);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Submitted At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "
                SELECT cm.id, u.username, u.email, cm.subject, cm.message, cm.created_at
                FROM contact_messages cm
                JOIN users u ON cm.user_id = u.user_id";
            $stmt = $conn->query($sql);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {
                foreach ($results as $row) {
                    echo "<tr>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['subject']}</td>
                            <td>{$row['message']}</td>
                            <td>{$row['created_at']}</td>
                            <td>
                                <form action='models/deleteMessage.php' method='post'>
                                    <input type='hidden' name='message_id' value='{$row['id']}'>
                                    <button type='submit' class='w-100 btn btn-danger btn-sm'>Delete</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No messages found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>