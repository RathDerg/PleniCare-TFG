document.getElementById("especialidad").addEventListener("change", function(){
    let especialidad = this.value;
    let fecha = document.querySelector("input[name='fecha']").value;
    let hora = document.querySelector("input[name='hora']").value;

    fetch("../php/buscarMedico.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "especialidad=" + encodeURIComponent(especialidad) +
                "&fecha=" + encodeURIComponent(fecha) +
                "&hora=" + encodeURIComponent(hora)
    })
    .then(res => res.json())
    .then(data => {
        const select = document.getElementById("medico");

        if(data.length === 0){
            select.innerHTML = "<option disabled>No hay médicos disponibles</option>";
            return;
        }

        select.innerHTML = "<option disabled selected>Selecciona médico</option>";

        data.forEach(medico => {
            select.innerHTML += `<option value="${medico.id}">${medico.nombre}</option>`;
        });

    });
});