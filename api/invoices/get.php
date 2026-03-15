<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
$pdo = DB::pdo();
$id = (int)($_GET['id'] ?? 0);
if ($id<=0) { http_response_code(400); echo json_encode(['error'=>'Invalid id']); exit; }
$inv = $pdo->prepare("SELECT * FROM invoices WHERE id=?");
$inv->execute([$id]);
$invoice = $inv->fetch();
if (!$invoice) { http_response_code(404); echo json_encode(['error'=>'Not found']); exit; }
$cli = $pdo->prepare("SELECT id,name,email,phone,address FROM clients WHERE id=?");
$cli->execute([$invoice['client_id']]);
$client = $cli->fetch();
$items = $pdo->prepare("SELECT description,quantity,price FROM invoice_items WHERE invoice_id=?");
$items->execute([$id]);
echo json_encode(['invoice'=>$invoice,'client'=>$client,'items'=>$items->fetchAll()]);
