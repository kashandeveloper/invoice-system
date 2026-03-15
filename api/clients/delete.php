<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
$pdo = DB::pdo();
$id = (int)($_GET['id'] ?? 0);
if ($id<=0) { http_response_code(400); echo json_encode(['error'=>'Invalid id']); exit; }
$stmt = $pdo->prepare("DELETE FROM clients WHERE id=?");
$stmt->execute([$id]);
echo json_encode(['success'=>true]);
