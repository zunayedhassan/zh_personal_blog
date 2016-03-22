-- --------------------------------------------------------------
-- Password encrypted
-- --------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS minefield_db;

USE minefield_db;

CREATE TABLE login_info (login_name VARCHAR(16), password BLOB, email TINYTEXT, PRIMARY KEY (login_name));

SELECT * FROM login_info;

INSERT INTO login_info VALUES ('zunayed-hassan', AES_ENCRYPT('$w0rdf1$h', 'welcome_to_the_real_world_neo'), "zunayed-hassan@live.com");

SELECT * FROM login_info;

-- --------------------------------------------------------------

SELECT AES_DECRYPT(password, 'welcome_to_the_real_world_neo') AS unencrypted FROM login_info;

SELECT *, CAST(AES_DECRYPT(password, 'welcome_to_the_real_world_neo') AS CHAR) AS decrypted_password FROM login_info WHERE login_name = "zunayed-hassan";

SELECT login_name, CAST(AES_DECRYPT(password, 'welcome_to_the_real_world_neo') AS CHAR) AS decrypted_password FROM login_info WHERE email = "zunayed-hassan@live.com";