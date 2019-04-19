/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/
$(document).ready(function() {
	if ($('ul.timezones li').size() > 10) {
		$('#searchWrapper').show();
		$('#searchTerm').change(function() {  searchTimeZones(); });
		$('#searchTerm').keyup(function() {  searchTimeZones(); });	
	}
});

function searchTimeZones() {
	var searchTerm = $('#searchTerm').val().trim().toLowerCase();
	if (searchTerm == '') {
		$('ul.timezones li').show();
	} else {
		$('ul.timezones li').each(function() {
			var elem = $(this);
			var text = elem.text().toLowerCase();
			if (text.search(searchTerm) == -1) {
				elem.hide();
			} else {
				elem.show();
			}
		});
	}
	$('#searchTerm').focus();
}

