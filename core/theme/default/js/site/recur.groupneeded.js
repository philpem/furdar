/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/
$( document ).ready( function() {
	$('#GroupSearchText').change(function() { groupSearchChanged(); });
	$('#GroupSearchText').keyup(function() { groupSearchChanged(); });
	groupSearchChanged();
});

var lastGroupSearchValue = '';
var groupSearchAJAX;

function groupSearchChanged() {
	var groupSearchValue = $('#GroupSearchText').val();

	if (groupSearchValue == '') {
		lastGroupSearchValue = '';
		$('#GroupSearchList').empty();
	} else if (groupSearchValue != lastGroupSearchValue) {
		lastGroupSearchValue = groupSearchValue;
	
		if (groupSearchAJAX) {
			groupSearchAJAX.abort();
		}
	
		groupSearchAJAX = $.ajax({
				url: "/api1/groups.json?includeDeleted=no&search=" + groupSearchValue,
			}).success(function ( data ) {
				var out = '';
				for(i in data.data) {
					var group = data.data[i];
					out += '<li class="group">';
					out += '<form action="" method="POST" class="oneActionFormRight">';
					out += '<input type="hidden" name="intoGroupSlug" value="'+group.slug+'">';
					out += '<input type="submit" value="Put event in this group" class="button">';
					out += '</form>';
					out += '<div class="title">'+escapeHTML(group.title)+'</div>';
					out += '<div class="afterOneActionFormRight"></div></li>';
				}
				$('#GroupSearchList').empty();
				$('#GroupSearchList').append(out);
			});

	}
}


