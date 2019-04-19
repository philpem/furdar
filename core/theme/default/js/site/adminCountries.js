/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/
$(document).ready(function() {
	$('#searchWrapper').show();
	$('#searchTerm').change(function() {  searchCountries(); });
	$('#searchTerm').keyup(function() {  searchCountries(); });	
});

function searchCountries() {
	var searchTerm = $('#searchTerm').val().trim().toLowerCase();
	if (searchTerm == '') {
		$('ul.selectCountries li').show();
	} else {
		$('ul.selectCountries li').each(function() {
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

function selectAll() {
	$('#searchTerm').val('');
	searchCountries();
	$('#CountriesForm input[type=checkbox]').prop('checked', true);
}

function selectNone() {
	$('#searchTerm').val('');
	searchCountries();
	$('#CountriesForm input[type=checkbox]').prop('checked', false)
}

