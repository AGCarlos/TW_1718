<?php

function backUp($db){

ob_clean();
flush();

}

function restaurarBACKUP($f,$db){

  mysqli_query($db,'SET FOREIGN_KEY_CHECKS=0');

  $result = mysqli_query($db,'SHOW TABLES');
  while ($row = mysqli_fetch_row($result))
    mysqli_query($db,'DELETE * FROM '.$row[0]);

  $error = '';
  $sql = file_get_contents($f);
  $queries = explode(';',$sql);
  foreach ($queries as $q) {
    if (!mysqli_query($db,$q))
      $error .= mysqli_error($db);
  }

  mysqli_query($db,'SET FOREIGN_KEY_CHECKS=1');

}

function borrarBBDD($db){

  //Vaciamos y borramos todas las tablas
  mysqli_query($db,"DELETE FROM componente");
  mysqli_query($db,"DELETE FROM biografia");
  mysqli_query($db,"DELETE FROM disco");
  mysqli_query($db,"DELETE FROM cancion");
  mysqli_query($db,"DELETE FROM concierto");
  mysqli_query($db,"DELETE FROM pedido");
  mysqli_query($db,"DELETE FROM usuariosProyecto WHERE login != '{$_SESSION['usuario']}'");
}


 ?>
