define(['./ComponentButton', 'tantaman/web/widgets/TableModal'],
	function(ComponentButton, TableModal) {
		'use strict';

		/**
		 * @class TableButton
		 * @augments ComponentButton
		 */
		return ComponentButton.extend({
			/**
			 * Initialize TableButton.
			 */
			initialize: function() {
				ComponentButton.prototype.initialize.apply(this, arguments);
				this._modal = TableModal.get(this.options);
				this._tableEl = this._tableEl.bind(this);
			},

			/**
			 * React on button click.
			 * @private
			 */
			_clicked: function() {
				this._modal.show(this._tableEl);
			},

			/**
			 * Add importent component to the slide.
			 * @private
			 */
			_tableEl: function(column,row) {
				this.options.editorModel.addComponent({
					column: column,
					row: row,
					type: this.options.componentType
				});
			},
			constructor: function TableButton() {
				ComponentButton.prototype.constructor.apply(this, arguments);
			}
		});
	})