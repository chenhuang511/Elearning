$.validator.addMethod("noLeadingWhitespace", function(value, element) {
	return this.optional(element) || /^\S.*$/i.test(value);
}, "No leading whitespace allowed");

$.validator.addMethod("noTrailingWhitespace", function(value, element) {
	return this.optional(element) || /^.*\S$/i.test(value);
}, "No trailing whitespace allowed");

//validate moodle user_name
$.validator.addMethod("onlyLowercase", function(value, element) {
    return this.optional(element) || /^[^A-Z]+$/.test(value);
}, "only Lowercase allowed");

$.validator.addMethod("usernameCheck", function(value, element) {
    return this.optional(element) || /^[a-z0-9_@\-\.]+$/.test(value);
}, "The username can only contain alphanumeric lowercase characters (letters and numbers), underscore (_), hyphen (-), period (.) or at symbol (@).");

//validate moodle password
$.validator.addMethod("checkPass", function(value, element) {
    return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/.test(value);
}, "Passwords must have at least 1 digit(s).");


jQuery.validator.addMethod("memberOf", function(value, element, arr) {
    return $.inArray(value, arr) != -1;
}, "Data provided must match one of the provided options.");

jQuery.validator.addMethod("notMemberOf", function(value, element, arr) {
    return $.inArray(value, arr) == -1;
}, "Data provided must NOT match one of the provided options.");

jQuery.validator.addMethod("matchFormField", function(value, element, field) {
    return value === $(element).closest('form').find("input[name=" + field + "]").val();
}, "The specified fields must match.");

jQuery.validator.addMethod("notMatchFormField", function(value, element, field) {
    return value !== $(element).closest('form').find("input[name=" + field + "]").val();
}, "The specified fields must NOT match.");
