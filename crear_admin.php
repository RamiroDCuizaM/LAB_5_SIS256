<?php
require_once 'includes/db.php';

$username = 'juanjo';
$password = password_hash('123456', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->execute([$username, $password]);

echo "Admin creado correctamente.";
