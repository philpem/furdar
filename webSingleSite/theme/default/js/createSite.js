/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/
var hasUserEditedSlug = false;
var titleObj;
var slugObj;

var titleFunc = function() {
	if (!hasUserEditedSlug) {
		slug = titleObj.val().replace(/\W/g, '').toLowerCase();
		slugObj.val( slug  );
	}
};

var slugFunc = function() {
	hasUserEditedSlug = true;
};

$(document).ready(function() {
	
	titleObj = $('#CreateForm_title');
	titleObj.change(titleFunc);
	titleObj.keyup(titleFunc);
	
	slugObj = $('#CreateForm_slug')
	slugObj.keyup(slugFunc);
});


