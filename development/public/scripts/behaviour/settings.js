function Settings() {
	
}

Settings.MIN_LOGIN_NAME_LENGTH = 1;
Settings.MAX_LOGIN_NAME_LENGTH = 16;
Settings.MIN_PASSWORD_LENGTH   = 8;
Settings.MAX_PASSWORD_LENGTH   = 16;
Settings.LOGIN_PATTERN		   = /^([a-z0-9]+-)*[a-z0-9]+$/i;
Settings.EMAIL_ADDRESS_VALIDATION_PATTERN = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;