<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
$pdo = DB::pdo();
$rows = $pdo->query("SELECT i.id,c.name AS client_name,i.total,i.created_at FROM invoices i JOIN clients c ON c.id=i.client_id ORDER BY i.id DESC")->fetchAll();
echo json_encode($rows);
