<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST, OPTIONS");
require_once("../../php/modelo/Usuarios.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>DarksApp</title>
  <link rel="stylesheet" href="../css/bootstrap.css" />
  <link rel="stylesheet" href="../css/chat.css" />
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let contenido_enviar = {};
      //Realizamos la conexión cada vez que se carga esta página.
      var conn = new WebSocket("ws://localhost:8080");
      conn.onopen = function(e) {
        console.log("¡Conexión establecida! ");
      };
      conn.onmessage = function(e) {
        let respuesta = JSON.parse(e.data);

        const chatArea = document.getElementById("chat-area");

        const chatDiv = document.createElement("div");
        chatDiv.className = "friends-chat";
        chatDiv.innerHTML = `
                              <div class="profile friends-chat-photo">
                                <img src="../images/ava2.jpg" alt="" />
                              </div>
                              <div class="friends-chat-content">
                                  <p class="friends-chat-name">${respuesta.nombre}</p>
                                  <p class="friends-chat-balloon">${respuesta.mensaje}</p>
                                  <h5 class="chat-datetime">${new Date().toLocaleString()}</h5>
                              </div>
                            `;

        chatArea.appendChild(chatDiv);
        chatArea.scrollTop = chatArea.scrollHeight;
      };

      var receptor = document.getElementById("nombre_amigo");
      var emisor = document.getElementById("emisor")
      let btn_enviar = document.getElementById("btn_enviar_mensaje");
      btn_enviar.addEventListener("click", function() {
        // Evitamos la recarga de la página (si usas un formulario real)
        // e.preventDefault();

        var id_receptor = receptor.dataset.receptor;
        console.log(id_receptor)
        var id_emisor = emisor.dataset.emisor;
        console.log(id_emisor)
        var nombre_amigo = document.getElementById("nombre_amigo").textContent;

        var contenido_mensaje = document.getElementById("type-area");
        var mensaje = contenido_mensaje.value;

        var enviar_datos = {
          "receptor_id": id_receptor,
          "emisor_id": id_emisor,
          "contenido": mensaje,
        };
        console.log(enviar_datos);

        // Enviamos al backend con fetch
        fetch("http://localhost/Chat/php/public/api/ChatController.php", {
            method: "POST",
            body: JSON.stringify(enviar_datos)
          })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              console.log("Mensaje guardado:", data);
            } else {
              console.error("Error en backend:", data);
            }
          })
          .catch((err) => {
            console.error("Error en fetch:", err);
          });

        // // Enviamos por WebSocket también (si aplica)
        // if (typeof conn !== "undefined" && conn.readyState === WebSocket.OPEN) {
        //   conn.send(JSON.stringify(enviar_datos)); // Usamos el mismo objeto
        // }

        contenido_mensaje.value = "";
      });

    });
  </script>
</head>

