<?php

//OPERACIONES DE DISCOGRAFIA
function añadirDisco($conn){


  if( isset( $_POST['botonAniadirDisco'] ) ){
    echo<<<HTML
    <div class='main'>
      <div class='aniadirDisco'>

        <form enctype="multipart/form-data" class="formOperacion" action="index.php?p=8" method="post">
          <h2>Añadiendo disco a la discografía</h2>
          <label>Imagen:</label>
          <input type="file" name="imagenDisco" required>
          <label>Titulo:</label>
          <input type="text" name="tituloDisco" required>
          <label>Año de publicacion:</label>
          <input type="number" name="fechaDisco" required>
          <label>Precio:</label>
          <input type="text" name="precioDisco" required>
          <label>Número de canciones:</label>
          <input type="number" name="nCanciones" required>

          <input type="submit" name="aniadiendoDisco1" value="Añadir">
        </form>
        <form class="formOperacion" action="index.php?p=8" method="post">
          <input type='submit' name='volverDisco' value='Volver'>
        </form>
       </div>
     </div>

HTML;
} else if ( isset( $_POST['aniadiendoDisco1'] ) ){

  //Comprobar si hay un disco con ese titulo
  $consulta  = sprintf("SELECT titulo from disco WHERE titulo='{$_POST["tituloDisco"]}'");
  $resultado = mysqli_query($conn, $consulta);

    if (mysqli_num_rows($resultado)>0) {

      echo<<<HTML
      <div class='main'>
        <div class='aniadirDisco'>

          <form enctype="multipart/form-data" class="formOperacion" action="index.php?p=8" method="post">
            <h2>Añadiendo disco a la discografía</h2>
            <label>Imagen:</label>
            <input type="file" name="imagenDisco" required>
            <label>Titulo:</label>
            <input type="text" name="tituloDisco" required>
            <h3>Ya existe un disco con ese titulo, debe ser diferente</h3>
            <label>Año de publicacion:</label>
            <input type="number" name="fechaDisco" value="{$_POST["fechaDisco"]}" required>
            <label>Precio:</label>
            <input type="text" name="precioDisco" value="{$_POST["precioDisco"]}" required>
            <label>Número de canciones:</label>
            <input type="number" name="nCanciones" value="{$_POST["nCanciones"]}" required>

            <input type="submit" name="aniadiendoDisco1" value="Añadir">
          </form>
          <form class="formOperacion" action="index.php?p=8" method="post">
            <input type='submit' name='volverDisco' value='Volver'>
          </form>
         </div>
       </div>

HTML;

  } else {
//Mover la imagen a la carpeta de descargas
  $path="./imagenes/";
  $name = $_FILES['imagenDisco']['name'];
  $temp = $_FILES['imagenDisco']['tmp_name'];
  move_uploaded_file($temp, $path . $name);

$_SESSION['img-name'] = $_FILES['imagenDisco']['name'];
$_SESSION['img-temp'] = $_FILES['imagenDisco']['tmp_name'];

$_SESSION['imagenDisco'] = $_FILES['imagenDisco']['name'];
$_SESSION['tituloDisco'] = $_POST["tituloDisco"];
$_SESSION['fechaDisco'] = $_POST["fechaDisco"];
$_SESSION['precioDisco'] = $_POST['precioDisco'];
$_SESSION['nCanciones'] = $_POST['nCanciones'];
echo "<h3>Introduzca las canciones del disco junto a su duración:</h3>
      <form class='formOperacion' action='index.php?p=8' method='post'>";
for ($i=0; $i < $_SESSION['nCanciones']; $i++) {
  echo "
        <label>Cancion $i:</label>
        <input type='text' name='cancion$i' required>
        <label>Duración:</label>
        <input type='text' name='duracion$i' required>";
}
echo "<input type='submit' name='aniadiendoDisco2' value='Añadir canciones'>
    </form>";
  }

} else if ( isset( $_POST['aniadiendoDisco2'] ) ){

 mysqli_set_charset($conn,"utf8");

 // Realizar inserción del nuevo disco
 $consulta  = sprintf("INSERT INTO disco(imagen,titulo,año,precio) VALUES('%s','%s','%s','%s');",
                 mysqli_real_escape_string($conn,$_SESSION['imagenDisco']),
                 mysqli_real_escape_string($conn,$_SESSION['tituloDisco']),
                 mysqli_real_escape_string($conn,$_SESSION['fechaDisco']),
                 mysqli_real_escape_string($conn,$_SESSION['precioDisco']));
 $resultado = mysqli_query($conn, $consulta);

 //Añadir ocurrencia al log
 $fechaLog = getdate();
 $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
 $fnombre = sprintf("./protected/log.txt");
 $cad = sprintf("{$fechaLog2}: Disco con titulo {$_SESSION['tituloDisco']} aniadido por {$_SESSION['usuario']}\n");
 file_put_contents($fnombre,$cad,FILE_APPEND);

 //Realizar inserción de todas las canciones
 for ($i=0; $i < $_SESSION['nCanciones']; $i++) {
   $indiceCancion = sprintf("cancion{$i}");
   $indiceDuracion = sprintf("duracion{$i}");
   $consulta  = sprintf("INSERT INTO cancion(disco,titulo,duracion) VALUES('%s','%s','%s');",
                   mysqli_real_escape_string($conn,$_SESSION['tituloDisco']),
                   mysqli_real_escape_string($conn,$_POST[$indiceCancion]),
                   mysqli_real_escape_string($conn,$_POST[$indiceDuracion]));
   $resultado = mysqli_query($conn, $consulta);

 }

 echo<<<HTML

 <div class="main">
 <div class="concierto">
   <h3>Disco {$_SESSION['tituloDisco']} añadido con éxito</h3>
   <form class="formOperacion" action="index.php?p=3" method="post">
     <input type="submit" name="volverDisco"value="Volver a la discografía">
   </form>
 </div>
 </div>

HTML;

 } else if ( isset( $_POST['volverDisco'] ) ){
   header("Location: ./index.php?p=3");
 }

}

