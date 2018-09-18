 <!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../estilaso.css">
  <title id="titulo">Resultado búsqueda libro UGR</title>
</head>
<body>
<?php
  if(!isset($_POST["enviar"])){
      echo <<<HTML
        <h1>Buscador de libros UGR</h1>
        <form action="curl.php" method="post">
          <fieldset>
            <legend>Introduza el libro que desea buscar:</legend>
            <input type="text" name="libro">
            <input type="submit" name ="enviar" value="Enviar datos" />
          </fieldset>
        </form>
HTML;
}else {
      // create curl resource
      $curl = curl_init();

      $libro = htmlentities($_POST['libro']);
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

      echo "<h2>Titulo y autor de los libros relacionados con $libro</h2> \n <ul>";
      for ($i=0; $i<count($patron[1]); $i++)
        echo "<li>".$patron[1][$i]." ".$patron[2][$i]."</li>";
      }
      echo "</ul>";
?>
</body>
</html>
