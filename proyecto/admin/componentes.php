<?php

 //OPERACIONES DE COMPONENTE
 //Añadir un componente
 function añadirComponente($conn){


   if( isset( $_POST['botonAñadirComponente'] ) ){
     echo<<<HTML
     <div class='main'>
       <div class='aniadirComponente'>

         <form enctype="multipart/form-data" class="formOperacion" action="index.php?p=6" method="post">
           <h2>Añadiendo componente</h2>
           <label>Nombre:</label>
           <input type="text" name="nombreComponente" required>
           <label>Fecha de nacimiento:</label>
           <input type="text" name="fechaComponente" required>
           <label>Nacionalidad:</label>
           <input type="text" name="nacionalidadComponente" required>
           <label>Imagen:</label>
           <input type="file" name="imagenComponente" required>
           <label>Breve reseña del componente:</label>
           <input type="text" name="reseñaComponente" required>

           <input type="submit" name="aniadiendoComponente" value="Añadir">
         </form>
         <form class="formOperacion" action="index.php?p=6" method="post">
           <input type="submit" name="volverComponente" value="Volver">
         </form>
        </div>
      </div>

HTML;
} else if ( isset( $_POST['aniadiendoComponente'] ) ){

    $nombreComponente = $_POST["nombreComponente"];
    $fechaComponente = $_POST["fechaComponente"];
    $nacionalidadComponente = $_POST["nacionalidadComponente"];
    $imagenComponente = $_FILES['imagenComponente']['name'];
    $reseñaComponente = $_POST["reseñaComponente"];

  //Comprobar si hay un componente con ese nombre
  $consulta  = sprintf("SELECT nombre from componente WHERE nombre='{$nombreComponente}'");
  $resultado = mysqli_query($conn, $consulta);

  if (mysqli_num_rows($resultado)>0) {

    echo<<<HTML
    <div class='main'>
      <div class='aniadirComponente'>

        <form enctype="multipart/form-data" class="formOperacion" action="index.php?p=6" method="post">
          <h2>Añadiendo componente</h2>
          <label>Nombre:</label>
          <input type="text" name="nombreComponente" required>
          <h3>Ya existe un componente con ese nombre, debe ser diferente</h3>
          <label>Fecha de nacimiento:</label>
          <input type="text" name="fechaComponente"  value="{$fechaComponente}" required>
          <label>Nacionalidad:</label>
          <input type="text" name="nacionalidadComponente" value="{$nacionalidadComponente}" required>
          <label>Imagen:</label>
          <input type="file" name="imagenComponente" required>
          <label>Breve reseña del componente:</label>
          <input type="text" name="reseñaComponente" value="$reseñaComponente" required>

          <input type="submit" name="aniadiendoComponente" value="Añadir">
        </form>
        <form class="formOperacion" action="index.php?p=6" method="post">
          <input type="submit" name="volverComponente" value="Volver">
        </form>
       </div>
     </div>

HTML;

  } else {

    mysqli_set_charset($conn,"utf8");


    //Mover la imagen a la carpeta de descargas
      $path="./imagenes/";
      $name = $_FILES['imagenComponente']['name'];//Name of the File
      $temp = $_FILES['imagenComponente']['tmp_name'];
      move_uploaded_file($temp, $path . $name);

    // Realizar inserción del nuevo componente
    $consulta  = sprintf("INSERT INTO componente(nombre,fechaNac,lugarNac,imagen,reseña) VALUES('%s','%s','%s','%s','%s');",
                    mysqli_real_escape_string($conn,$nombreComponente),
                    mysqli_real_escape_string($conn,$fechaComponente),
                    mysqli_real_escape_string($conn,$nacionalidadComponente),
                    mysqli_real_escape_string($conn,$imagenComponente),
                    mysqli_real_escape_string($conn,$reseñaComponente));
    $resultado = mysqli_query($conn, $consulta);

    //Añadir ocurrencia al log
    $fechaLog = getdate();
    $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
    $fnombre = sprintf("./protected/log.txt");
    $cad = sprintf("{$fechaLog2}: Componente {$nombreComponente} aniadido por {$_SESSION['usuario']}\n");
    file_put_contents($fnombre,$cad,FILE_APPEND);

    echo<<<HTML
    <div class='main'>
      <div class='aniadirComponente'>
        <h3>Componente añadido con éxito</h3>
        <form class="formOperacion" action="index.php?p=0" method="post">
          <input type="submit" name="volverComponentes"value="Volver a componentes">
        </form>
      </div>
    </div>

HTML;

  }

} else if ( isset( $_POST['volverComponente'] ) ){
  header("Location: ./index.php?p=0");
}

 }

 function borrarComponente($conn){
  if ( isset ( $_POST['botonBorrarComponente'] ) ){
    $result = mysqli_query($conn,"SELECT nombre FROM componente");

    echo "
    <div class='main'>
      <div class='borrarComponente'>
        <h3>Seleccione el nombre del componente a borrar:</h3>
        <form class='formOperacion' action='index.php?p=6' method='post'>
        <label>Nombre:</label>
        <select name='nombre'>";
          while ($row = mysqli_fetch_row($result)){
              echo "<option>{$row[0]}</option> \n";
            }
        echo" </select>
        <input type='submit' name='borrarComponente' value='Borrar componente'>
        <input type='submit' name='volverComponente' value='Volver'>
        </form>
      </div>
    </div>";
  } else if ( isset( $_POST['borrarComponente'] ) ){
    $nombre = $_POST["nombre"];

    //Borrar la foto del componente de la carpeta de imagenes
    $consulta = sprintf("SELECT * FROM componente WHERE nombre='%s' ",
                    mysqli_real_escape_string($conn,$nombre) );
    $result = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_row($result);
    unlink('./imagenes/'.$row[3]);
    // Borrar el componente indicado
    $consulta  = sprintf("DELETE FROM componente WHERE nombre='%s'",
    mysqli_real_escape_string($conn,$nombre));
    $resultado = mysqli_query($conn, $consulta);

    //Añadir ocurrencia al log
    $fechaLog = getdate();
    $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
    $fnombre = sprintf("./protected/log.txt");
    $cad = sprintf("{$fechaLog2}: Componente {$nombre} borrado por {$_SESSION['usuario']}\n");
    file_put_contents($fnombre,$cad,FILE_APPEND);

    echo<<<HTML
    <div class='main'>
      <div class='borrarComponente'>
        <h3>Componente borrado con éxito</h3>
        <form class="formOperacion" action="index.php?p=0" method="post">
          <input type="submit" name="volverComponentes"value="Volver a componentes">
        </form>
      </div>
    </div>
HTML;

  } else if ( isset( $_POST['volverComponente'] ) ){
    header("Location: ./index.php?p=0");
  }
}

