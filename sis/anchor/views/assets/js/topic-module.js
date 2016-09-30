var topicModule = (function () {

    var getAddTopicButtons = function () {
        return $('a[id^=add_new_topic_]');
    };

    var getTimeTopics = function () {
        return $('input[id^=time_]');
    };

    var getTopics = function () {
        return $('textarea[id^=topic_]');
    };

    var getTeachers = function () {
        return $('select[id^=teacher_]');
    };

    var getNoteTopics = function () {
        return $('textarea[id^=note_topic_]');
    };

    var getContentTopics = function () {
        return $('input[id^=content_topic_]');
    };

    var getHTMLTopics = function () {
        return $('div[id^=topic_html_]');
    };

    var validation = function (topic, teacher) {
        var isValidate = true;
        var topic_val = $(topic).val();
        var teacher_selected = $(teacher).find(':selected').val();
        if (!topic_val.length) {
            addErrorClass($(topic).parent(), 'Tên chuyên đề không được để trống');
        }
        if (!teacher_selected.length || parseInt(teacher_selected) === 0) {
            addErrorClass($(teacher).parent(), 'Bạn phải chọn giảng viên thực hiện');
        } else {
            isValidate = false;
            removeErrorClass($(topic).parent());
            removeErrorClass($(teacher).parent());
        }

        return isValidate;
    };

    var addErrorClass = function (node, message) {
        if (!$(node).hasClass('has-error')) {
            $(node).addClass('has-error');
        }

        var helpBlock = $(node).find('p.help-block');
        if (helpBlock.length) {
            helpBlock.html(message);
        } else {
            $(node).append('<p class="help-block">' + message + '</p>');
        }
    };

    var removeErrorClass = function (node) {
        if ($(node).hasClass('has-error')) {
            $(node).removeClass('has-error');
        }

        var helpBlock = $(node).find('p.help-block');
        if (helpBlock.length) {
            helpBlock.remove();
        }
    };

    var generateHTML = function (node, contents) {
        var html = $(node).html('');
        html += '<table class="table table-hover">';
        html += '<thead><tr><th>Tên chuyên đề</th><th>Giảng viên thực hiện</th><th>Ghi chú</th></tr></thead>';
        html += '<tbody>';
        for (var i = 0; i < contents.length; i++) {
            var content = contents[i];
            html += '<tr>';
            html += '<td>' + content.time + ' ' + content.name + '</td>';
            html += '<td>' + content.teacher + '</td>';
            html += '<td>' + content.note + '</td>';
            html += '</tr>';
        }
        html += '</tbody>';
        html += '</table>';

        $(node).append(html);
    };

    var init = function () {
        var addTopicButtons = getAddTopicButtons(),
            timeTopics = getTimeTopics(),
            nameTopics = getTopics(),
            teachers = getTeachers(),
            notes = getNoteTopics(),
            contentTopics = getContentTopics(),
            htmlTopics = getHTMLTopics();

        $.each(addTopicButtons, function (index, element) {
            $(element).on('click', function (e) {
                // validation
                var is_validate = validation(nameTopics[index], teachers[index]);
                console.log('validate: ', is_validate);
                // if have no validate
                if (!is_validate) {
                    var content = $(contentTopics[index]).val();
                    if (content.length) {
                        content = $.parseJSON(content);
                    } else {
                        content = [];
                    }

                    var time = $(timeTopics[index]).val(),
                        name = $(nameTopics[index]).val(),
                        teacher = $(teachers[index]).find(':selected').val(),
                        note = $(notes[index]).val();

                    content.push({time: time, name: name, teacher: teacher, note: note});

                    generateHTML($(htmlTopics[index]), content);
                }

                e.preventDefault();
            });
        });
    };
    return {
        init: init
    };
}).call(this);