<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireAdmin();

$error = '';
$success = '';


$name = '';
$type = '';
$description = '';
$price = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $images = $_FILES['images'] ?? [];

 
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
        
        $images_str = implode(',', $uploaded_images);
        
      
        $stmt = $pdo->prepare("INSERT INTO rooms (name, type, description, price, images) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $type, $description, $price, $images_str])) {
            $success = 'Habitación agregada con éxito.';
          
            $name = $type = $description = '';
            $price = 0;
        } else {
            $error = 'Error al agregar la habitación.';
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<h1 class="mb-4"><i class="bi bi-plus-circle"></i> Agregar Habitación</h1>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Nombre de la Habitación</label>
        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($name) ?>" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Tipo de Habitación</label>
        <input type="text" class="form-control" name="type" value="<?= htmlspecialchars($type) ?>" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea class="form-control" name="description" rows="3" required><?= htmlspecialchars($description) ?></textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Precio por Noche (Bs.)</label>
        <input type="number" step="0.01" class="form-control" name="price" value="<?= $price ?>" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Imágenes</label>
        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
        <div class="form-text">Puede seleccionar múltiples imágenes.</div>
    </div>
    
    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-save"></i> Guardar Habitación
        </button>
    </div>
</form>
<?php include '../includes/footer.php'; ?>
