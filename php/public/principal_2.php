<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST, OPTIONS");
require_once("../modelo/Usuarios.php");
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Chat App Template</title>
  <link rel="stylesheet" href="../../src/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../../src/css/chat.css" />
  <!-- jQuey, Popper, BootstrapJS -->
  <script src="javascript/jquery-3.3.1.min.js"></script>
  <script src="javascript/bootstrap.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let contenido_enviar = {};
      //Realizamos la conexión cada vez que se carga esta página.
      var conn = new WebSocket('ws://localhost:8080');
      conn.onopen = function(e) {
        console.log("¡Conexión establecida! ");
      };
      //Este es el metodo que muestra los mensajes que recibes
      conn.onmessage = function(e) {
        let respuesta = JSON.parse(e.data);
        console.log("nombre: " + respuesta.nombre + "Mensaje: " + respuesta.mensaje);
      };
      let btn_enviar = document.getElementById("btn_enviar_mensaje");
      btn_enviar.addEventListener("click", function() {
        var nombre_amigo = document.getElementById("nombre_amigo").textContent;
        var mensaje = document.getElementById("type-area").value;
        var enviar = {
          "nombre": nombre_amigo,
          "mensaje": mensaje
        };
        conn.send(JSON.stringify(enviar));

        //Obtenemos el div grande donde se mostrarán los mensajes.
        var cuadro_mensaje_amigo = document.getElementById("cuadro_mensaje_amigo");
        //Creamos los elementos que añadimos a ese cuadro de mensaje.
        var friends_chat_name = document.createElement("p");
        friends_chat_name.classList.add("friends-chat-name");
        friends_chat_name.textContent = nombre_amigo;        
      })
    })
  </script>
</head>

