/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/
/** This is used on event show page **/
$(document).ready(function() {
	$('form.UserAttendingOptions').change(function(){
		var formObj = $(this);
		var savingIndicatorObj = formObj.children(".savingIndicator");
		var savedIndicatorObj = formObj.children(".savedIndicator");
		savingIndicatorObj.show();
		savedIndicatorObj.hide();
		var ajax = $.ajax({
			url: formObj.attr('action'),
			type: 'POST',
			data : formObj.serialize()
		}).success(function ( eventdata ) {
			savingIndicatorObj.hide();
			savedIndicatorObj.show();
			$( "#UserAttendingListAjaxWrapper" ).load( "/event/"+eventData.slug+"/userAttendance.html" );
		});
		var attendingObj = formObj.children('select[name="attending"]');
		var privacyWrapperObj = formObj.children(".UserAttendingPrivacyOptionsWrapper");
		if (attendingObj.val() == 'no' || attendingObj.val() == 'unknown') {
			privacyWrapperObj.hide();
		} else {
			privacyWrapperObj.show();
		}
	});
});
