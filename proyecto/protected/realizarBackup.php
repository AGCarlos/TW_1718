<?php
session_start();

require_once('./dbcredenciales.php');

if (isset($_SESSION["tipoUsuario"])) {
   if ($_SESSION["tipoUsuario"] == "admin") {
     //Realizar conexión a la BBDD
     $db = mysqli_connect(DB_HOST,DB_USER,DB_PASSWD,DB_DATABASE);
     if (!$db) {
       echo "Ocurrió un error.\n";
       exit;
     }
     mysqli_set_charset($db,"utf8");

      // Obtener listado de tablas
      $tablas = array();
      $result = mysqli_query($db,'SHOW TABLES');
      while ($row = mysqli_fetch_row($result))
        $tablas[] = $row[0];

      // Salvar cada tabla
      $salida = '';
      foreach ($tablas as $tab) {
          $result = mysqli_query($db,'SELECT * FROM '.$tab);
          $num = mysqli_num_fields($result);
          $salida .= 'DROP TABLE '.$tab.';';
          $row2 = mysqli_fetch_row(mysqli_query($db,'SHOW CREATE TABLE '.$tab));
          $salida .= "\n\n".$row2[1].";\n\n"; // row2[0]=nombre de tabla
          while ($row = mysqli_fetch_row($result)) {
            $salida .= 'INSERT INTO '.$tab.' VALUES(';
            for ($j=0; $j<$num; $j++) {
              $row[$j] = addslashes($row[$j]);
              $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
              if (isset($row[$j]))
                $salida .= '"'.$row[$j].'"';
              else
                $salida .= '""';
              if ($j < ($num-1)) $salida .= ',';
            }
            $salida .= ");\n";
          }
          $salida .= "\n\n\n";
        }

        //Escribir el backup en un fichero SQL
        file_put_contents("./backup.sql", $salida);

        //Descargar BackUp
        header("Content-disposition: attachment; filename=backup.sql");
        header("Content-type: application/sql");
        readfile("./backup.sql");

      }
    }

 ?>
