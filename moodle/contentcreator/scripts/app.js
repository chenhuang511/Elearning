(function ($) {
    'use strict';
    function actionAfterDone(element) {
        var $this = $(element);
        var root = $this.closest('.list-slide-content');
        root.find('.update-input-value').hide();
        root.find('.update-do-action').hide();
        root.find('.btn-update-title').show();
        root.find('.slide-title').show();
    }
    $(document).ready(function () {
        $('.btn-update-title').bind('click', function (e) {
            var $this = $(this);
            var parent = $this.closest('.list-slide-content');
            $this.hide();
            parent.find('.slide-title').hide();
            parent.find('.update-input-value').show();
            parent.find('.update-do-action').show();
            parent.find('.btn-save-title').prop('disabled', false);
        });
        $('.btn-cancel-title').bind('click', function (e) {
            actionAfterDone(this);
        });

        $('.btn-save-title').bind('click', function (e) {
            var $this = $(this);
            $this.prop('disabled', true);
            var parent = $this.closest('.list-slide-content');
            var title = parent.find('.slide-title');
            var oldTitle = title.text();
            var inputTitle = parent.find('.input-update-title');
            var newTitle = inputTitle.val();
            if (newTitle != oldTitle) {
                $.ajax({
                    url: '/contentcreator/slidestorage.php',
                    type: 'POST',
                    data: {
                        action: 'updateSlideTitle',
                        filename: newTitle + '.strut',
                        data: JSON.stringify({'id': title.attr('data-slide') || 0}),
                    },
                    success: function (response) {
                        var res = JSON.parse(response);
                        console.log(res);
                        title.text(res.title);
                        inputTitle.val(res.title);
                        actionAfterDone($this);
                    },
                    error: function (e) {
                        console.log(e.message);
                        inputTitle.val(oldTitle);
                        actionAfterDone($this);
                    }
                });
            } else {
                $this.prop('disabled', false);
            }

        })

    });
})(jQuery || $);
