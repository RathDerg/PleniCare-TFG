document.getElementById("btnDiagnosticar").addEventListener("click", function(){

    const modalElement = document.getElementById("modalHistorial");
    const modal = new bootstrap.Modal(modalElement);

    document.getElementById("pacienteMostrado").style.display = "none";
    document.getElementById("pacienteEditable").style.display = "block";

    //document.getElementById("busquedaPaciente").value = "";
    document.getElementById("idPaciente").value = "";

    modal.show();
});

document.getElementById("buscarPaciente").addEventListener("input", function(){
        let paciente = this.value.trim();
        const contenedorResultados = document.getElementById("resultadosBusqueda");

        if(paciente.length < 3){
            contenedorResultados.innerHTML = "";
            return;
        }

        fetch("../php/buscarPaciente.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "dato=" + encodeURIComponent(paciente)
        })
        .then(res => res.json())
        .then(data => {
            contenedorResultados.innerHTML = "";

            if(data.error){
                contenedorResultados.innerHTML = 
                    `<div class="text-danger p-2">No encontrado</div>`;
                return;
            }

            contenedorResultados.innerHTML = `
                <div class="resultado-item p-2 border rounded mb-1"
                    data-id="${data.id}"
                    data-nombre="${data.nombre}">
                    ${data.nombre}
                </div>
            `;

            document.querySelector(".resultado-item").addEventListener("click", function(){
                document.getElementById("buscarPaciente").value = this.dataset.nombre;
                document.getElementById("idPaciente").value = this.dataset.id;
                contenedorResultados.innerHTML = "";

            });

        })
        .catch(err => {
            console.error("ERROR FETCH:", err);
        });
});