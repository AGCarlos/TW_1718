<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title id="titulo">Validación</title>
</head>
<body>
<?php

$rev = array(array("Sabelotodo","Solo sé que no sé nada","Muy interesado","Ciencia con sabor"), //Divulgación
           array("Supercoches","Corre que te pillo","El más lento de la carretera"), //Motor
           array("Paraisos del mundo","Conoce tu ciudad","La casa de tu vecino: rincones inhóspitos")); //Viajes

        if (empty($_GET['nombre']))
        $hayerror['nombre'] = '<p style="color:red;">No ha indicado ningún nombre</p>';
        else $hayerror['nombre'] = '';

        if (empty($_GET['ape']))
        $hayerror['apellidos'] = '<p style="color:red;">No ha indicado ningún apellido</p>';
        else $hayerror['apellidos'] = '';

        if (empty($_GET['postal']))
        $hayerror['postal'] = '<p style="color:red;">No ha indicado ninguna dirección postal</p>';
        else if (!is_numeric($_GET['postal']))
        $hayerror['postal'] = '<p style="color:red;">La dirección postal debe ser un número</p>';
        else if (!preg_match('/[0-9]{5}/',$_GET['postal']))
        $hayerror['postal'] = '<p style="color:red;">La dirección postal debe tener 5 números</p>';
        else $hayerror['postal'] = '';

        if (empty($_GET['nac']))
        $hayerror['nac'] = '<p style="color:red;">No ha indicado ninguna fecha de nacimiento</p>';

        if (empty($_GET['email']))
        $hayerror['email'] = '<p style="color:red;">No ha indicado ningun e-mail</p>';


        if (empty($_GET['telef']))
        $hayerror['telefono'] = '<p style="color:red;">No ha indicado ningún teléfono</p>';
        else if (!is_numeric($_GET['telef']))
        $hayerror['telefono'] = '<p style="color:red;">El teléfono debe ser un número</p>';
        else if (!preg_match('/[0-9]{9}/',$_GET['telef']))
        $hayerror['telefono'] = '<p style="color:red;">El teléfono debe de tener 9 números</p>';
        else $hayerror['telefono'] = '';

        if (empty($_GET['anio']))
        $hayerror['anio'] = '<p style="color:red;">No ha indicado ningún tipo de suscripción</p>';
        else $hayerror['anio'] = '';

        if (empty($_GET['pago']))
        $hayerror['pago'] = '<p style="color:red;">Debe escoger un método de pago</p>';
        else $hayerror['pago'] = '';

        if (empty($_GET['ttar']))
        $hayerror['ttar'] = '<p style="color:red;">Escoja un tipo de tarjeta</p>';
        else $hayerror['ttar'] = '';

        if( isset($_GET["form1"]) ){
          echo "
            <h2>Corrija la información necesaria de su suscripción</h2>
            <form action='validacion.php' method='get'>
                <fieldset>
                  <legend>Datos personales</legend>
                    Nombre:<br>
                    <input type='text' name='nombre' ";
                    if( isset($_GET['nombre']) ) echo "value='{$_GET['nombre']}' />";
                    echo "<br>{$hayerror['nombre']}<br>

                    Apellidos:<br>
                    <input type='text' name='ape' ";
                    if( isset($_GET['ape']) ) echo "value='{$_GET['ape']}' />";
                    echo" <br>{$hayerror['apellidos']}<br>

                    Dirección postal:<br>
                    <input type='text' name='postal' ";
                    if( isset($_GET['postal']) ) echo "value='{$_GET['postal']}' />";
                    echo" <br>{$hayerror['postal']}<br>

                    Fecha de nacimiento:<br>
                    <input type='date' name='nac' ";
                    if( isset($_GET['nac']) ) echo "value='{$_GET['nac']}' />";
                    echo" <br>{$hayerror['nac']}<br>

                    Caja de email: <br>
                    <input type='email' name='email' ";
                    if( isset($_GET['email']) ) echo "value='{$_GET['email']}' />";
                    echo" <br>{$hayerror['email']}<br>

                    Caja de teléfono: <br>
                    <input type='tel' name='telef' ";
                    if( isset($_GET['telef']) ) echo "value='{$_GET['telef']}' />";
                    echo" <br>{$hayerror['telefono']}<br>

                    Número de CC:<br>
                    <input type='number' name='cc' ";
                    if( isset($_GET['cc']) ) echo "value='{$_GET['cc']}' />";
                    echo" <br><br>
                </fieldset>

                <fieldset>
                  <legend>Datos de suscripción</legend>";

                  if ($_GET["area"] == "divulgacion"){
                    echo "
                    <select name='revista'>
                      <option>{$rev[0][0]}</option>
                      <option>{$rev[0][1]}</option>
                      <option>{$rev[0][2]}</option>
                      <option>{$rev[0][3]}</option>
                    </select>";
                  } else if ($_GET["area"] == "motor"){
                      echo "
                      <select name='revista'>
                        <option>{$rev[1][0]}</option>
                        <option>{$rev[1][1]}</option>
                        <option>{$rev[1][2]}</option>
                      </select>";
                  } else {
                      echo "
                      <select name='revista'>
                        <option>{$rev[2][0]}</option>
                        <option>{$rev[2][1]}</option>
                        <option>{$rev[2][2]}</option>
                      </select>";
                  }
                  echo"<br><br>

                  <label>Tipo:<br>
                    <input type='radio' name='anio' value='anual'/>
                    Anual<br>
                    <input type='radio' name='anio' value='bianual'/>
                    Bianual<br>{$hayerror['anio']}<br>

                  <label>Método de pago:<br>
                    <input type='radio' name='pago' value='reembolso'/>
                    Reembolso<br>
                    <input type='radio' name='pago' value='tarjeta'/>
                    Tarjeta<br>{$hayerror['pago']}<br>

                  <label>Detalles de la tarjeta:<br>
                    <input type='radio' name='ttar' value='visa'/>
                    Visa<br>
                    <input type='radio' name='ttar' value='mastercard'/>
                    Mastercard<br>";
                    if($_GET['pago']=="tarjeta") echo $hayerror['ttar'];
                    echo "
                    <br>

                    Número de tarjeta:<br>
                    <input type='number' name='ntar'/> <br>

                    Mes y año:<br>
                    <input type='number' name='mestar'/>
                    <input type='number' name='aniotar'/> <br>
                    Código CVC:<br>
                    <input type='number' name='ntar'/> <br><br>
                  </label>
                  </label>
                </fieldset>

                <fieldset>
                  <legend>Otros datos</legend>
                  <label>Temas de interés:<br>
                    <input type='checkbox' name='temaint[]' value='divul'/> Divulgación<br>
                    <input type='checkbox' name='temaint[]' value='mot'/> Motor<br>
                    <input type='checkbox' name='temaint[]' value='viaj' checked/> Viajes<br><br>
                  </label>

                  <label>Publicidad:<br>
                    <input type='checkbox' name='publicidad' value='publicidad' checked/> Acepto que se me envíe publicidad a mi e-mail<br>
                  </label><br>

                </fieldset>

                <input type='submit' name ='form1' value='Enviar datos suscripcion' />

          ";
        } else if (isset($_GET["area"])) {
          echo "
            <h2> Información necesaria de su suscripción EDITODO </h2>
            <form action='validacion.php' method='get'>
                <fieldset>
                  <legend>Datos personales</legend>
                    Nombre:<br>
                    <input type='text' name='nombre'/> <br><br>

                    Apellidos:<br>
                    <input type='text' name='ape'/> <br><br>

                    Dirección postal:<br>
                    <input type='number' name='postal'/> <br><br>

                    Fecha de nacimiento:<br>
                    <input type='date' name='nac'/> <br><br>

                    Caja de email: <br>
                    <input type='email' name='email'/> <br><br>

                    Caja de teléfono: <br>
                    <input type='tel' name='telef'/> <br><br>

                    Número de CC:<br>
                    <input type='number' name='cc'/> <br><br>
                </fieldset>

                <fieldset>
                  <legend>Datos de suscripción</legend>";

                  if ($_GET["area"] == "divulgacion"){
                    echo "
                    <select name='revista'>
                      <option>{$rev[0][0]}</option>
                      <option>{$rev[0][1]}</option>
                      <option selected>{$rev[0][2]}</option>
                      <option>{$rev[0][3]}</option>
                    </select>";
                  } else if ($_GET["area"] == "motor"){
                      echo "
                      <select name='revista'>
                        <option>{$rev[1][0]}</option>
                        <option>{$rev[1][1]}</option>
                        <option selected>{$rev[1][2]}</option>
                      </select>";
                  } else {
                      echo "
                      <select name='revista'>
                        <option>{$rev[2][0]}</option>
                        <option>{$rev[2][1]}</option>
                        <option selected>{$rev[2][2]}</option>
                      </select>";
                  }
                  echo"<br><br>

                  <label>Tipo:<br>
                    <input type='radio' name='anio' value='anual'/>
                    Anual<br>
                    <input type='radio' name='anio' value='bianual'/>
                    Bianual<br><br>

                  <label>Método de pago:<br>
                    <input type='radio' name='pago' value='reembolso'/>
                    Reembolso<br>
                    <input type='radio' name='pago' value='tarjeta'/>
                    Tarjeta<br><br>

                  <label>Detalles de la tarjeta:<br>
                    <input type='radio' name='ttar' value='visa'/>
                    Visa<br>
                    <input type='radio' name='ttar' value='mastercard'/>
                    Mastercard<br><br>

                    Número de tarjeta:<br>
                    <input type='number' name='ntar'/> <br>

                    Mes y año:<br>
                    <input type='number' name='mestar'/>
                    <input type='number' name='aniotar'/> <br>
                    Código CVC:<br>
                    <input type='number' name='ntar'/> <br><br>
                  </label>
                  </label>
                </fieldset>

                <fieldset>
                  <legend>Otros datos</legend>
                  <label>Temas de interés:<br>
                    <input type='checkbox' name='temaint[]' value='divul'/> Divulgación<br>
                    <input type='checkbox' name='temaint[]' value='mot'/> Motor<br>
                    <input type='checkbox' name='temaint[]' value='viaj' checked/> Viajes<br><br>
                  </label>

                  <label>Publicidad:<br>
                    <input type='checkbox' name='publicidad' value='publicidad' checked/> Acepto que se me envíe publicidad a mi e-mail<br>
                  </label><br>

                </fieldset>

                <input type='submit' name ='form1' value='Enviar datos suscripcion' />

          ";
        } else {
          /* Si no se han recibido datos del formulario */
          echo "
            <h2> Suscripción a revistas de EDITODO </h2>
            <form action='validacion.php' method='get'>
              <label>Seleccione el área de interés:<br>
                    <input type='radio' name='area' value='divulgacion'/>
                    Divulgación<br>
                    <input type='radio' name='area' value='motor'/>
                    Motor<br>
                    <input type='radio' name='area' value='viajes' checked/>
                    Viajes<br>
                </label>
              <input type='submit' value='Ingresar'/>
            </form>";
        }


?>
</body>
</html>
