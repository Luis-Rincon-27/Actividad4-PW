<?php
require_once 'usuarios.php';

// Comprobar si la sesión ya ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Autenticar al usuario
    $usuario_id = authenticate($_POST['nombre_usuario'], $_POST['clave']);

    if ($usuario_id !== false) {
        // Almacenar el ID del usuario en la sesión
        $_SESSION['usuario_id'] = $usuario_id;
        // Redirigir al usuario a la página de llenados
        header('Location: index.php');
        exit();
    } else {
        // Manejar el error de autenticación
        $error = "Nombre de usuario o clave incorrectos <br> Vuelva a intentar";
    }
}

header("X-Content-Type-Options: nosniff");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

         <!--Import Google Icon Font-->
         <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
    <nav>
        <div class="nav-wrapper #80cbc4 #004d40 teal darken-4">
            <a href="login.php" class="brand-logo left">Embotelladora de agua Thompsom</a>
        </div>
    </nav>
<br><br><br><br>
        <div class="container marginbottom #e0e0e0 grey lighten-2 col s12" style="border-radius: 15px;">
            <div> <p>‎ </p></div>
            <div class='row center-align'>
                <?php if (isset($error)): ?>
                    <p><?php echo $error; ?></p>
                <?php endif; ?>
            </div>

            <form method="post" action="login.php">
            <h2>Login</h2>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="nombre_usuario" type="text" name="nombre_usuario" class="validate">
                        <label for="nombre_usuario">Nombre de usuario</label><br>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <input id="clave" type="password" name="clave" class="validate">
                        <label for="clave">Clave</label><br>
                    </div>
                </div>

                <div class='row center-align'>
                    <button class="waves-effect waves-light btn-small #80cbc4 #004d40 teal darken-4" value="Iniciar sesión" type="submit">Iniciar Sesión</button>
                </div>
            </form>
            <div> <p>‎ </p></div>
        </div>
<br><br><br><br><br><br>
     <!--JavaScript at end of body for optimized loading-->
     <script type="text/javascript" src="js/materialize.min.js"></script>
	
</body>
<footer class="page-footer #004d40 teal darken-4">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">Footer Content</h5>
                <p class="grey-text text-lighten-4">Luis Rincon Page 4°.</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Links</h5>
                <ul>
                  <li><a class="grey-text text-lighten-3" href="#!">Link del directorio Principal</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2088 Copyright Text
            <a class="grey-text text-lighten-4 right" href="#!">hello~</a>
            </div>
          </div>
        </footer>
</html>