<body>
  <div id="app" class="app">
    <!-- LEFT SECTION -->

    <section id="main-left" class="main-left">
      <!-- header -->
      <div id="header-left" class="header-left">
        <!-- <span class="glyphicon glyphicon-menu-hamburger hamburger-btn"></span> -->
        <div class="input-wrapper">
          <button class="icon">
            <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                stroke="#fff"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"></path>
              <path d="M22 22L20 20" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
          </button>
          <input type="text" name="text" class="input" placeholder="search.." />
        </div>
        <!-- self-profile -->
        <div id="self-info" class="self-info">
          <!-- photo -->
          <div class="profile your-photo">
            <img src="../images/ava4.jpg" alt="" />
          </div>
          <!-- name -->
          <h4 class="name your-name" name="emisor_id" id="emisor" data-emisor="<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : ''; ?>">
            <?php echo isset($_SESSION["nombre_usuario"]) ? $_SESSION["nombre_usuario"] : "No hay usuario logado"; ?>
          </h4>



          <!-- setting btn -->
          <span class="glyphicon glyphicon-cog"></span>
          <!--btn logout -->
          <button class="btn-logout">
            <div class="sign">
              <svg viewBox="0 0 512 512">
                <path
                  d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
              </svg>
            </div>
            <div class="text">Logout</div>
          </button>
        </div>
      </div>

      <!-- chat list -->
      <div id="chat-list" class="chat-list">
        <!-- Lista de usuarios -->
        <?php
        foreach (Usuarios::mostrarAllUsuarios() as $usuario) {
        ?>
          <?php if ($_SESSION["nombre_usuario"] != $usuario->nombre_usuario) { ?>
            <div id="friends" <?php if ($usuario->en_linea == 0) { ?> style="background-color: red;" <?php } ?> class="friends" data-key="<?php echo $usuario->id ?>">
              <!-- photo -->
              <div class="profile friends-photo">
                <img src="../images/ava2.jpg" alt="" />
              </div>

              <div class="friends-credent">
                <!-- name -->
                <span hidden id="id_usuario"><?php echo  $usuario->id ?></span>
                <span class="friends-name"><?php echo  $usuario->nombre_usuario ?></span>
                <!-- last message -->
                <span class="friends-message"><!--Mensaje que se ve en el menu?--></span>
              </div>
              <!-- notification badge -->
              <span class="badge notif-badge"> <!-- Numero de notificaciones--></span>
            </div>
          <?php } ?>
        <?php } ?>


      </div>
    </section>

    <!-- RIGHT SECTION -->

    <section id="main-right" class="main-right">
      <!-- header -->
      <div id="header-right" class="header-right">
        <!-- profile pict -->
        <div id="header-img" class="profile header-img">
          <img src="../images/ava2.jpg" alt="" />
        </div>

        <!-- name -->
        <h4 class="name friend-name" id="nombre_amigo" data-receptor="" name="receptor_id"> <!--Aqui va el nombre cuando pinchas --></h4>
        <!-- some btn -->
        <div class="some-btn">
          <span class="glyphicon glyphicon-facetime-video"></span>
          <span class="glyphicon glyphicon-earphone"></span>
          <span class="glyphicon glyphicon-option-vertical option-btn" id="option-btn">
            <div class="dropdown text-end">
              <ul class="dropdown-menu custom-dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item custom-dropdown-item" href="#">Bloquear usuario</a></li>
                <li><a class="dropdown-item custom-dropdown-item" href="#">Vaciar chat</a></li>
              </ul>
            </div>
          </span>
        </div>
      </div>

      <!-- chat area -->
      <div id="chat-area" class="chat-area">
        <!-- chat content -->
        <!-- typing area -->
        <div id="typing-area" class="typing-area">
          <!-- input form -->
          <input id="type-area" class="type-area" placeholder="Type something..." type="text" name="contenido" />
          <!-- attachment btn -->
          <div class="attach-btn">
            <!-- Vista previa de la cámara en escritorio -->
            <video id="videoPreview" width="320" height="240" autoplay style="display: none; margin-top: 10px"></video>
            <span class="glyphicon glyphicon-paperclip file-btn icon-hover-scale" id="spanFile">
              <input type="file" style="display: none" id="inputFile" />
            </span>
            <span class="glyphicon glyphicon-camera icon-hover-scale" id="spanCam">
              <input type="file" accept="image/*" capture="environment" style="display: none" id="inputCam" />
            </span>
            <span class="glyphicon glyphicon-picture icon-hover-scale" id="spanImg">
              <input type="file" style="display: none" id="inputImg" />
            </span>
          </div>
          <!-- send btn -->
          <button type="button" class="btn-enviar" id="btn_enviar_mensaje">
            <div class="svg-wrapper-1">
              <div class="svg-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                  <path fill="none" d="M0 0h24v24H0z"></path>
                  <path
                    fill="currentColor"
                    d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"></path>
                </svg>
              </div>
            </div>
            <span>Send</span>
          </button>
        </div>
    </section>
  </div>

  <!-- jQuey, Popper, BootstrapJS -->
  <script src="../javascript/jquery-3.3.1.min.js"></script>
  <script src="../javascript/bootstrap.min.js"></script>
  <script src="../javascript/bootstrap.min.js"></script>
  <script src="../javascript/botonesEnviar.js"></script>
  <script>
    const optionBtn = document.querySelector("#option-btn");
    const dropdownMenu = optionBtn.querySelector("ul");

    optionBtn.addEventListener("click", function(e) {
      e.stopPropagation(); // evita que el clic se propague al documento
      dropdownMenu.style.display = "block";
    });

    document.addEventListener("click", function(e) {
      // Si el clic NO es dentro del botón o el menú, ocultamos el menú
      if (!optionBtn.contains(e.target)) {
        dropdownMenu.style.display = "none";
      }
    });

    // var id_usuario = "";
    var nombre_receptor = document.getElementById("nombre_amigo");
    document.querySelectorAll(".friends").forEach(element => {

      element.addEventListener("click", function() {
        var id_usuario = element.dataset.key;
        nombre_receptor.setAttribute("data-receptor", id_usuario);
        fetch(`http://localhost/Chat/php/public/api/ChatController.php?id=${id_usuario}`)
          .then(res => res.json())
          .then(usuario => {
            // Cambiar nombre en la cabecera
            document.getElementById("nombre_amigo").textContent = usuario.nombre_usuario;
            // // Cambiar imagen de cabecera si tienes una ruta dinámica en la DB
            // if (usuario.foto_perfil) {
            //   document.querySelector("#header-img img").src = usuario.foto_perfil;
            // }

            window.usuarioSeleccionadoId = usuario.id;
          })
          .catch(err => console.error("Error al obtener datos del usuario:", err));
      })
    });
  </script>
</body>

</html>