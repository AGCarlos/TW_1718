<?php

//OPERACIONES DE CONCIERTOS
//Añadir un concierto
function añadirConcierto($conn){

  if( isset( $_POST['botonAniadirConcierto'] ) ){
    echo<<<HTML
    <div class='main'>
      <div class='aniadirConcierto'>

        <form class="formOperacion" action="index.php?p=5" method="post">
          <h2>Añadiendo concierto</h2>
          <label>Fecha del concierto:</label>
          <label>Dia:</label>
          <select class="" name="dia">
            <option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option>
            <option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
            <option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option>
            <option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
            <option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option>
            <option value="32">31</option>
          </select>
          <label>Mes:</label>
          <select class="" name="mes">
            <option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option>
            <option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
          </select>
          <label>Año:</label>
          <input type="number" name="anio" value="">
          <label>Lugar del concierto:</label>
          <input type="text" name="lugarConcierto" required>
          <label>Texto del concierto:</label>
          <input type="text" name="textoConcierto" required>
          <input type="submit" name="aniadiendoConcierto" value="Añadir">
        </form>
        <form class="formOperacion" action="index.php?p=5" method="post">
          <input type='submit' name='volverConcierto' value='Volver'>
        </form>
       </div>
     </div>

HTML;
} else if ( isset( $_POST['aniadiendoConcierto'] ) ){

  $anio = htmlentities($_POST['anio']);
  $fechaConcierto = sprintf("{$anio}-{$_POST['mes']}-{$_POST['dia']}");
  $lugarConcierto = $_POST["lugarConcierto"];
  $textoConcierto = $_POST["textoConcierto"];

  //Comprobar si hay un componente con ese nombre
  $consulta  = sprintf("SELECT fecha from concierto WHERE fecha='{$fechaConcierto}'");
  $resultado = mysqli_query($conn, $consulta);

  if (mysqli_num_rows($resultado)>0 || !preg_match('/^[0-9]{4}$/',$anio)) {

    echo<<<HTML
    <div class='main'>
      <div class='aniadirConcierto'>

        <form class="formOperacion" action="index.php?p=5" method="post">
          <h2>Añadiendo concierto</h2>
          <label>Fecha del concierto:</label>
          <label>Dia:</label>
          <select class="" name="dia">
            <option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option>
            <option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
            <option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option>
            <option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
            <option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option>
            <option value="32">31</option>
          </select>
          <label>Mes:</label>
          <select class="" name="mes">
            <option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option>
            <option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
          </select>
          <label>Año:</label>
          <input type="number" name="anio" value="">
          <h3>Ya existe un concierto con esa fecha, debe ser diferente ó el año debe contener 4 números</h3>
          <label>Lugar del concierto:</label>
          <input type="text" name="lugarConcierto" value="{$lugarConcierto}" required>
          <label>Texto del concierto:</label>
          <input type="text" name="textoConcierto" value="{$textoConcierto}" required>
          <input type="submit" name="aniadiendoConcierto" value="Añadir">
        </form>
        <form class="formOperacion" action="index.php?p=5" method="post">
          <input type='submit' name='volverConcierto' value='Volver'>
        </form>
       </div>
     </div>

HTML;

  } else {

     mysqli_set_charset($conn,"utf8");

     // Realizar inserción del nuevo usuario
     $consulta  = sprintf("INSERT INTO concierto(fecha,lugar,texto) VALUES('%s','%s','%s');",
                     mysqli_real_escape_string($conn,$fechaConcierto),
                     mysqli_real_escape_string($conn,$lugarConcierto),
                     mysqli_real_escape_string($conn,$textoConcierto));
     $resultado = mysqli_query($conn, $consulta);

     //Añadir ocurrencia al log
     $fechaLog = getdate();
     $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
     $fnombre = sprintf("./protected/log.txt");
     $cad = sprintf("{$fechaLog2}: Concierto en fecha {$fechaConcierto} aniadido por {$_SESSION['usuario']}\n");
     file_put_contents($fnombre,$cad,FILE_APPEND);

     echo<<<HTML
      <div class="main">
      <div class="concierto">

       <h3 id="completado">Concierto añadido con éxito</h3>
       <form class="formOperacion" action="index.php?p=4" method="post">
         <input type="submit" name="volverConciertos"value="Volver a conciertos">
       </form>

      </div>
      </div>
HTML;
    }

 } else if ( isset( $_POST['volverConcierto'] ) ){
   header("Location: ./index.php?p=4");
 }

}

