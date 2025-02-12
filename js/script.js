document.addEventListener("DOMContentLoaded", function () {
    console.log("Script loaded successfully");

    // Zeiterfassungs-Start
    const startBtn = document.getElementById("startBtn");
    const stopBtn = document.getElementById("stopBtn");
    const statusText = document.getElementById("status");
    let startTime, endTime;

    if (startBtn && stopBtn && statusText) {
        startBtn.addEventListener("click", function () {
            startTime = new Date();
            statusText.innerText = "Status: Erfassung gestartet um " + startTime.toLocaleTimeString();
            startBtn.disabled = true;
            stopBtn.disabled = false;
            getLocation();
        });

        stopBtn.addEventListener("click", function () {
            endTime = new Date();
            statusText.innerText = "Status: Erfassung beendet um " + endTime.toLocaleTimeString();
            stopBtn.disabled = true;
            startBtn.disabled = false;
            saveTimeData();
        });
    }

    // Standort erfassen
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                document.getElementById("location").innerText = `Standort: ${position.coords.latitude}, ${position.coords.longitude}`;
            }, function (error) {
                console.error("Fehler bei der Standortabfrage:", error);
            });
        } else {
            document.getElementById("location").innerText = "Standort: Nicht verfügbar";
        }
    }

    // Zeiterfassungsdaten speichern (Hier kann später ein AJAX-Request hinzugefügt werden)
    function saveTimeData() {
        console.log("Zeiterfassungsdaten gespeichert");
        // Beispiel für eine AJAX-Anfrage:
        // fetch("../php/save_time.php", {
        //     method: "POST",
        //     body: JSON.stringify({ startTime, endTime }),
        //     headers: { "Content-Type": "application/json" }
        // })
        // .then(response => response.json())
        // .then(data => console.log("Daten erfolgreich gespeichert:", data))
        // .catch(error => console.error("Fehler beim Speichern:", error));
    }
});
