/*
@author Matt Crinklaw-Vogt
*/
define(['libs/backbone'],
function(Backbone) {

	var Modal = Backbone.View.extend({
		className: "table modal hide",
		events: {
			"click .ok": "okClicked",
			"input input[name='tableColumn']": "setColumn",
			"input input[name='tableRow']": "setRow",
			"hidden": "hidden"
		},
		initialize: function() {
			
		},
		show: function(cb) {
			this.cb = cb;
			return this.$el.modal('show');
		},
		okClicked: function() {
			if (this.checkCol && this.checkRow && !this.$el.find(".ok").hasClass("disabled")) {
				this.cb(this.col,this.row);
				return this.$el.modal('hide');
			}
		},

		setColumn: function() {
			var number_column = this.$el.find('input[name="tableColumn"]').val();
			var reg = /\d$/;
			if(!reg.test(number_column) || number_column > 50){
       			this.$el.find('[data-toggle="toggle"]').tooltip();
				this.checkCol = false;
			}
			else {
				this.checkCol = true;
				return this.col = number_column;
			}
		},

		setRow: function() {
			var number_row = this.$el.find('input[name="tableRow"]').val();
			var reg = /\d$/;
			if(!reg.test(number_row) || number_row > 50){
       			this.$el.find('[data-toggle="toggle"]').tooltip();
				this.$el.find(".ok").addClass("disabled");
				this.checkRow = false;
			}
			else {
				this.$el.find(".ok").removeClass("disabled");
				this.checkRow = true;
				return this.row = number_row;
			}
		},
		
		render: function() {
			var _this = this;
			this.$el.html(JST["tantaman.web.widgets/TableModal"]);
			this.$el.modal();
			this.$el.modal("hide");
			return this.$el;
		},
		constructor: function TableModal() {
		Backbone.View.prototype.constructor.apply(this, arguments);
	}
	});

	return {
		get: function(options) {
		    var previous = new Modal();
			previous.render();
			return previous;
		},
		ctor: Modal
	};
});
