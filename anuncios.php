<?php 
    require 'includes/funciones.php';
    incluirTemplate('header');
?>


    <main class="contenedor seccion">

        <h2>Casas y Depas en Venta</h2>

        <?php 
            $limite = 3; //solo muestre 3
            include 'includes/templates/anuncios.php';
        ?>

            

        </div> <!--.contenedor-anuncios-->
    </main>

    <?php 
       incluirTemplate('footer');
    ?>