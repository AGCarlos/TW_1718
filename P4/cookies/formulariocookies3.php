<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title id="titulo">Cookies</title>
</head>
<body>
<?php

    setcookie("nombre",$_GET['talla'], 0);
    setcookie("prenda",$_GET['color'], 0);
    /*Talla: {$_COOKIE['nombre']}<br><br>
    Color: {$_COOKIE['nombre']}<br><br>*/
    echo "
      <h2> Datos de la compra </h2>
      Nombre: {$_COOKIE['nombre']}<br><br>
      Prenda: {$_COOKIE['prenda']}<br><br>
      Talla: {$_COOKIE['talla']}<br><br>
      Color: {$_COOKIE['color']}<br><br>

    ";

?>
</body>
</html>
