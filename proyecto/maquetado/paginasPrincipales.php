<?php


 // Inicio del fichero html
 function HTMLinicio($titulo) {

   echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
  <title>$titulo</title>
  <link rel="shortcut icon" href="./imagenes/favicon.ico" type="image/x-icon">
  <link rel="animated icon" href="./imagenes/favicon.ico" type="image/x-icon">
  <meta charset="utf-8">
  <link rel="stylesheet" href="./maquetado/estilo.css">
</head>
HTML;
 }

 //Header común de la página
 function HTMLheader(){

   echo <<<HTML

   <body>

     <div class="grid-container">

       <div class="header">

         <div class="header-left">
           <a href="./index.php?p=0"><img src="./imagenes/Deathbat.jpg" alt="logo"></a>
         </div>

         <div class="header-right">

           <nav>
             <ul>
               <li><a href="./index.php?p=0">INICIO</a></li>
               <li><a href="./index.php?p=1">NOTICIAS</a></li>
               <li><a href="./index.php?p=2">BIOGRAFÍA</a></li>
               <li><a href="./index.php?p=3">DISCOGRAFÍA</a></li>
               <li><a href="./index.php?p=4">CONCIERTOS</a></li>
               <li id="fanclub"><a href="http://www.deathbatnation.com/" target="_blank">FAN CLUB</a></li>

             </ul>
           </nav>
           <div class="redes">
             <ul>
               <li><a href="https://www.facebook.com/AvengedSevenfold" target="_blank"><img src="./imagenes/facebook.png" alt="facebook"></a></li>
               <li><a href="https://twitter.com/TheOfficialA7X" target="_blank"><img src="./imagenes/twitter.png" alt="twitter"></a></li>
               <li><a href="https://www.instagram.com/avengedsevenfold/" target="_blank"><img src="./imagenes/instagram.png" alt="instagram"></a></li>
             </ul>
           </div>

           <div class="buscador">

           </div>

         </div>

       </div>

HTML;

 }

 //Página principal de inicio
 function HTMLmain($conn) {

   echo <<<HTML
   <div class="main">
     <!--
           <div class="noticiaspeq">
             <p>Parte noticias</p>
           </div>

   -->
     <div class="informacion">
       <div class="img-c"> <img src="./imagenes/fotoportada.jpg" alt="porta7x"> </div>
       <h2>ACERCA DEL GRUPO</h2>
       <p>&emsp;Avenged Sevenfold (frecuentemente abreviado como A7X) es una banda estadounidense de heavy metal originaria de Huntington Beach, California, fundada en 1999. Avenged Sevenfold comenzó como una banda de género metalcore con su álbum debut
         Sounding the Seventh Trumpet de 2001 y más tarde con su segundo álbum Waking The Fallen de 2003, en el que The Rev y M. Shadows utilizaron el estilo vocal screaming en muchas canciones de este álbum. La banda es principalmente conocida por la
         versatilidad de sus estilos musicales, sus dramáticas portadas en cada uno de sus álbumes y su logotipo: Deathbat.<br><br> &emsp;Sus integrantes son M. Shadows (vocalista), Synyster Gates (guitarrista líder y coros), Zacky Vengeance (guitarrista
         rítmico y coros), Johnny Christ (bajista).​ Anteriormente, el baterista y vocalista era The Rev hasta su muerte en diciembre de 2009. Asimismo, el ex-baterista de Dream Theater, Mike Portnoy, entró temporalmente a Avenged Sevenfold para ayudar
         a sus miembros en sus próximos conciertos, incluyendo la gira Nightmare After Christmas durante el 2010 y salió de la banda el 16 de diciembre de ese mismo año tras concluirla.<br><br> &emsp;Después del lanzamiento de su tercer álbum de estudio
         y primer éxito City of Evil de 2005, Avenged Sevenfold fue transformando su estilo hacia un género más limpio y sin gritos. Posteriormente lanzaron el DVD All Excess que cuenta la historia de la banda desde su formación hasta 2007. En julio
         de 2010 publicaron su quinto álbum de estudio titulado Nightmare que debutó en la posición #1 en la cartelera Billboard 200.​ Éste fue el último álbum de la banda con su fundador The Rev, ya que sus grabaciones vocales fueron incluidas,​ mientras
         que sus demos en la batería fueron reproducidos en esencia por Mike Portnoy. El 11 de abril de 2011 la banda ganó el premio al mejor grupo en directo y más dedicado a los fanáticos en el festival Revolver Golden Gods. El 26 de agosto de 2013
         lanzaron su más reciente álbum de estudio Hail to the King​ que marcó el debut del baterista Arin Ilejay y también el segundo logró consecutivo de la banda en alcanzar el primer puesto en el conteo de Billboard 200.<br><br> &emsp;Hasta la fecha
         Avenged Sevenfold ha vendido más de 8 millones de discos en todo el mundo.​</p>

       <h2>COMPONENTES</h2>
HTML;

//Consultamos la tabla de componentes y los mostramos todos
$result = mysqli_query($conn,"SELECT * FROM componente");

if($result != false){
  while ($row = mysqli_fetch_row($result)){
    //$image = sprintf("./imagenes/{$row[3]}")
    echo<<<HTML

    <div class="componente">
      <div class="col col-1">
        <img id="img-comp" src="./imagenes/{$row[3]}" atl="brooks">
      </div>
      <div class="col col-2">
        <h3>{$row[0]}</h3>
        <ul>
          <p>Nacimiento</p>
          <li>{$row[1]}</li>
          <p>Nacionalidad</p>
          <li>{$row[2]} <img src="./imagenes/USflag.png" alt="USflag"> </li>
        </ul>
      </div>
    </div>
    <div class="bio-comp">
      <h3>BIOGRAFÍA</h3>
      <p> {$row[4]} </p>
    </div>

HTML;
  }
}

if( isset( $_SESSION['tipoUsuario']) ){
  if ( $_SESSION['tipoUsuario'] == "admin" ){
    echo<<<HTML
    <div class="botonesAdministrador">
      <h3>Controles de administrador</h3>
       <form class="añadirComponente" action="index.php?p=6" method="post">
         <input type="submit" name="botonAñadirComponente" value="Añadir componente">
         <input type="submit" name="botonModificarComponente" value="Modificar componente">
         <input type="submit" name="botonBorrarComponente" value="Borrar componente">
       </form>
    </div>
HTML;
   }
}
    echo<<<HTML
       <h2>ÉXITOS</h2>
       <ul class="exitos">
         <li><a href="https://www.youtube.com/watch?v=IHS3qJdxefY" target="_blank">Bat country</a></li>
         <li><a href="https://www.youtube.com/watch?v=Fi_GN1pHCVc" target="_blank">Almost Easy</a></li>
         <li><a href="https://www.youtube.com/watch?v=VurhzANQ_B0" target="_blank">A little piece of heaven</a></li>
         <li><a href="https://www.youtube.com/watch?v=94bGzWyHbu0" target="_blank">Nightmare</a></li>
         <li><a href="https://www.youtube.com/watch?v=Jgk3u44W2i4" target="_blank">This means war</a></li>
         <li><a href="https://www.youtube.com/watch?v=fBYVlFXsEME" target="_blank">The stage</a></li>
       </ul>

     </div>

   </div>

HTML;

 }

 function HTMLbiografia($conn){

   echo<<<HTML
   <div class="main">
     <div class="bio">
       <h2>Biografía</h2>
HTML;

  //Consultamos la tabla de biografia y los mostramos todas las entradas
  $result = mysqli_query($conn,"SELECT * FROM biografia");

  if($result != false){
    while ($row = mysqli_fetch_row($result)){
      echo<<<HTML

      <div class="bio-comp">
        <div class="img-p"> <img src="./imagenes/{$row[0]}" alt="portabio"> </div>
        <h3>{$row[1]}</h3>
        <p>
          &emsp;{$row[2]}</p>
      </div>

HTML;
    }
  }

if( isset( $_SESSION['tipoUsuario']) ){
  if ( $_SESSION['tipoUsuario'] == "admin" ){
   echo<<<HTML
     <div class="botonesAdministrador">
       <h3>Controles de administrador</h3>
       <form class="botonesBiografia" action="index.php?p=7" method="post">
         <input type="submit" name="botonAniadirBiografia" value="Crear entrada">
         <input type="submit" name="botonBorrarBiografia" value="Borrar entrada">
         <input type="submit" name="botonModificarBiografia" value="Modificar entrada">
       </form>
     </div>
HTML;
  }

  echo"</div></div>";

} else echo"</div></div>";

 }

 function HTMLdiscografia($conn){

   if ( isset($_POST['buscarDiscoFechas']) ){

        echo<<<HTML
        <div class="main">
          <div class="disco">
            <h3> Discos publicados entre {$_POST['fecha1Buscar']} y {$_POST['fecha2Buscar']}</h3>
            <ul>
HTML;
     $result = mysqli_query($conn, "SELECT * FROM disco WHERE año >= {$_POST['fecha1Buscar']} AND año <= {$_POST['fecha2Buscar']} ");
     if ($result != false) {
        while ($row = mysqli_fetch_row($result)) {
            echo "<li>{$row[1]} ({$row[2]})</li>";
          }
     }
     echo<<<HTML
       </ul>
       <form id="formBusqueda" class="formOperacion" action="index.php?p=3" method="post">
         <input type="submit" name="" value="Volver">
       </form>
      </div>
    </div>
HTML;


   } else if ( isset($_POST['buscarDisco']) ){
       echo<<<HTML
       <div class="main">
         <div class="disco">
           <h3> Discos con titulo {$_POST['discoBuscar']}</h3>
           <ul>
HTML;

          $result = mysqli_query($conn, "SELECT titulo FROM disco WHERE titulo LIKE '%{$_POST['discoBuscar']}%'");
          if ($result != false) {
             while ($row = mysqli_fetch_row($result)) {
                 echo "<li>{$row[0]}</li>";
               }
               echo "</ul>";
          }

          echo "<h3> Discos que contienen canciones con titulo {$_POST['discoBuscar']}</h3>";

          $result = mysqli_query($conn, "SELECT * FROM cancion WHERE titulo LIKE '%{$_POST['discoBuscar']}%'");
          if ($result != false) {
              echo "<ul>";
             while ($row = mysqli_fetch_row($result)) {
                 echo "<li>{$row[0]} con la cancion {$row[1]}</li>";
               }
               echo "</ul>";
          }

      echo<<<HTML
        <form id="formBusqueda" class="formOperacion" action="index.php?p=3" method="post">
          <input type="submit" name="" value="Volver">
        </form>
       </div>
      </div>
HTML;
   } else {

   echo<<<HTML
   <div class="main">
     <div class="disco">

  <h2 id="busqueda">Búsqueda de discos:</h2>
  <form id="formBusqueda" class="formOperacion" action="index.php?p=3" method="post">
  <label>Buscar disco por nombre o temas incluídos:</label>
  <input type="text" name="discoBuscar" placeholder="Nombre de disco o canción">
  <input type="submit" name="buscarDisco" value="Buscar por nombre">
  </form>
  <form id="formBusqueda" class="formOperacion" action="index.php?p=3" method="post">
  <label>Selecciona dos fechas entre las que buscar el disco:</label>
  <select name="fecha1Buscar" required>
HTML;

  $result = mysqli_query($conn, "SELECT año FROM disco");
  if ($result != false) {
     while ($row = mysqli_fetch_row($result)) {
         echo "<option value='{$row[0]}'>{$row[0]}</option>";
  }
  }
   echo<<<HTML
  </select>
  <select name="fecha2Buscar" required>
HTML;
  $result = mysqli_query($conn, "SELECT año FROM disco");
  if ($result != false) {
  while ($row = mysqli_fetch_row($result)) {
  echo "<option value='{$row[0]}'>{$row[0]}</option>";
  }
  }
   echo<<<HTML
  </select>
  <input type="submit" name="buscarDiscoFechas" value="Buscar por fechas">

  </form>
       <h2>Discografía</h2>
HTML;

//Consultamos la tabla de discos y los mostramos todos
$result = mysqli_query($conn,"SELECT * FROM disco ORDER BY año DESC");

if($result != false){
  while ($row = mysqli_fetch_row($result)){
    echo<<<HTML

    <div class="album">

      <div class="img-d"><img src="./imagenes/{$row[0]}" alt="d{$row[0]}"></div>
      <h2>{$row[1]}</h2>
      <div class="discoCompra">
        <h3>Publicado en {$row[2]}</h3>
        <h3 id="precio">Precio: {$row[3]}€</h3>
        <form action="index.php" method="post">
          <input type="submit" name="comprarDisco" value="Comprar">
          <input type="hidden" name="discoCompraTitulo" value="{$row[1]}">
          <input type="hidden" name="discoCompraPrecio" value="{$row[3]}">
        </form>
      </div>

HTML;

  //Consultamos las canciones y mostramos las correspondientes al discoCompra
  $consulta2 = sprintf("SELECT * FROM cancion WHERE disco='%s' ",
                  mysqli_real_escape_string($conn,$row[1]) );
  $result2 = mysqli_query($conn,$consulta2);

  echo<<<HTML
  <div class="table">

    <table>
      <tr>
        <th>N.º</th>
        <th>Nombre</th>
        <th>Duración</th>
      </tr>
HTML;
$i=0;
  if($result2 != false){
    while ($row = mysqli_fetch_row($result2)){
      $i = $i + 1;
      echo<<<HTML
          <tr>
            <td>{$i}.</td>
            <td>«{$row[1]}»</td>
            <td>{$row[2]}</td>
          </tr>
HTML;
    }
    echo "</table>";
  }
  echo<<<HTML
      </div>
    </div>
HTML;

  }
}


if( isset( $_SESSION['tipoUsuario']) ){
  if ( $_SESSION['tipoUsuario'] == "admin" ){
   echo<<<HTML
     <div class="botonesAdministrador">
       <h3>Controles de administrador</h3>
       <form class="botonesDiscografia" action="index.php?p=8" method="post">
         <input type="submit" name="botonAniadirDisco" value="Crear álbum">
         <input type="submit" name="botonBorrarDisco" value="Borrar álbum">
         <input type="submit" name="botonModificarDisco" value="Modificar álbum">
       </form>
     </div>
HTML;
  }
}

echo<<<HTML
  </div>
  </div>
HTML;
  }
 }

 function HTMLnoticias(){

   echo<<<HTML

   <div class="main">

     <div class="noticia">

       <div class="img-c"> <img src="./imagenes/pink.jpg" alt="porta7x"> </div>
       <h2>Avenged Sevenfold versionan "As Tears Go By" de The Rolling Stones</h2>
       <h3>8 de Septiembre de 2017</h3>
       <p>Avenged Sevenfold siguen lanzando material complementario a su último álbum "The Stage".<br> En esta ocasión lo hacen con una versión de 'As Tears Go By' de The Rolling Stones.
       </p>

     </div>

     <div class="noticia">

       <div class="img-c"> <img src="./imagenes/shout.jpg" alt="porta7x"> </div>
       <h2>Avenged Sevenfold versiona a Mr. Bungle</h2>
       <h3>30 de Junio de 2017</h3>
       <p>Tal como anunció Avenged Sevenfold, en su afán de ampliar la experiencia de su último álbum, "The Stage", y tras la sorprendente "Malagueña Salerosa", acaba de publicar nuevo tema. En esta ocasión se trata de su versión de "Retrovertigo", tema
         de Mr. Bungle incluido en su álbum "California" de 1999.</p>

     </div>

     <div class="noticia">

       <div class="img-c"> <img src="./imagenes/face.jpg" alt="porta7x"> </div>
       <h2>Avenged Sevenfold publica el vídeo para 'God Damn'</h2>
       <h3>26 de Abril de 2017</h3>
       <p>Avenged Sevenfold han publicado en primicia para Billboard el vídeo oficial para "God Damn", tema incluido en su último álbum, "The Stage", disponible desde octubre del pasado año.</p>

     </div>

     <div class="noticia">

       <div class="img-c"> <img src="./imagenes/deathbatnat.jpg" alt="porta7x"> </div>
       <h2>¡El nuevo disco de Avenged Sevenfold saldrá mañana!</h2>
       <h3>27 de Octubre de 2016</h3>
       <p>Avenged Sevenfold han anunciado por sorpresa que su nuevo álbum verá la luz mañana mismo, 28 de octubre. El disco llevará por título "The Stage", igual que el tema que lanzaron hace unos días, y será publicado por Capitol Records. El ábum contendrá
         11 canciones con una extensión de 71 minutos.</p>

     </div>

     <div class="noticia">

       <div class="img-c"> <img src="./imagenes/group.jpg" alt="porta7x"> </div>
       <h2>Avenged Sevenfold versionan "As Tears Go By" de The Rolling Stones</h2>
       <h3>13 de Octubre de 2016</h3>
       <p>Los californianos Avenged Sevenfold han lanzado 'The Stage', primer single de su esperado nuevo álbum. Los rumores apuntan a que éste llevará por título "Voltaic Oceans" y podría ser lanzado el 9 de diciembre, aunque incluso se especula con que
         podría ser publicado antes. Su séptimo álbum de estudio será publicado por Capitol Records, después de su ruptura con Warner, y será el primero en el que Brooks Wackerman figurará como batería.
       </p>

     </div>


   </div>

HTML;

 }

 function HTMLconciertos($conn)
 {
     if ( isset($_POST['buscarConciertoFechas'])){
       echo <<<HTML
   <div class="main">
      <div class="conciertos">
        <div class="matches">
          <ul>
HTML;
         $fecha1 = strtotime($_POST['fecha1Buscar']);
         $fecha2 = strtotime($_POST['fecha2Buscar']);
         $result = mysqli_query($conn, "SELECT fecha FROM concierto ");
         echo "<h3>Fechas de conciertos entre las fechas {$_POST['fecha1Buscar']} y {$_POST['fecha2Buscar']}<h3>";
         if ($result != false) {
             while ($row = mysqli_fetch_row($result)) {
               if ( strtotime($row[0]) >= $fecha1 && strtotime($row[0]) <= $fecha2  )
                 echo "<li>{$row[0]}</li>";
             }
         }
         echo<<<HTML
         <form id="formBusqueda" class="formOperacion" action="index.php?p=4" method="post">
           <input type="submit" name="" value="Volver">
         </form>
HTML;
       echo "      </ul></div></div></div>";

     } else if (isset($_POST['buscarConciertoLugar'])) {
         echo <<<HTML
     <div class="main">
        <div class="conciertos">
          <div class="matches">
HTML;

         foreach ($_POST['lugaresBuscar'] as $value) {

             $result = mysqli_query($conn, "SELECT fecha FROM concierto WHERE lugar='{$value}' ");
             echo "<h3>Fechas de conciertos en {$value}</h3><ul>";
             if ($result != false) {
                 while ($row = mysqli_fetch_row($result)) {
                     echo <<<HTML
                     <li>{$row[0]}</li>
HTML;

                 }
             }
         }
         echo <<<HTML
          </ul>
          </div>
          <form id="formBusqueda" class="formOperacion" action="index.php?p=4" method="post">
            <input type="submit" name="" value="Volver">
          </form>
HTML;

         echo "</div></div>";

     } else {

         echo <<<HTML
   <div class="main">
      <div class="conciertos">
        <h3>Búsqueda de conciertos</h3>
        <form id="formBusqueda" class="formOperacion" action="index.php?p=4" method="post">
        <label>Seleccione los lugares:</label>
        <select name="lugaresBuscar[]" multiple required>
HTML;
         $result = mysqli_query($conn, "SELECT lugar FROM concierto");

         if ($result != false) {
             while ($row = mysqli_fetch_row($result)) {
                 echo <<<HTML
            <option value="{$row[0]}">{$row[0]}</option>
HTML;
             }
         }
         echo <<<HTML
      </select>
      <input type="submit" name="buscarConciertoLugar" value="Buscar por lugar">
    </form>
    <form id="formBusqueda" class="formOperacion" action="index.php?p=4" method="post">
      <label>Selecciona dos fechas entre las que buscar:</label>
      <select name="fecha1Buscar" required>
HTML;

       $result = mysqli_query($conn, "SELECT fecha FROM concierto");
       if ($result != false) {
           while ($row = mysqli_fetch_row($result)) {
               echo "<option value='{$row[0]}'>{$row[0]}</option>";
     }
 }
         echo<<<HTML
      </select>
      <select name="fecha2Buscar" required>
HTML;
 $result = mysqli_query($conn, "SELECT fecha FROM concierto");
 if ($result != false) {
     while ($row = mysqli_fetch_row($result)) {
       echo "<option value='{$row[0]}'>{$row[0]}</option>";
 }
 }
         echo<<<HTML
      </select>
      <input type="submit" name="buscarConciertoFechas" value="Buscar por fechas">

        </form>
        <h2>Conciertos</h2>
HTML;

         //Consultamos la tabla de conciertos y los mostramos todos
         $result = mysqli_query($conn, "SELECT * FROM concierto ORDER BY fecha DESC");

         if ($result != false) {
             while ($row = mysqli_fetch_row($result)) {
                 echo <<<HTML

       <div class="concierto">
         <h3 id="fechad">{$row[0]}</h3>
         <h3 id="lugard">{$row[1]}</h3>
         <p>{$row[2]} </p>
       </div>
HTML;
             }
         }
         if (isset($_SESSION['tipoUsuario'])) {
             if ($_SESSION['tipoUsuario'] == "admin") {
                 echo <<<HTML
       <div class="botonesAdministrador">
          <h3>Controles de administrador</h3>
          <form class="botonAniadirConcierto" action="index.php?p=5" method="post">
            <input type="submit" name="botonAniadirConcierto" value="Crear concierto">
            <input type="submit" name="botonBorrarConcierto" value="Borrar concierto">
            <input type="submit" name="botonModificarConcierto" value="Modificar concierto">
          </form>
        </div>
HTML;
             }
         }

         echo "</div></div>";
     }
 }

 //Recuadro de login
 function HTMLlogin($estado){

   echo <<<HTML

HTML;

  if($estado == 'sinlog'){ // Formulario login sin identificación
   echo <<<HTML
     <div class="login">
       <h2>IDENTIFICACIÓN</h2>
       <form class="formulariolog" action="index.php?p={$_GET['p']}" method="post">
          <fieldset>
            <label>Login:</label><br>
            <input type="text" name="usuario"><br>
            <label>Contraseña:</label><br>
            <input type="password" name="contraseña"><br>
            <input type="submit" name="blogin">
          </fieldset>
HTML;

} else if ($estado == 'identificado'){ // Formulario login con identificación
      echo <<<HTML
        <div class="login">
          <h2>Bienvenido {$_SESSION['usuario']}</h2>
          <form class="formulariolog" action="index.php?p={$_GET['p']}" method="post">
             <fieldset>
HTML;
      if ($_SESSION['tipoUsuario'] == "admin"){
        echo "<input type='submit' name='gusuarios' value='Gestión de usuarios'>";
      } else if ($_SESSION['tipoUsuario'] == "gestor"){
        echo "<input type='submit' name='gpedidos' value='Gestión de pedidos'>";
      }

      echo<<<HTML
               <input type="submit" name="logout" value="Salir">
             </fieldset>
HTML;
} else if ($estado == 'error'){ // Formulario login con error
    echo <<<HTML
    <div class="login">
      <h2>IDENTIFICACIÓN</h2>
      <form class="formulariolog" action="index.php?p={$_GET['p']}" method="post">
         <fieldset>
           <label>Login:</label><br>
           <input type="text" name="usuario"><br>
           <label>Contraseña:</label><br>
           <input type="password" name="contraseña"><br>
           <input type="submit" name="blogin">
           <p>Usuario o contraseña incorrecta</p>
         </fieldset>
HTML;
  }
    echo <<<HTML
        </form>
HTML;

    echo <<<HTML

      <div class="aside">
        <h2>NOTICIAS</h2>

        <div class="comp-aside">
          <img src="./imagenes/download.jpg" alt="fest">
          <h2>Avenged Sevenfold confirmado en el Download Festival de Madrid</h2>
        </div>

        <div class="comp-aside">
          <img src="./imagenes/pink.jpg" alt="fest">
          <h2>Avenged Sevenfold continúan ampliando su último álbum "The Stage" con variopintas versiones.<br> En esta ocasión, la banda ha publicado su versión del clásico de Pink Floyd "Wish You Were Here".</h2>
        </div>

        <div class="comp-aside">
          <img src="./imagenes/malagueña.jpg" alt="fest">
          <h2>Siguiendo con su política de sorprender al personal desde que publicaran su último disco "The Stage", Avenged Sevenfold han lanzado un nuevo single y vídeo en el que interpretan la canción popular mexicana 'Malagueña Salerosa' con M. shadows cantando en español.</h2>
        </div>


      </div>
    </div>

HTML;

}

 //Aside común
 function HTMLaside(){

   echo <<<HTML

   <div class="aside">
     <h2>NOTICIAS</h2>

     <div class="comp-aside">
       <img src="./imagenes/download.jpg" alt="fest">
       <h2>Avenged Sevenfold confirmado en el Download Festival de Madrid</h2>
     </div>

     <div class="comp-aside">
       <img src="./imagenes/pink.jpg" alt="fest">
       <h2>Avenged Sevenfold continúan ampliando su último álbum "The Stage" con variopintas versiones.<br> En esta ocasión, la banda ha publicado su versión del clásico de Pink Floyd "Wish You Were Here".</h2>
     </div>

     <div class="comp-aside">
       <img src="./imagenes/malagueña.jpg" alt="fest">
       <h2>Siguiendo con su política de sorprender al personal desde que publicaran su último disco "The Stage", Avenged Sevenfold han lanzado un nuevo single y vídeo en el que interpretan la canción popular mexicana 'Malagueña Salerosa' con M. shadows cantando en español.</h2>
     </div>


   </div>

HTML;

 }

 //FOrmulario de compra
 function HTMLcompra($conn){

   //Obtener el precio del discoCompra
   if ( isset($_POST['comprarDisco']) ){
     $_SESSION['tituloCompra'] = $_POST['discoCompraTitulo'] ;
     $_SESSION['precio'] = $_POST['discoCompraPrecio'] ;
     echo <<<HTML

     <div class="main">

         <form class="compraDisco" action="index.php?p=0" method="post">
           <fieldset>
             <legend>Datos del disco</legend>
             <h3>Disco seleccionado: {$_SESSION['tituloCompra']}</h3>
             <h3>Precio: {$_SESSION['precio']}</h3>
           </fieldset>

           <fieldset>
             <legend>Datos del comprador</legend>
             <label>Nombre:</label>
             <input type="text" name="nombre" required>
             <label>Apellidos:</label>
             <input type="text" name="apellidos" required>
             <label>Email:</label>
             <input type="email" name="email" required>
             <label>Teléfono:</label>
             <input type="number" name="telefono" required>
             <label>Dirección de envío</label>
             <input type="text" name="direccionEnvio" required>
           </fieldset>

           <fieldset>
             <legend>Datos de pago</legend>
             <label>Método de pago:</label>
               <label><input type='radio' name='pago' value='reembolso' required/>Reembolso</label>
               <label><input type='radio' name='pago' value='tarjeta' required/>Tarjeta</label>

             <label>Detalles de la tarjeta:

               <label><input type='radio' name='ttar' value='visa' />Visa</label>
               <label><input type='radio' name='ttar' value='mastercard' />Mastercard</label>

               <label>Número de tarjeta:</label>
               <input type='number' name='ntar' />

               <label>Mes y año:</label>
               <input type='number' name='mestar' />
               <input type='number' name='aniotar' />
               <label>Código CVC:</label>
               <input type='number' name='cvc' />
           </fieldset>
               <input type="submit" name="comprarDisco1" value="Comprar el disco">
         </form>

     </div>
HTML;
} else if ( isset( $_POST['comprarDisco1'] ) ){

    $errores = "no";
    //Validación del telefono
    if (filter_var($_POST['telefono'], FILTER_VALIDATE_INT) == FALSE){
    $hayerror['telefono'] = '<h3>El teléfono debe ser un número (entero)</h3>';
    $errores = "si";
    } else if (!preg_match('/^[0-9]{9}$/',$_POST['telefono'])){
    $hayerror['telefono'] = '<h3>El teléfono debe de tener 9 números</h3>';
    $errores = "si";
    }else $hayerror['telefono'] = '';
    //Validación de número de tarjeta
    if (empty($_POST['ntar'])){
    $hayerror['ntar'] = '<h3>Introduzca el número de tarjeta</h3>'; if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else if ( !is_numeric($_POST['ntar']) ){
    $hayerror['ntar'] = '<h3>El número de tarjeta debe ser un número</h3>';  if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else if (!preg_match('/^[0-9]{16}$/',$_POST['ntar'])){
    $hayerror['ntar'] = '<h3>El número de tarjeta debe tener 16 números</h3>'; if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else $hayerror['ntar'] = '';
    //Validación del mes
    $optionsmes = array('options' => array('min_range' => 1,'max_range' => 12,));
    if (empty($_POST['mestar'])){
    $hayerror['mestar'] = '<h3>Introduzca el mes de caducidad de tarjeta</h3>'; if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else if ( !is_numeric($_POST['mestar']) ){
    $hayerror['mestar'] = '<h3>El mes debe ser un número</h3>'; if ( $_POST['pago'] == "tarjeta" )  if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else if (filter_var($_POST['mestar'], FILTER_VALIDATE_INT, $optionsmes) == FALSE){
    $hayerror['mestar'] = '<h3>El mes debe estar entre 1 y 12</h3>'; if ( $_POST['pago'] == "tarjeta" )  if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else $hayerror['mestar'] = '';
    //Validación del año
    $optionsanio = array('options' => array('min_range' => 2018,'max_range' => 2030,));
    if (empty($_POST['aniotar'])){
    $hayerror['aniotar'] = '<h3>Introduzca el año de caducidad de tarjeta</h3>'; if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else if ( !is_numeric($_POST['aniotar']) ){
    $hayerror['aniotar'] = '<h3>El año debe ser un número</h3>'; if ( $_POST['pago'] == "tarjeta" )  if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else if (filter_var($_POST['aniotar'], FILTER_VALIDATE_INT, $optionsanio) == FALSE){
    $hayerror['aniotar'] = '<h3>El año debe estar entre 2018 y 2030</h3>'; if ( $_POST['pago'] == "tarjeta" )  if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else $hayerror['aniotar'] = '';
    //Validación del cvc
    if (empty($_POST['cvc'])){
    $hayerror['cvc'] = '<h3>Introduzca el CVC de la tarjeta</h3>'; if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else if ( !is_numeric($_POST['cvc']) ){
    $hayerror['cvc'] = '<h3>El cvc debe ser un número</h3>'; if ( $_POST['pago'] == "tarjeta" )  if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else if (!preg_match('/^[0-9]{3}$/',$_POST['cvc'])){
    $hayerror['cvc'] = '<h3>El CVC de la tarjeta debe tener 3 números</h3>'; if ( $_POST['pago'] == "tarjeta" )  if ( $_POST['pago'] == "tarjeta" )  $errores = "si";
    }else $hayerror['cvc'] = '';

  if ( $errores == "si" ){

    echo "

    <div class='main'>

        <form class='compraDisco' action='index.php?p=0' method='post'>
          <fieldset>
            <legend>Datos del disco</legend>
            <h3>Disco seleccionado: {$_SESSION['tituloCompra']}</h3>
            <h3>Precio: {$_SESSION['precio']}</h3>
          </fieldset>

          <fieldset>
            <legend>Datos del comprador</legend>
            <label>Nombre:</label>
            <input type='text' name='nombre' value='{$_POST['nombre']}' required>
            <label>Apellidos:</label>
            <input type='text' name='apellidos' value='{$_POST['apellidos']}' required>
            <label>Email:</label>
            <input type='email' name='email' value='{$_POST['email']}' required>
            <label>Teléfono:</label>
            <input type='number' name='telefono' value='{$_POST['telefono']}' required> {$hayerror['telefono']}
            <label>Dirección de envío</label>
            <input type='text' name='direccionEnvio' value='{$_POST['direccionEnvio']}' required>
          </fieldset>

          <fieldset>
            <legend>Datos de pago</legend>
            <label>Método de pago:</label>
            <label><input type='radio' name='pago' value='reembolso'";
            if( isset($_POST['pago']) && $_POST['pago'] == "reembolso"  ) echo "checked";
            echo" required />
            Reembolso</label>
            <label><input type='radio' name='pago' value='tarjeta'";
            if( isset($_POST['pago']) && $_POST['pago'] == "tarjeta"  ) echo "checked";
            echo" required />
            Tarjeta</label>";
            echo"
            <label>Detalles de la tarjeta:

            <label><input type='radio' name='ttar' value='visa' ";
            if( isset($_POST['ttar']) && $_POST['ttar'] == "visa"  ) echo "checked";
            echo"  />
            Visa</label>
            <label><input type='radio' name='ttar' value='mastercard' ";
            if( isset($_POST['ttar']) && $_POST['ttar'] == "mastercard"  ) echo "checked";
            echo"  />
            Mastercard</label>";
            echo "
              <label>Número de tarjeta:</label>
              <input type='number' name='ntar' value='{$_POST['ntar']}' /> {$hayerror['ntar']}

              <label>Mes y año:</label>
              <input type='number' name='mestar' value='{$_POST['mestar']}' /> {$hayerror['mestar']}
              <input type='number' name='aniotar' value='{$_POST['aniotar']}' /> {$hayerror['aniotar']}
              <label>Código CVC:</label>
              <input type='number' name='cvc' value='{$_POST['cvc']}' /> {$hayerror['cvc']}
          </fieldset>
              <input type='submit' name='comprarDisco1' value='Comprar el disco'>
        </form>

    </div>";

  } else {
    $fecha = getdate();
    $fecha2 = sprintf("{$fecha['mday']}-{$fecha['mon']}-{$fecha['year']} a las {$fecha['hours']}:{$fecha['minutes']}");
    $estado = "Pendiente";
    $textoDescriptivo = "A completar";
    $login = $_SESSION['usuario'];

      //Inserción del pedido en la base de Datos
      $consulta  = sprintf("INSERT INTO pedido(fecha,estado,textoDescriptivo,login,disco,precio) VALUES('%s','%s','%s','%s','%s','%s');",
                      mysqli_real_escape_string($conn,$fecha2),
                      mysqli_real_escape_string($conn,$estado),
                      mysqli_real_escape_string($conn,$textoDescriptivo),
                      mysqli_real_escape_string($conn,$login),
                      mysqli_real_escape_string($conn,$_SESSION['tituloCompra']),
                      mysqli_real_escape_string($conn,$_SESSION['precio']));
      $resultado = mysqli_query($conn, $consulta);

     echo <<<HTML
     <div class="main">
       <div class="disco">
         <h3>Pedido del disco {$_SESSION['tituloCompra']} realizado.</h3>
         <form class="formOperacion" action="index.php?p=0" method="post">
           <input type="submit" name="volver" value="Volver">
         </form>
       </div>
     </div>
HTML;
$_SESSION['compra']="no";
  }
//PONER ESTOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
//

} else {
  $_SESSION['compra']="no";
  header("Location: ./index.php?p=3");

}

 }

 //Footer común
 function HTMLfooter(){

   echo <<<HTML

   <div class="footer">
         <p>Diseñado por Carlos Ariza García</p>
   </div>


HTML;

 }

 //Final del fichero html
 function HTMLfin(){

   echo <<<HTML

   </body>
   </html>

HTML;

 }




 ?>
