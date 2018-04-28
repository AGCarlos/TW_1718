<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title id="titulo">Resultado búsqueda libro UGR</title>
</head>
<body>
<?php

      // create curl resource
      $curl = curl_init();

      $libro = $_GET['libro'];
      // set url
      curl_setopt($curl, CURLOPT_URL, 'http://bencore.ugr.es/iii/encore/search?formids=target&lang=spi&suite=def&reservedids=lang%2Csuite&submitmode=&submitname=&target='.urlencode($libro) );

      //return the transfer as a string
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

      curl_setopt($curl, CURLOPT_USERAGENT	, "Dark Secret Ninja/1.0");


      // $output contains the output string
      $output = curl_exec($curl);

      // close curl resource to free up system resources
      curl_close($curl);

      preg_match_all('/<span\sclass="title"><a\sid="recordDisplayLink2Component\w*[0-9]*"\shref=".*">\s*(.*)<\/a><\/span>\s*<span\sclass="title"><span\sclass="additionalFields\scustomSecondaryText">(.*)<\/span><\/span>/',$output,$patron);
      //print_r($patron);

      echo "<h2>Titulo y autor de los libros relacionados con $libro</h2> \n";
      for ($i=0; $i<count($patron[0]); $i++)
        echo $patron[0][$i], '<br>';
?>
</body>
</html>
