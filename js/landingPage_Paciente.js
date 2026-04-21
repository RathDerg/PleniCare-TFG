document.addEventListener("click", function(e){
    if(e.target.classList.contains("bi")){
        console.log("Entra aqui.");
        const icono = e.target;

        const modal = new bootstrap.Modal(document.getElementById("modalHistorialInfo"));

        document.getElementById("modalFecha").innerText = icono.dataset.fecha;
        document.getElementById("modalTitulo").innerText = icono.dataset.titulo;
        document.getElementById("modalMedico").innerText = icono.dataset.medico;
        document.getElementById("modalEspecialidad").innerText = icono.dataset.especialidad;
        document.getElementById("modalDescripcion").innerText = icono.dataset.descripcion;
        document.getElementById("modalMedicamento").innerText = icono.dataset.medicamento || "Sin medicación";

        modal.show();
    }
});