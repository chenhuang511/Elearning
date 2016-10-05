var loadPresentation = function() {
	var presentation = localStorage.getItem('preview-string');
	var config = JSON.parse(localStorage.getItem('preview-config'));

	if (presentation) {
		document.body.innerHTML = presentation;
        console.log(presentation);
	//	document.body.className = config.surface + " " + document.body.className;
	}
};
