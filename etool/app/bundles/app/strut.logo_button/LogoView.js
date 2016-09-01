define(['libs/backbone',
		'./LogoModel',
		'./SaveLogo',
		'strut/storage/model/StorageInterface',
		'css!styles/logo_button/logo.css'],
function(Backbone, LogoModel, SaveLogo, StorageInterface) {
	'use strict';
	return Backbone.View.extend({
		className: 'logo-group btn-group',
		events: {
			'click .actSave': '_save'
		},


		initialize: function() {
			this._template = JST['strut.logo_button/Logo'];
			this._editorModel =  this.options.editorModel;
			var storageInterface = new StorageInterface(this._editorModel.registry);
			this._saveLogo = new SaveLogo(this._editorModel, storageInterface);
			this.model = new LogoModel(this.options.editorModel);
			delete this.options.editorModel;
		},

		_save: function () {
			// @TODO: Handle event
			this._saveLogo.save();
		},

		render: function() {
			this.$el.html(this._template());

			var $dropdown = this.$el.find('.dropdown-menu');
			this.model.items.forEach(function(item) {
				$dropdown.append(item.render().$el);
			}, this);

			return this;
		},

		constructor: function LogoView() {
			Backbone.View.prototype.constructor.apply(this, arguments);
		}
	});
});
