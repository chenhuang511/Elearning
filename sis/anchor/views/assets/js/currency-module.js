var currencyModule = (function () {
    var changeValue = function (input, hidden) {
        var value = input.val();

        if (checkNumber(value) == true) {
            hidden.val(value);
        } else {
            input.val('');
        }
    };

    var checkNumber = function (value) {
        var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
        if (numberRegex.test(value)) {
            return true;
        }

        return false;
    };

    var init = function (accounting, inputs, hiddens) {
        $.each(inputs, function (index, element) {
            $(element).on('change', function () {
                changeValue($(element), $(hiddens[index]));

                var number = $(element).val();
                $(element).val(accounting.formatMoney(number, '') + ' VNĐ');
            });

            $(element).on('keydown', function () {
                var value = $(hiddens[index]).val();
                if (value.length > 0) {
                    changeValue($(element), $(hiddens[index]));
                }
            });
        });
    };

    return {
        init: init
    };
}).call(this);