<?php
// OPTIONAL: set a secret token and match it in GitHub webhook
$secret = 'e7c2d76a26b845b6a2f16b9df23b5017e90c12a6d5c343b6c294a67b4f8888c8 ';

// Validate GitHub signature (optional but recommended)
$headers = getallheaders();
$payload = file_get_contents('php://input');
$signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if (!isset($headers['X-Hub-Signature-256']) || !hash_equals($signature, $headers['X-Hub-Signature-256'])) {
    http_response_code(403);
    exit('Invalid signature.');
}

// Run Git pull command
$output = shell_exec('cd /home/sourcecodelk/sites/source-code-erp && git pull 2>&1');
echo "<pre>$output</pre>";
