document.addEventListener("DOMContentLoaded", function () {
    console.log("signature.js geladen");

    const canvas = document.getElementById("signature-pad");
    const ctx = canvas.getContext("2d");
    const clearButton = document.getElementById("clear-signature");
    const signatureInput = document.getElementById("signature");
    let drawing = false;

    // Canvas Hintergrund weiß setzen
    ctx.fillStyle = "white";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    function startDrawing(event) {
        drawing = true;
        ctx.beginPath();
        ctx.moveTo(event.offsetX, event.offsetY);
    }

    function draw(event) {
        if (!drawing) return;
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.strokeStyle = "black";
        ctx.lineTo(event.offsetX, event.offsetY);
        ctx.stroke();
    }

    function stopDrawing() {
        drawing = false;
        saveSignature();
    }

    function saveSignature() {
        signatureInput.value = canvas.toDataURL("image/png");
    }

    function clearSignature() {
        ctx.fillStyle = "white";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        signatureInput.value = "";
    }

    canvas.addEventListener("mousedown", startDrawing);
    canvas.addEventListener("mouseup", stopDrawing);
    canvas.addEventListener("mousemove", draw);
    clearButton.addEventListener("click", clearSignature);
});
