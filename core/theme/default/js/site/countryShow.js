/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/
var map;
var markerGroup;

$(document).ready(function() {
	
	map = L.map('Map', { 'scrollWheelZoom':false });
	configureBasicMap(map);
		
	markerGroup = new L.MarkerClusterGroup();
	map.addLayer(markerGroup);
	
	if (countryData.maxLat && countryData.maxLng && countryData.minLat && countryData.minLng) {
	
		var southWest = L.latLng(countryData.minLat, countryData.minLng),
			northEast = L.latLng(countryData.maxLat, countryData.maxLng),
			bounds = L.latLngBounds(southWest, northEast);
			
		map.fitBounds(bounds);
	
	} else {
		map.setView([55.952035, -3.196807], 3);
	}

	$.ajax({
			dataType: "json",
			url: '/country/'+countryData.code+'/info.json?includeVenues=1',
			success: function(data) {
				
				for(i in data.venues) {
					hasMapPos = data.venues[i].lat && data.venues[i].lng;
					if (hasMapPos) {
						var marker = L.marker([data.venues[i].lat,data.venues[i].lng]);
						marker.bindPopup(escapeHTML(data.venues[i].title)+'<br><a href="/venue/'+data.venues[i].slug+'">More details</a>');
						markerGroup.addLayer(marker);
					}
				}
			}
		});


});
