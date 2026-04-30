document.getElementById("formBuscarPaciente").addEventListener("submit", function(e){
    e.preventDefault();

    let dato = document.getElementById("busquedaPaciente").value;

    fetch("../php/buscarPaciente.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "dato=" + encodeURIComponent(dato)
    })
    .then(res => res.json())
    .then(data => {

        if(data.error){
            document.getElementById("mensajeBusqueda").innerText = "Paciente no encontrado";
        } else {
            abrirModalConPaciente(data);
        }
    })
    .catch(err => {
        console.error("Error en fetch:", err);
    });
});


function abrirModalConPaciente(data){

    const modalElement = document.getElementById("modalHistorial");
    const modal = new bootstrap.Modal(modalElement);

    document.getElementById("pacienteMostrado").style.display = "block";
    document.getElementById("pacienteEditable").style.display = "none";

    document.getElementById("pacienteInput").value = data.nombre;
    document.getElementById("idPaciente").value = data.id;

    modal.show();
}