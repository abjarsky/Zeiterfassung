// Initialisiere die Google Maps Karte
function initMap() {
    let map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: { lat: 50.1109, lng: 8.6821 } // Standard: Frankfurt am Main
    });

    window.map = map;
}

// Funktion zum Abrufen der Geodaten
function getGeoLocation() {
    let address = document.getElementById("address").value;

    if (address.trim() === "") {
        alert("Bitte geben Sie eine gültige Adresse ein.");
        return;
    }

    let geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': address }, function (results, status) {
        if (status === 'OK') {
            let location = results[0].geometry.location;
            document.getElementById("latitude").value = location.lat();
            document.getElementById("longitude").value = location.lng();

            // Setze den Marker auf die Karte
            let marker = new google.maps.Marker({
                position: location,
                map: window.map
            });

            // Karte auf den neuen Standort zentrieren
            window.map.setCenter(location);
        } else {
            alert("Geocoding war nicht erfolgreich: " + status);
        }
    });
}

// Funktion zum Speichern der Koordinaten in der Datenbank
function saveGeoLocation() {
    let latitude = document.g
