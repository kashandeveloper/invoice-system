<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
$pdo = DB::pdo();
$data = json_decode(file_get_contents('php://input'), true) ?: [];
$client_id = (int)($data['client_id'] ?? 0);
$tax_percent = (float)($data['tax_percent'] ?? 0);
$items = $data['items'] ?? [];
if ($client_id<=0 || !is_array($items) || count($items)===0) { http_response_code(400); echo json_encode(['error'=>'Invalid input']); exit; }
$calc_sub = 0.0;
foreach ($items as $it) {
  $q = (float)($it['quantity'] ?? 0);
  $p = (float)($it['price'] ?? 0);
  $desc = trim((string)($it['description'] ?? ''));
  if ($desc !== '' && $q>0) $calc_sub += $q*$p;
}
$tax_amount = $calc_sub * ($tax_percent/100);
$total = $calc_sub + $tax_amount;
try {
  $pdo->beginTransaction();
  $stmt = $pdo->prepare("INSERT INTO invoices (client_id,subtotal,tax_percent,tax_amount,total) VALUES (?,?,?,?,?)");
  $stmt->execute([$client_id,$calc_sub,$tax_percent,$tax_amount,$total]);
  $invoice_id = (int)$pdo->lastInsertId();
  $stmtItem = $pdo->prepare("INSERT INTO invoice_items (invoice_id,description,quantity,price) VALUES (?,?,?,?)");
  foreach ($items as $it) {
    $desc = trim((string)($it['description'] ?? ''));
    $q = (float)($it['quantity'] ?? 0);
    $p = (float)($it['price'] ?? 0);
    if ($desc === '' || $q<=0) continue;
    $stmtItem->execute([$invoice_id,$desc,$q,$p]);
  }
  $pdo->commit();
  echo json_encode(['success'=>true,'id'=>$invoice_id,'total'=>$total]);
} catch (Throwable $e) {
  if ($pdo->inTransaction()) { $pdo->rollBack(); }
  http_response_code(500);
  echo json_encode(['error'=>'Failed to create invoice']);
}
