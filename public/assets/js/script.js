$(document).ready(function () {
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