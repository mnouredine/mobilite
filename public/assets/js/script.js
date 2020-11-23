$(document).ready(function () {
    function goBack() {
        window.history.back();
    }

    $('.back').on('click', () => {
        goBack();
    });

    // Itineraire arrêt logic
    $('#compagnie_select').on('change', function (e) {
        var valueSelected = this.value;
        window.location = '../../../itineraire/arret/' + valueSelected + '/0';
    });

    $('#itineraire_select').on('change', function (e) {
        var valueSelected = this.value;
        var itin = $('#compagnie_select').val();
        // console.log('itineraire: ' + itin);
        window.location = '../../../itineraire/arret/' + itin + '/' + valueSelected;
    });

    // Mapping
    var mymap = L.map('map').setView([13.51915, 2.09445], 15);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoibm91cm1vdW4iLCJhIjoiY2toZ2h6enVuMGx2OTJ4bndzNnZhbTA1MSJ9.ou3FRi4iWT-ct74OZkjSbA', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'your.mapbox.access.token'
    }).addTo(mymap);

    var marker = L.marker([13.51915, 2.09445]).addTo(mymap);

});