<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireLogin();

// Obtener todas las habitaciones
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<h1 class="mb-4"><i class="bi bi-house-door"></i> Catálogo de Habitaciones</h1>

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
            <img src="../assets/img/default.jpg" class="card-img-top" alt="Imagen no disponible">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($room['name']) ?></h5>
                <p class="card-text">
                    <span class="badge bg-primary"><?= htmlspecialchars($room['type']) ?></span>
                    <span class="badge bg-success">Bs. <?= number_format($room['price'], 2) ?></span>
                </p>
                <p class="card-text"><?= htmlspecialchars($room['description']) ?></p>
                <a href="room_detail.php?id=<?= $room['id'] ?>" class="btn btn-primary w-100">
                    <i class="bi bi-eye"></i> Ver Detalles
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include '../includes/footer.php'; ?>
