<?php 

require '../../includes/funciones.php';
$auth = estaAutenticado();

if(!$auth) {
    header('Location: ../index.php');
}

    //base de datos
    require '../../includes/config/database.php';
    $db = conectarDB();

    //consultar para obtener vendedores
    $consulta = "SELECT * from vendedores";
    $resultado = mysqli_query($db, $consulta);

    //arreglo errores
    $errores = [];

    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $estacionamiento = '';
    $vendedorId = '';

    //ejecuta luego de enviar
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        //ejemplo de sanitizar datos
        // $numero = "1HOLA1";
        // $numero2 = 1;

        // $resultado = filter_var($numero, FILTER_SANITIZE_NUMBER_INT); //hago que solo guarde los numeros int imprime 11  FILTER_SANITIZE_STRING imprime todo 1HOLA1

        // $resultado = filter_var($numero2, FILTER_VALIDATE_INT); //valida si es el tipo de dato, es true

        // var_dump($resultado);
        // exit;

        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";

        // echo "<pre>";
        // var_dump($_FILES);
        // echo "</pre>";

        $titulo = mysqli_real_escape_string( $db, $_POST['titulo']);
        $precio = mysqli_real_escape_string( $db, $_POST['precio']);
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion']);
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones']);
        $wc = mysqli_real_escape_string( $db, $_POST['wc']);
        $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento']);
        $vendedorId = mysqli_real_escape_string( $db, $_POST['vendedor']);
        $creado = date('Y/m/d');

        //asignar files hacia una variable
        $imagen = $_FILES['imagen'];
      


        if(!$titulo) {
            $errores[] = "Añade un titulo";
        }

        if(!$precio) {
            $errores[] = "Añade un precio";
        }

        if( strlen($descripcion) < 50) {
            $errores[] = "Añade una descripcion de almenos 50 caracteres";
        }

        if(!$habitaciones) {
            $errores[] = "Añade numero de habitaciones";
        }

        if(!$wc) {
            $errores[] = "Añade numero de baños";
        }

        if(!$estacionamiento) {
            $errores[] = "Añade numero de estacionemiento";
        }

        if(!$vendedorId) {
            $errores[] = "Elige un vendedor";
        }

        if(!$imagen['name'] || $imagen['error']) {
            $errores[] = "Imagen obligatoria";
        }

        // validar por tamaño (1mb maximo)
        $medida = 1000 * 1000;

        if($imagen['size'] > $medida) {
            $errores[] = "La imagen es muy pesada";
        }



        // echo "<pre>";
        // var_dump($errores);
        // echo "</pre>";

       //revisar que arreglo este vacio 
       if(empty($errores)){
            // subida de archivos

            // crear una carpeta
            $carpetaImagenes = '../../imagenes/';

            if(!is_dir( $carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            //generar nombre unico
            $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";


            //suibr la imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);


            $query = "INSERT INTO propiedades (
                                    titulo, 
                                    precio, 
                                    imagen,
                                    descripcion, 
                                    habitaciones, 
                                    wc, 
                                    estacionamiento,
                                    creado,
                                    vendedorId) 
                        VALUES (
                                    '$titulo', 
                                    '$precio',
                                    '$nombreImagen',
                                    '$descripcion',
                                    '$habitaciones',
                                    '$wc',
                                    '$estacionamiento',
                                    '$creado',
                                    '$vendedorId'
                                )";
                        $resultado = mysqli_query($db, $query);

                        if($resultado){
                            //redireccionar

                            header('Location: ../?resultado=1');
                        }
       }

        
      

    }

    incluirTemplate('header');
?>


    <main class="contenedor seccion">
        <h1>Crear</h1>

        <a href="../" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            
            <div class="alerta error">
                <?php echo $error?>
            </div>
           
        <?php endforeach; ?>

    <form class="formulario" method="POST" action="crear.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Informacion General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>

        </fieldset>

        <fieldset>
            <legend>Información Propiedad</legend>

            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">

            <label for="wc">Ba&ntilde;os:</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc; ?>">

            <label for="estacionamiento">Estacionamiento:</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">

        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>

            <select name="vendedor">
                <option value="">Selecciona</option>
                <?php while($vendedor = mysqli_fetch_assoc($resultado)): ?>
                    <option   <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?>   value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre']." ".  $vendedor['apellido']; ?></option>
                <?php endwhile; ?>
            </select>
        </fieldset><br>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form><br>

    </main>

    <?php 
       incluirTemplate('footer');
    ?>