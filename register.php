<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (isLoggedIn()) {
    header('Location: user/dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Este email ya está registrado.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashed_password])) {
                $user_id = $pdo->lastInsertId();
                
                // Iniciar sesión automáticamente
                $_SESSION['user_id'] = $user_id;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = 'user';
                
                header('Location: user/dashboard.php');
                exit;
            } else {
                $error = 'Error al registrar el usuario.';
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="card-title mb-0"><i class="bi bi-person-plus"></i> Registro de Usuario</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Registrarse</button>
                </form>
                <div class="mt-3 text-center">
                    <p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>