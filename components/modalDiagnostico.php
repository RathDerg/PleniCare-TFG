<section class="modal fade" id="modalHistorial">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="../php/guardarHistorial.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5>Nuevo Historial Médico</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <!------------------------------------------------------------------ MODO VISUAL -->
                                <div id="pacienteMostrado" style="display:none;">
                                    <label>Paciente</label>
                                    <input type="text" id="pacienteInput" class="form-control mb-3" readonly>
                                </div>
                                <!---------------------------------------------------------------- MODO EDITABLE -->
                                <div id="pacienteEditable" style="display:none;">
                                    <label>Paciente (DNI o SIP)</label>
                                    <input type="text" id="buscarPaciente" class="form-control mb-3">
                                    <div id="resultadosBusqueda"></div>
                                </div>
                                <input type="hidden" name="id_paciente" id="idPaciente">

                                <label>Título</label>
                                <input type="text" name="titulo" class="form-control mb-3" required>

                                <label>Descripción</label>
                                <textarea name="descripcion" class="form-control mb-3" required></textarea>

                                <label>Medicamentos</label>
                                <select name="medicamentos[]" class="form-select mb-3" multiple>
                                    <?php
                                        $meds = $conexion->query("SELECT codigo_nacional, nombre FROM medicamento");
                                        while($medicamento = $meds->fetch()){
                                            echo "<option value='".$medicamento["nombre"]."'>".$medicamento["codigo_nacional"].
                                                    " - ".$medicamento["nombre"].
                                                "</option>";
                                        }
                                    ?>
                                </select>
                                <label>Archivos</label>
                                <input type="file" name="archivos[]" class="form-control" multiple>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button class="btn btn-success" name="btnFormHistorial">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>