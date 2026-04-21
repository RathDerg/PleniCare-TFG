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
                        <a class="nav-link <?= linkActivo('landingPage_Medico.php') ?>" href="./landingPage_Medico.php">Mi Área</a>
                        <a class="nav-link <?= linkActivo('citas.php') ?>" href="./citas.php">Citas</a>
                        <a class="nav-link" id="btnDiagnosticar" style="cursor:pointer;">Diagnosticar</a>
                        <a class="nav-link <?= linkActivo('tablonAnuncios.php') ?>" href="#">Tablón de Anuncios</a>
                        <a class="nav-link" href="../php/cerrarSesion.php">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>