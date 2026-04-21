(() => {
    'use strict'

    const forms = document.querySelectorAll('.needs-validation')

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }
        form.classList.add('was-validated')
        }, false)
    })
})()

let modalSolicitar = document.getElementById('modalSolicitar');
let boton = document.getElementById('btnMSolicitar');

modalSolicitar.addEventListener('shown.bs.modal', () => {
    boton.focus()
})

const cardCancelar = document.querySelectorAll('.icono');
const modalCancelar = document.getElementById("modalCancelar");
cardCancelar.forEach(function(card){
    card.addEventListener("click", ()=>{
        const modal = new bootstrap.Modal(modalCancelar);
        const inFecha = document.getElementById("cancelarFecha");
        const inHora = document.getElementById("cancelarHora");

        let fecha = card.dataset.fecha;
        let hora = card.dataset.hora;
        inFecha.value = fecha;
        inHora.value = hora;

        if(card.dataset.rol == "paciente"){
            const inMedico = document.getElementById("cancelarMedico");
            let medico = card.dataset.nombre;
            let idMedico = card.dataset.medico;
            inMedico.value = idMedico;
            document.getElementById("infoCita").innerHTML = `<h6>${medico}<hr>${fecha} a las ${hora}</h6>`;
        }else {
            const inPaciente = document.getElementById("cancelarPaciente");
            let paciente = card.dataset.nombre;
            let idPaciente = card.dataset.paciente;
            inPaciente.value = idMedico;
            document.getElementById("infoCita").innerHTML = `<h6>${paciente}<hr>${fecha} a las ${hora}</h6>`;
        }
        modal.show();
    });
});