function borrarConcierto($conn){

  if ( isset ( $_POST['botonBorrarConcierto'] ) ){
    $result = mysqli_query($conn,"SELECT fecha FROM concierto");
    echo "
    <div class='main'>
    <div class='concierto'>
    <form class='formOperacion' action='index.php?p=5' method='post'>
    <h2>Seleccione la fecha del concierto a borrar:</h2>
    <label>Fecha:</label>
    <select name='fecha'>";
      while ($row = mysqli_fetch_row($result)){
          echo "<option>{$row[0]}</option> \n";
        }
    echo" </select>
    <input type='submit' name='borrarConcierto' value='Borrar concierto'>
    <input type='submit' name='volverConcierto' value='Volver'>

    </form></div></div>";
  } else if ( isset( $_POST['borrarConcierto'] ) ){
    $fecha = $_POST["fecha"];
    // Borrar el componente indicado
    $consulta  = sprintf("DELETE FROM concierto WHERE fecha='%s'",mysqli_real_escape_string($conn,$fecha));

    $resultado = mysqli_query($conn, $consulta);

    //Añadir ocurrencia al log
    $fechaLog = getdate();
    $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
    $fnombre = sprintf("./protected/log.txt");
    $cad = sprintf("{$fechaLog2}: Concierto en fecha {$fecha} borrado por {$_SESSION['usuario']}\n");
    file_put_contents($fnombre,$cad,FILE_APPEND);

    echo<<<HTML
    <div class="main">
    <div class="concierto">
      <h3 id="completado">Concierto borrado con éxito</h3>
      <form class="formOperacion" action="index.php?p=4" method="post">
        <input type="submit" name="volverConciertos"value="Volver a conciertos">
      </form>
    </div>
    </div>

HTML;

  } else if ( isset( $_POST['volverConcierto'] ) ){
    header("Location: ./index.php?p=4");
  }

}

