define(['./ImpressGenerator'],
function(ImpressGenerator) {
	'use strict';

	var service = {
		displayName: 'Impress',
		id: 'impress',
		capabilities: {
			freeformStepping: true
		},
		generate: function(deckAttrs) {
			return ImpressGenerator.render(deckAttrs);
		},

		getSlideHash: function(editorModel) {
			return '#/step-' + (editorModel.activeSlideIndex() + 1);
		}
	};

    tinymce.init({
					selector: '#impress',
					plugins: [
						'advlist autolink lists link image charmap print preview anchor',
						'searchreplace visualblocks code fullscreen',
						'insertdatetime media table contextmenu paste code'
					],
					toolbar: 'none',
					menubar: 'none',
                    statusbar: false,
					height: 200,
                    //min_height: 200,
                    //autoresize: true,
					content_css: [
   					 '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
					'//www.tinymce.com/css/codepen.min.css'
                    ]
				});

	return {
		initialize: function(registry) {
			registry.register({
				interfaces: 'strut.presentation_generator'
			}, service);
		}
	};
});
