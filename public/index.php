<?php
/*
 *
 * Copyright (C) 2016 - 2023 CyberDay Studio. All right reserved.
 * Author: Nguyen Duy Thanh (segfault.e404)
 *
 */

set_include_path("../private/include");
include('cyberblog.php');
include('../private/config.php');

// Header
echo "<!DOCTYPE html>";

function checkPublicPrivateKey($directory = '../private/') {
	if (!file_exists($directory . 'public_key.pem') || 
		!file_exists($directory . 'private_key.pem')) {
		return false;
	} else return true;
}

function createKey($directory = '../private/') {
	if (!checkPublicPrivateKey($directory)) {
		// First, delete all key!
		unlink($directory . 'public_key.pem');
		unlink($directory . 'private_key.pem');

		$rsa = new Crypt_RSA();
		
		// Create new key
		$key = $rsa->createKey();

		// Save the public key and private key to files
		file_put_contents($directory . 'public_key.pem', $key['publickey']);
		file_put_contents($directory . 'private_key.pem', $key['privatekey']);
	}
}

function encryptData($plainData, $keyDirectory = '../private/') {
	// Check the keys if they already existed
	if (!checkPublicPrivateKey()) {
		createKey();
	} else {
		$publicKey = file_get_contents($keyDirectory . 'public_key.pem');
		$rsa = new Crypt_RSA();
		$rsa->loadKey($publicKey);
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$output = $rsa->encrypt($plainData);

		return base64_encode($output);
	}
}

function decryptData($encryptedData, $keyDirectory = '../private/') {
	if (!checkPublicPrivateKey()) {
		return "500";
	} else {
		$privateKey = file_get_contents($keyDirectory . 'private_key.pem');
		$rsa = new Crypt_RSA();

		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$cipherText = base64_decode($encryptedData);
		$rsa->loadKey($privateKey);
		$output = $rsa->decrypt($cipherText);

		return $output;
	}
}

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

// Pre-check
if (checkPublicPrivateKey()) {
	$generateString = generateRandomString(128);
	$encrypted = encryptData($generateString);
	$decrypted = decryptData($encrypted);
?>

<script>console.log("<?php if ($generateString == $decrypted) { echo "Crypto service working properly"; } else { echo "Crypto service not working"; } ?>");</script>
<?php
} else {
    createKey();
}

// Include our HTML page
include('./main_page.html');
?>