function modificarConcierto($conn){

  if( isset( $_POST['botonModificarConcierto'] ) ){
    $result = mysqli_query($conn,"SELECT fecha FROM concierto");

    echo<<<HTML
    <div class='main'>
      <div class='aniadirConcierto'>
        <h2>Modificando concierto:</h2>
        <form class="formOperacion" action='index.php?p=5' method='post'>
        <h3>Seleccione la fecha del concierto a modificar</h3>

        <select name='fechac'>"
HTML;
          while ($row = mysqli_fetch_row($result)){
              echo "<option>{$row[0]}</option> \n";
            }
        echo<<<HTML
        </select>
        <h3>Introduzca los nuevos datos para el concierto (solo los que desea modificar)</h3>
        <label>Fecha del concierto:</label>
        <label>Dia:</label>
        <select class="" name="dia">
          <option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option>
          <option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
          <option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option>
          <option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
          <option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option>
          <option value="32">31</option>
        </select>
        <label>Mes:</label>
        <select class="" name="mes">
          <option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option>
          <option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
        </select>
        <label>Año:</label>
        <input type="number" name="anio">
        <label>Lugar del concierto:</label>
        <input type="text" name="lugarConcierto">
        <label>Texto del concierto:</label>
        <input type="text" name="textoConcierto">

        <input type='submit' name='modificarConcierto' value='Modificar concierto'>
        <input type='submit' name='volverConcierto' value='Volver'>
        </form>
      </div>
    </div>
    "
HTML;

} else if ( isset( $_POST['modificarConcierto'] ) ){

  $anio = htmlentities($_POST['anio']);
  $fechaConcierto = sprintf("{$anio}-{$_POST['mes']}-{$_POST['dia']}");
  //Comprobar si hay un componente con ese nombre
  $consulta  = sprintf("SELECT fecha from concierto WHERE fecha='{$fechaConcierto}'");
  $resultado = mysqli_query($conn, $consulta);

  if (mysqli_num_rows($resultado)>0 || (!preg_match('/^[0-9]{4}$/',htmlentities($_POST['anio'])) && !empty($_POST['anio']) )  ) {

    $result = mysqli_query($conn,"SELECT fecha FROM concierto");

    echo<<<HTML
    <div class='main'>
      <div class='aniadirConcierto'>
        <h2>Modificando concierto:</h2>
        <form class="formOperacion" action='index.php?p=5' method='post'>
        <h3>Seleccione la fecha del concierto a modificar</h3>

        <select name='fechac'>"
HTML;
          while ($row = mysqli_fetch_row($result)){
              echo "<option>{$row[0]}</option> \n";
            }
        echo<<<HTML
        </select>
        <h3>Introduzca los nuevos datos para el concierto (solo los que desea modificar)</h3>
        <label>Fecha del concierto:</label>
        <label>Dia:</label>
        <select class="" name="dia">
          <option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option>
          <option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
          <option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option>
          <option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
          <option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option>
          <option value="32">31</option>
        </select>
        <label>Mes:</label>
        <select class="" name="mes">
          <option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option>
          <option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
        </select>
        <label>Año:</label>
        <input type="number" name="anio">
        <h3>Ya existe un concierto con esa fecha, debe ser diferente ó el año debe contener 4 números</h3>
        <label>Lugar del concierto:</label>
        <input type="text" name="lugarConcierto" value="{$_POST["lugarConcierto"]}">
        <label>Texto del concierto:</label>
        <input type="text" name="textoConcierto" value="{$_POST["textoConcierto"]}">

        <input type='submit' name='modificarConcierto' value='Modificar concierto'>
        <input type='submit' name='volverConcierto' value='Volver'>
        </form>
      </div>
    </div>
    "
HTML;

  } else {

    $fecha = $_POST["fechac"];
    $fechaa = $_POST["fechac"];
    $consulta = sprintf("SELECT * FROM concierto WHERE fecha='%s' ",
                    mysqli_real_escape_string($conn,$fecha) );
    $result = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_row($result);

    $anio = htmlentities($_POST['anio']);
    $fechaConciertoCal = sprintf("{$anio}-{$_POST['mes']}-{$_POST['dia']}");

    if( $_POST["dia"] == 01 && $_POST["mes"] == 01 && empty($_POST["anio"]) ){
      $fechaConcierto = $row[0];
    }else $fechaConcierto = $fechaConciertoCal;

      if( empty($_POST["lugarConcierto"]) ){
        $lugarConcierto = $row[1];
      }else $lugarConcierto = $_POST["lugarConcierto"];

      if( empty($_POST["textoConcierto"]) ){
        $textoConcierto = $row[2];
      }else $textoConcierto = $_POST["textoConcierto"];

      //Actualizar el usuario

      $consulta  = sprintf("UPDATE concierto SET fecha='%s', lugar='%s', texto='%s' WHERE fecha='%s' ",
                      mysqli_real_escape_string($conn,$fechaConcierto),
                      mysqli_real_escape_string($conn,$lugarConcierto),
                      mysqli_real_escape_string($conn,$textoConcierto),
                      mysqli_real_escape_string($conn,$fechaa));

      $resultado = mysqli_query($conn, $consulta);

      //Añadir ocurrencia al log
      $fechaLog = getdate();
      $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
      $fnombre = sprintf("./protected/log.txt");
      $cad = sprintf("{$fechaLog2}: Concierto en fecha {$fechaConcierto} modificado por {$_SESSION['usuario']}\n");
      file_put_contents($fnombre,$cad,FILE_APPEND);

      echo<<<HTML
      <div class="main">
      <div class="concierto">
          <h3 id="completado">Concierto modificado con éxito</h3>
          <form class="formOperacion" action="index.php?p=4" method="post">
            <input type="submit" name="volverConciertos"value="Volver a conciertos">
          </form>
      </div>
      </div>
HTML;
}

    } else if ( isset( $_POST['volverConcierto'] ) ){
      header("Location: ./index.php?p=4");
    }

}

 ?>
