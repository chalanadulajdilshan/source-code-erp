<?php
$encrypted = $_GET['code'] ?? null;

if (!$encrypted) {
    echo "Missing code!";
    exit;
}

$key = hex2bin(getenv('ENCRYPTION_KEY'));
$iv = substr($key, 0, 16);

$decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
$decrypted = trim($decrypted);

$whitelist = [
    'customer-master.php',
    'item-master.php',
    'manage-brand-master.php',
    'manage-department-master.php',
    'manage-branch-master.php',
];

if (in_array($decrypted, $whitelist)) {
    header("Location: /source-code-erp/$decrypted");
    exit;
} else {
    echo "Unauthorized access!";
}
