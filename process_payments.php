<?php
include('db_connection.php');

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'];
$ids = $data['ids'];

$status = $action == 'pagar' ? 'pago' : 'em aberto';

$ids_placeholder = implode(',', array_fill(0, count($ids), '?'));
$query = "UPDATE mensalidades SET status = ? WHERE id IN ($ids_placeholder)";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    $params = array_merge([$status], $ids);
    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false]);
}

mysqli_close($conn);
?>
