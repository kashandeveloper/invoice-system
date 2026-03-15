<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
$pdo = DB::pdo();
$data = json_decode(file_get_contents('php://input'), true) ?: [];
$name = trim((string)($data['name'] ?? ''));
$email = trim((string)($data['email'] ?? ''));
$phone = trim((string)($data['phone'] ?? ''));
$address = trim((string)($data['address'] ?? ''));
if ($name === '' || $email === '') { http_response_code(400); echo json_encode(['error'=>'Missing required fields']); exit; }
$stmt = $pdo->prepare("INSERT INTO clients (name,email,phone,address) VALUES (?,?,?,?)");
$stmt->execute([$name,$email,$phone,$address]);
echo json_encode(['success'=>true,'id'=>$pdo->lastInsertId()]);
