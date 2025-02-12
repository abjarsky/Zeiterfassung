document.addEventListener("DOMContentLoaded", function () {
    console.log("location.js geladen");

    const locationText = document.getElementById("location");
    
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const coords = `${position.coords.latitude}, ${position.coords.longitude}`;
                locationText.innerText = `Aktueller Standort: ${coords}`;
                console.log("Standort erfasst:", coords);
            }, function (error) {
                console.error("Fehler bei der Standortabfrage:", error);
                locationText.innerText = "Standort konnte nicht erfasst werden";
            });
        } else {
            locationText.innerText = "Geolocation wird von diesem Browser nicht unterstützt";
        }
    }
    
    // Standort abrufen, wenn die Seite geladen wird
    getLocation();
});
