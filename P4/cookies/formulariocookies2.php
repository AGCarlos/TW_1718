<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title id="titulo">Cookies</title>
</head>
<body>
<?php

    setcookie("nombre",$_GET['nombre'], 0);
    setcookie("prenda",$_GET['prenda'], 0);

    echo "
    <h2> Compra de ropa </h2>
    <form action='formulariocookies3.php' method='get'>
        <fieldset>

          <legend>Detalles de la prenda</legend>
            Talla:<br>
            <input type='number' name='talla' value='30'/> <br><br>
            
            Color:<br>
            <select name='color'>
              <option>Rojo</option>
              <option>Verde</option>
              <option>Azul</option>
            </select>

        </fieldset><br>
        <input type='submit' name ='form2' value='Enviar datos prenda' />

    ";


?>
</body>
</html>
