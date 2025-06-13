<?php
require_once 'includes/session.php';
require_once 'includes/db.php';

if (isLoggedIn()) {
    header('Location: user/dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Buscar usuario
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        header('Location: user/dashboard.php');
        exit;
    } else {
        // Si no es usuario, probar con admin
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['name'] = 'Admin';
            $_SESSION['email'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header('Location: admin/index.php');
            exit;
        } else {
            $error = 'Credenciales incorrectas.';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0"><i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form id="loginForm" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email o Usuario</label>
                        <input type="text" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                </form>
                <div class="mt-3 text-center">
                    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>