var topicModule = (function () {

    Array.prototype.last = function () {
        return this[this.length - 1];
    };

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

    var getHiddenTeacherIds = function () {
        return $('input[id^=hidden_teacher_id_]');
    };

    var getIdOfLastObject = function (list) {
        var lastObj = list.last();
        return lastObj.id;
    }

    var validation = function (topic, teacher) {
        var isValidate = true;
        var topic_val = $(topic).val();
        var teacher_selected = $(teacher).find(':selected').val();
        if (!topic_val.length) {
            addErrorClass($(topic).parent(), 'Tên chuyên đề không được để trống');
        } else {
            removeErrorClass($(topic).parent());
        }
        if (!teacher_selected.length || parseInt(teacher_selected) === 0) {
            addErrorClass($(teacher).parent(), 'Bạn phải chọn giảng viên thực hiện');
        } else {
            removeErrorClass($(teacher).parent());
        }

        if (topic_val.length && (teacher_selected.length && parseInt(teacher_selected) !== 0)) {
            isValidate = false;
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
        if (contents.length === 0) {
            node.html('');
        } else {
            var html = '';

            html += '<table class="table table-hover">';
            html += '<thead><tr><th>Tên chuyên đề</th><th>Giảng viên thực hiện</th><th>Ghi chú</th><th></th></tr></thead>';
            html += '<tbody>';
            for (var i = 0; i < contents.length; i++) {
                var content = contents[i];
                html += '<tr>';
                if (content.timetopic.length) {
                    html += '<td>' + '<strong>' + content.timetopic + '</strong>' + ' ' + content.name + '</td>';
                } else {
                    html += '<td>' + content.name + '</td>';
                }

                html += '<td>' + content.teachername + '</td>';
                html += '<td>' + content.note + '</td>';
                html += '<td>' + '<a href="#" id="' + 'remove_' + node.attr('id') + '_' + content.id + '">' + '<i class="fa fa-times" aria-hidden="true"></i> Xóa</a>' + '</td>';
                html += '</tr>';
            }
            html += '</tbody>';
            html += '</table>';

            node.html(html);
        }
    };

    var resetInput = function (timeInput, topicInput, teacherInput, noteInput) {
        timeInput.val('');
        topicInput.val('');
        noteInput.val('');
        teacherInput.prop('selectedIndex', 0);
    };

    var removeTopicTolList = function (id, topics) {
        for (var i = 0; i < topics.length; i++) {
            var obj = topics[i];

            if (obj.id === id) {
                topics.splice(i, 1);
            }
        }
        return topics;
    };

    var init = function () {
        var addTopicButtons = getAddTopicButtons(),
            timeTopics = getTimeTopics(),
            nameTopics = getTopics(),
            teachers = getTeachers(),
            notes = getNoteTopics(),
            contentTopics = getContentTopics(),
            htmlTopics = getHTMLTopics(),
            hiddenTeacherIds = getHiddenTeacherIds();

        $.each(addTopicButtons, function (index, element) {
            $(element).on('click', function (e) {
                // validation
                var is_validate = validation(nameTopics[index], teachers[index]);
                // if have no validate
                if (!is_validate) {
                    var lastid = 0;
                    var content = $(contentTopics[index]).val();
                    if (content.length) {
                        content = $.parseJSON(content);
                        lastid = getIdOfLastObject(content);
                    } else {
                        content = [];
                    }

                    var time = $(timeTopics[index]).val(),
                        name = $(nameTopics[index]).val(),
                        teacherid = $(teachers[index]).find(':selected').val(),
                        teachername = $(teachers[index]).find(':selected').text(),
                        note = $(notes[index]).val();

                    content.push({
                        id: (lastid + 1),
                        timetopic: time,
                        name: name,
                        teacherid: teacherid,
                        teachername: teachername,
                        note: note
                    });

                    generateHTML($(htmlTopics[index]), content);
                    // update content topic
                    $(contentTopics[index]).val(JSON.stringify(content));
                    //reset values that choose
                    resetInput($(timeTopics[index]), $(nameTopics[index]), $(teachers[index]), $(notes[index]));
                    $(htmlTopics[index]).click(function (e) {
                        var id = e.target.id;
                        id = parseInt(id.substr(-1));
                        content = removeTopicTolList(id, content);
                        // generate html
                        generateHTML($(htmlTopics[index]), content);
                        // update content topic
                        if (content.length === 0) {
                            $(contentTopics[index]).val('');
                            $(hiddenTeacherIds[index]).val('');
                        } else {
                            $(contentTopics[index]).val(JSON.stringify(content));
                        }
                    });
                }

                e.preventDefault();
            });
        });

        $.each(teachers, function (index, element) {
            var teacherid = $(hiddenTeacherIds[index]).val();
            if (teacherid.length) {
                $(element).find('option[value=' + teacherid + ']').attr('selected', 'selected');
            }
            $(element).on('change', function () {
                var id = $(this).find(':selected').val();
                if (parseInt(id) !== 0) {
                    $(hiddenTeacherIds[index]).val(id);
                } else {
                    $(hiddenTeacherIds[index]).val('');
                }
            });
        });
    };
    return {
        init: init
    };
}).call(this);