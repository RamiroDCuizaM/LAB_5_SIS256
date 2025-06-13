<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireAdmin();

$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
$statusCondition = '';


if ($statusFilter === 'confirmed') {
    $statusCondition = "WHERE r.status = 'confirmed'";
} elseif ($statusFilter === 'cancelled') {
    $statusCondition = "WHERE r.status = 'cancelled'";
}

$stmt = $pdo->query("
    SELECT r.id, u.name AS user_name, rm.name AS room_name, r.date, r.status, r.created_at 
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN rooms rm ON r.room_id = rm.id
    $statusCondition
    ORDER BY r.date DESC
");
$reservations = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<h1 class="mb-4"><i class="bi bi-calendar-check"></i> Gestión de Reservas</h1>

<div class="mb-3">
    <a href="?status=all" class="btn btn-secondary">Todas</a>
    <a href="?status=confirmed" class="btn btn-success">Confirmadas</a>
    <a href="?status=cancelled" class="btn btn-danger">Canceladas</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Habitación</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Reserva</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= $reservation['id'] ?></td>
                        <td><?= htmlspecialchars($reservation['user_name']) ?></td>
                        <td><?= htmlspecialchars($reservation['room_name']) ?></td>
                        <td><?= $reservation['date'] ?></td>
                        <td>
                            <span class="badge bg-<?= $reservation['status'] === 'confirmed' ? 'success' : 'danger' ?>">
                                <?= $reservation['status'] === 'confirmed' ? 'Confirmada' : 'Cancelada' ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($reservation['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
