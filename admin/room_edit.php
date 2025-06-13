<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireAdmin();

$room_id = $_GET['id'] ?? 0;


$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

if (!$room) {
    header('Location: rooms.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $images = $_FILES['images'] ?? [];
    $existing_images = $_POST['existing_images'] ?? [];

 
    if (empty($name) || empty($type) || empty($description) || $price <= 0) {
        $error = 'Por favor complete todos los campos correctamente.';
    } else {
      
        $uploaded_images = [];
        if (!empty($images['name'][0])) {
            
            $target_dir = 'C:/xampp/htdocs/DESARROLLO WEB/Laboratorios/LAB_5_SIS256/assets/img/';
            

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            for ($i = 0; $i < count($images['name']); $i++) {
                $file_name = $images['name'][$i];
                $file_tmp = $images['tmp_name'][$i];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($file_ext, $allowed_ext)) {
                    $new_name = uniqid() . '.' . $file_ext;
                    $target = $target_dir . $new_name;
                    
                    if (move_uploaded_file($file_tmp, $target)) {
                        $uploaded_images[] = $new_name;
                    } else {
                        error_log("Error al mover archivo: " . $file_tmp . " a " . $target);
                    }
                }
            }
        }
        

        $all_images = array_merge($existing_images, $uploaded_images);
        $images_str = implode(',', $all_images);
        

        $stmt = $pdo->prepare("UPDATE rooms SET name = ?, type = ?, description = ?, price = ?, images = ? WHERE id = ?");
        if ($stmt->execute([$name, $type, $description, $price, $images_str, $room_id])) {
            $success = 'Habitación actualizada con éxito.';
      
            $room['name'] = $name;
            $room['type'] = $type;
            $room['description'] = $description;
            $room['price'] = $price;
            $room['images'] = $images_str;
        } else {
            $error = 'Error al actualizar la habitación.';
        }
    }
}

$current_images = explode(',', $room['images']);
?>

<?php include '../includes/header.php'; ?>
<h1 class="mb-4"><i class="bi bi-pencil"></i> Editar Habitación</h1>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Nombre de la Habitación</label>
        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($room['name']) ?>" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Tipo de Habitación</label>
        <input type="text" class="form-control" name="type" value="<?= htmlspecialchars($room['type']) ?>" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea class="form-control" name="description" rows="3" required><?= htmlspecialchars($room['description']) ?></textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Precio por Noche (Bs.)</label>
        <input type="number" step="0.01" class="form-control" name="price" value="<?= $room['price'] ?>" required>
    </div>
    
    <div class="mb-4">
        <label class="form-label">Imágenes Actuales</label>
        <div class="d-flex flex-wrap gap-3">
            <?php foreach ($current_images as $image): 
                $image = trim($image);
                if ($image):
            ?>
            <div class="position-relative">
                <img src="../assets/img/<?= $image ?>" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                <div class="form-check position-absolute top-0 start-0 m-1">
                    <input class="form-check-input" type="checkbox" name="existing_images[]" value="<?= $image ?>" checked>
                </div>
            </div>
            <?php endif; endforeach; ?>
        </div>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Agregar Nuevas Imágenes</label>
        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
        <div class="form-text">Puede seleccionar múltiples imágenes.</div>
    </div>
    
    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-save"></i> Guardar Cambios
        </button>
    </div>
</form>
<?php include '../includes/footer.php'; ?>
