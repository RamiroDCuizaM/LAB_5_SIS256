<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$id]);
    $room = $stmt->fetch();
    
    if (!$room) {
        echo json_encode(['success' => false, 'message' => 'Habitación no encontrada']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
    if ($stmt->execute([$id])) {
       
        $images = explode(',', $room['images']);
        foreach ($images as $image) {
            $image = trim($image);
            if ($image) {
                
                $file = 'C:/xampp/htdocs/DESARROLLO WEB/Laboratorios/LAB_5_SIS256/assets/img/' . $image;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Error en la solicitud']);
?>
