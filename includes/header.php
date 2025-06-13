<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Hotelera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css"> <!-- Agregar CSS del datepicker -->
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../"> 
                <i class="bi bi-building"></i> Hotel Dulces Alegrías
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../user/dashboard.php"> 
                                <i class="bi bi-house-door"></i> Catálogo de Habitaciones
                            </a>
                        </li>
                        <?php if (!isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../user/reservations.php"> 
                                <i class="bi bi-calendar-check"></i> Mis Reservas
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/"> 
                                    <i class="bi bi-speedometer2"></i> Panel Admin
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php"> 
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php"> 
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../register.php"> 
                                <i class="bi bi-person-plus"></i> Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-4"></div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</body>
</html>
