<?php
declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
use Dompdf\Dompdf;
use Dompdf\Options;
$id = (int)($_GET['id'] ?? 0);
if ($id<=0) { http_response_code(400); exit; }
$pdo = DB::pdo();
$inv = $pdo->prepare("SELECT i.*, c.name AS client_name, c.email, c.phone, c.address FROM invoices i JOIN clients c ON c.id=i.client_id WHERE i.id=?");
$inv->execute([$id]);
$invoice = $inv->fetch();
if (!$invoice) { http_response_code(404); exit; }
$items = $pdo->prepare("SELECT description,quantity,price FROM invoice_items WHERE invoice_id=?");
$items->execute([$id]);
$rows = $items->fetchAll();
$htmlRows = '';
foreach ($rows as $r) {
  $htmlRows .= '<tr><td>'.htmlspecialchars($r['description']).'</td><td style="text-align:right">'.number_format((float)$r['quantity'],2).'</td><td style="text-align:right">$'.number_format((float)$r['price'],2).'</td></tr>';
}
$html = '
<style>
body{font-family:DejaVu Sans, sans-serif;color:#111}
.title{font-weight:700;font-size:20px}
.muted{color:#666}
table{width:100%;border-collapse:collapse}
th,td{padding:8px;border-bottom:1px solid #eee}
.right{text-align:right}
.total{font-weight:700;font-size:16px}
</style>
<div>
  <div class="title">Invoice #'.$invoice['id'].'</div>
  <div class="muted">Created '.$invoice['created_at'].'</div>
  <hr>
  <div><strong>Bill To</strong><br>'.htmlspecialchars($invoice['client_name']).'<br>'.htmlspecialchars((string)$invoice['email']).'<br>'.htmlspecialchars((string)$invoice['phone']).'<br>'.nl2br(htmlspecialchars((string)$invoice['address'])).'</div>
  <br>
  <table>
    <thead><tr><th>Description</th><th class="right">Qty</th><th class="right">Price</th></tr></thead>
    <tbody>'.$htmlRows.'</tbody>
  </table>
  <br>
  <table>
    <tr><td class="right" style="width:80%">Subtotal</td><td class="right">$'.number_format((float)$invoice['subtotal'],2).'</td></tr>
    <tr><td class="right">Tax</td><td class="right">$'.number_format((float)$invoice['tax_amount'],2).'</td></tr>
    <tr><td class="right total">Total</td><td class="right total">$'.number_format((float)$invoice['total'],2).'</td></tr>
  </table>
</div>';
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream('invoice-'.$invoice['id'].'.pdf', ['Attachment'=>true]);
