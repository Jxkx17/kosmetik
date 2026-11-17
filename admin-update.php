<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require_post();
require_admin();

$changes = $_POST['quantity'] ?? [];
if (!is_array($changes)) {
    redirect('admin.php');
}

$updated = 0;
$db = db();
$db->begin_transaction();
foreach ($changes as $id => $delta) {
    $id    = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $delta = filter_var($delta, FILTER_VALIDATE_INT, ['options' => ['min_range' => -99999, 'max_range' => 99999]]);
    if ($id === false || $delta === false || $delta === 0) continue;
    // GREATEST empêche un stock négatif
    db_query('UPDATE products SET qty = GREATEST(qty + ?, 0) WHERE id = ?', 'ii', [$delta, $id]);
    $updated += db()->affected_rows;
}
$db->commit();

flash($updated ? 'success' : 'info', $updated ? "$updated produit(s) mis à jour." : 'Aucune modification.');
redirect('admin.php');