function modificarComponente($conn){

  if( isset( $_POST['botonModificarComponente'] ) ){
    $result = mysqli_query($conn,"SELECT nombre FROM componente");

    echo<<<HTML
    <div class='main'>
      <div class='modificarComponente'>
        <h2>Modificando componente:</h2>
        <form enctype="multipart/form-data" class="formOperacion" action='index.php?p=6' method='post'>
        <h3>Seleccione el componente a modificar</h3>

        <select name='nombreu'>
HTML;
      while ($row = mysqli_fetch_row($result)){
          echo "<option>{$row[0]}</option> \n";
        }
    echo<<<HTML
        </select>
        <h3>Introduza la nueva información del componente (solo la que desea modificar)</h3>
        <label>Nombre:</label>
        <input type="text" name="nombreComponente">
        <label>Fecha de nacimiento:</label>
        <input type="text" name="fechaComponente">
        <label>Nacionalidad:</label>
        <input type="text" name="nacionalidadComponente">
        <label>Imagen:</label>
        <input type="file" name="imagenComponente">
        <label>Breve reseña del componente:</label>
        <input type="text" name="reseñaComponente">

        <input type='submit' name='modificarComponente' value='Modificar componente'>
        <input type='submit' name='volverComponente' value='Volver'>

        </form>
      </div>
    </div>
HTML;

} else if ( isset( $_POST['modificarComponente'] ) ){

    //Comprobar si hay un componente con ese nombre
    $consulta  = sprintf("SELECT nombre from componente WHERE nombre='{$_POST["nombreComponente"]}'");
    $resultado = mysqli_query($conn, $consulta);

    if (mysqli_num_rows($resultado)>0) {

      $result = mysqli_query($conn,"SELECT nombre FROM componente");

      echo<<<HTML
      <div class='main'>
        <div class='modificarComponente'>
          <h2>Modificando componente:</h2>
          <form enctype="multipart/form-data" class="formOperacion" action='index.php?p=6' method='post'>
          <h3>Seleccione el componente a modificar</h3>

          <select name='nombreu'>
HTML;
        while ($row = mysqli_fetch_row($result)){
            echo "<option>{$row[0]}</option> \n";
          }
      echo<<<HTML
          </select>
          <h3>Introduza la nueva información del componente (solo la que desea modificar)</h3>
          <label>Nombre:</label>
          <input type="text" name="nombreComponente">
          <h3>Ya existe un componente con ese nombre, debe ser diferente</h3>
          <label>Fecha de nacimiento:</label>
          <input type="text" name="fechaComponente" value="{$_POST["fechaComponente"]}" >
          <label>Nacionalidad:</label>
          <input type="text" name="nacionalidadComponente" value="{$_POST["nacionalidadComponente"]}" >
          <label>Imagen:</label>
          <input type="file" name="imagenComponente">
          <label>Breve reseña del componente:</label>
          <input type="text" name="reseñaComponente" value="{$_POST["reseñaComponente"]}" >

          <input type='submit' name='modificarComponente' value='Modificar componente'>
          <input type='submit' name='volverComponente' value='Volver'>

          </form>
        </div>
      </div>
HTML;


    } else {

    $nombre = $_POST["nombreu"];
    $nombrea = $_POST["nombreu"];
    $consulta = sprintf("SELECT * FROM componente WHERE nombre='%s' ",
                    mysqli_real_escape_string($conn,$nombre) );
    $result = mysqli_query($conn,$consulta);
    $row = mysqli_fetch_row($result);

      if( !empty($_POST["nombreComponente"]) ){
        $nombre = $_POST["nombreComponente"];
      }

      if( empty($_POST["fechaComponente"]) ){
        $fechaComponente = $row[1];
      }else $fechaComponente = $_POST["fechaComponente"];

      if( empty($_POST["nacionalidadComponente"]) ){
        $nacionalidadComponente = $row[2];
      }else $nacionalidadComponente = $_POST["nacionalidadComponente"];

      if( !empty($_FILES['imagenComponente']['name']) ) {
        unlink('./imagenes/'.$row[3]);
        $imagenComponente = $_FILES['imagenComponente']['name'];
        //Mover la imagen a la carpeta de descargas
          $path="./imagenes/";
          $name = $_FILES['imagenComponente']['name'];//Name of the File
          $temp = $_FILES['imagenComponente']['tmp_name'];
          move_uploaded_file($temp, $path . $name);
        } else {
          $imagenComponente = $row[3];
        }

      if( empty($_POST["reseñaComponente"]) ){
        $reseñaComponente = $row[4];
      }else $reseñaComponente = $_POST["reseñaComponente"];

      //Actualizar el componente

      $consulta  = sprintf("UPDATE componente SET nombre='%s', fechaNac='%s', lugarNac='%s', imagen='%s', reseña='%s' WHERE nombre='%s' ",
                      mysqli_real_escape_string($conn,$nombre),
                      mysqli_real_escape_string($conn,$fechaComponente),
                      mysqli_real_escape_string($conn,$nacionalidadComponente),
                      mysqli_real_escape_string($conn,$imagenComponente),
                      mysqli_real_escape_string($conn,$reseñaComponente),
                      mysqli_real_escape_string($conn,$nombrea));
      $resultado = mysqli_query($conn, $consulta);

      //Añadir ocurrencia al log
      $fechaLog = getdate();
      $fechaLog2 = sprintf("{$fechaLog['year']}-{$fechaLog['mon']}-{$fechaLog['mday']} {$fechaLog['hours']}:{$fechaLog['minutes']}:{$fechaLog['seconds']}");
      $fnombre = sprintf("./protected/log.txt");
      $cad = sprintf("{$fechaLog2}: Componente {$nombre} modificado por {$_SESSION['usuario']}\n");
      file_put_contents($fnombre,$cad,FILE_APPEND);

      echo<<<HTML
      <div class='main'>
        <div class='modificarComponente'>
          <h3>Componente modificado con éxito</h3>
          <form class="formOperacion" action="index.php?p=0" method="post">
            <input type="submit" name="volverComponentes"value="Volver a componentes">
          </form>
        </div>
      </div>
HTML;
    }

    } else if ( isset( $_POST['volverComponente'] ) ){
      header("Location: ./index.php?p=0");
    }
}


 ?>
