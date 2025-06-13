<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireLogin();

$room_id = $_GET['id'] ?? 0;

// Obtener la habitación
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

if (!$room) {
    header('Location: dashboard.php');
    exit;
}

// Obtener fechas reservadas
$stmt = $pdo->prepare("SELECT date FROM reservations WHERE room_id = ? AND status = 'confirmed'");
$stmt->execute([$room_id]);
$reserved_dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Convertir a JSON para JavaScript
$reserved_dates_js = json_encode($reserved_dates);
?>

<?php include '../includes/header.php'; ?>
<div class="row">
    <div class="col-md-6">
        <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $images = explode(',', $room['images']);
                foreach ($images as $index => $image):
                    $image = trim($image);
                    if ($image):
                ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="../assets/img/<?= $image ?>" class="d-block w-100" alt="Imagen de habitación">
                </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
    <div class="col-md-6">
        <h2><?= htmlspecialchars($room['name']) ?></h2>
        <p class="lead">
            <span class="badge bg-primary"><?= htmlspecialchars($room['type']) ?></span>
            <span class="badge bg-success">Bs. <?= number_format($room['price'], 2) ?></span>
        </p>
        <p><?= htmlspecialchars($room['description']) ?></p>
        
        <div class="mt-5">
            <h4>Reservar Habitación</h4>
            <div class="mb-3">
                <label class="form-label">Seleccione las fechas:</label>
                <input type="text" id="datepicker" class="form-control datepicker" readonly>
            </div>
            <div class="alert alert-danger d-none" id="dateError"></div>
            <div class="d-grid">
                <button id="bookButton" class="btn btn-success" disabled>
                    <i class="bi bi-credit-card"></i> Reservar por Bs. <span id="totalPrice"><?= $room['price'] ?></span>
                </button>
                <button id="payButton" class="btn btn-primary d-none">
                    <i class="bi bi-credit-card"></i> Pagar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reservedDates = <?= $reserved_dates_js ?>;
    const roomPrice = <?= $room['price'] ?>;
    const roomId = <?= $room_id ?>;
    
    // Configurar datepicker
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        multidate: true,
        beforeShowDay: function(date) {
            const string = moment(date).format('YYYY-MM-DD'); // Usar moment.js para formatear la fecha
            return [reservedDates.indexOf(string) === -1];
        }
    }).on('changeDate', function(e) {
        const selectedDates = e.dates; // Obtener las fechas seleccionadas
        $('#bookButton').prop('disabled', selectedDates.length === 0);
        $('#totalPrice').text((selectedDates.length * roomPrice).toFixed(2)); // Calcular el total
    });
    
    // Manejar clic en botón de reserva
    $('#bookButton').click(function() {
        const selectedDates = $('#datepicker').val();
        if (!selectedDates) {
            $('#dateError').text('Por favor seleccione al menos una fecha').removeClass('d-none');
            return;
        }
        
        // Realizar reserva con AJAX
        $.ajax({
            url: 'booking.php',
            type: 'POST',
            data: {
                room_id: roomId,
                dates: selectedDates // Enviar las fechas seleccionadas
            },
            success: function(response) {
                if (response.success) {
                    alert('Reserva realizada con éxito!');
                    $('#payButton').removeClass('d-none'); // Mostrar botón de pago
                } else {
                    $('#dateError').text(response.message).removeClass('d-none');
                }
            }
        });
    });

    // Manejar clic en botón de pago
    $('#payButton').click(function() {
        const totalPrice = $('#totalPrice').text();
        alert('Total a pagar: Bs. ' + totalPrice);
        // Aquí puedes redirigir al usuario a una página de pago o abrir un modal
    });
});
</script>

<?php include '../includes/footer.php'; ?>
