var topicModule = (function () {

    var confirmRoom = '#confirmRoom';

    Array.prototype.last = function () {
        return this[this.length - 1];
    };

    var getAddTopicButtons = function () {
        return $('a[id^=add_new_topic_]');
    };

    var getTimeTopics = function () {
        return $('select[id^=time_]');
    };

    var getTopics = function () {
        return $('input[id^=topic_name_]');
    };

    var getTeachers = function () {
        return $('select[id^=teacher_]');
    };

    var getRooms = function () {
        return $('select[id^=room_]');
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

    var validation = function (topic, teacher, room) {
        var isValidate = true;
        var topic_val = $(topic).val();
        var teacher_selected = $(teacher).find(':selected').val();
        var room_selected = $(room).find(':selected').val();
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
        if (!room_selected.length || parseInt(room_selected) === 0) {
            addErrorClass($(room).parent(), 'Bạn phải chọn phòng học cho chuyên đề');
        } else {
            removeErrorClass($(room).parent());
        }

        if (topic_val.length && (teacher_selected.length && parseInt(teacher_selected) !== 0) && (room_selected.length && parseInt(room_selected) !== 0)) {
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

    var addWarningClass = function (node, message) {
        if (!$(node).hasClass('has-warning')) {
            $(node).addClass('has-warning');
        }

        var helpBlock = $(node).find('p.help-block');
        if (helpBlock.length) {
            helpBlock.html(message);
        } else {
            $(node).append('<p class="help-block">' + message + '</p>');
        }
    }

    var removeWarningClass = function (node) {
        if ($(node).hasClass('has-warning')) {
            $(node).removeClass('has-warning');
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
            html += '<thead><tr><th>Tên chuyên đề</th><th>Thời gian</th><th>Giảng viên thực hiện</th><th>Phòng học</th><th>Ghi chú</th><th></th></tr></thead>';
            html += '<tbody>';
            for (var i = 0; i < contents.length; i++) {
                var content = contents[i];
                html += '<tr>';
                html += '<td>' + content.name + '</td>';
                if (content.timeid != 0) {
                    html += '<td>' + '<strong>' + content.timename + '</strong>' + '</td>';
                } else {
                    html += '<td> </td>';
                }
                html += '<td>' + content.teachername + '</td>';
                html += '<td>' + content.roomname + '</td>';
                html += '<td>' + content.note + '</td>';
                html += '<td>' + '<a href="#" id="' + 'remove_' + node.attr('id') + '_' + content.id + '">' + '<i class="fa fa-times" aria-hidden="true"></i> Xóa</a>' + '</td>';
                html += '</tr>';
            }
            html += '</tbody>';
            html += '</table>';

            node.html(html);
        }
    };

    var resetInput = function (timeInput, topicInput, teacherInput, noteInput, roomInput) {
        timeInput.val('');
        topicInput.val('');
        noteInput.val('');
        teacherInput.prop('selectedIndex', 0);
        roomInput.prop('selectedIndex', 0);
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

    var checkRoom = function (day, roomid, time, node, addButton) {
        var url = '/admin/curriculum/topic/checkroom/' + day + '/' + roomid + '/' + time;

        $.ajax({
            method: "GET",
            url: url,
            dataType: "text",
            success: function (rs) {
                if (rs == 'y') {
                    $(confirmRoom).modal('show');
                    addWarningClass($(node).parent(), '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Phòng học này đã được sử dụng');
                    // disable add new topic
                    $(addButton).attr('disabled', 'disabled');
                }
                if (rs == 'n') {
                    removeWarningClass($(node).parent());
                    $(addButton).removeAttr('disabled');
                }
            },
            fail: function (data) {
                console.log('co loi');
            }
        });
    };

    var init = function () {
        var addTopicButtons = getAddTopicButtons(),
            timeTopics = getTimeTopics(),
            nameTopics = getTopics(),
            teachers = getTeachers(),
            rooms = getRooms(),
            notes = getNoteTopics(),
            contentTopics = getContentTopics(),
            htmlTopics = getHTMLTopics(),
            hiddenTeacherIds = getHiddenTeacherIds();

        $.each(addTopicButtons, function (index, element) {
            $(element).on('click', function (e) {
                var disabled = $(element).attr('disabled');
                if(disabled) {
                    return;
                }
                // validation
                var is_validate = validation(nameTopics[index], teachers[index], rooms[index]);
                // if have no validate
                if (!is_validate) {
                    var lastid = 0;
                    var content = $(contentTopics[index]).val();
                    if (content.length) {
                        content = $.parseJSON(content);
                        lastid = getIdOfLastObject(content);

                        if (lastid === 2) {
                            alert('Bạn chỉ tạo được 2 chuyên đề trong ngày');
                            return;
                        }
                    } else {
                        content = [];
                    }

                    var timeid = $(timeTopics[index]).find(':selected').val(),
                        timename = $(timeTopics[index]).find(':selected').text(),
                        name = $(nameTopics[index]).val(),
                        teacherid = $(teachers[index]).find(':selected').val(),
                        teachername = $(teachers[index]).find(':selected').text(),
                        roomid = $(rooms[index]).find(':selected').val(),
                        roomname = $(rooms[index]).find(':selected').text(),
                        note = $(notes[index]).val();

                    content.push({
                        id: (lastid + 1),
                        timeid: timeid,
                        timename: timename,
                        name: name,
                        teacherid: teacherid,
                        teachername: teachername,
                        roomid: roomid,
                        roomname: roomname,
                        note: note
                    });

                    generateHTML($(htmlTopics[index]), content);
                    // update content topic
                    $(contentTopics[index]).val(JSON.stringify(content));
                    //reset values that choose
                    resetInput($(timeTopics[index]), $(nameTopics[index]), $(teachers[index]), $(notes[index]), $(rooms[index]));
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

        $.each(rooms, function (index, element) {
            var day = $('#topic_day_' + (index + 1)).val();
            $(element).on('change', function () {
                var time = $(timeTopics[index]).find(':selected').val();
                var roomid = $(this).find(':selected').val();
                checkRoom(day, roomid, time, element, addTopicButtons[index]);
            });
        });

        $.each(timeTopics, function (index, element) {
            var day = $('#topic_day_' + (index + 1)).val();
            $(element).on('change', function () {
                var time = $(this).find(':selected').val();
                var roomid = $(rooms[index]).find(':selected').val();
                if (roomid != 0) {
                    checkRoom(day, roomid, time, rooms[index], addTopicButtons[index]);
                }
            });
        });

        $('#confirmRoomButton').on('click', function () {
            $.each(addTopicButtons, function (index, element) {
                var disabled = $(element).attr('disabled');
                if (disabled) {
                    $(element).removeAttr('disabled');
                }
            });
        });

    };
    return {
        init: init
    };
}).call(this);