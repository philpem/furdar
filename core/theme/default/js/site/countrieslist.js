/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/
var map;

$(document).ready(function() {
	
	map = L.map('Map', { 'scrollWheelZoom':false });
	configureBasicMap(map);

	if (countries.length < 20) {
		var minLat = countries[0].minLat;
		var minLng = countries[0].minLng;
		var maxLat = countries[0].maxLat;
		var maxLng = countries[0].maxLng;
		for (var i = 0; i < countries.length; i++) {
			var country = countries[i];
			if (country.minLat < minLat) minLat  = country.minLat;
			if (country.minLng < minLng) minLng  = country.minLng;
			if (country.maxLat > maxLat) maxLat  = country.maxLat;
			if (country.maxLng > maxLng) maxLng  = country.maxLng;
		}
		map.fitBounds([[minLat, minLng],[maxLat, maxLng]]);
	} else {
		map.setView([55.952035, -3.196807], 3);
	}
});
