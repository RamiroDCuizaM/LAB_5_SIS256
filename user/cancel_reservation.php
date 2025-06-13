<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['id'] ?? 0;
    $dates = $_POST['dates'] ?? [];

    if (empty($reservation_id) || empty($dates)) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Verificar que la reserva pertenece al usuario
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->execute([$reservation_id, $user_id]);
    $reservation = $stmt->fetch();

    if (!$reservation) {
        echo json_encode(['success' => false, 'message' => 'Reserva no encontrada']);
        exit;
    }

    // Preparar placeholders para la consulta in
    $placeholders = implode(',', array_fill(0, count($dates), '?'));
    $params = array_merge([$reservation_id, $user_id], $dates);

    $sql = "UPDATE reservations SET status = 'cancelled' WHERE id = ? AND user_id = ? AND date IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Error al cancelar la reserva']);
}