//Borrar disco de la discografía
function borrarDisco($conn){

  if ( isset ( $_POST['botonBorrarDisco'] ) ){
    $result = mysqli_query($conn,"SELECT titulo FROM disco");

    echo "
    <div class='main'>
    <div class='borrandoDisco'>
    <h3>Seleccione el titulo del disco a borrar:</h3>
    <form class='formOperacion' action='index.php?p=8' method='post'>
    <label>Titulo:</label>
    <select name='titulo'>";
      while ($row = mysqli_fetch_row($result)){
          echo "<option>{$row[0]}</option> \n";
        }
    echo" </select>
    <input type='submit' name='borrarDiscografia' value='Borrar disco'>
    <input type='submit' name='volverDiscografia' value='Volver'>

    </form></div></div>";
  } else if ( isset( $_POST['borrarDiscografia'] ) ){
    $titulo = $_POST["titulo"];

    //Borrar la foto del componente de la carpeta de imagenes
    $consulta = sprintf("SELECT imagen FROM disco WHERE titulo='%s' ",
                    mysqli_real_escape_string($conn,$titulo) );
    $result = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_row($result);
    unlink('./imagenes/'.$row[0]);

    // Borrar el disco indicado
    $consulta  = sprintf("DELETE FROM disco WHERE titulo='%s'",mysqli_real_escape_string($conn,$titulo));
    $resultado = mysqli_query($conn, $consulta);

    //Añadir ocurrencia al log
    $fechaLog = getdate();
    $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
    $fnombre = sprintf("./protected/log.txt");
    $cad = sprintf("{$fechaLog2}: Disco con titulo {$titulo} borrado por {$_SESSION['usuario']}\n");
    file_put_contents($fnombre,$cad,FILE_APPEND);

    // Borrar las canciones del disco indicado
    $consulta  = sprintf("DELETE FROM cancion WHERE disco='%s'",mysqli_real_escape_string($conn,$titulo));
    $resultado = mysqli_query($conn, $consulta);

    echo<<<HTML
    <div class="main">
    <div class="concierto">
      <h3>El disco $titulo ha sido borrado con éxito</h3>
      <form class="formOperacion" action="index.php?p=3" method="post">
        <input type="submit" name="volverDiscografia"value="Volver a discografía">
      </form>
    </div>
    </div>

HTML;

  } else if ( isset( $_POST['volverDiscografia'] ) ){
    header("Location: ./index.php?p=3");
  }

}

