<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../estilaso.css">
  <title id="titulo">Cookies</title>
</head>
<body>
<?php

  setcookie("nombre",'0',time()-1000); // Caducar cookie
  setcookie("prenda",'0',time()-1000); // Caducar cookie
  setcookie("talla",'0',time()-1000); // Caducar cookie
  setcookie("color",'0',time()-1000); // Caducar cookie

  if( isset($_COOKIE['nombrevac']) ){

    echo "
    <h2> Compra de ropa </h2>
    <form action='formulariocookies2.php' method='post'>
        <fieldset>

          <legend>Datos de la compra</legend>
            Introduzca su nombre:<br>
            <input type='text' name='nombre'/> <br>
            <p style='color:red;'>Introduzca el nombre</p>

            Seleccione la prenda:<br>
            <select name='prenda'>
              <option>Camisa</option>
              <option>Pantalón</option>
              <option>Falda</option>
            </select>

        </fieldset><br>
        <input type='submit' name ='form1' value='Enviar datos compra' />
    ";


  }else {

    echo "
    <h2> Compra de ropa </h2>
    <form action='formulariocookies2.php' method='post'>
        <fieldset>

          <legend>Datos de la compra</legend>
            Introduzca su nombre:<br>
            <input type='text' name='nombre'/> <br><br>

            Seleccione la prenda:<br>
            <select name='prenda'>
              <option>Camisa</option>
              <option>Pantalón</option>
              <option>Falda</option>
            </select>

        </fieldset><br>
        <input type='submit' name ='form1' value='Enviar datos compra' />
    ";
}
?>
</body>
</html>
