<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
$pdo = DB::pdo();
$rows = $pdo->query("SELECT DATE_FORMAT(created_at,'%Y-%m') AS month, SUM(total) AS total FROM invoices GROUP BY month ORDER BY month DESC LIMIT 24")->fetchAll();
echo json_encode($rows);
