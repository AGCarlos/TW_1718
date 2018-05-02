<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title id="titulo">Cookies</title>
</head>
<body>
<?php

    echo "
    <h2> Compra de ropa </h2>
    <form action='formulariocookies2.php' method='get'>
        <fieldset>

          <legend>Datos de la compra</legend>
            Nombre:<br>
            <input type='text' name='nombre'/> <br><br>

            Prenda:<br>
            <select name='prenda'>
              <option>Camisa</option>
              <option>Pantalón</option>
              <option>Falda</option>
            </select>

        </fieldset><br>
        <input type='submit' name ='form1' value='Enviar datos compra' />
    ";

?>
</body>
</html>
