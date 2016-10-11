
var virtualclassequipmentModule = (function () {

    Array.prototype.last = function () {
        return this[this.length - 1];
    };

    var getAddVirtual_class_equipmentButtons = function () {
        return $('a[id^=add_new_virtual_class_equipment_]');
    };

    var getTimeVirtual_class_equipments = function () {
        return $('input[id^=time_]');
    };

    var getVirtual_class_equipments = function () {
        return $('input[id^=virtual_class_equipment_]');
    };

    var getDescriptionVirtual_class_equipments = function () {
        return $('textarea[id^=description_virtual_class_equipment_]');
    };

    var getContentVirtual_class_equipments = function () {
        return $('input[id^=content_virtual_class_equipment_]');
    };

    var getHTMLVirtual_class_equipments = function () {
        return $('div[id^=virtual_class_equipment_html_]');
    };

    var getQuantityVirtual_class_equipments = function () {
        return $('input[id^=quantity_virtual_class_equipment_]');
    };

    var getStatusVirtual_class_equipments = function () {
        return $('select[id^=status_virtual_class_equipment_]');
    };

    var getIdOfLastObject = function (list) {
        var lastObj = list.last();
        return lastObj.id;
    }

    var validation = function (virtual_class_equipment, description_virtual_class_equipment, quantity_virtual_class_equipment) {
        var isValidate = true;
        var virtual_class_equipment_val = $(virtual_class_equipment).val();
        var description_virtual_class_equipment_val = $(description_virtual_class_equipment).val();
        var quantity_virtual_class_equipment_val = $(quantity_virtual_class_equipment).val();

        if (!virtual_class_equipment_val.length) {
            addErrorClass($(virtual_class_equipment).parent(), 'Tên thiết bị không được để trống');
        } else {
            removeErrorClass($(virtual_class_equipment).parent());
        }

        if (!description_virtual_class_equipment_val.length) {
            addErrorClass($(description_virtual_class_equipment).parent(), 'Mô tả thiết bị không được để trống');
        } else {
            removeErrorClass($(description_virtual_class_equipment).parent());
        }

        if (!quantity_virtual_class_equipment_val.length) {
            addErrorClass($(quantity_virtual_class_equipment).parent(), 'Số lượng thiết bị không được để trống hoặc sai định dạng');
        } else {
            removeErrorClass($(quantity_virtual_class_equipment).parent());
        }

        if (virtual_class_equipment_val.length !== 0 &&
            description_virtual_class_equipment_val.length !== 0 &&
            quantity_virtual_class_equipment_val.length !== 0)
        {
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
            html += '<thead><tr><th>Tên thiết bị</th><th>Mô tả</th><th>Số lượng</th><th>Trạng thái</th><th>Quản lí</th></tr></thead>';
            html += '<tbody>';
            for (var i = 0; i < contents.length; i++) {
                var content = contents[i];
                html += '<tr>';
                html += '<td>' + content.name + '</td>';
                html += '<td>' + content.description + '</td>';
                html += '<td>' + content.quantity + '</td>';
                html += '<td>' + content.statusname + '</td>';
                html += '<td>' + '<a href="#" id="' + 'remove_' + node.attr('id') + '_' + content.id + '">' + '<i class="fa fa-times" aria-hidden="true"></i> Xóa</a>' + '</td>';
                html += '</tr>';
            }
            html += '</tbody>';
            html += '</table>';

            node.html(html);
        }
    };

    var resetInput = function (timeInput, virtual_class_equipmentInput, quantityInput, descriptionInput, statusInput) {
        timeInput.val('');
        virtual_class_equipmentInput.val('');
        descriptionInput.val('');
        statusInput.prop('selectedIndex', 1);
        quantityInput.val('');
    };

    var removeVirtual_class_equipmentTolList = function (id, virtual_class_equipments) {
        for (var i = 0; i < virtual_class_equipments.length; i++) {
            var obj = virtual_class_equipments[i];

            if (obj.id === id) {
                virtual_class_equipments.splice(i, 1);
            }
        }
        return virtual_class_equipments;
    };

    var init = function () {
        var addVirtual_class_equipmentButtons = getAddVirtual_class_equipmentButtons(),
            timeVirtual_class_equipments = getTimeVirtual_class_equipments(),
            nameVirtual_class_equipments = getVirtual_class_equipments(),
            descriptionVirtual_class_equipments = getDescriptionVirtual_class_equipments(),
            quantityVirtual_class_equipments = getQuantityVirtual_class_equipments(),
            statusVirtual_class_equipments = getStatusVirtual_class_equipments(),
            contentVirtual_class_equipments = getContentVirtual_class_equipments(),
            htmlVirtual_class_equipments = getHTMLVirtual_class_equipments();

        $.each(addVirtual_class_equipmentButtons, function (index, element) {
            $(element).on('click', function (e) {
                // validation
                var is_validate = validation(nameVirtual_class_equipments[index], descriptionVirtual_class_equipments[index], quantityVirtual_class_equipments[index]);
                // if have no validate
                if (!is_validate) {
                    var lastid = 0;
                    var content = $(contentVirtual_class_equipments[index]).val();
                    if (content.length) {
                        content = $.parseJSON(content);
                        lastid = getIdOfLastObject(content);
                    } else {
                        content = [];
                    }

                    var time = $(timeVirtual_class_equipments[index]).val();
                    var name = $(nameVirtual_class_equipments[index]).val();
                    var description = $(descriptionVirtual_class_equipments[index]).val();
                    var quantity = $(quantityVirtual_class_equipments[index]).val();
                    var status = $(statusVirtual_class_equipments[index]).find(':selected').val();
                    var statusname = $(statusVirtual_class_equipments[index]).find(':selected').text();

                    content.push({
                        id: (lastid + 1),
                        timevirtual_class_equipment: time,
                        name: name,
                        description: description,
                        quantity: quantity,
                        status: status,
                        statusname: statusname,
                    });
                    generateHTML($(htmlVirtual_class_equipments[index]), content);
                    // update content virtual_class_equipment
                    $(contentVirtual_class_equipments[index]).val(JSON.stringify(content));
                    //reset values that choose
                    resetInput($(timeVirtual_class_equipments[index]), $(nameVirtual_class_equipments[index]), $(descriptionVirtual_class_equipments[index]), $(quantityVirtual_class_equipments[index]), $(statusVirtual_class_equipments[index]));
                    $(htmlVirtual_class_equipments[index]).click(function (e) {
                        var id = e.target.id;
                        id = parseInt(id.substr(-1));
                        content = removeVirtual_class_equipmentTolList(id, content);
                        // generate html
                        generateHTML($(htmlVirtual_class_equipments[index]), content);
                        // update content virtual_class_equipment
                        if (content.length === 0) {
                            $(contentVirtual_class_equipments[index]).val('');
                        } else {
                            $(contentVirtual_class_equipments[index]).val(JSON.stringify(content));
                        }
                    });
                }
                e.preventDefault();
                return 0;
            });
        });
    };
    return {
        init: init
    };
}).call(this);