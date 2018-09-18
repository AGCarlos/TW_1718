<?php

  //Operaciones de biografía
  function añadirBiografia($conn){


    if( isset( $_POST['botonAniadirBiografia'] ) ){
      echo<<<HTML
      <div class='main'>
        <div class='aniadirBiografia'>

          <form enctype="multipart/form-data" class="formOperacion" action="index.php?p=7" method="post">
            <h2>Añadiendo entrada a la biografía</h2>
            <label>Imagen:</label>
            <input type="file" name="imagenBiografia" required>
            <label>Titulo:</label>
            <input type="text" name="tituloBiografia" required>
            <label>Texto:</label>
            <input type="text" name="textoEntrada" required>
            <input type="submit" name="aniadiendoBiografia" value="Añadir">
          </form>
          <form class="formOperacion" action="index.php?p=7" method="post">
            <input type='submit' name='volverBiografia' value='Volver'>
          </form>
         </div>
       </div>

HTML;
 } else if ( isset( $_POST['aniadiendoBiografia'] ) ){

      $imagenBiografia = $_FILES['imagenBiografia']['name'];
      $tituloBiografia = $_POST["tituloBiografia"];
      $textoEntrada = $_POST["textoEntrada"];

   //Comprobar si hay una entrada con ese titulo
   $consulta  = sprintf("SELECT titulo from biografia WHERE titulo='{$tituloBiografia}'");
   $resultado = mysqli_query($conn, $consulta);

   if (mysqli_num_rows($resultado)>0) {

     echo<<<HTML
     <div class='main'>
       <div class='aniadirBiografia'>

         <form enctype="multipart/form-data" class="formOperacion" action="index.php?p=7" method="post">
           <h2>Añadiendo entrada a la biografía</h2>
           <label>Imagen:</label>
           <input type="file" name="imagenBiografia" required>
           <label>Titulo:</label>
           <input type="text" name="tituloBiografia" required>
           <h3>Ya existe una entrada con ese titulo, debe ser diferente</h3>
           <label>Texto:</label>
           <input type="text" name="textoEntrada" value="{$textoEntrada}" required>
           <input type="submit" name="aniadiendoBiografia" value="Añadir">
         </form>
         <form class="formOperacion" action="index.php?p=7" method="post">
           <input type='submit' name='volverBiografia' value='Volver'>
         </form>
        </div>
      </div>

HTML;

   } else {

   mysqli_set_charset($conn,"utf8");

   //Mover la imagen a la carpeta de descargas
     $path="./imagenes/";
     $name = $_FILES['imagenBiografia']['name'];//Name of the File
     $temp = $_FILES['imagenBiografia']['tmp_name'];
     move_uploaded_file($temp, $path . $name);

   // Realizar inserción del nuevo usuario
   $consulta  = sprintf("INSERT INTO biografia(imagen,titulo,texto) VALUES('%s','%s','%s');",
                   mysqli_real_escape_string($conn,$imagenBiografia),
                   mysqli_real_escape_string($conn,$tituloBiografia),
                   mysqli_real_escape_string($conn,$textoEntrada));
   $resultado = mysqli_query($conn, $consulta);

   //Añadir ocurrencia al log
   $fechaLog = getdate();
   $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
   $fnombre = sprintf("./protected/log.txt");
   $cad = sprintf("{$fechaLog2}: Entrada de biografia con titulo {$tituloBiografia} aniadida por {$_SESSION['usuario']}\n");
   file_put_contents($fnombre,$cad,FILE_APPEND);

   echo<<<HTML
   <div class='main'>
     <div class='aniadirBiografia'>
       <h3>Entrada añadida con éxito</h3>
       <form class="formOperacion" action="index.php?p=2" method="post">
         <input type="submit" name="volverBiografia"value="Volver a la biografía">
       </form>
     </div>
     </div>


HTML;
  }

   } else if ( isset( $_POST['volverBiografia'] ) ){
     header("Location: ./index.php?p=2");
   }

  }

  //Borrar entrada de la biografía
  function borrarBiografia($conn){

    if ( isset ( $_POST['botonBorrarBiografia'] ) ){
      $result = mysqli_query($conn,"SELECT titulo FROM biografia");

      echo "
      <div class='main'>
        <div class='borrarBiografia'>
      <h3>Seleccione el titulo de la entrada a borrar:</h3>
      <form class='formOperacion' action='index.php?p=7' method='post'>
      <label>Titulo:</label>
      <select name='titulo'>";
        while ($row = mysqli_fetch_row($result)){
            echo "<option>{$row[0]}</option> \n";
          }
      echo" </select>
      <input type='submit' name='borrarBiografia' value='Borrar entrada'>
      <input type='submit' name='volverBiografia' value='Volver'>

      </form></div></div>";
    } else if ( isset( $_POST['borrarBiografia'] ) ){
      $titulo = $_POST["titulo"];

      //Borrar la foto del componente de la carpeta de imagenes
      $consulta = sprintf("SELECT imagen FROM biografia WHERE titulo='%s' ",
                      mysqli_real_escape_string($conn,$titulo) );
      $result = mysqli_query($conn,$consulta);
      $row = mysqli_fetch_row($result);
      unlink('./imagenes/'.$row[0]);

      // Borrar el componente indicado
      $consulta  = sprintf("DELETE FROM biografia WHERE titulo='%s'",mysqli_real_escape_string($conn,$titulo));

      $resultado = mysqli_query($conn, $consulta);

      //Añadir ocurrencia al log
      $fechaLog = getdate();
      $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
      $fnombre = sprintf("./protected/log.txt");
      $cad = sprintf("{$fechaLog2}: Entrada de biografia con titulo {$titulo} borrada por {$_SESSION['usuario']}\n");
      file_put_contents($fnombre,$cad,FILE_APPEND);

      echo<<<HTML
      <div class='main'>
        <div class='borrarBiografia'>
          <h3>Entrada borrada con éxito</h3>
          <form class="formOperacion" action="index.php?p=2" method="post">
            <input type="submit" name="volverBiografia"value="Volver a biografía">
          </form>
        </div>
        </div>

HTML;

    } else if ( isset( $_POST['volverBiografia'] ) ){
      header("Location: ./index.php?p=2");
    }

  }

  //Modificar entrada de biografia
  function modificarBiografia($conn){

    if( isset( $_POST['botonModificarBiografia'] ) ){
      $result = mysqli_query($conn,"SELECT titulo FROM biografia");

      echo<<<HTML
      <div class='main'>
        <div class='modificarBiografia'>
      <h2>Modificando entrada de biografía:</h2>
      <form enctype="multipart/form-data" class="formOperacion" action='index.php?p=7' method='post'>
      <h3>Seleccione la entrada a modificar</h3>

      <select name='titulou'>
HTML;
        while ($row = mysqli_fetch_row($result)){
            echo "<option>{$row[0]}</option> \n";
          }
      echo<<<HTML
      </select>
      <h2>Introduzca la nueva información de la entrada (solo la que quiera modificar)</h2>
      <label>Imagen:</label>
      <input type="file" name="imagenBiografia">
      <label>Titulo:</label>
      <input type="text" name="tituloBiografia">
      <label>Texto:</label>
      <input type="text" name="textoBiografia">

      <input type='submit' name='modificarBiografia' value='Modificar entrada'>
      <input type='submit' name='volverBiografia' value='Volver'>

      </form>
    </div>
    </div>
HTML;

  } else if ( isset( $_POST['modificarBiografia'] ) ){

    //Comprobar si hay una entrada con ese titulo
    $consulta  = sprintf("SELECT titulo from biografia WHERE titulo='{$_POST["tituloBiografia"]}'");
    $resultado = mysqli_query($conn, $consulta);

    if (mysqli_num_rows($resultado)>0) {

      $result = mysqli_query($conn,"SELECT titulo FROM biografia");

      echo<<<HTML
      <div class='main'>
        <div class='modificarBiografia'>
      <h2>Modificando entrada de biografía:</h2>
      <form enctype="multipart/form-data" class="formOperacion" action='index.php?p=7' method='post'>
      <h3>Seleccione la entrada a modificar</h3>

      <select name='titulou'>
HTML;
        while ($row = mysqli_fetch_row($result)){
            echo "<option>{$row[0]}</option> \n";
          }
      echo<<<HTML
      </select>
      <h2>Introduzca la nueva información de la entrada (solo la que quiera modificar)</h2>
      <label>Imagen:</label>
      <input type="file" name="imagenBiografia">
      <label>Titulo:</label>
      <input type="text" name="tituloBiografia">
      <h3>Ya existe una entrada con ese titulo, debe ser diferente</h3>
      <label>Texto:</label>
      <input type="text" name="textoBiografia" value="{$_POST["textoBiografia"]}" >

      <input type='submit' name='modificarBiografia' value='Modificar entrada'>
      <input type='submit' name='volverBiografia' value='Volver'>

      </form>
    </div>
    </div>
HTML;

    } else {

      $titulo = $_POST["titulou"];
      $tituloa = $_POST["titulou"];
      $consulta = sprintf("SELECT * FROM biografia WHERE titulo='%s' ",
                      mysqli_real_escape_string($conn,$titulo) );
      $result = mysqli_query($conn,$consulta);
      $row = mysqli_fetch_row($result);

        if( !empty($_POST["tituloBiografia"]) ){
          $titulo = $_POST["tituloBiografia"];
        }

        if( empty($_POST["textoBiografia"]) ){
          $textoBiografia = $row[2];
        }else $textoBiografia = $_POST["textoBiografia"];

        if( !empty($_FILES['imagenBiografia']['name']) ) {
          unlink('./imagenes/'.$row[0]);
          $imagenBiografia = $_FILES['imagenBiografia']['name'];
          //Mover la imagen a la carpeta de descargas
            $path="./imagenes/";
            $name = $_FILES['imagenBiografia']['name'];//Name of the File
            $temp = $_FILES['imagenBiografia']['tmp_name'];
            move_uploaded_file($temp, $path . $name);
          } else {
            $imagenBiografia = $row[0];
          }

        //Actualizar la entrada

        $consulta  = sprintf("UPDATE biografia SET imagen='%s', titulo='%s', texto='%s' WHERE titulo='%s' ",
                        mysqli_real_escape_string($conn,$imagenBiografia),
                        mysqli_real_escape_string($conn,$titulo),
                        mysqli_real_escape_string($conn,$textoBiografia),
                        mysqli_real_escape_string($conn,$tituloa));
        $resultado = mysqli_query($conn, $consulta);

        //Añadir ocurrencia al log
        $fechaLog = getdate();
        $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
        $fnombre = sprintf("./protected/log.txt");
        $cad = sprintf("{$fechaLog2}: Entrada de biografia con titulo {$titulo} modificada por {$_SESSION['usuario']}\n");
        file_put_contents($fnombre,$cad,FILE_APPEND);

        echo<<<HTML

        <div class='main'>
          <div class='modificarBiografia'>
            <h3>Entrada modificada con éxito</h3>
            <form class="formOperacion" action="index.php?p=2" method="post">
              <input type="submit" name="volverBiografia"value="Volver a biografia">
            </form>
          </div>
          </div>

HTML;
  }

      } else if ( isset( $_POST['volverBiografia'] ) ){
        header("Location: ./index.php?p=2");
      }
  }

 ?>
