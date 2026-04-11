<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PleniCare - Sobre Nosotros</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap-5.3.8-dist/css/bootstrap.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../css/styleHeaderFooter.css">
        <link rel="stylesheet" href="../css/styleSobreNosotros.css">
        <link rel="icon" href="../media/simbolo.png">
    </head>
    <body>
        <!----------------------------------------------------------------------------------------  HEADER   -->
        <?php
            if($_SESSION["tipo"]=="paciente"){
                include_once("../components/headerPaciente.php");
            }else if($_SESSION["tipo"]=="medico"){
                include_once("../components/headerMedico.php");
            }else {
                include_once("../components/header.php");
            }
        ?>
        <!----------------------------------------------------------------------------------------  MAIN     -->
        <main class="container my-5">
            <div class="card shadow-lg border-0 p-4" style="background-color:#0C5678; color:white;">
                <section id="quienesSomos" class="py-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <h2 class="text-center mb-4">Quiénes Somos</h2>
                            <p>
                                En <strong>PleniCare</strong> creemos que la salud debe cuidarse con cercanía,
                                profesionalidad y compromiso. Nuestro objetivo es ofrecer una atención médica
                                privada que combine la experiencia de profesionales cualificados con un trato
                                humano y cercano hacia cada paciente.
                            </p>
                            <p>
                                Nacimos con la idea de facilitar el acceso a la atención sanitaria, eliminando
                                largas esperas y ofreciendo un entorno donde cada persona pueda sentirse
                                escuchada y acompañada. Para nosotros, cada paciente es único y merece una
                                atención personalizada que se adapte a sus necesidades.
                            </p>
                        </div>
                    </div>
                </section>

                <hr style="border-color:#3CCFCF; opacity:1; border-width:3px;">

                <section id="nuestraLabor" class="py-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <h2 class="text-center mb-4">Nuestra Labor</h2>
                            <p>
                                En PleniCare trabajamos para ofrecer un servicio médico completo basado en la
                                prevención, el diagnóstico y el seguimiento continuo de nuestros pacientes.
                                Nuestro equipo médico colabora de manera coordinada para garantizar una
                                atención eficaz, adaptada a cada situación y respaldada por protocolos
                                actualizados y tecnología moderna.
                            </p>
                            <p>
                                Más allá de la atención médica, nuestra labor también consiste en orientar,
                                informar y acompañar a las personas en cada etapa de su cuidado sanitario.
                                Queremos que cada paciente tenga la tranquilidad de contar con profesionales
                                que se preocupan realmente por su bienestar.
                            </p>
                        </div>
                    </div>
                </section>

                <hr style="border-color:#3CCFCF; opacity:1; border-width:3px;">

                <section id="contacto" class="py-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <h2 class="text-center mb-4">Contacto</h2>

                            <form>

                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Número de teléfono</label>
                                    <input type="tel" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mensaje</label>
                                    <textarea class="form-control" rows="5"></textarea>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn-enviar" style="background:#3CCFCF; color:#0C5678; font-weight:600;">
                                        Enviar mensaje
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        <!----------------------------------------------------------------------------------------  FOOTER    -->
        <?php @include_once("../components/footer.php"); ?>
        <!---------------------------------------------------------------------------------------- JAVASCRIPT -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    </body>
</html>