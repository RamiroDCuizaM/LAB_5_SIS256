// Funciones para manejar AJAX y otros eventos
document.addEventListener('DOMContentLoaded', function() {
    
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    
    if (typeof $.fn.datepicker !== 'undefined') {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    }
    
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.classList.add('fade');
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        });
    }, 5000);

    
    $('#payButton').click(function() {
        const selectedDates = $('#datepicker').val();
        const totalPrice = $('#totalPrice').text();
        
        
        alert('Total a pagar: Bs. ' + totalPrice);
        
    });

    
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
