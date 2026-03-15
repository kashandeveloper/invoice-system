<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
$pdo = DB::pdo();
$clients = (int)$pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$invoices = (int)$pdo->query("SELECT COUNT(*) FROM invoices")->fetchColumn();
$stmt = $pdo->prepare("SELECT COALESCE(SUM(total),0) FROM invoices WHERE YEAR(created_at)=YEAR(CURDATE()) AND MONTH(created_at)=MONTH(CURDATE())");
$stmt->execute();
$revenue_month = (float)$stmt->fetchColumn();
$recent = $pdo->query("SELECT i.id,c.name AS client_name,i.total FROM invoices i JOIN clients c ON c.id=i.client_id ORDER BY i.id DESC LIMIT 6")->fetchAll();
$monthly = $pdo->query("SELECT DATE_FORMAT(created_at,'%Y-%m') AS m, SUM(total) AS t FROM invoices GROUP BY m ORDER BY m DESC LIMIT 12")->fetchAll();
$monthly = array_map(fn($r)=>['month'=>$r['m'],'total'=>(float)$r['t']], $monthly);
echo json_encode([
  'clients'=>$clients,
  'invoices'=>$invoices,
  'revenue_month'=>$revenue_month,
  'recent'=>$recent,
  'monthly'=>$monthly
]);
