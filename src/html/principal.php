<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST, OPTIONS");
require_once("../../php/modelo/Usuarios.php");
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Minimalista Responsive</title>
    <link rel="stylesheet" href="../css/chat.css" />
    <style>
        .loader {
            width: 160px;
            height: 185px;
            position: relative;
            background: #34C759;
            border-radius: 100px 100px 0 0;
            align-items: center;
            left: 741px;
            top: 656px;

        }

        .loader:after {
            content: "";
            position: absolute;
            width: 100px;
            height: 125px;
            left: 50%;
            top: 25px;
            transform: translateX(-50%);
            background-image: radial-gradient(circle, #000 48%, transparent 55%),
                radial-gradient(circle, #000 48%, transparent 55%),
                radial-gradient(circle, #fff 30%, transparent 45%),
                radial-gradient(circle, #000 48%, transparent 51%),
                linear-gradient(#000 20px, transparent 0),
                linear-gradient(#cfecf9 60px, transparent 0),
                radial-gradient(circle, #cfecf9 50%, transparent 51%),
                radial-gradient(circle, #cfecf9 50%, transparent 51%);
            background-repeat: no-repeat;
            background-size: 16px 16px, 16px 16px, 10px 10px, 42px 42px, 12px 3px,
                50px 25px, 70px 70px, 70px 70px;
            background-position: 25px 10px, 55px 10px, 36px 44px, 50% 30px, 50% 85px,
                50% 50px, 50% 22px, 50% 45px;
            animation: faceLift 3s linear infinite alternate;
        }

        .loader:before {
            content: "";
            position: absolute;
            width: 140%;
            height: 125px;
            left: -20%;
            top: 0;
            background-image: radial-gradient(circle, #34C759 48%, transparent 50%),
                radial-gradient(circle, #34C759 48%, transparent 50%);
            background-repeat: no-repeat;
            background-size: 65px 65px;
            background-position: 0px 12px, 145px 12px;
            animation: earLift 3s linear infinite alternate;
        }

        @keyframes faceLift {
            0% {
                transform: translateX(-60%);
            }

            100% {
                transform: translateX(-30%);
            }
        }

        @keyframes earLift {
            0% {
                transform: translateX(10px);
            }

            100% {
                transform: translateX(0px);
            }
        }


        .Btn {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition-duration: .3s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
            background-color: rgb(255, 65, 65);
        }

        /* plus sign */
        .sign {
            width: 100%;
            transition-duration: .3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sign svg {
            width: 17px;
        }

        .sign svg path {
            fill: white;
        }


        /* button click effect*/
        .Btn:active {
            transform: translate(2px, 2px);
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const id_emisor_actual = document.getElementById("emisor").dataset.emisor;
            var conn = new WebSocket("ws://localhost:8080?id=" + id_emisor_actual);
            var emisor = document.getElementById("emisor")
            var id_emisor = emisor.dataset.emisor;
            var receptor = document.getElementById("nombre_amigo");
            let btn_enviar = document.getElementById("btn_enviar_mensaje");



            conn.onopen = function(e) {
                console.log("Conexion establecida" + id_emisor_actual);
                //Aqui enviamos a el servidor los siguientes datos.
                conn.send(JSON.stringify({
                    type: "login",
                    userid: id_emisor_actual
                }));
            };

            conn.onmessage = function(e) {
                var mensaje = JSON.parse(e.data);
                const chatArea = document.querySelector(".chat-messages");
                var avatar = document.getElementById("avatar_" + mensaje.userid);
                var status = document.getElementById("user_status_" + mensaje.userid);
                switch (mensaje.type) {
                    case "login":
                        avatar.style.backgroundColor = "green";
                        status.textContent = "En Línea";
                        break;
                    case "message":
                        if (mensaje.emisor_id == id_emisor_actual) {
                            return;
                        }

                        const hora_mensaje = new Date().toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        const chatDiv = document.createElement("div");
                        chatDiv.className = "message-container received";
                        chatDiv.innerHTML = `
                                        <div class="message">${mensaje.contenido}</div>
                                        <div class="message-time">${hora_mensaje}</div>
                                     `;
                        chatArea.appendChild(chatDiv);
                        // chatArea.scrollTop = chatArea.scrollHeight;
                        break;
                    case "logout":
                        avatar.style.backgroundColor = "red";
                        status.textContent = "Desconectado";
                        break;
                }
            };



            btn_enviar.addEventListener("click", function() {
                var id_receptor = receptor.dataset.receptor;
                var mensaje = document.getElementById("type-area").value;
                //Evitamos que envie mensajes vacios.
                if (!mensaje.trim() || receptor.textContent == "No hay chat seleccionado.") return;
                //Convertimos la hora del mensaje
                const hora_mensaje = new Date().toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const chatArea = document.querySelector(".chat-messages");
                const chatDiv = document.createElement("div");
                chatDiv.className = "message-container sent";
                chatDiv.innerHTML = `
                                    <div class="message">${mensaje}</div>
                                    <div class="message-time">${hora_mensaje}</div>
                                    `;
                chatArea.appendChild(chatDiv);
                // chatArea.scrollTop = chatArea.scrollHeight;

                // Limpiamos el input.
                document.getElementById("type-area").value = "";

                const formData = new FormData();
                formData.append("receptor_id", id_receptor);
                formData.append("emisor_id", id_emisor);
                formData.append("contenido", mensaje);

                fetch("http://localhost/Chat/php/public/api/ChatController.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            console.error("Error al guardar mensaje:", data);
                        }
                    })
                    .catch(err => console.error("Error en fetch:", err));

                // Enviar por WebSocket
                if (conn && conn.readyState === WebSocket.OPEN) {
                    conn.send(JSON.stringify({
                        type: "message",
                        contenido: mensaje,
                        emisor_id: id_emisor,
                        receptor_id: id_receptor,
                    }));
                }
            });

            conn.onclose = function(e) {
                console.log("Usuario desconectado");
                conn.send(JSON.stringify({
                    type: "logout",
                    userid: id_emisor_actual
                }));
            };

            //Evento para cerrar 
            var btn_cerrar_sesion = document.getElementById("btn_cerrar_sesion");
            btn_cerrar_sesion.addEventListener("click", function() {
                conn.onclose();
                // console.log("Hola");
                const formData = new FormData();
                formData.append("cerrar_sesion", 1);
                fetch("http://localhost/Chat/php/public/api/ChatController.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            //Redirigimos al usuario a la pagina de login
                            document.location.href = ('http://localhost/Chat/src/html/login_registro.html');
                        }
                    })
                    .catch(err => console.error("Error en fetch:", err));
            })
        });
    </script>
</head>

<body>
    <!-- Sidebar -->
    <aside class="users-sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4 class="name your-name" name="emisor_id" id="emisor" data-emisor="<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : ''; ?>">
                <?php echo isset($_SESSION["nombre_usuario"]) ? $_SESSION["nombre_usuario"] : "No hay usuario logado"; ?>
            </h4>
            <button class="Btn" id="btn_cerrar_sesion" name="cerrar_sesion">
                <div class="sign"><svg viewBox="0 0 512 512">
                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                    </svg></div>
            </button>



            <button class="menu-button" id="closeSidebar">
                <svg class="menu-icon" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </button>
        </div>
        <div class="user-search">
            <input type="text" placeholder="Buscar" name="buscador_usuarios" id="buscador_usuarios">
        </div>
        <div class="user-list" id="user-list">
            <!-- Cajas de los usuarios -->
            <?php
            foreach (Usuarios::mostrarAllUsuarios() as $usuario) {
            ?>
                <?php if ($_SESSION["nombre_usuario"] != $usuario->nombre_usuario) { ?>
                    <div class="user-item" data-key="<?php echo $usuario->id ?>" data-nombre="<?php echo $usuario->nombre_usuario ?>">
                        <span hidden id="id_usuario"><?php echo  $usuario->id ?></span>
                        <div class="user-avatar"
                            id="avatar_<?php echo $usuario->id ?>"
                            style="background-color: <?php echo ($usuario->en_linea == 0) ? 'red' : 'green'; ?>;">
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?php echo  $usuario->nombre_usuario ?></div>
                            <div class="user-status" id="user_status_<?php echo $usuario->id ?>">
                                <?= ($usuario->en_linea == 0) ? 'Desconectado' : 'En Línea' ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </aside>

    <!-- Overlay para moviles -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!--chat -->
    <main class="chat-container" id="chatContainer">
        <div class="chat-header">
            <button class="back-button" id="backButton">
                <svg class="back-icon" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                </svg>
            </button>
            <div class="chat-title" id="nombre_amigo" data-receptor="" name="receptor_id">No hay chat seleccionado.</div>
            <button class="menu-button" id="openSidebar">
                <svg class="menu-icon" viewBox="0 0 24 24">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" />
                </svg>
            </button>
        </div>

        <div class="chat-messages">

            <div class="loader"></div>
        </div>

        <div class="chat-input-container">
            <div class="chat-input-wrapper">
                <input type="text" class="chat-input" placeholder="Escribe aquí..." name="contenido" id="type-area">
                <button class="send-button" type="button" id="btn_enviar_mensaje">
                    <svg class="send-icon" viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                    </svg>
                </button>
            </div>
        </div>
    </main>

    <script>
        // Funcionalidad para responsive
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        const backButton = document.getElementById('backButton');
        const chatContainer = document.getElementById('chatContainer');

        // Abrir sidebar en moviles
        openSidebarBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
        });

        // Cerrar sidebar
        const closeSidebar = () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        };

        closeSidebarBtn.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Boton de volver (para moviles)
        backButton.addEventListener('click', () => {
            // Pondremos la funcionalidad de cerrar sesion
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.add('active');
                sidebarOverlay.classList.remove('active');
            }
        });
        //Funcion para cargar los mensajes cuando el usuario seleccione una conversacion.
        function cargarMensajes(idEmisor, idReceptor) {
            fetch(`http://localhost/Chat/php/public/api/MensajesController.php?id_emisor=${idEmisor}&id_receptor=${idReceptor}`)
                .then(res => res.json())
                .then(mensajes => {
                    const chatArea = document.querySelector(".chat-messages");
                    const chatDiv = document.createElement("div");

                    if (mensajes.length == 0) {
                        chatArea.innerHTML = ""; // Limpiamos el contenedor
                        chatDiv.innerHTML = `
                                            <div class="loader"></div>
                                            `;
                        chatArea.appendChild(chatDiv);
                        return;
                    }
                    chatArea.innerHTML = ""; // Limpiamos el contenedor
                    mensajes.forEach(mensaje => {
                        const esEnviado = mensaje.emisor_id == idEmisor;

                        const chatDiv = document.createElement("div");
                        chatDiv.className = `message-container ${esEnviado ? 'sent' : 'received'}`;
                        const hora_mensaje = new Date(mensaje.enviado_en).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        chatDiv.innerHTML = `
                    <div class="message">${mensaje.contenido}</div>
                    <div class="message-time">${hora_mensaje}</div>
                `;

                        chatArea.appendChild(chatDiv);
                    });

                    // chatArea.scrollTop = chatArea.scrollHeight;
                })
                .catch(err => console.error("Error al cargar mensajes:", err));
        }

        var emisor = document.getElementById("emisor")
        var nombre_receptor = document.getElementById("nombre_amigo");
        document.querySelectorAll(".user-item").forEach(element => {
            element.addEventListener("click", function() {
                document.querySelectorAll(".user-item").forEach(elemento => {
                    elemento.classList.remove("active");
                });

                element.classList.add("active");
                var id_usuario = element.dataset.key;
                var id_emisor = emisor.dataset.emisor;
                // console.log(id_usuario);
                cargarMensajes(id_emisor, id_usuario);
                nombre_receptor.setAttribute("data-receptor", id_usuario);
                fetch(`http://localhost/Chat/php/public/api/ChatController.php?id=${id_usuario}`)
                    .then(res => res.json())
                    .then(usuario => {
                        document.getElementById("nombre_amigo").textContent = usuario.nombre_usuario;
                        window.usuarioSeleccionadoId = usuario.id;
                    })
                    .catch(err => console.error("Error al obtener datos del usuario:", err));
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            })
        });

        //Evento para el buscador 
        document.getElementById("buscador_usuarios").addEventListener("input", function() {
            var valor_buscador = document.getElementById("buscador_usuarios").value;
            if (valor_buscador) {
                document.querySelectorAll(".user-item").forEach(usuario => {
                    var nombre_usuario = usuario.dataset.nombre;
                    if (!nombre_usuario.includes(valor_buscador)) {
                        usuario.style.display = "none";
                    } else {
                        usuario.style.display = "block";
                    }
                });
            } else if (!valor_buscador) {
                document.querySelectorAll(".user-item").forEach(usuario => {
                    usuario.style.display = "block";
                });
            }


        })
    </script>
</body>

</html>