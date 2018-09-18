<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../estilaso.css">
  <title id="titulo">Gestión de usuarios</title>
</head>
<body>
<?php
session_start();
require_once('dbcredenciales.php');

//Conexión a la BBDD
$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWD,DB_DATABASE);
if (!$conn) {
  echo "Ocurrió un error.\n";
  exit;
}

mysqli_set_charset($conn,"utf8");


  if( isset($_POST["logout"]) ){

    // La sesión debe estar iniciada
    if (session_status()==PHP_SESSION_NONE)
    session_start();
    // Borrar variables de sesión
    //$_SESSION = array();
    session_unset();
    // Obtener parámetros de cookie de sesión
    $param = session_get_cookie_params();
    // Borrar cookie de sesión
    setcookie(session_name(), $_COOKIE[session_name()], time()-2592000,
    $param['path'], $param['domain'], $param['secure'], $param['httponly']);
    // Destruir sesión
    session_destroy();

  }

  //Modificación en BBDD
  if( isset($_POST["modificar1"]) ){

    $nombre = $_POST["nombreu"];
    $nombrea = $_POST["nombreu"];
    $consulta = sprintf("SELECT * FROM usuarios WHERE nombre='%s' ",
                    mysqli_real_escape_string($conn,$nombre) );
    $result = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_row($result);

      if( !empty($_POST["nombre"]) ){
        $nombre = $_POST["nombre"];
      }

      if( empty($_POST["apellido"]) ){
        $apellido = $row[1];
      }else $apellido = $_POST["apellido"];

      if( empty($_POST["email"]) ){
        $email = $row[2];
      }else $email = $_POST["email"];

      if( empty($_POST["contraseña"]) ){
        $contraseña = $row[3];
      }else $contraseña = $_POST["contraseña"];

      if( empty($_POST["tipo"]) ){
        $tipo = $row[4];
      }else $tipo = $_POST["tipo"];

      //Actualizar el usuario

      $consulta  = sprintf("UPDATE usuarios SET nombre='%s', apellidos='%s', email='%s', pwd='%s', tipo='%s' WHERE nombre='%s' ",
                      mysqli_real_escape_string($conn,$nombre),
                      mysqli_real_escape_string($conn,$apellido),
                      mysqli_real_escape_string($conn,$email),
                      password_hash($contraseña, PASSWORD_DEFAULT),
                      mysqli_real_escape_string($conn,$tipo),
                      mysqli_real_escape_string($conn,$nombrea) );
      $resultado = mysqli_query($conn, $consulta);

  }

  //Borrado en BBDD
  if( isset($_POST["borrar1"]) ){

    $nombre = $_POST["nombre"];

    // Borrar el usuario indicado
    $consulta  = sprintf("DELETE FROM usuarios WHERE nombre='%s'",mysqli_real_escape_string($conn,$nombre));

    $resultado = mysqli_query($conn, $consulta);

  }

  //Creación en BBDD
  if( isset($_POST["crear1"]) ){

    mysqli_set_charset($conn,"utf8");

    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];
    $tipo = $_POST["tipo"];

    // Realizar inserción del nuevo usuario
    $consulta  = sprintf("INSERT INTO usuarios(nombre,apellidos,email,pwd,tipo) VALUES('%s','%s','%s','%s','%s');",
                    mysqli_real_escape_string($conn,$nombre),
                    mysqli_real_escape_string($conn,$apellido),
                    mysqli_real_escape_string($conn,$email),
                    password_hash($contraseña, PASSWORD_DEFAULT),
                    mysqli_real_escape_string($conn,$tipo));
    $resultado = mysqli_query($conn, $consulta);

  }

  if( isset($_POST["modifu"]) ){

    $result = mysqli_query($conn,"SELECT nombre FROM usuarios");

    echo "<h2>Modificando usuario:</h2>
    <form action='usuarios.php' method='post'>
    <p>Seleccione el usuario a modificar</p>

    <select name='nombreu'>";
      while ($row = mysqli_fetch_row($result)){
          echo "<option>{$row[0]}</option> \n";
        }
    echo" </select><br>

    <p>Introduza los valores que desea modificar:</p><br>
    <label>Nombre:</label>
    <input type='text' name='nombre'> <br><br>
    <label>Apellidos:</label>
    <input type='text' name='apellido'> <br><br>
    <label>E-mail:</label>
    <input type='text' name='email'> <br><br>
    <label>Contraseña:</label>
    <input type='password' name='contraseña'> <br><br>
    <label>Tipo:</label>
    <select name='tipo'>
      <option>admin</option>
      <option>usuario</option>
    </select><br><br>
    <input type='submit' name='modificar1' value='Modificar usuario'><br>
    </form>
    ";

  } else if(  isset($_POST["borraru"]) ){

    $result = mysqli_query($conn,"SELECT nombre FROM usuarios");

    echo "Introduza el nombre del usuario a borrar:<br><br>
    <form action='usuarios.php' method='post'>
    <label>Nombre:</label>
    <select name='nombre'>";
      while ($row = mysqli_fetch_row($result)){
          echo "<option>{$row[0]}</option> \n";
        }
    echo" </select><br><br>
    <input type='submit' name='borrar1' value='Borrar usuario'><br>
    </form>";

  } else if( isset($_POST["crearu"]) ){

      echo "Introduza los valores para el nuevo usuario:
      <form action='usuarios.php' method='post'>
      <label>Nombre:</label>
      <input type='text' name='nombre'> <br><br>
      <label>Apellidos:</label>
      <input type='text' name='apellido'> <br><br>
      <label>E-mail:</label>
      <input type='text' name='email'> <br><br>
      <label>Contraseña:</label>
      <input type='password' name='contraseña'> <br><br>
      <label>Tipo:</label>
      <select name='tipo'>
        <option>admin</option>
        <option>usuario</option>
      </select><br><br>
      <input type='submit' name='crear1' value='Crear usuario'><br>
      </form>
      ";

  } else if( (!empty($_POST['usuario']) && !empty($_POST['pwd'])) || isset($_POST["crear1"]) || isset($_POST["borrar1"]) || isset($_POST["modificar1"]) ){

    if(!empty($_POST['usuario']) && !empty($_POST['pwd'])){
      $_SESSION["nombre"] = $_POST['usuario'];
      $_SESSION["contraseña"] = $_POST['pwd'];

      $nombre_usuario = $_POST['usuario'];
      $contraseña = $_POST['pwd'];
    }else {

      $nombre_usuario = $_SESSION["nombre"];
      $contraseña = $_SESSION["contraseña"];
    }



    // Consultar si el usuario envió la contraseña correcta
    $consulta = sprintf("SELECT pwd FROM usuarios WHERE nombre='%s';",
                    mysqli_real_escape_string($conn,$nombre_usuario));
    $fila = mysqli_fetch_assoc(mysqli_query($conn, $consulta));

    if ($fila && password_verify($contraseña, $fila['pwd'])) { // Si la autenticación es correcta
        echo 'Bienvenido, <b>' . htmlspecialchars($nombre_usuario) . '</b>!';

          //Consultamos si el usuario es admin
          $consulta = sprintf("SELECT nombre FROM usuarios WHERE tipo='admin' AND nombre= '%s' ;",
                          mysqli_real_escape_string($conn,$nombre_usuario) );
          $mq = mysqli_query($conn,$consulta);
          if( mysqli_affected_rows($conn) !== 0 ){ // Si el usuarios se ha encontrado muestro opciones Admin
            echo "<p>Como administrador puede realizar gestiones en la lista de usuarios:</p>";
            //Mostramos los usuarios
            $result = mysqli_query($conn,"SELECT * FROM usuarios");
            echo "<table border = '1'> \n";
            echo "<tr><td>Nombre</td><td>Apellidos</td><td>E-Mail</td><td>Tipo de usuario</td></tr> \n";
            while ($row = mysqli_fetch_row($result)){
                echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[4]</td></tr> \n";
              }
            //Opciones para modificar, crear o eliminar usuarios
            echo "
            <form action='usuarios.php' method='post'>
            <input type='submit' name='crearu' value='Crear usuario'><br><br>
            <input type='submit' name='borraru' value='Borrar usuario'><br><br>
            <input type='submit' name='modifu' value='Modificar usuario'><br><br>
            <input type='submit' name='logout' value='Logout'><br><br>
            </form>
            ";

            echo "</table> \n";

          }else{ //En caso contrario muestro las opciones de usuario normal
            echo "
            <form action='usuarios.php' method='post'>
            <p>El usuario normal no tiene permisos sobre la base de datos, puede hacer logout:</p>
            <input type='submit' name='logout' value='Logout'>
            </form>
            ";
          }

      } else // Si la autenticación ha fallado
            echo 'La autenticación ha fallado para ' . htmlspecialchars($nombre_usuario) . '.';


  } else if( (!empty($_POST['usuario']) && empty($_POST['pwd'])) || (empty($_POST['usuario']) && !empty($_POST['pwd']))  ){

    if (empty($_POST['usuario']))
    $hayerror['usuario'] = '<p style="color:red;">No ha indicado ningún nombre</p>';
    else $hayerror['usuario'] = '';

    if (empty($_POST['pwd']))
    $hayerror['pwd'] = '<p style="color:red;">No ha indicado ninguna contraseña</p>';
    else $hayerror['pwd'] = '';

    echo "
    <p>Introduzca sus credenciales:</p>
    <form action='usuarios.php' method='post'>
    <label>Usuario</label>
    <input type='text' name='usuario' ";
    if( isset($_POST['usuario']) ) echo "value='{$_POST['usuario']}' />";
    echo "<br> {$hayerror['usuario']}
    <label>Clave</label>
    <input type='password' name='pwd' ";
    if( isset($_POST['pwd']) ) echo "value='{$_POST['pwd']}' />";
    echo "<br> {$hayerror['pwd']}
    <input type='submit' name='login' value='Login'>
    </form> ";

  } else if( empty($_POST['usuario']) && empty($_POST['pwd']) ){

    echo "
    <p>Introduzca sus credenciales:</p>
    <form action='usuarios.php' method='post'>
    <label>Usuario</label>
    <input type='text' name='usuario'> <br><br>
    <label>Clave</label>
    <input type='password' name='pwd'> <br><br>
    <input type='submit' name='login' value='Login'>
    </form>
    <p><b>Credenciales del profesor:</b><br><br>
    Usuario: Profesor<br>
    Contraseña: twprofesor<br><br>
    <b>Credenciales de usuario no administrador:</b><br><br>
    Usuarios: noadmin<br>
    Contraseña: noadmin</p>
    ";

  }

?>
</body>
</html>
