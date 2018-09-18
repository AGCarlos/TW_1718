<?php
require "./protected/backupBBDD.php";

//BBDD

  //Realiza la conexión a la BBDD
 function conexionBBDD(){

   $conn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWD,DB_DATABASE);
   if (!$conn) {
     echo "Ocurrió un error.\n";
     exit;
   }
   mysqli_set_charset($conn,"utf8");
   return $conn;
 }

 function desconexionSesion(){

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

 function comprobarTipoUsuario($nombre,$conn){
   $consulta = sprintf("SELECT tipo FROM usuariosProyecto WHERE login='%s';",
                   mysqli_real_escape_string($conn,$nombre));
   $fila = mysqli_fetch_assoc(mysqli_query($conn, $consulta));

   if ( $fila['tipo'] == "admin" ){
     $_SESSION['tipoUsuario'] ="admin";
   } else if ( $fila['tipo'] == "gestor" ){
     $_SESSION['tipoUsuario'] ="gestor";
   }

 }

 //Comprueba si los credenciales de login de usuario son correctos
 function comprobarCredenciales($nombre,$pwd,$conn){

   // Consultar si el usuario envió la contraseña correcta
   $consulta = sprintf("SELECT pwd FROM usuariosProyecto WHERE login='%s';",
                   mysqli_real_escape_string($conn,$nombre));
   $fila = mysqli_fetch_assoc(mysqli_query($conn, $consulta));

   if ($fila && password_verify($pwd, $fila['pwd'])) { // Si la autenticación es correcta
     return true;
   } else return false;
 }

 function comprobarOperacion(){
   if ( isset( $_POST['botonAñadirComponente'] ) || isset( $_POST['botonAniadirConcierto'] )
   || isset( $_POST['botonAniadirBiografia'] ) || isset( $_POST['botonAniadirDisco'] )   ){
     $_SESSION['operacion'] = "0";
   } else if ( isset( $_POST['botonBorrarComponente'] ) || isset( $_POST['botonBorrarConcierto'] )
   || isset( $_POST['botonBorrarBiografia'] ) || isset( $_POST['botonBorrarDisco'] )   ){
     $_SESSION['operacion'] = "1";
   } else if ( isset( $_POST['botonModificarComponente'] ) || isset( $_POST['botonModificarConcierto'] )
   || isset( $_POST['botonModificarBiografia'] ) || isset( $_POST['botonModificarDisco'] )   ){
     $_SESSION['operacion'] = "2";
   }
 }




  //OPERACIONES DE GESTOR
  function gestionPedidos($conn){
    echo "<div class='main'>";
    echo "<div class='gestion'>";
    if (isset($_POST['denegar'])){
     echo<<<HTML
     <h3>Denegando el pedido del pedido con ID {$_POST['pedidoID']}</h3>
     <form class="formDenegar" action="./index.php?p=0" method="post">
       <label>Introduzca el texto a enviar por e-mail</label>
       <input type="text" name="textoDescriptivo" value="Gracias por su pedido" required>
       <input type="submit" name="denegarPedido" value="Denegar pedido">
       <input type="hidden" name="pedidoID" value="{$_POST['pedidoID']}">
     </form>
   </div>
   </div>
HTML;

} else if ( isset($_POST['denegarPedido']) ){
     $consulta  = sprintf("UPDATE pedido SET estado='%s', textoDescriptivo='%s' WHERE id='{$_POST['pedidoID']}'",
                     mysqli_real_escape_string($conn,"Denegado"),
                     mysqli_real_escape_string($conn,$_POST['textoDescriptivo']));
     $resultado = mysqli_query($conn, $consulta);
     header("Location: ./index.php?p=0");
     echo "</div>
     </div>";

     //Añadir ocurrencia al log
     $fechaLog = getdate();
     $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
     $fnombre = sprintf("./protected/log.txt");
     $cad = sprintf("{$fechaLog2}: Pedido con ID {$_POST['pedidoID']} denegado por {$_SESSION['usuario']}\n");
     file_put_contents($fnombre,$cad,FILE_APPEND);

   } else if (isset($_POST['aceptar'])){
      echo<<<HTML
      <h3>Aceptando el pedido del pedido con ID {$_POST['pedidoID']}</h3>
      <form class="formAceptar" action="./index.php?p=0" method="post">
        <label>Introduzca el texto a enviar por e-mail</label>
        <input type="text" name="textoDescriptivo" value="Gracias por su pedido" required>
        <input type="submit" name="aceptarPedido" value="Aceptar pedido">
        <input type="hidden" name="pedidoID" value="{$_POST['pedidoID']}">
      </form>
    </div>
    </div>
HTML;
    } else if ( isset($_POST['aceptarPedido']) ){
      $consulta  = sprintf("UPDATE pedido SET estado='%s', textoDescriptivo='%s' WHERE id='{$_POST['pedidoID']}'",
                      mysqli_real_escape_string($conn,"Aceptado"),
                      mysqli_real_escape_string($conn,$_POST['textoDescriptivo']));
      $resultado = mysqli_query($conn, $consulta);
      header("Location: ./index.php?p=0");
      echo "</div>
      </div>";

      //Añadir ocurrencia al log
      $fechaLog = getdate();
      $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
      $fnombre = sprintf("./protected/log.txt");
      $cad = sprintf("{$fechaLog2}: Pedido con ID {$_POST['pedidoID']} aceptado por {$_SESSION['usuario']}\n");
      file_put_contents($fnombre,$cad,FILE_APPEND);

    } else {
    //Consultar los pedidos e histórico en la BBDD
    $result = mysqli_query($conn,"SELECT * FROM pedido");
    if (mysqli_num_rows($result) > 0){

      echo<<<HTML

      <h2>Gestión de pedidos</h2>
      <h3>Bienvenido {$_SESSION['usuario']}, comprueba los pedidos:</h3>
      <h3>Pedidos pendientes:</h3>
      <table id="tableGest">
        <tr>
          <th>N.º</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th>Texto</th>
          <th>Usuario</th>
          <th>Disco</th>
          <th>Precio</th>
          <th>Acciones</th>
        </tr>
HTML;

    while ($row = mysqli_fetch_row($result)){
        echo<<<HTML
        <tr>
          <td>{$row[4]}</td>
          <td>{$row[0]}</td>
          <td>{$row[1]}</td>
          <td>{$row[2]}</td>
          <td>{$row[3]}</td>
          <td>{$row[5]}</td>
          <td>{$row[6]}€</td>
HTML;
  if ( $row[1] == "Pendiente" )
          echo<<<HTML
          <td>
            <form class="denegarAceptar" action="./index.php?p=0" method="post">
              <input id="aceptar" type="submit" name="aceptar" value="Aceptar">
              <input id="denegar" type="submit" name="denegar" value="Denegar">
              <input type="hidden" name="pedidoID" value="{$row[4]}">
          </form>
          </td>
HTML;

          echo<<<HTML
        </tr>
HTML;
      }

        echo "</table>
        <form class='formSalir' action='./index.php?p=0' method='post'>
          <input type='submit' name='salirGestion' value='Salir de gestión'>
        </form>";
        echo "</div>";
        echo "</div>";
      } else{
        echo<<<HTML
        <h3>Pedidos pendientes:</h3>
        <h3>No hay pedidos pendientes</h3>
        <form class="formSalir" action="./index.php?p=0" method="post">
          <input type='submit' name='salirGestion' value='Salir de gestión'>
        </form>
        </div>
        </div>
HTML;
      }
    }
  }

  // OPERACIONES DE ADMINISTRADOR
 //Gestión de usuarios de administrador
 function gestionUsuarios($conn){
   echo "<div class='main'>";
   echo "<div class='gestion'>";
   //Modificación en BBDD
   if( isset($_POST["modificar1"]) ){

     $nombre = $_POST["nombreu"];
     $nombrea = $_POST["nombreu"];
     $consulta = sprintf("SELECT * FROM usuariosProyecto WHERE nombre='%s' ",
                     mysqli_real_escape_string($conn,$nombre) );
     $result = mysqli_query($conn,$consulta);
     $row = mysqli_fetch_row($result);

       if( !empty($_POST["nombre"]) ){
         $nombre = $_POST["nombre"];
       }

       if( empty($_POST["apellido"]) ){
         $apellido = $row[1];
       }else $apellido = $_POST["apellido"];

       if( empty($_POST["telefono"]) ){
         $telefono = $row[2];
       }else $telefono = $_POST["telefono"];

       if( empty($_POST["email"]) ){
         $email = $row[3];
       }else $email = $_POST["email"];

       if( empty($_POST["contraseña"]) ){
         $contraseña = $row[4];
       }else $contraseña = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);

       if( empty($_POST["login"]) ){
         $login = $row[5];
       }else $login = $_POST["login"];

       if( empty($_POST["tipo"]) ){
         $tipo = $row[6];
       }else $tipo = $_POST["tipo"];

       //Actualizar el usuario

       $consulta  = sprintf("UPDATE usuariosProyecto SET nombre='%s', apellidos='%s', telefono='%s', email='%s', pwd='%s', login='%s', tipo='%s' WHERE nombre='%s' ",
                       mysqli_real_escape_string($conn,$nombre),
                       mysqli_real_escape_string($conn,$apellido),
                       mysqli_real_escape_string($conn,$telefono),
                       mysqli_real_escape_string($conn,$email),
                       mysqli_real_escape_string($conn,$contraseña),
                       mysqli_real_escape_string($conn,$login),
                       mysqli_real_escape_string($conn,$tipo),
                       mysqli_real_escape_string($conn,$nombrea) );
       $resultado = mysqli_query($conn, $consulta);

       //Añadir ocurrencia al log
       $fechaLog = getdate();
       $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
       $fnombre = sprintf("./protected/log.txt");
       $cad = sprintf("{$fechaLog2}: Usuario con nombre {$nombre} modificado por {$_SESSION['usuario']} \n");
       file_put_contents($fnombre,$cad,FILE_APPEND);

   }

   //Borrado en BBDD
   if( isset($_POST["borrar1"]) ){

     $nombre = $_POST["nombre"];

     // Borrar el usuario indicado
     $consulta  = sprintf("DELETE FROM usuariosProyecto WHERE login='%s'",mysqli_real_escape_string($conn,$nombre));

     $resultado = mysqli_query($conn, $consulta);

     if ( $nombre == $_SESSION['usuario'] ){
       $_SESSION['logged']="no";
       $_SESSION['gestionActiva']="no";
       $_SESSION['tipoUsuario'] ="no";
       header("Location: ./index.php?p=0");
     }

     //Añadir ocurrencia al log
     $fechaLog = getdate();
     $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
     $fnombre = sprintf("./protected/log.txt");
     $cad = sprintf("{$fechaLog2}: Usuario con nombre {$nombre} borrado por {$_SESSION['usuario']} \n");
     file_put_contents($fnombre,$cad,FILE_APPEND);

   }

   //Creación en BBDD
   if( isset($_POST["crear1"]) ){

     $nombre = $_POST["nombre"];
     $apellido = $_POST["apellido"];
     $telefono = $_POST["telefono"];
     $email = $_POST["email"];
     $contraseña = $_POST["contraseña"];
     $login = $_POST["login"];
     $tipo = $_POST["tipo"];

     //Comprobar si hay un usuario con ese nombre
     $consulta  = sprintf("SELECT nombre from usuariosProyecto WHERE login='{$login}'");
     $resultado = mysqli_query($conn, $consulta);

     if (mysqli_num_rows($resultado)>0) {

       echo "<h2>Creando usuario:</h2>
       <h3>Introduza los valores para el nuevo usuario:</h3>
       <form id='formUsuario' action='index.php?p=0' method='post' required>
       <label>Nombre:</label>
       <input type='text' name='nombre' value='{$nombre}' required>
       <label>Apellidos:</label>
       <input type='text' name='apellido' value='{$apellido}' required>
       <label>Teléfono:</label>
       <input type='number' name='telefono' value='{$telefono}' required>
       <label>E-mail:</label>
       <input type='text' name='email' value='{$email}' required>
       <label>Contraseña:</label>
       <input type='password' name='contraseña' value='{$contraseña}' required>
       <label>Login:</label>
       <input type='text' name='login' required>
       <h3>Ya hay un usuario con ese login, debe utilizar otro</h3>
       <label>Tipo:</label>
       <select name='tipo' required>
         <option>admin</option>
         <option>gestor</option>
       </select>
       <input type='submit' name='crear1' value='Crear usuario'>
       </form>
       <form id='formUsuario' action='index.php?p=0' method='post'>
       <input type='submit' name='gusuarios' value='Volver'>
       </form>
       ";
     } else {

       mysqli_set_charset($conn,"utf8");

       // Realizar inserción del nuevo usuario
       $consulta  = sprintf("INSERT INTO usuariosProyecto(nombre,apellidos,telefono,email,pwd,login,tipo) VALUES('%s','%s','%s','%s','%s','%s','%s');",
                       mysqli_real_escape_string($conn,$nombre),
                       mysqli_real_escape_string($conn,$apellido),
                       mysqli_real_escape_string($conn,$telefono),
                       mysqli_real_escape_string($conn,$email),
                       password_hash($contraseña, PASSWORD_DEFAULT),
                       mysqli_real_escape_string($conn,$login),
                       mysqli_real_escape_string($conn,$tipo));
       $resultado = mysqli_query($conn, $consulta);

       //Añadir ocurrencia al log
       $fechaLog = getdate();
       $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
       $fnombre = sprintf("./protected/log.txt");
       $cad = sprintf("{$fechaLog2}: Usuario con nombre {$nombre} añadido por {$_SESSION['usuario']} \n");
       file_put_contents($fnombre,$cad,FILE_APPEND);
       header("Location: ./index.php?p=0");

     }

   }else if( isset($_POST['borrarBBDD']) ){

     borrarBBDD($conn);
     echo<<<HTML
     <div class="main">
        <div class="borrarBBDD">
          <h3>Contenidos de la base de datos borrados correctamente</h3>
          <form class="formOperacion" action="index.php?p=0" method="post">
            <input type="submit" name="volverGestion" value="Volver a gestión">
          </form>
        </div>
     </div>
HTML;

   } else if ( isset($_POST['restaurarBackup']) ){

     //Archivo backUp
     $temp = $_FILES['backupFile']['tmp_name'];
     //Restauramos la BBDD con el archivo
     restaurarBACKUP($temp,$conn);

     echo<<<HTML
      <div class="main">
        <div class="restaurar">
          <h3>BACKUP restaurado en la BBDD</h3>
          <form class="formOperacion" action="index.php?p=0" method="post">
            <input type="submit" name="volverbackup" value="Volver a gestión">
          </form>
        </div>
      </div>
HTML;

   } else if ( isset($_POST['verlog']) ){

     echo"<h3>Log del sitio web:</h3>";
     $f = fopen("./protected/log.txt","r") or die("Error en apertura");
      while (!feof($f)) {
        $cad = fgets($f);
        echo nl2br("{$cad}");
      }
     echo<<<HTML
     <form class="formOperacion" action="index.php?p=0" method="post">
       <input type="submit" name="volverGestion" value="Volver a gestión">
     </form>
HTML;

   } else if( isset($_POST["modifu"]) ){

     $result = mysqli_query($conn,"SELECT nombre FROM usuariosProyecto");

     echo "<h2>Modificando usuario:</h2>
     <form id='formUsuario' action='index.php?p=0' method='post'>
     <h3>Seleccione el usuario a modificar</h3>

     <select name='nombreu'>";
       while ($row = mysqli_fetch_row($result)){
           echo "<option>{$row[0]}</option> \n";
         }
     echo" </select>

     <h3>Introduza los valores que desea modificar:</h3>
     <label>Nombre:</label>
     <input type='text' name='nombre'>
     <label>Apellidos:</label>
     <input type='text' name='apellido'>
     <label>Teléfono:</label>
     <input type='number' name='telefono'>
     <label>E-mail:</label>
     <input type='text' name='email'>
     <label>Contraseña:</label>
     <input type='password' name='contraseña'>
     <label>Login:</label>
     <input type='text' name='login'>
     <label>Tipo:</label>
     <select name='tipo'>
       <option>admin</option>
       <option>gestor</option>
     </select>
     <input type='submit' name='modificar1' value='Modificar usuario'>
     </form>
     <form id='formUsuario' action='index.php?p=0' method='post'>
     <input type='submit' name='gusuarios' value='Volver'>
     </form>
     ";

   } else if(  isset($_POST["borraru"]) ){

     $result = mysqli_query($conn,"SELECT login FROM usuariosProyecto");

     echo "<h2>Borrando usuario:</h2>
     <h3>Introduza el login del usuario a borrar:</h3>
     <form id='formUsuario'  action='index.php?p=0' method='post'>
     <label>Nombre:</label>
     <select name='nombre'>";
       while ($row = mysqli_fetch_row($result)){
           echo "<option>{$row[0]}</option> \n";
         }
     echo" </select>
     <input type='submit' name='borrar1' value='Borrar usuario'>
     </form>
     <form id='formUsuario' action='index.php?p=0' method='post'>
     <input type='submit' name='gusuarios' value='Volver'>
     </form>";


   } else if( isset($_POST["crearu"]) ){

       echo "<h2>Creando usuario:</h2>
       <h3>Introduza los valores para el nuevo usuario:</h3>
       <form id='formUsuario' action='index.php?p=0' method='post' required>
       <label>Nombre:</label>
       <input type='text' name='nombre' required>
       <label>Apellidos:</label>
       <input type='text' name='apellido' required>
       <label>Teléfono:</label>
       <input type='number' name='telefono' required>
       <label>E-mail:</label>
       <input type='text' name='email' required>
       <label>Contraseña:</label>
       <input type='password' name='contraseña' required>
       <label>Login:</label>
       <input type='text' name='login' required>
       <label>Tipo:</label>
       <select name='tipo' required>
         <option>admin</option>
         <option>gestor</option>
       </select>
       <input type='submit' name='crear1' value='Crear usuario'>
       </form>
       <form id='formUsuario' action='index.php?p=0' method='post'>
       <input type='submit' name='gusuarios' value='Volver'>
       </form>
       ";

   } else {

             echo "<h2>Gestión de usuarios:</h2>";

             //Opciones para modificar, crear o eliminar usuarios
             echo "
             <form action='index.php?p=0' method='post'>
             <div class = 'botonesGestion'>
             <input type='submit' name='crearu' value='Crear usuario'>
             <input type='submit' name='borraru' value='Borrar usuario'>
             <input type='submit' name='modifu' value='Modificar usuario'>
             </div>

             ";

             //Mostramos los usuarios
             $result = mysqli_query($conn,"SELECT * FROM usuariosProyecto");
             echo "<table id='usuarios'> \n";
             echo "<tr><td>Nombre</td><td>Apellidos</td><td>Teléfono</td><td>E-Mail</td><td>Login</td><td>Tipo de usuario</td></tr> \n";
             while ($row = mysqli_fetch_row($result)){
                 echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[5]</td><td>$row[6]</td></tr> \n";
             }
             echo "</table> \n";
             echo<<<HTML
             <form class="formOperacion" action="index.php?p=0" method="post">
               <label>Log de eventos del sitio web:</label>
               <input type="submit" name="verlog" value="Visualizar log">
            </form>

            <form class="formOperacion" action="./protected/realizarBackup.php" method="post">
              <label>Crear BACKUP de la BBDD en el archivo backup.sql</label>
              <input type="submit" name="crearBackup" value="Crear BACKUP">
            </form>
            <form enctype="multipart/form-data" class="formOperacion" action="index.php?p=0" method="post">
              <label>Restaurar BACKUP de la BBDD con archivo:</label>
              <input type="file" name="backupFile" value="" required>
              <input type="submit" name="restaurarBackup" value="Restaurar con BACKUP">
            </form>
            <form class="formOperacion" action="index.php?p=0" method="post">
              <label>Borrar BBDD</label>
              <input type="submit" name="borrarBBDD" value="Borrar la Base de Datos">
            </form>
            <form class="formOperacion" action="index.php?p=0" method="post">
              <input  id='volverLog' type='submit' name='salirGestion' value='Salir de gestión'>
            </form>
HTML;

           }
     echo "</div>";
     echo "</div>";
}

 ?>
