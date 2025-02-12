document.addEventListener("DOMContentLoaded", function () {
    console.log("time_tracking.js geladen");

    const startTimeInput = document.getElementById("start_time");
    const endTimeInput = document.getElementById("end_time");
    const breakTimeInput = document.getElementById("break_time");
    const totalTimeInput = document.getElementById("total_time");
    const signaturePad = document.getElementById("signature-pad");
    const signatureInput = document.getElementById("signature");
    const clearSignatureButton = document.getElementById("clear-signature");
    let ctx = signaturePad.getContext("2d");
    let drawing = false;

    function calculateTotalTime() {
        let start = new Date(startTimeInput.value);
        let end = new Date(endTimeInput.value);
        let breakTime = parseInt(breakTimeInput.value) || 0;

        if (start && end) {
            let diff = (end - start) / (1000 * 60); // Minuten
            let total = Math.max(0, diff - breakTime);
            totalTimeInput.value = total + " Minuten";
        }
    }

    endTimeInput.addEventListener("change", calculateTotalTime);
    breakTimeInput.addEventListener("input", calculateTotalTime);

    // Unterschrift erfassen
    signaturePad.addEventListener("mousedown", () => { drawing = true; ctx.beginPath(); });
    signaturePad.addEventListener("mouseup", () => { drawing = false; saveSignature(); });
    signaturePad.addEventListener("mousemove", draw);

    function draw(event) {
        if (!drawing) return;
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.strokeStyle = "black";
        ctx.lineTo(event.offsetX, event.offsetY);
        ctx.stroke();
    }

    clearSignatureButton.addEventListener("click", function () {
        ctx.clearRect(0, 0, signaturePad.width, signaturePad.height);
        signatureInput.value = "";
    });

    function saveSignature() {
        signatureInput.value = signaturePad.toDataURL("image/png");
    }
});