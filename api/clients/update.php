<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
$pdo = DB::pdo();
$data = json_decode(file_get_contents('php://input'), true) ?: [];
$id = (int)($data['id'] ?? 0);
$name = trim((string)($data['name'] ?? ''));
$email = trim((string)($data['email'] ?? ''));
$phone = trim((string)($data['phone'] ?? ''));
$address = trim((string)($data['address'] ?? ''));
if ($id<=0 || $name === '' || $email === '') { http_response_code(400); echo json_encode(['error'=>'Invalid input']); exit; }
$stmt = $pdo->prepare("UPDATE clients SET name=?,email=?,phone=?,address=? WHERE id=?");
$stmt->execute([$name,$email,$phone,$address,$id]);
echo json_encode(['success'=>true]);
