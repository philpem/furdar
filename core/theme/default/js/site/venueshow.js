/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/
var map;
var marker;

$(document).ready(function() {
	
	if (mapData.lat && mapData.lng) {
		map = L.map('Map', { 'scrollWheelZoom':false });
		configureBasicMap(map);
		map.setView([mapData.lat,mapData.lng], 13);

		marker = L.marker([mapData.lat,mapData.lng]);
		marker.addTo(map);
	} else {
		$('#Map').hide();
	}

	var PushToChildAreaForm = $('form#PushToChildAreaForm');
	if (PushToChildAreaForm.length) {
		$('form#PushToChildAreaForm input[name="newAreaTitle"]').keyup(function() {
			if ($(this).val() != '') {
				$('form#PushToChildAreaForm li.newarea input[name="area"]').prop("checked", true);
			}
		});
	}

});

