<?php

require_once 'clientes.php';
require_once 'llenados.php';

// Comprobar si la sesión ya ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header('Location: login.php');
    exit();
}

// Todas las zonas para la combobox
$zonas = get_zonas();

// Establecer tiempo de inactividad en minutos
$inactividad = 4;

// Comprobar si $_SESSION['tiempo'] está establecido
if(isset($_SESSION['tiempo']) ) {
    // Calcular el tiempo de inactividad
    $vida_session = time() - $_SESSION['tiempo'];
    
    if($vida_session > $inactividad*60) {
        // Si ha pasado más tiempo del establecido en $inactividad, destruir la sesión
        session_destroy();
        
        // Redirigir al usuario a la página de inicio de sesión
        header("Location: login.php");
    }
}

// Asignar la hora actual a $_SESSION['tiempo']
$_SESSION['tiempo'] = time();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio4
    </title>

         <!--Import Google Icon Font-->
         <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      
      <script>
	    $(window).on('beforeunload', function(){
	      $.ajax({
	        type: 'POST',
	        async: false,
	        url: 'logout.php'
	      });
	    });
      </script>

</head>
<nav>
        <div class="nav-wrapper #80cbc4 #004d40 teal darken-4">
            <a href="login.php" class="brand-logo left">Embotelladora de agua Thompsom</a>
            <ul id="nav-mobile" class="left hide-on-med-and-down">
                <li><a href="sass.html"></a></li>
            </ul>
        </div>
    </nav>
<body>
    <div class="row">
        <div class="container #e0e0e0 grey lighten-2 col s12">
            <?php include_once 'clientes.php'?>
        <form method="post" id="form_crear_cliente">
            <h2>Agregar Cliente</h2>
            <label for="nombre_cliente">Nombre del cliente:</label><br>
            <input type="text" id="nombre_cliente" name="nombre_cliente" pattern="[A-Za-z]+" title="Por favor ingrese solo letras"><br>
            <label for="cedula">Cédula del cliente:</label><br>
            <input type="text" id="cedula" name="cedula" pattern="\d+" title="Por favor ingrese una cedula"><br>
            <label for="zona_id">Zona del cliente:</label><br>
        <select id="zona_id" name="zona_id" class="browser-default #78909c blue-grey lighten-1">
            <?php foreach ($zonas as $zona): ?>
                <option value="<?php echo $zona['id']; ?>"><?php echo $zona['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <input class="#78909c blue-grey lighten-1" type="submit" name="crear_cliente" value="Crear cliente">
    </form>
    <div id="mensaje_crear">
        <?php if (isset($_SESSION['mensaje_crear'])): ?>
            <p><?php echo $_SESSION['mensaje_crear']; unset($_SESSION['mensaje_crear']); ?></p>
        <?php endif; ?>
    </div>
    
    <br>

    <form method="post" id="form_llenar_botellones">
        <h2>Llenar botellones</h2>
        <!-- Aquí deberías tener una forma para que el usuario seleccione un cliente y un botellón -->
        <label for="cliente_cedula">Cédula del cliente:</label><br>
        <input type="text" id="cliente_cedula" name="cliente_cedula" pattern="\d+" title="Por favor ingrese una cedula"><br>
        <label for="cantidad">Cantidad:</label><br>
        <input type="number" id="cantidad" name="cantidad"><br>
        <input class="#78909c blue-grey lighten-1" type="submit" name="llenar_botellones" value="Llenar botellones">
    </form>
    <div id="mensaje_llenar">
        <?php if (isset($_SESSION['mensaje_llenar'])): ?>
            <p><?php echo $_SESSION['mensaje_llenar']; unset($_SESSION['mensaje_llenar']); ?></p>
        <?php endif; ?>
    </div>


    <form method="post" id="form_generar_reporte" action="DownloadPdf.php">
        <h2>Generar reporte</h2>
        <input class="#78909c blue-grey lighten-1" type="submit" name="generar_reporte" value="Generar reporte" style="display: block; margin: 0 auto;">
    </form>
    <div id="reporte">
        <?php
            if (isset($_POST['generar_reporte'])) {
                $conn = connect();
                $query = "SELECT `llenados`.`cliente_cedula`, `llenados`.`cantidad`, `zonas`.`nombre`, `llenados`.`fecha_hora`
                        FROM `llenados`
                        LEFT JOIN `clientes` ON `llenados`.`cliente_cedula` = `clientes`.`cedula`
                        LEFT JOIN `zonas` ON `clientes`.`zona_id` = `zonas`.`id`";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                json_encode($result);  // Añade esta línea
                echo "<table>";
                echo "<tr><th>Cliente Cédula</th><th>Cantidad</th><th>Nombre de Zona</th><th>Fecha y Hora</th></tr>";
                foreach ($result as $row) {
                    echo "<tr><td>{$row['cliente_cedula']}</td><td>{$row['cantidad']}</td><td>{$row['nombre']}</td><td>{$row['fecha_hora']}</td></tr>";
                }
                echo "</table>";
            }
        ?>
    </div>
    </div>
    </div>
    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="js/materialize.min.js"></script>
	
    <br><br>
    <!-- En tu archivo index.php -->

    <script>
        $(document).ready(function(){
            $("#form_buscar_clientes").on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'buscar_clientes.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        $("#mensaje_buscar").html(data);
                    }
                });
            });

            $("#form_crear_cliente").on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'crear_cliente.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        $("#mensaje_crear").html(data);
                    }
                });
            });

            $("#form_crear_cliente").on('submit', function(e) {
                var cedula = $("#cedula_cliente").val();
                if (cedula.length < 7 || cedula.length > 8) {
                    alert("La cédula debe tener 7 u 8 caracteres.");
                    e.preventDefault();
                }
            });

            $("#form_llenar_botellones").on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'llenar_botellones.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        console.log(data);  // Añade esta línea
                        $("#mensaje_llenar").html(data);
                    }
                });
            });
    });
    </script>
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
</body>
</html>