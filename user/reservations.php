<?php
require_once '../includes/session.php';
require_once '../includes/db.php';

requireLogin();

$limit = 1000; // Mostrar todas las reservas sin paginación
$page = 1;
$offset = 0;

// Obtener solo reservas activas (status = 'confirmed') del usuario
$stmt = $pdo->prepare("
    SELECT 
        r.id AS reservation_id, r.date, r.status, 
        rm.name AS room_name, rm.type AS room_type, rm.price AS room_price, rm.images AS room_images
    FROM reservations r
    JOIN rooms rm ON r.room_id = rm.id
    WHERE r.user_id = ? AND r.status = 'confirmed'
    ORDER BY r.date DESC
    LIMIT ? OFFSET ?
");
$stmt->execute([$_SESSION['user_id'], $limit, $offset]);
$reservations = $stmt->fetchAll();

?>

<?php include '../includes/header.php'; ?>

<h1 class="mb-4"><i class="bi bi-calendar-check"></i> Mis Reservas</h1>

<?php if (empty($reservations)): ?>
    <div class="alert alert-info">
        No tienes reservas activas.
    </div>
<?php else: ?>
    <div class="row">
    <?php 
    $roomsGrouped = [];
    foreach ($reservations as $res) {
        $roomId = $res['room_name'].'-'.$res['room_type'];
        if (!isset($roomsGrouped[$roomId])) {
            $roomsGrouped[$roomId] = [
                'room_name' => $res['room_name'],
                'room_type' => $res['room_type'],
                'room_price' => $res['room_price'],
                'room_images' => $res['room_images'],
                'dates' => [],
                'reservation_id' => $res['reservation_id'],
                'status' => $res['status']
            ];
        }
        $roomsGrouped[$roomId]['dates'][] = $res['date'];
    }
    ?>
    <?php foreach ($roomsGrouped as $room): 
        $images = explode(',', $room['room_images']);
        $firstImage = trim($images[0]) ?: 'default.jpg';

        sort($room['dates']);
        $totalNights = count($room['dates']);
        $totalPrice = $totalNights * $room['room_price'];
    ?>
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <img src="../assets/img/<?= htmlspecialchars($firstImage) ?>" class="card-img-top" alt="Imagen habitación <?= htmlspecialchars($room['room_name']) ?>" style="height: 150px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($room['room_name']) ?></h5>
                    <p><strong>Tipo:</strong> <?= htmlspecialchars($room['room_type']) ?></p>
                    <p><strong>Fechas reservadas:</strong><br>
                        <?php foreach ($room['dates'] as $date): ?>
                            <span class="badge bg-secondary me-1"><?= htmlspecialchars($date) ?></span>
                        <?php endforeach; ?>
                    </p>
                    <p><strong>Precio por noche:</strong> Bs. <?= number_format($room['room_price'], 2) ?><br>
                       <strong>Noches reservadas:</strong> <?= $totalNights ?><br>
                       <strong>Total:</strong> Bs. <?= number_format($totalPrice, 2) ?>
                    </p>
                    <p>
                        <button class="btn btn-danger cancel-reservation" data-id="<?= $room['reservation_id'] ?>" data-dates='<?= json_encode($room['dates']) ?>'>Cancelar Reserva</button>
                    </p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Modal para cancelar reservas -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelModalLabel">Cancelar Reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>Estas son las fechas reservadas. Selecciona las que deseas cancelar o marca "Cancelar Todas":</p>
        <form id="cancelForm">
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="cancelAllDates" />
            <label class="form-check-label" for="cancelAllDates">Cancelar todas las fechas</label>
          </div>
          <div id="datesCheckboxContainer" class="mb-3">
            <!-- Aquí irán las fechas con checkbox -->
          </div>
        </form>
        <div id="cancelError" class="alert alert-danger d-none"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" id="confirmCancel" class="btn btn-danger">Confirmar Cancelación</button>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        let currentReservationId = null;
        let currentDates = [];

        $('.cancel-reservation').on('click', function() {
            currentReservationId = $(this).data('id');
            currentDates = $(this).data('dates');

            const container = $('#datesCheckboxContainer');
            container.empty();
            currentDates.forEach(date => {
                const checkbox = $('<div class="form-check"><input class="form-check-input date-checkbox" type="checkbox" value="' + date + '" id="date-'+date+'"><label class="form-check-label" for="date-'+date+'">'+date+'</label></div>');
                container.append(checkbox);
            });

            $('#cancelAllDates').prop('checked', false);

            $('.date-checkbox').prop('disabled', false).prop('checked', false);
            $('#cancelError').addClass('d-none').text('');

            const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
            modal.show();
        });

        $('#cancelAllDates').on('change', function() {
            const checked = $(this).prop('checked');
            $('.date-checkbox').prop('disabled', checked);
            if(checked) {
                $('.date-checkbox').prop('checked', false);
            }
        });

        $('#confirmCancel').on('click', function() {
            let datesToCancel = [];

            if ($('#cancelAllDates').prop('checked')) {
                datesToCancel = currentDates;
            } else {
                $('.date-checkbox:checked').each(function(){
                    datesToCancel.push($(this).val());
                });
            }

            if (datesToCancel.length === 0) {
                $('#cancelError').removeClass('d-none').text('Selecciona al menos una fecha para cancelar.');
                return;
            }

            $.ajax({
                url: '../user/cancel_reservation.php',
                type: 'POST',
                data: {
                    id: currentReservationId,
                    dates: datesToCancel
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Reserva cancelada con éxito.');
                        location.reload();
                    } else {
                        $('#cancelError').removeClass('d-none').text(response.message);
                    }
                },
                error: function() {
                    $('#cancelError').removeClass('d-none').text('Error en la solicitud.');
                }
            });
        });
    });
</script>
