<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireAdmin();

// Obtener estadísticas
$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$reservations_count = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$rooms_count = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
?>

<?php include '../includes/header.php'; ?>
<h1 class="mb-4"><i class="bi bi-speedometer2"></i> Panel de Administración</h1>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text display-4"><?= $users_count ?></p>
                    </div>
                    <i class="bi bi-people display-4"></i>
                </div>
                <a href="users.php" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Reservas</h5>
                        <p class="card-text display-4"><?= $reservations_count ?></p>
                    </div>
                    <i class="bi bi-calendar-check display-4"></i>
                </div>
                <a href="reservations.php" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Habitaciones</h5>
                        <p class="card-text display-4"><?= $rooms_count ?></p>
                    </div>
                    <i class="bi bi-door-closed display-4"></i>
                </div>
                <a href="rooms.php" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-list-check"></i> Acciones Rápidas</h5>
    </div>
    <div class="card-body">
        <div class="d-grid gap-3">
            <a href="rooms.php" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Agregar Habitación
            </a>
            <a href="reservations.php" class="btn btn-outline-success btn-lg">
                <i class="bi bi-eye"></i> Ver Todas las Reservas
            </a>
            <a href="users.php" class="btn btn-outline-info btn-lg">
                <i class="bi bi-people"></i> Gestionar Usuarios
            </a>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>