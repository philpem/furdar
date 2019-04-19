/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/

var notDuplicateOfEventSlugs = "";

$(document).ready(function() {
	$('#EditEventForm').change(function() {
		loadData();
	});
	loadData();

});

var loadDataAJAX;

var startDate, startHours, startMins, endDate, endHours, endMins, timezone;

function loadData() {
	// cancel old loads
	if (loadDataAJAX) {
		loadDataAJAX.abort();
	}
	// set loading indicators
    var currentStartDate = $('#FieldStartAtWrapper input[type="text"]').val();
    var currentStartHours = $('#FieldStartAtWrapper #event_edit_form_start_at_time_hour').val();
    var currentStartMins = $('#FieldStartAtWrapper #event_edit_form_start_at_time_minute').val();
    var currentEndDate = $('#FieldEndAtWrapper input[type="text"]').val();
    var currentEndHours = $('#FieldEndAtWrapper  #event_edit_form_end_at_time_hour').val();
    var currentEndMins = $('#FieldEndAtWrapper  #event_edit_form_end_at_time_minute').val();
    var currentTimezone = $('#FieldTimeZoneWrapper select').val();
	if (currentStartDate != startDate || currentStartHours != startHours || currentStartMins != startMins || currentEndDate != endDate || currentEndHours != endHours || currentEndMins != endMins || currentTimezone != timezone) {
		$('#ReadableDateTimeRange').html('&nbsp;');
		startDate = currentStartDate;
		startHours = currentStartHours;
		startMins = currentStartMins;
		endDate = currentEndDate;
		endHours = currentEndHours;
		endMins = currentEndMins;
		timezone = currentTimezone;
	}
	// load
	var dataIn = $('#EditEventForm').serialize();
	loadDataAJAX = $.post('/event/'+editingEventSlug+'/edit/details/editing.json', dataIn,function(data) {
		$('#ReadableDateTimeRange').html(data.readableStartEndRange);
	});
}
