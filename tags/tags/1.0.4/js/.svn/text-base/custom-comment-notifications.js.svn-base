jQuery(document).ready(function($) {
    $('#ccn-template').change(function() {
        if($('#ccn-template').val() == 'author_comment') {
            $('#ccn-protect-author').show();
        } else {
            $('#ccn-protect-author').hide();
        }
	if($('#ccn-template').val() == 'moderator_comment' || $('#ccn-template').val() == 'moderator_pingback' || $('#ccn-template').val() == 'moderator_trackback') {
	    $('#ccn-allow-author-moderation').show();
	} else {
	    $('#ccn-allow-author-moderation').hide();
	}
    });
});
