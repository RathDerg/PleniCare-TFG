<?php
    session_start();
    include_once("../php/conexionBBDD.php");

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $dni = $_POST["dni"];
        $password = $_POST["contrasenya"];

        try {
            $conexion = new PDO($url, $user, $passBD);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = $conexion->prepare("SELECT * FROM medico WHERE dni = :dni");
            $sql->bindParam(":dni", $dni);
            $sql->execute();

            if($sql->rowCount() > 0){

                $user = $sql->fetch(PDO::FETCH_ASSOC);

                if(password_verify($password, $user["contrasenya"])){
                    $_SESSION["login"] = true;
                    $_SESSION["user"] = $user["id_medico"];
                    $_SESSION["name"] = $user["nombre"];
                    $_SESSION["tipo"] = "medico";

                    header("Location:./landingPage_Medico.php");
                    exit();
                }
            }

            $sql = $conexion->prepare("SELECT * FROM paciente WHERE dni = :dni");
            $sql->bindParam(":dni", $dni);
            $sql->execute();

            if($sql->rowCount() > 0){
                $user = $sql->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password, $user["contrasenya"])){
                    $_SESSION["login"] = true;
                    $_SESSION["user"] = $user["id_paciente"];
                    $_SESSION["name"] = $user["nombre"];
                    $_SESSION["tipo"] = "paciente";

                    header("Location:./landingPage_Paciente.php");
                    exit();
                }
            }

            $_SESSION["error_login"] = "DNI o contraseña incorrectos";
            header("Location:./login.php");
            exit();

        } catch (PDOException $e) {
            echo "Error: ".$e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inciar Sesión</title>
    <link rel="stylesheet" href="../css/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styleHeaderFooter.css">
    <link rel="stylesheet" href="../css/styleLogin.css">
    <link rel="icon" href="../media/simbolo.png">
</head>
<body>
    <!----------------------------------------------------------------------------------------  HEADER  -->
    <?php
        include_once("../components/header.php")
    ?>
    <!----------------------------------------------------------------------------------------  MAIN    -->
    <main class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4 login-card">
            <div class="text-center mb-4">
                <img src="../media/simbolo.png" alt="PleniCare" style="width: 60px;">
                <h3 class="mt-3">Iniciar Sesión</h3>
                <p class="text-muted">Accede a tu área privada</p>
            </div>

            <form class="row g-3 needs-validation" action="./login.php" method="POST" novalidate>
                <?php
                    if(isset($_SESSION["error_login"])){
                        echo "<div class='alert alert-danger text-center'>".$_SESSION["error_login"]."</div>";
                        unset($_SESSION["error_login"]);
                    }
                ?>
                <div class="col-12">
                    <label for="dni" class="form-label">DNI</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="dni" name="dni" placeholder="12345678A" required>
                        <div class="invalid-feedback">
                            Introduce un DNI válido.
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <label for="contrasenya" class="form-label">Contraseña</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="contrasenya" name="contrasenya" required>
                        <div class="invalid-feedback">
                            Introduce tu contraseña.
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Iniciar sesión</button>
                </div>
            </form>
        </div>
    </main>
    <!---------------------------------------------------------------------------------------- JAVASCRIPT -->
    <script>
        (() => {
            'use strict';

            const forms = document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>