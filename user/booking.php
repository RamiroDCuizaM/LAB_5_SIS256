<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'] ?? 0;
    $dates = $_POST['dates'] ?? ''; // Cambiado a dates
    $user_id = $_SESSION['user_id'];
    
    // Validar datos
    if (empty($dates) || $room_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }
    
    // Convertir fechas a un array
    $datesArray = explode(',', $dates);
    
    // Verificar disponibilidad
    foreach ($datesArray as $date) {
        $stmt = $pdo->prepare("SELECT * FROM reservations WHERE room_id = ? AND date = ? AND status = 'confirmed'");
        $stmt->execute([$room_id, $date]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'La habitación no está disponible en la fecha: ' . $date]);
            exit;
        }
    }
    
    // Crear reserva
    foreach ($datesArray as $date) {
        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, room_id, date, status) VALUES (?, ?, ?, 'confirmed')");
        if (!$stmt->execute([$user_id, $room_id, $date])) {
            echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva.']);
            exit;
        }
    }

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Error en la solicitud']);
?>
