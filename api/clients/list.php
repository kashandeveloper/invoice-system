<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
$pdo = DB::pdo();
$rows = $pdo->query("SELECT id,name,email,phone,address,created_at FROM clients ORDER BY id DESC")->fetchAll();
echo json_encode($rows);
