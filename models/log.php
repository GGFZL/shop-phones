<?php
$logFile = 'data/log.txt';

function logAccess($page) {
    global $logFile;
    
    if(isset($_SESSION['username'])) {
        $user = $_SESSION['username'];
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "$timestamp - $ip - $user accessed $page\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}

?>