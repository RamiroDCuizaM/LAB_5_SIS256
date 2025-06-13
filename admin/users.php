<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireAdmin();

// Obtener todos los usuarios
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>
<h1 class="mb-4"><i class="bi bi-people"></i> Gestión de Usuarios</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                <?= $user['role'] === 'admin' ? 'Administrador' : 'Usuario' ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>