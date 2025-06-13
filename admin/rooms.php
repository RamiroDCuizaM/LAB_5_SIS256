<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireAdmin();

// Obtener todas las habitaciones
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<h1 class="mb-4"><i class="bi bi-door-closed"></i> Gestión de Habitaciones</h1>

<div class="d-flex justify-content-end mb-3">
    <a href="room_add.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Agregar Habitación
    </a>
</div>

<div class="row">
    <?php foreach ($rooms as $room): 
        $images = explode(',', $room['images']);
        $firstImage = trim($images[0]);
    ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow">
            <?php if ($firstImage): ?>
            <img src="../assets/img/<?= $firstImage ?>" class="card-img-top" alt="<?= htmlspecialchars($room['name']) ?>">
            <?php else: ?>
            <img src="../assets/img/default.jpg" class="card-img-top" alt="Imagen no disponible"> <!-- Imagen por defecto -->
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($room['name']) ?></h5>
                <p class="card-text">
                    <span class="badge bg-primary"><?= htmlspecialchars($room['type']) ?></span>
                    <span class="badge bg-success">Bs. <?= number_format($room['price'], 2) ?></span>
                </p>
                <p class="card-text"><?= htmlspecialchars($room['description']) ?></p>
            </div>
            <div class="card-footer bg-white">
                <a href="room_edit.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <button class="btn btn-sm btn-danger delete-room" data-id="<?= $room['id'] ?>">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  
    document.querySelectorAll('.delete-room').forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.getAttribute('data-id');
            
            if (confirm('¿Estás seguro de eliminar esta habitación?')) {
                $.ajax({
                    url: 'room_delete.php',
                    type: 'POST',
                    data: { id: roomId },
                    success: function(response) {
                        if (response.success) {
                            alert('Habitación eliminada con éxito');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            }
        });
    });
});
</script>
<?php include '../includes/footer.php'; ?>
