define(['strut/storage/model/ActionHandlers',
        'tantaman/web/widgets/ErrorModal'],
    function(ActionHandlers, ErrorModal) {
    'use strict';
    function SaveLogo(editorModel, storageInterface) {
        this.model = editorModel;
        this.storageInterface = storageInterface;
    };

    SaveLogo.prototype = {
        save: function() {
            var fileName = this.model.fileName();
            ActionHandlers.save(this.storageInterface, this.model, fileName, ErrorModal.show);
        }
    };

    return SaveLogo;
});
