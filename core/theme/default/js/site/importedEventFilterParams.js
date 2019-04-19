/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/
var importedEventListFromNowInput;
var importedEventListFromDiv;
var importedEventListFromInput;
$(document).ready(function() {
	importedEventListFromNowInput = $('form.filterListImportedEvent input[name="fromNow"]');
	if (importedEventListFromNowInput.size() > 0) {
		importedEventListFromDiv = $('form.filterListImportedEvent #importedEventListFilterFromWrapper');
		importedEventListFromInput = $('form.filterListImportedEvent #importedEventListFilterFromWrapper input');
		if (importedEventListFromNowInput.attr('checked')) {
			importedEventListFromDiv.hide();
		} else {
			importedEventListFromDiv.show();
		}	
		importedEventListFromNowInput.change(function() {
			if (importedEventListFromNowInput.is(':checked')) {
				importedEventListFromDiv.hide();
			} else {
				importedEventListFromDiv.show();
			}
		});
		importedEventListFromInput.datepicker({
			dateFormat:'d MM yy'
		});
	}
});
