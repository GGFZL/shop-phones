<?php
    $logFile = 'data/log.txt';
    $logContents = file_get_contents($logFile);

    $logEntries = explode("\n", trim($logContents));
    $pageCounts = [];
    $totalAccesses = 0;

    foreach ($logEntries as $entry) {
        if (empty($entry)) continue;

        $parts = explode(' ', $entry);
        $page = end($parts);

        if (!isset($pageCounts[$page])) {
            $pageCounts[$page] = 0;
        }
        $pageCounts[$page]++;
        $totalAccesses++;
    }

    $pagePercentages = [];
    foreach ($pageCounts as $page => $count) {
        $pagePercentages[$page] = ($count / $totalAccesses) * 100;
    }
?>

<div class="container">
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            <h2>Page Access Statistics</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Access Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagePercentages as $page => $percentage): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($page); ?></td>
                            <td><?php echo $pageCounts[$page]; ?></td>
                            <td><?php echo number_format($percentage, 2); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="col-12 d-flex justify-content-center">
            <h2>Access Log</h2>
        </div>

        <div class="col-12 d-flex justify-content-center">
            <pre><?php echo htmlspecialchars($logContents); ?></pre>
        </div>

    </div>
</div>