/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
*/





function clearArea() {
	$('#AreaFieldsWrapper').html('<input type="hidden" name="fieldAreaSearchText" value=""><input type="hidden" name="fieldAreaSlug" value=""><input type="hidden" name="fieldAreaSlugSelected" value="">');
	$('#NewVenueForm').submit();
}