<body>
  <div id="app" class="app">
    <!-- LEFT SECTION -->

    <section id="main-left" class="main-left">
      <!-- header -->
      <div id="header-left" class="header-left">
        <span class="glyphicon glyphicon-menu-hamburger hamburger-btn"></span>
        <span class="glyphicon glyphicon-search search-btn"></span>
        <span class="glyphicon glyphicon-option-vertical option-btn"></span>
      </div>
      <!-- chat list -->
      <div id="chat-list" class="chat-list">
        <!-- user lists -->
        <?php
        foreach (Usuarios::mostrarAllUsuarios() as $usuario) {
        ?>
          <!-- name -->
          <?php if ($_SESSION["nombre_usuario"] != $usuario->nombre_usuario) { ?>

            <div id="friends" <?php if ($usuario->en_linea == 0) { ?> style="background-color: red;" <?php } ?> class="friends">
              <!-- photo -->
              <div class="profile friends-photo">
                <img src="../../src/assets/img/perfil_generico.svg" alt="" />
              </div>
              <div class="friends-credent">
                <span class="friends-name"><?php echo  $usuario->nombre_usuario ?></span>
              </div>
            </div>
          <?php } ?>

          <!-- last message -->
          <!-- <span class="friends-message">How are you?</span> -->

          <!-- notification badge -->
          <!-- <span class="badge notif-badge">999</span> -->
        <?php } ?>
        <!-- self-profile -->
        <div id="self-info" class="self-info">
          <!-- photo -->
          <div class="profile your-photo">
            <!-- <img src="../images/ava4.jpg" alt="" /> -->
          </div>
          <!-- name -->
          <h4 class="name your-name">Iqbal Taufiq</h4>
          <!-- setting btn -->
          <span class="glyphicon glyphicon-cog"></span>
        </div>
    </section>

    <!-- RIGHT SECTION -->

    <section id="main-right" class="main-right">
      <!-- header -->
      <div id="header-right" class="header-right">
        <!-- profile pict -->
        <div id="header-img" class="profile header-img">
          <!-- <img src="../images/ava2.jpg" alt="" /> -->
        </div>

        <!-- name -->
        <h4 class="name friend-name" id="nombre_amigo">Mario Gomez</h4>

        <!-- some btn -->
        <div class="some-btn">
          <span class="glyphicon glyphicon-facetime-video"></span>
          <span class="glyphicon glyphicon-earphone"></span>
          <span class="glyphicon glyphicon-option-vertical option-btn"></span>
        </div>
      </div>

      <!-- chat area -->
      <div id="chat-area" class="chat-area">
        <!-- chat content -->

        <!-- FRIENDS CHAT TEMPLATE -->
        <div id="friends-chat" class="friends-chat">
          <div class="profile friends-chat-photo">
            <!-- <img src="../images/ava2.jpg" alt="" /> -->
          </div>
          <div class="friends-chat-content" id="cuadro_mensaje_amigo">
            <p class="friends-chat-name" id="chat_nombre_amigo"></p>
            <p class="friends-chat-balloon" id="texto_amigo"></p>
            <h5 class="chat-datetime">Sun, Aug 30 | 15:41</h5>
          </div>
        </div>

        <!-- YOUR CHAT TEMPLATE -->
        <div id="your-chat" class="your-chat">
          <p class="your-chat-balloon">'sup</p>
          <p class="chat-datetime"><span class="glyphicon glyphicon-ok"></span> Sun, Aug 30 | 15:45</p>
        </div>

        <!-- FRIENDS CHAT TEMPLATE -->
        <div id="friends-chat" class="friends-chat">
          <div class="profile friends-chat-photo">
            <!-- <img src="../images/ava2.jpg" alt="" /> -->
          </div>
          <div class="friends-chat-content">
            <p class="friends-chat-name">Mario Gomez</p>
            <p class="friends-chat-balloon">
              Lorem ipsum dolor, sit amet consectetur adipisicing elit. Itaque vero iusto perferendis nemo ad ipsam
              mollitia ducimus? Et possimus numquam minus impedit tempora, nulla, enim in magni
              accusamus dolore fugiat nisi culpa exercitationem fuga consequatur eos aliquam perspiciatis. Minima
              praesentium expedita facere quod labore magnam eveniet commodi veniam porro placeat?
            </p>
            <h5 class="chat-datetime">Sun, Aug 30 | 15:41</h5>
          </div>
        </div>

        <!-- FRIENDS CHAT TEMPLATE -->
        <div id="friends-chat" class="friends-chat">
          <div class="profile friends-chat-photo">
            <!-- <img src="../images/ava2.jpg" alt="" /> -->
          </div>
          <div class="friends-chat-content">
            <p class="friends-chat-name">Mario Gomez</p>
            <p class="friends-chat-balloon">Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium,
              consequatur!</p>
            <h5 class="chat-datetime">Sun, Aug 30 | 15:41</h5>
          </div>
        </div>

        <!-- YOUR CHAT TEMPLATE -->
        <div id="your-chat" class="your-chat">
          <p class="your-chat-balloon">
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Beatae amet modi aliquid molestiae. Adipisci,
            similique dolor expedita error explicabo at cupiditate exercitationem dolorem
            corrupti. Pariatur.
          </p>
          <p class="chat-datetime"><span class="glyphicon glyphicon-ok"></span> Sun, Aug 30 | 15:45</p>
        </div>

        <!-- FRIENDS CHAT TEMPLATE -->
        <div id="friends-chat" class="friends-chat">
          <div class="profile friends-chat-photo">
            <!-- <img src="../images/ava2.jpg" alt="" /> -->
          </div>
          <div class="friends-chat-content">
            <p class="friends-chat-name">Mario Gomez</p>
            <p class="friends-chat-balloon">Crap! I forgot my shoes. Can you bring extra pair for me?</p>
            <h5 class="chat-datetime">Sun, Aug 30 | 15:41</h5>
          </div>
        </div>

        <!-- YOUR CHAT TEMPLATE -->
        <div id="your-chat" class="your-chat">
          <p class="your-chat-balloon">sure m8</p>
          <p class="chat-datetime"><span class="glyphicon glyphicon-ok"></span> Sun, Aug 30 | 15:45</p>
        </div>

        <!-- FRIENDS CHAT TEMPLATE -->
        <div id="friends-chat" class="friends-chat">
          <div class="profile friends-chat-photo">
            <!-- <img src="../images/ava2.jpg" alt="" /> -->
          </div>
          <div class="friends-chat-content">
            <p class="friends-chat-name">Mario Gomez</p>
            <p class="friends-chat-balloon">Thanks!</p>
            <h5 class="chat-datetime">Sun, Aug 30 | 15:41</h5>
          </div>
        </div>
      </div>

      <!-- typing area -->
      <div id="typing-area" class="typing-area">
        <!-- input form -->
        <input id="type-area" class="type-area" placeholder="Type something..." type="text" />
        <!-- attachment btn -->
        <div class="attach-btn">
          <span class="glyphicon glyphicon-paperclip file-btn"></span>
          <span class="glyphicon glyphicon-camera"></span>
          <span class="glyphicon glyphicon-picture"></span>
        </div>
        <!-- send btn -->
        <span class="glyphicon glyphicon-send send-btn" id="btn_enviar_mensaje"></span>
      </div>
    </section>
  </div>



</body>

</html>