<?php
/*
 *
 * Copyright (C) 2016 - 2023 CyberDay Studio. All right reserved.
 * Author: Nguyen Duy Thanh (segfault.e404)
 *
 */

include('../private/config.php');

// Header
echo "<!DOCTYPE html>";

// Lowest length accepted is 64
function generateRandomString($length = 128) {
	$salt = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ`~!@#$%^&*()_+{}|:\"<>?-=[]\;',./";
	$randstring = '';
	for ($i = 0; $i < $length; $i++) {
		$randstring .= $salt[random_int(0, strlen($salt) - 1)];
	}

	return $randstring;
}

function createDirectory($directory = '../private/', $directoryName) {
	if (!file_exists($directory . $directoryName)) {
		mkdir($directory . $directoryName, 0777, true);
		return true;
	} else return false;
}

function checkDir($directory = '../private/', $directoryName) {
	if (!file_exists($directory . $directoryName)) return false;
	else return true;
}

// https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function get_client_ip_addr() {
	$ip = 
		getenv('HTTP_CLIENT_IP')?:
		getenv('HTTP_X_FORWARDED_FOR')?:
		getenv('HTTP_X_FORWARDED')?:
		getenv('HTTP_FORWARDED_FOR')?:
		getenv('HTTP_FORWARDED')?:
		getenv('REMOTE_ADDR');

	return $ip;
}

include 'main_page.html';

?>