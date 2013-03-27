function ad() {
    var coord=document.getElementById('coords').innerHTML;
    $.getJSON('http://nominatim.openstreetmap.org/search?format=json&limit=1&q='+coord, function(data) {
	var items = [];
	$.each(data, function(key, val) {
	items.push(document.getElementById('coords1').innerHTML=val.display_name);
	})
});
}
