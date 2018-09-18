<?php

//Iniciar sesión para mantener datos del usuario logeado
session_start();
//Archivos requeridos para formar la web
require "./maquetado/paginasPrincipales.php";
require "./admin/administracion.php";
require "./admin/discografia.php";
require "./admin/biografia.php";
require "./admin/conciertos.php";
require "./admin/componentes.php";
require_once('./protected/dbcredenciales.php');

//Disolver la sesión si se ha hecho Logout
if ( isset($_POST['logout']) ){
 $_SESSION['logged']="no";
 $_SESSION['gestionActiva']="no";
 $_SESSION['tipoUsuario'] ="no";
 $_SESSION['usuario'] = "anónimo";

 //Añadir ocurrencia al log
 $fechaLog = getdate();
 $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
 $fnombre = sprintf("./protected/log.txt");
 $cad = sprintf("{$fechaLog2}: Usuario {$_SESSION['usuario']} desconectado \n");
 file_put_contents($fnombre,$cad,FILE_APPEND);
}


if ( !isset($_GET['p'])){
  $_GET['p']=0;
}

if ( $_GET['p'] < 0 || $_GET['p'] > 8 ){
  $_GET['p']=0;
}

if ( !isset($_GET['compra'])){
  $_GET['compra']="no";
}

//Comprobar que operacion de adminstrador se está realizando
comprobarOperacion();

//Comprobar si estamos comprando un disco
if ( isset( $_POST['comprarDisco'] ) ){
  $_SESSION['compra']="si";
}

//Comprobar si hemos salidoo entrado en modo de gestión de usuarios
if( isset($_POST['salirGestion']) ){
   $_SESSION['gestionActiva']="no";
} else if ( isset($_POST['gusuarios']) ){
   $_SESSION['gestionActiva']="si";
} else if ( isset($_POST['gpedidos']) ){
   $_SESSION['gestionActiva']="si";
 }
//Variables de sesión
  //Indica si el usuario esta logeado
  if( !isset($_SESSION['logged']) ){
     $_SESSION['logged']="no";
     $_SESSION['usuario'] = "anónimo";
  }
  //Indica si hay error en los credenciales
  $_SESSION['errorlog']="no";
  //Indica si se esta haciendo gestión de usuarios
  if( !isset($_SESSION['gestionActiva']) ){
    $_SESSION['gestionActiva'] = "no";
  }

//Realizar conexión a la BBDD
$conn = conexionBBDD();

//Comprobar los credenciales si se estan logeando y el tipo de usuario
if ( isset($_POST['blogin']) ){
  if ( comprobarCredenciales($_POST['usuario'],$_POST['contraseña'],$conn) ){
      $_SESSION['logged']="si";
      $_SESSION['usuario']=$_POST['usuario'];
      //Añadir ocurrencia al log
      $fechaLog = getdate();
      $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
      $fnombre = sprintf("./protected/log.txt");
      $cad = sprintf("{$fechaLog2}: Usuario {$_POST['usuario']} identificado con exito \n");
      file_put_contents($fnombre,$cad,FILE_APPEND);
  } else {
    $_SESSION['errorlog']="si";

    //Añadir ocurrencia al log
    $fechaLog = getdate();
    $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
    $fnombre = sprintf("./protected/log.txt");
    $cad = sprintf("{$fechaLog2}: Intento de identificacion del usuario {$_POST['usuario']} sin exito \n");
    file_put_contents($fnombre,$cad,FILE_APPEND);
  }
  //Comprobar comprobar TipoUsuario
  comprobarTipoUsuario($_POST['usuario'],$conn);
}

//Se muestran páginas dependiendo
HTMLinicio("Avenged Sevenfold");
HTMLheader();

if ( isset( $_GET['p'] ) ){
  if ( isset($_SESSION['compra']) && $_SESSION['compra'] == "si" ){
      HTMLcompra($conn);
  } else if ($_SESSION['gestionActiva']=="no"){

    switch ($_GET['p']) {
      case 0:
        HTMLmain($conn);
        break;

      case 1:
        HTMLnoticias();
        break;

      case 2:
        HTMLbiografia($conn);
        break;

      case 3:
        HTMLdiscografia($conn);
        break;

      case 4:
        HTMLconciertos($conn);
        break;

      case 5:
      switch ($_SESSION['operacion']) {
        case 0:
          añadirConcierto($conn);
          break;
        case 1:
          borrarConcierto($conn);
          break;
        case 2:
          modificarConcierto($conn);
          break;
      }
        break;
      case 6:
        switch ($_SESSION['operacion']) {
          case 0:
            añadirComponente($conn);
            break;
          case 1:
            borrarComponente($conn);
            break;
          case 2:
            modificarComponente($conn);
            break;
        }
        break;

      case 7:
        switch ($_SESSION['operacion']) {
          case 0:
            añadirBiografia($conn);
            break;
          case 1:
            borrarBiografia($conn);
            break;
          case 2:
            modificarBiografia($conn);
            break;
          }
          break;

        case 8:
          switch ($_SESSION['operacion']) {
            case 0:
              añadirDisco($conn);
              break;
            case 1:
              borrarDisco($conn);
              break;
            case 2:
              modificarDisco($conn);
              break;
          }
              break;

    }
  }else if ($_SESSION['gestionActiva']=="si"){
    if ( $_SESSION['tipoUsuario'] == "admin" ){
      gestionUsuarios($conn);
    } else if ( $_SESSION['tipoUsuario'] == "gestor" ){
      gestionPedidos($conn);
    }
  } else HTMLmain($conn);
} else HTMLmain($conn);

if ( $_SESSION['logged'] == "si" ) {
  HTMLlogin("identificado");
} else {
  if ( $_SESSION['errorlog'] == "si" ){
    HTMLlogin("error");
  } else {
    HTMLlogin("sinlog");
  }
}
HTMLaside();
HTMLfooter();
HTMLfin();

 ?>
