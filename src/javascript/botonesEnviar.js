document.querySelector("#spanFile").addEventListener("click", function () {
  document.querySelector("#inputFile").click();
});
document.querySelector("#spanImg").addEventListener("click", function () {
  document.querySelector("#inputImg").click();
});

document.querySelector("#spanCam").addEventListener("click", async function () {
  if (!document.getElementById("cameraModal")) {
    const modal = document.createElement("div");
    modal.id = "cameraModal";

    const container = document.createElement("div");
    container.id = "cameraContainer";

    const video = document.createElement("video");
    video.id = "videoPreview";
    video.autoplay = true;
    video.playsInline = true;
    container.appendChild(video);

    const canvas = document.createElement("canvas");
    canvas.id = "canvasPhoto";
    canvas.style.display = "none";
    container.appendChild(canvas);

    const img = document.createElement("img");
    img.id = "capturedImg";
    img.style.display = "none";
    container.appendChild(img);

    const captureBtn = document.createElement("button");
    captureBtn.id = "captureBtn";
    captureBtn.textContent = "Tomar Foto";
    container.appendChild(captureBtn);

    const sendBtn = document.createElement("button");
    sendBtn.id = "sendBtn";
    sendBtn.textContent = "Enviar Foto";
    sendBtn.style.display = "none";
    container.appendChild(sendBtn);

    const retryBtn = document.createElement("button");
    retryBtn.id = "retryBtn";
    retryBtn.textContent = "Tomar Otra Foto";
    retryBtn.style.display = "none";
    container.appendChild(retryBtn);

    const closeBtn = document.createElement("button");
    closeBtn.id = "closeBtn";
    closeBtn.textContent = "Cerrar Cámara";
    container.appendChild(closeBtn);

    modal.appendChild(container);
    document.body.appendChild(modal);
    document.body.style.overflow = "hidden";

    let stream;

    // Botón cerrar cámara
    closeBtn.addEventListener("click", () => {
      if (stream) {
        stream.getTracks().forEach((track) => track.stop());
      }
      document.body.removeChild(modal);
      document.body.style.overflow = "";
    });

    // Botón capturar imagen
    captureBtn.addEventListener("click", () => {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const ctx = canvas.getContext("2d");
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      const imageData = canvas.toDataURL("image/png");
      img.src = imageData;

      // Ocultar video, mostrar imagen
      video.style.display = "none";
      img.style.display = "block";

      // Mostrar botones correspondientes
      sendBtn.style.display = "inline-block";
      retryBtn.style.display = "inline-block";
      captureBtn.style.display = "none";

      // Pausar video
      video.pause();
    });

    // Botón otra foto
    retryBtn.addEventListener("click", () => {
      img.style.display = "none";
      video.style.display = "block";
      captureBtn.style.display = "inline-block";
      sendBtn.style.display = "none";
      retryBtn.style.display = "none";
      video.play();
    });

    // Botón enviar
    sendBtn.addEventListener("click", () => {
      const imageData = img.src;
      console.log("Foto enviada:", imageData);
      alert("📸 Foto enviada (simulado)");
      // Aquí va un fetch('/api/enviar', { method: 'POST', body: imageData })
    });

    try {
      stream = await navigator.mediaDevices.getUserMedia({ video: true });
      video.srcObject = stream;
      await video.play();
    } catch (err) {
      alert("No se pudo acceder a la cámara: " + err.message);
      document.body.removeChild(modal);
      document.body.style.overflow = "";
    }
  }
});
