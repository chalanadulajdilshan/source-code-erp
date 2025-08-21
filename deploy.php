<?php
// --- CONFIG ---
$secret = 'e7c2d76a26b845b6a2f16b9df23b5017e90c12a6d5c343b6c294a67b4f8888c8';

$repoDir = "/home/chalcepi/sites/source-code-erp";
$branch = "main"; // change if using "master"

// --- VERIFY SIGNATURE ---
$payload = file_get_contents('php://input');
$signature = 'sha1=' . hash_hmac('sha1', $payload, $secret, false);

if (!isset($_SERVER['HTTP_X_HUB_SIGNATURE']) || !hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE'])) {
    http_response_code(403);
    exit("Access denied: Invalid signature");
}

// --- DEPLOY ---
exec("cd {$repoDir} && git reset --hard && git pull origin {$branch} 2>&1", $output, $result);

if ($result !== 0) {
    http_response_code(500);
    echo "Deploy failed:\n" . implode("\n", $output);
    exit;
}
