function ad() {
    var coord=document.getElementById('coords').innerHTML;
    $.getJSON('http://nominatim.openstreetmap.org/search?format=json&limit=1&q='+coord, function(data) {
	var items = [];
	$.each(data, function(key, val) {
	items.push(document.getElementById('coords1').innerHTML=val.display_name);
	})
});
};

function onLocationFound(e) {
    var radius = e.accuracy / 2;
    var lat = e.latlng.lat;
    var lon = e.latlng.lng;
    L.marker(e.latlng).addTo(map).bindPopup('Вы где-то здесь').openPopup();
    L.circle(e.latlng, radius).addTo(map);
    $.getJSON('http://nominatim.openstreetmap.org/search?format=json&limit=1&q='+lat+' '+lon, function(data) {
        var items = [];
        $.each(data, function(key, val) {
        items.push(document.getElementById('location').innerHTML=val.display_name);
	})
});
};
