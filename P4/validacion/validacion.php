<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../estilaso.css">
  <title id="titulo">Validación</title>
</head>
<body>
<?php

//FUNCIONES

function valcuenta_bancaria($cuenta1,$cuenta2,$cuenta3,$cuenta4){
if (strlen($cuenta1)!=4) return false;
if (strlen($cuenta2)!=4) return false;
if (strlen($cuenta3)!=2) return false;
if (strlen($cuenta4)!=10) return false;

if (mod11_cuenta_bancaria("00".$cuenta1.$cuenta2)!=$cuenta3[0]) return false;
if (mod11_cuenta_bancaria($cuenta4)!=$cuenta3[1]) return false;
return true;
}
//////////////////////////////////////////////////////////////////////////////////////

function mod11_cuenta_bancaria($numero){
if (strlen($numero)!=10) return "?";

$cifras = Array(1,2,4,8,5,10,9,7,3,6);
$chequeo=0;
for ($i=0; $i < 10; $i++)
    $chequeo += substr($numero,$i,1) * $cifras[$i];

$chequeo = 11 - ($chequeo % 11);
if ($chequeo == 11) $chequeo = 0;
if ($chequeo == 10) $chequeo = 1;
return $chequeo;
}
        //COMPROBACION DE VARIABLE

        if ( isset($_POST['nombre']) ) $nombre = htmlentities($_POST['nombre']);
        if ( isset($_POST['ape']) ) $apellido = htmlentities($_POST['ape']);
        if ( isset($_POST['telef']) ) $telef = htmlentities($_POST['telef']);
        if ( isset($_POST['email']) ) $email = htmlentities($_POST['email']);

        //FORMULARIOS

        $rev = array(array("Sabelotodo","Solo sé que no sé nada","Muy interesado","Ciencia con sabor"), //Divulgación
                   array("Supercoches","Corre que te pillo","El más lento de la carretera"), //Motor
                   array("Paraisos del mundo","Conoce tu ciudad","La casa de tu vecino: rincones inhóspitos")); //Viajes

        //USUARIO
        if (empty($nombre))
        $hayerror['nombre'] = '<p style="color:red;">No ha indicado ningún nombre</p>';
        else $hayerror['nombre'] = '';

        if (empty($apellido))
        $hayerror['apellidos'] = '<p style="color:red;">No ha indicado ningún apellido</p>';
        else $hayerror['apellidos'] = '';

        if (empty($_POST['postal']))
        $hayerror['postal'] = '<p style="color:red;">No ha indicado ninguna dirección postal</p>';
        else if ( !is_numeric($_POST['postal']) )
        $hayerror['postal'] = '<p style="color:red;">La dirección postal debe ser un número</p>';
        else if (!preg_match('/^[0-9]{5}$/',$_POST['postal']))
        $hayerror['postal'] = '<p style="color:red;">La dirección postal debe tener 5 números</p>';
        else $hayerror['postal'] = '';

        $optionsdia = array('options' => array('min_range' => 1,'max_range' => 31,));
        if (empty($_POST['diaf']))
        $hayerror['diaf'] = '<p style="color:red;">Introduzca el dia de nacimiento</p>';
        else if ( !is_numeric($_POST['diaf']) )
        $hayerror['diaf'] = '<p style="color:red;">El día debe ser un número</p>';
        else if (filter_var($_POST['diaf'], FILTER_VALIDATE_INT, $optionsdia) == FALSE)
        $hayerror['diaf'] = '<p style="color:red;">El día debe estar entre 1 y 31</p>';
        else $hayerror['diaf'] = '';

        $optionsmes = array('options' => array('min_range' => 1,'max_range' => 12,));
        if (empty($_POST['mesf']))
        $hayerror['mesf'] = '<p style="color:red;">Introduzca el mes de nacimiento</p>';
        else if ( !is_numeric($_POST['mesf']) )
        $hayerror['mesf'] = '<p style="color:red;">El mes debe ser un número</p>';
        else if (filter_var($_POST['mesf'], FILTER_VALIDATE_INT, $optionsmes) == FALSE)
        $hayerror['mesf'] = '<p style="color:red;">El mes debe estar entre 1 y 12</p>';
        else $hayerror['mesf'] = '';

        $optionsanio = array('options' => array('min_range' => 0,'max_range' => 2000,));
        if (empty($_POST['aniof']))
        $hayerror['aniof'] = '<p style="color:red;">Introduzca el año de nacimiento</p>';
        else if ( !is_numeric($_POST['aniof']) )
        $hayerror['aniof'] = '<p style="color:red;">El año debe ser un número</p>';
        else if (filter_var($_POST['aniof'], FILTER_VALIDATE_INT, $optionsanio) == FALSE)
        $hayerror['aniof'] = '<p style="color:red;">Debes ser mayor de edad (nacido antes del 2000)</p>';
        else $hayerror['aniof'] = '';

        if (empty($_POST['email']))
        $hayerror['email'] = '<p style="color:red;">No ha indicado ningun e-mail</p>';
        else $hayerror['email'] = '';

        if (empty($telef))
        $hayerror['telefono'] = '<p style="color:red;">No ha indicado ningún teléfono</p>';
        else if (filter_var($telef, FILTER_VALIDATE_INT) == FALSE)
        $hayerror['telefono'] = '<p style="color:red;">El teléfono debe ser un número (entero)</p>';
        else if (!preg_match('/^[0-9]{9}$/',$telef))
        $hayerror['telefono'] = '<p style="color:red;">El teléfono debe de tener 9 números</p>';
        else $hayerror['telefono'] = '';

        if (empty($_POST['cc']))
        $hayerror['cc'] = '<p style="color:red;">No ha indicado ningun CC</p>';
        else if ( !is_numeric($_POST['cc']) )
        $hayerror['cc'] = '<p style="color:red;">El CC debe ser un número</p>';
        else if (!preg_match('/^[0-9]{20}$/',$_POST['cc']))
        $hayerror['cc'] = '<p style="color:red;">El CC debe tener 20 números</p>';
        else if ( !valcuenta_bancaria(substr($_POST['cc'], 0, -16),substr($_POST['cc'], 4, -12),substr($_POST['cc'], 8, -10),substr($_POST['cc'],10)) )
        $hayerror['cc'] = '<p style="color:red;">El CC no es correcto</p>';
        else $hayerror['cc'] = '';

        //TARJETA
        if (empty($_POST['anio']))
        $hayerror['anio'] = '<p style="color:red;">No ha indicado ningún tipo de suscripción</p>';
        else $hayerror['anio'] = '';

        if (empty($_POST['pago']))
        $hayerror['pago'] = '<p style="color:red;">Debe escoger un método de pago</p>';
        else $hayerror['pago'] = '';

        if( isset($_POST['pago']) ) if( $_POST['pago'] == "tarjeta"){
          if (empty($_POST['ttar']))
          $hayerror['ttar'] = '<p style="color:red;">Escoja un tipo de tarjeta</p>';
          else $hayerror['ttar'] = '';

          if (empty($_POST['ntar']))
          $hayerror['ntar'] = '<p style="color:red;">Introduzca el número de tarjeta</p>';
          else if ( !is_numeric($_POST['ntar']) )
          $hayerror['ntar'] = '<p style="color:red;">El número de tarjeta debe ser un número</p>';
          else if (!preg_match('/^[0-9]{16}$/',$_POST['ntar']))
          $hayerror['ntar'] = '<p style="color:red;">El número de tarjeta debe tener 16 números</p>';
          else $hayerror['ntar'] = '';

          $optionsmes = array('options' => array('min_range' => 1,'max_range' => 12,));
          if (empty($_POST['mestar']))
          $hayerror['mestar'] = '<p style="color:red;">Introduzca el mes de caducidad de tarjeta</p>';
          else if ( !is_numeric($_POST['mestar']) )
          $hayerror['mestar'] = '<p style="color:red;">El mes debe ser un número</p>';
          else if (filter_var($_POST['mestar'], FILTER_VALIDATE_INT, $optionsmes) == FALSE)
          $hayerror['mestar'] = '<p style="color:red;">El mes debe estar entre 1 y 12</p>';
          else $hayerror['mestar'] = '';

          $optionsanio = array('options' => array('min_range' => 2018,'max_range' => 2030,));
          if (empty($_POST['aniotar']))
          $hayerror['aniotar'] = '<p style="color:red;">Introduzca el año de caducidad de tarjeta</p>';
          else if ( !is_numeric($_POST['aniotar']) )
          $hayerror['aniotar'] = '<p style="color:red;">El año debe ser un número</p>';
          else if (filter_var($_POST['aniotar'], FILTER_VALIDATE_INT, $optionsanio) == FALSE)
          $hayerror['aniotar'] = '<p style="color:red;">El año debe estar entre 2018 y 2030</p>';
          else $hayerror['aniotar'] = '';

          if (empty($_POST['cvc']))
          $hayerror['cvc'] = '<p style="color:red;">Introduzca el CVC de la tarjeta</p>';
          else if ( !is_numeric($_POST['cvc']) )
          $hayerror['cvc'] = '<p style="color:red;">El cvc debe ser un número</p>';
          else if (!preg_match('/^[0-9]{3}$/',$_POST['cvc']))
          $hayerror['cvc'] = '<p style="color:red;">El CVC de la tarjeta debe tener 3 números</p>';
          else $hayerror['cvc'] = '';
        }

        if( empty($_POST['publicidad']) )
        $hayerror['publicidad'] = '<p style="color:red;">Desea recibir spam ?</p>';
        else $hayerror['publicidad'] = '';

        //Comprobar si hay algún error
        $hayerrores ="no";
        foreach ($hayerror as $valor){
          if ($valor !== "")
            $hayerrores ="si";
        }

        if ( $hayerrores == "no" ){

          echo"
          <h2> Información de su suscripción</h2>
          <h3>Datos de usuario:</h3>
          <p><b>Nombre: </b>$nombre</p>
          <p><b>Apellido: </b>$apellido</p>
          <p><b>Dirección postal: </b>{$_POST['postal']}</p>
          <p><b>Fecha de nacimiento: </b>{$_POST['diaf']} del {$_POST['mesf']} del año {$_POST['aniof']} </p>
          <p><b>Email: </b>$email</p>
          <p><b>Teléfono: </b>$telef</p>
          <p><b>CC: </b>{$_POST['cc']} </p><br>

          <h3>Datos de suscripción:</h3>
          <p><b>Revista: </b>{$_POST['revista']}</p>
          <p><b>Tipo de suscripción: </b>{$_POST['anio']}</p>
          <p><b>Método de pago: </b>{$_POST['pago']}</p>
          ";
          //Pago tarjeta (opcional)
          if ( $_POST['pago'] == "tarjeta" ) echo"
          <h4><b>Detalles de la tarjeta: </b></h4>
          <p><b>Tipo de tarjeta: </b>{$_POST['ttar']}</p>
          <p><b>Número de tarjeta: </b>{$_POST['ntar']}</p>
          <p><b>Més: </b>{$_POST['mestar']} <b>Año: </b> {$_POST['aniotar']}</p>
          <p><b>CVC: </b>{$_POST['cvc']}</p>
          ";
          echo "<br>
          <h3>Otros datos:</h3>";
          if ( !empty($_POST['temaint']) ) echo"
          <p><b>Temás de interes: </b></p>";
          else echo "<p><b>Temás de interes: </b>Ninguno</p>";
          if ( !empty($_POST['temaint']) ) foreach($_POST['temaint'] as $value) {
            echo ' - '.$value.'<br>';
          }
          echo"
          <p><b>Acepta envío de publicidad: </b> {$_POST['publicidad']}</p>

          ";

        } else if( isset($_POST["form1"]) ){
          echo "
            <h2>Corrija la información necesaria de su suscripción</h2>
            <form action='validacion.php' method='post'>
                <fieldset>
                  <legend>Datos personales</legend>
                    Nombre:<br>
                    <input type='text' name='nombre' ";
                    if( isset($nombre) ) echo "value='{$nombre}' />";
                    echo "<br>{$hayerror['nombre']}<br>

                    Apellidos:<br>
                    <input type='text' name='ape' ";
                    if( isset($apellido) ) echo "value='{$apellido}' />";
                    echo" <br>{$hayerror['apellidos']}<br>

                    Dirección postal:<br>
                    <input type='text' name='postal' ";
                    if( isset($_POST['postal']) ) echo "value='{$_POST['postal']}' />";
                    echo" <br>{$hayerror['postal']}<br>

                    Fecha de nacimiento:<br><br>
                    Día:&nbsp;
                    <input type='number' name='diaf' ";
                    if( isset($_POST['diaf']) ) echo "value='{$_POST['diaf']}'";
                    echo" />&nbsp;{$hayerror['diaf']}
                    Mes:&nbsp;
                    <input type='number' name='mesf' ";
                    if( isset($_POST['mesf']) ) echo "value='{$_POST['mesf']}'";
                    echo" />&nbsp;{$hayerror['mesf']}
                    Año:&nbsp;
                    <input type='number' name='aniof' ";
                    if( isset($_POST['aniof']) ) echo "value='{$_POST['aniof']}'";
                    echo" /> <br>{$hayerror['aniof']}<br>

                    Caja de email: <br>
                    <input type='email' name='email' ";
                    if( isset($_POST['email']) ) echo "value='{$_POST['email']}' />";
                    echo" <br>{$hayerror['email']}<br>

                    Caja de teléfono: <br>
                    <input type='tel' name='telef' ";
                    if( isset($telef) ) echo "value='{$telef}' />";
                    echo" <br>{$hayerror['telefono']}<br>

                    Número de CC:<br>
                    <input type='number' name='cc' ";
                    if( isset($_POST['cc']) ) echo "value='{$_POST['cc']}' />";
                    echo" <br> {$hayerror['cc']} <br>
                </fieldset>

                <fieldset>
                  <legend>Datos de suscripción</legend>
                  <input type='hidden' name='area' value='{$_POST['area']}'>";

                  if ($_POST["area"] == "divulgacion"){
                    echo "
                    <select name='revista'>
                      <option>{$rev[0][0]}</option>
                      <option>{$rev[0][1]}</option>
                      <option>{$rev[0][2]}</option>
                      <option>{$rev[0][3]}</option>
                    </select>";
                  } else if ($_POST["area"] == "motor"){
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
                    <input type='radio' name='anio' value='anual' ";
                    if( isset($_POST['anio']) && $_POST['anio'] == "anual"  ) echo "checked";
                    echo" />
                    Anual<br>
                    <input type='radio' name='anio' value='bianual' ";
                    if( isset($_POST['anio']) && $_POST['anio'] == "bianual"  ) echo "checked";
                    echo" />
                    Bianual<br>{$hayerror['anio']}<br>
                    </label>
                  <label>Método de pago:<br>
                    <input type='radio' name='pago' value='reembolso' ";
                    if( isset($_POST['pago']) && $_POST['pago'] == "reembolso"  ) echo "checked";
                    echo" />
                    Reembolso<br>
                    <input type='radio' name='pago' value='tarjeta' ";
                    if( isset($_POST['pago']) && $_POST['pago'] == "tarjeta"  ) echo "checked";
                    echo" />
                    Tarjeta<br>{$hayerror['pago']}<br> </label>";

                    echo"
                   <label>Detalles de la tarjeta:<br>
                    <input type='radio' name='ttar' value='visa' ";
                    if( isset($_POST['ttar']) && $_POST['ttar'] == "visa"  ) echo "checked";
                    echo" />
                    Visa<br>
                    <input type='radio' name='ttar' value='mastercard' ";
                    if( isset($_POST['ttar']) && $_POST['ttar'] == "mastercard"  ) echo "checked";
                    echo" />
                    Mastercard<br>";
                    if( !empty($_POST['pago']) ) if($_POST['pago'] == 'tarjeta') echo $hayerror['ttar'];
                    echo" <br>
                    </label>
                    Número de tarjeta:<br>
                    <input type='number' name='ntar' ";
                    if( isset($_POST['ntar']) ) echo "value='{$_POST['ntar']}'";
                    echo "/> <br>";
                    if( !empty($_POST['pago']) ) if($_POST['pago'] == 'tarjeta') echo $hayerror['ntar'];
                    echo" <br>
                    Mes y año:<br>
                    <input type='number' name='mestar' ";
                    if( isset($_POST['mestar']) )  echo "value='{$_POST['mestar']}'";
                    echo " />
                    <input type='number' name='aniotar' ";
                    if( isset($_POST['aniotar']) ) echo "value='{$_POST['aniotar']}'";
                     echo " /> <br> <br>";
                     if( !empty($_POST['pago']) ) if($_POST['pago'] == 'tarjeta') echo $hayerror['mestar'];
                     if( !empty($_POST['pago']) ) if($_POST['pago'] == 'tarjeta') echo $hayerror['aniotar'];
                     echo"
                    Código CVC:<br>
                    <input type='number' name='cvc' ";
                     if( isset($_POST['cvc']) ){
                     echo "value='{$_POST['cvc']}' /> <br>";
                     if( !empty($_POST['pago']) ) if($_POST['pago'] == 'tarjeta') echo $hayerror['cvc'];
                     echo" <br>";
                   }

                echo"
                </fieldset>
                <fieldset>
                  <legend>Otros datos</legend>
                  <label>Temas de interés:<br>
                    <input type='checkbox' name='temaint[]' value='divulgacion' ";
                    if( isset($_POST['temaint']) && $_POST['temaint'] == "divul" ) echo "checked";
                    echo" /> Divulgación<br>
                    <input type='checkbox' name='temaint[]' value='motor' ";
                    if( isset($_POST['temaint']) && $_POST['temaint'] == "mot" ) echo "checked";
                    echo" /> Motor<br>
                    <input type='checkbox' name='temaint[]' value='viajes' ";
                    if( isset($_POST['temaint']) && $_POST['temaint'] == "viaj" ) echo "checked";
                    echo" /> Viajes<br><br>
                  </label>

                  <label>Acepto que se me envíe publicidad a mi e-mail:<br>
                    <input type='radio' name='publicidad' value='Si' checked/>Si 
                    <input type='radio' name='publicidad' value='No' checked/>No <br>
                  </label><br> {$hayerror['publicidad']}

                </fieldset>

                <input type='submit' name ='form1' value='Enviar datos suscripcion' />

          ";
        } else if (isset($_POST["area"])) {
          echo "
            <h2> Información necesaria de su suscripción EDITODO </h2>
            <form action='validacion.php' method='post'>
                <fieldset>
                  <legend>Datos personales</legend>
                    Nombre:<br>
                    <input type='text' name='nombre'/> <br><br>

                    Apellidos:<br>
                    <input type='text' name='ape'/> <br><br>

                    Dirección postal:<br>
                    <input type='number' name='postal'/> <br><br>

                    Fecha de nacimiento:<br><br>
                    Día:&nbsp;
                    <input type='number' name='diaf'/>&nbsp;
                    Mes:&nbsp;
                    <input type='number' name='mesf'/>&nbsp;
                    Año:&nbsp;
                    <input type='number' name='aniof'/> <br><br>

                    Caja de email: <br>
                    <input type='email' name='email'/> <br><br>

                    Caja de teléfono: <br>
                    <input type='tel' name='telef'/> <br><br>

                    Número de CC:<br>
                    <input type='number' name='cc'/> <br><br>
                </fieldset>

                <fieldset>
                  <legend>Datos de suscripción</legend>
                  <input type='hidden' name='area' value='{$_POST['area']}'>";
                  if ($_POST["area"] == "divulgacion"){
                    echo "
                    <select name='revista'>
                      <option>{$rev[0][0]}</option>
                      <option>{$rev[0][1]}</option>
                      <option selected>{$rev[0][2]}</option>
                      <option>{$rev[0][3]}</option>
                    </select>";
                  } else if ($_POST["area"] == "motor"){
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
                    <input type='number' name='cvc'/> <br><br>
                  </label>
                  </label>
                </fieldset>

                <fieldset>
                  <legend>Otros datos</legend>
                  <label>Temas de interés:<br>
                    <input type='checkbox' name='temaint[]' value='divulgacion' /> Divulgación<br>
                    <input type='checkbox' name='temaint[]' value='motor' /> Motor<br>
                    <input type='checkbox' name='temaint[]' value='viajes' /> Viajes<br><br>
                  </label>

                  <label>Acepto que se me envíe publicidad a mi e-mail:<br>
                    <input type='radio' name='publicidad' value='Si' checked/>Si
                    <input type='radio' name='publicidad' value='No' checked/>No <br>
                  </label><br>

                </fieldset>

                <input type='submit' name ='form1' value='Enviar datos suscripcion' />

          ";
        } else {
          /* Si no se han recibido datos del formulario */
          echo "
            <h2> Suscripción a revistas de EDITODO </h2>
            <form action='validacion.php' method='post'>
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
