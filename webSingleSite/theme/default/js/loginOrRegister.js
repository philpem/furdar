/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/


function loginOrRegisterRemoveEvent(id) {
	$('#afterGetUserEvent'+id).remove();

	var wrapper = $('#afterGetUserWrapper');
	if (wrapper.find(".afterGetUserEvent").length == 0 && wrapper.find(".afterGetUserArea").length == 0) {
		wrapper.remove();
	}

	$.ajax({
		url: "/you/aftergetuserapi?removeEventId="+id
	});
}


function loginOrRegisterRemoveArea(id) {
	$('#afterGetUserArea'+id).remove();

	var wrapper = $('#afterGetUserWrapper');
	if (wrapper.find(".afterGetUserEvent").length == 0 && wrapper.find(".afterGetUserArea").length == 0) {
		wrapper.remove();
	}

	$.ajax({
		url: "/you/aftergetuserapi?removeAreaId="+id
	});
}
