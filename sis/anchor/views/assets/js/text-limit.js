/**
 * Limit Text
 */
$(function() {
	var limit = 300;
	$('.content-post').each(function() {
		var chars = $(this).text();
		if (chars.length > limit) {
			var visiblePart = $("<span> "+ chars.substr(0, limit-1) +"</span>");
			var dots = $("<span class='dots'>[...] </span>");
			$(this).empty()
				.append(visiblePart)
				.append(dots)

		}

	})
				
});