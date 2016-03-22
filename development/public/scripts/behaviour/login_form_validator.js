function ShowLoginError() {
    $(document).ready(function() {
        $("#loginValidationTips").slideDown();
    });
}


function IsLoginFormValid(loginFieldId, passwordFieldId, submitButtonId) {
    var submitButton= document.getElementById(submitButtonId);

    if (submitButton.value == "Sign In") {
        var userName = document.getElementById(loginFieldId).value.trim();
        var password = document.getElementById(passwordFieldId).value.trim();

        if (((userName.length >= Settings.MIN_LOGIN_NAME_LENGTH) && (userName.length <= Settings.MAX_LOGIN_NAME_LENGTH)) && ((password.length >= Settings.MIN_PASSWORD_LENGTH) && (password.length <= Settings.MAX_PASSWORD_LENGTH)) && Settings.LOGIN_PATTERN.test(userName)) {

            $("#loginValidationTips").slideUp();
            return true;
        }

        ShowLoginError();

        return false;
    }

    $("#loginValidationTips").slideUp();
    return true;
}

