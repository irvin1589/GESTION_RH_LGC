document.addEventListener('DOMContentLoaded', function () {
    const botones = document.querySelectorAll('.btn-responder');
    botones.forEach(btn => {
        btn.addEventListener('click', function () {
            const idAsignacion = this.getAttribute('data-id');
            window.location.href = 'RESPONDER_FORMULARIO.php?id_asignacion=' + idAsignacion;
        });
    });
});