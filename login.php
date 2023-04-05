<?php 
    require 'includes/config/database.php';
    $db = conectarDB();

    $errores = [];

    //Autenticar usuario
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";

        $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) );
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if(!$email){
            $errores[] = "Email obligatorio o no es valido";
        }

        if(!$password){
            $errores[] = "Password Obligatorio";
        }

        if(empty($errores) ) {
            //revisar si usuario existe
            $query = "SELECT * FROM usuarios where email = '$email'";
            $resultado = mysqli_query($db, $query);

           

            if( $resultado->num_rows ) {
                //revisar si password es correcto
                $usuario = mysqli_fetch_assoc($resultado);

                // var_dump($usuario['password']);

                //verificar si el password es crrecto
                $auth = password_verify($password, $usuario['password']); //pasword escrita, password de bdd

                if($auth){
                    //usuario autenticado
                    session_start();

                    //Llenar arreglo de sesion
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;

                    header('Location: admin/');

                  
                } else {
                    $errores[] = 'El password es incorrecto';
                }

            } else {
                $errores[] = "usuario no existe";
            }
        }
        
    }





    //incluye el header
    require 'includes/funciones.php';
    incluirTemplate('header');
?>


    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesión</h1>

        <?php foreach($errores as $error ): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario" method="post">
            <fieldset>
                <legend>Email y Password</legend>

                <label for="email">E-mail</label>
                <input type="email" name="email" placeholder="Tu Email" id="email" >

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Tu Password" id="password" >
            </fieldset><br>

            <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </form>

    </main><br>

    <?php 
       incluirTemplate('footer');
    ?>