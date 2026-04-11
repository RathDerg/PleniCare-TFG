<?php
    function linkActivo($pagina){
        return basename($_SERVER['PHP_SELF']) == $pagina ? 'active" aria-current="page': '';
    }
?>
    <header>
        <nav class="navbar navbar-expand-lg bg-darkBlue fixed-top">
            <div class="container-fluid">
                <a href="../index.php"><img src="../media/simbolo.png" class="navbar-brand mx-4" alt="Logotipo de Plenicare"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navLinks">
                    <span class="toggler-line"></span>
                    <span class="toggler-line"></span>
                    <span class="toggler-line"></span>
                </button>
                <div class="collapse navbar-collapse" id="navLinks">
                    <div class="navbar-nav ms-auto mx-3 justify-content-end">
                        <a class="nav-link" href="../index.php">Inicio</a>
                        <a class="nav-link <?= linkActivo('sobreNosotros.php') ?>" href="./sobreNosotros.php">Sobre Nosotros</a>
                        <a class="nav-link" href="./landingPage_Paciente.php">Iniciar Sesión</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>