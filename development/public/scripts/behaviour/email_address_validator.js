function ShowForgetPasswordError() {
    $(document).ready(function() {
        $("#forgotPasswordValidatorTips").slideDown();
    });
}

function IsEmailAddressValid(emailFieldId) {
	var emailAddress = document.getElementById(emailFieldId).value.trim();
	
	if (Settings.EMAIL_ADDRESS_VALIDATION_PATTERN.test(emailAddress)) {
        $(document).ready(function() {
            $("#forgotPasswordValidatorTips").slideUp();
        });

		return true;
	}
	else {
		ShowForgetPasswordError();
		return false;
	}
}