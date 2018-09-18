<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../estilaso.css">
  <title id="titulo">Cookies</title>
</head>
<body>
<?php

    if( empty($_POST['nombre']) && empty($_COOKIE['nombre']) ){
      header("Location: ./formulariocookies1.php");
      setcookie("nombrevac","nombrevacio", 0);

    } else if( isset($_COOKIE['talla']) ){
      if(  $_COOKIE['talla'] < 30 || $_COOKIE['talla'] > 50){

        echo "
        <h2> Compra de ropa </h2>
        <form action='formulariocookies3.php' method='post'>
            <fieldset>

              <legend>Detalles de la prenda</legend>
                Talla:<br>
                <input type='number' name='talla' /> <br><br>
                <p style='color:red;'>La talla debe de estar entre 30 y 50</p>
                Color:<br>
                <select name='color'>
                  <option>Rojo</option>
                  <option>Verde</option>
                  <option>Azul</option>
                </select>

            </fieldset><br>
            <input type='submit' name ='form2' value='Enviar datos prenda' />
        ";
      }
    } else if ( isset($_POST['form1']) ){

    setcookie("nombre",htmlentities($_POST['nombre']), 0);
    setcookie("prenda",htmlentities($_POST['prenda']), 0);

    echo "
    <h2> Compra de ropa </h2>
    <form action='formulariocookies3.php' method='post'>
        <fieldset>

          <legend>Detalles de la prenda</legend>
            Talla:<br>
            <input type='number' name='talla' /> <br><br>

            Color:<br>
            <select name='color'>
              <option>Rojo</option>
              <option>Verde</option>
              <option>Azul</option>
            </select>

        </fieldset><br>
        <input type='submit' name ='form2' value='Enviar datos prenda' />

    ";

  } else
    header("Location: ./formulariocookies1.php");

?>
</body>
</html>
