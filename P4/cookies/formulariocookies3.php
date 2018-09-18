<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../estilaso.css">
  <title id="titulo">Cookies</title>
</head>
<body>
<?php
    if ( !isset($_COOKIE['nombre']) ) header("Location: ./formulariocookies1.php");
    if (isset($_COOKIE['nombre'])){
      //VARIABLES
      if ( isset($_POST['form2']) ) $talla=htmlentities($_POST['talla']);

      //FORMULARIO
      if( isset($_COOKIE['talla']) && isset($_COOKIE['color']) ){

        echo "
          <h2> Datos de la compra </h2>
          Nombre: {$_COOKIE['nombre']}<br><br>
          Prenda: {$_COOKIE['prenda']}<br><br>
          Talla: {$_COOKIE['talla']}<br><br>
          Color: {$_COOKIE['color']}<br><br>";

          setcookie("nombre",'0',time()-1000); // Caducar cookie
          setcookie("prenda",'0',time()-1000); // Caducar cookie
          setcookie("talla",'0',time()-1000); // Caducar cookie
          setcookie("color",'0',time()-1000); // Caducar cookie
          setcookie("nombrevac",'0',time()-1000); // Caducar cookie

      } else if ($talla < 30 || $talla > 50){
        setcookie("talla",$talla, 0);
        header("Location: ./formulariocookies2.php");

      }else if ( $talla > 29 && $talla < 51 ){
        setcookie("talla",$talla, 0);
        setcookie("color",htmlentities($_POST['color']), 0);
        header("Location: ./formulariocookies3.php");

      }
    }

?>
</body>
</html>