//Modificar disco de discografía
function modificarDisco($conn){

  if( isset( $_POST['botonModificarDisco'] ) ){
    $result = mysqli_query($conn,"SELECT titulo FROM disco");

    echo<<<HTML
    <div class="main">
    <div class="modificandoDisco">
        <h2>Modificando disco de discografía:</h2>
        <form enctype="multipart/form-data" class="formOperacion" action='index.php?p=8' method='post'>
        <h3>Seleccione el disco a modificar</h3>
        <select name='titulou'>
HTML;
          while ($row = mysqli_fetch_row($result)){
              echo "<option>{$row[0]}</option> \n";
            }
        echo<<<HTML
        </select>

          <h3>Introduzaca los nuevos datos para el disco (solo los que desea modificar)</h3>
          <label>Imagen:</label>
          <input type="file" name="imagenDisco">
          <label>Titulo:</label>
          <input type="text" name="tituloDisco">
          <label>Año de publicacion:</label>
          <input type="number" name="fechaDisco">
          <label>Precio:</label>
          <input type="text" name="precioDisco">

        <input type='submit' name='modificarDiscografia1' value='Modificar disco'>
        <input type='submit' name='volverDiscografia' value='Volver'>

        </form>
      </div>
    </div>

HTML;

} else if ( isset( $_POST['modificarDiscografia1'] ) ){

  //Comprobar si hay un disco con ese titulo
  $consulta  = sprintf("SELECT titulo from disco WHERE titulo='{$_POST["tituloDisco"]}'");
  $resultado = mysqli_query($conn, $consulta);

    if (mysqli_num_rows($resultado)>0) {

      $result = mysqli_query($conn,"SELECT titulo FROM disco");

      echo<<<HTML
      <div class="main">
      <div class="modificandoDisco">
          <h2>Modificando disco de discografía:</h2>
          <form enctype="multipart/form-data" class="formOperacion" action='index.php?p=8' method='post'>
          <h3>Seleccione el disco a modificar</h3>
          <select name='titulou'>
HTML;
            while ($row = mysqli_fetch_row($result)){
                echo "<option>{$row[0]}</option> \n";
              }
          echo<<<HTML
          </select>

            <h3>Introduzaca los nuevos datos para el disco (solo los que desea modificar)</h3>
            <label>Imagen:</label>
            <input type="file" name="imagenDisco">
            <label>Titulo:</label>
            <input type="text" name="tituloDisco">
            <label>Año de publicacion:</label>
            <h3>Ya existe un disco con ese titulo, debe ser diferente</h3>
            <input type="number" name="fechaDisco" value="{$_POST["fechaDisco"]}" >
            <label>Precio:</label>
            <input type="text" name="precioDisco" value="{$_POST["precioDisco"]}" >

          <input type='submit' name='modificarDiscografia1' value='Modificar disco'>
          <input type='submit' name='volverDiscografia' value='Volver'>

          </form>
        </div>
      </div>

HTML;

    } else {

    $titulo = $_POST["titulou"];
    $tituloa = $_POST["titulou"];
    $consulta = sprintf("SELECT * FROM disco WHERE titulo='%s' ",
                    mysqli_real_escape_string($conn,$titulo) );
    $result = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_row($result);

      if( !empty($_POST["tituloDisco"]) ){
        $titulo = $_POST["tituloDisco"];
      }

      if( empty($_POST["fechaDisco"]) ){
        $fechaDisco = $row[2];
      }else $fechaDisco = $_POST["fechaDisco"];

      if( empty($_POST["precioDisco"]) ){
        $precioDisco = $row[3];
      }else $precioDisco = $_POST["precioDisco"];

      if( !empty($_FILES['imagenDisco']['name']) ) {
        unlink('./imagenes/'.$row[0]);
        $imagenDisco = $_FILES['imagenDisco']['name'];
        //Mover la imagen a la carpeta de descargas
          $path="./imagenes/";
          $name = $_FILES['imagenDisco']['name'];//Name of the File
          $temp = $_FILES['imagenDisco']['tmp_name'];
          move_uploaded_file($temp, $path . $name);
        } else {
          $imagenDisco = $row[0];
        }

      //Actualizar la entrada

      $consulta  = sprintf("UPDATE disco SET imagen='%s', titulo='%s', año='%s', precio='%s' WHERE titulo='%s' ",
                      mysqli_real_escape_string($conn,$imagenDisco),
                      mysqli_real_escape_string($conn,$titulo),
                      mysqli_real_escape_string($conn,$fechaDisco),
                      mysqli_real_escape_string($conn,$precioDisco),
                      mysqli_real_escape_string($conn,$tituloa));
      $resultado = mysqli_query($conn, $consulta);

      $consulta  = sprintf("UPDATE cancion SET disco='%s' WHERE disco='%s' ",
                      mysqli_real_escape_string($conn,$titulo),
                      mysqli_real_escape_string($conn,$tituloa));
      $resultado = mysqli_query($conn, $consulta);

      //Añadir ocurrencia al log
      $fechaLog = getdate();
      $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
      $fnombre = sprintf("./protected/log.txt");
      $cad = sprintf("{$fechaLog2}: Disco con titulo {$titulo} modificado por {$_SESSION['usuario']}\n");
      file_put_contents($fnombre,$cad,FILE_APPEND);

      echo<<<HTML

      <div class="main">
      <div class="concierto">
        <h3>Disco modificado con éxito</h3>
        <form class="formOperacion" action="index.php?p=3" method="post">
          <input type="submit" name="volverDiscografia"value="Volver a discografía">
        </form>
      </div>
      </div>

HTML;
    }

    } else if ( isset( $_POST['volverDiscografia'] ) ){
      header("Location: ./index.php?p=3");
    }
}

 ?>
