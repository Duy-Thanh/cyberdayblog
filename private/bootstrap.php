<?php
/*
 *
 * Copyright (C) 2016 - 2023 CyberDay Studio. All right reserved.
 * Author: Nguyen Duy Thanh (segfault.e404)
 *
 */

set_include_path(getcwd() . "/include");
include('cyberblog.php');
include('Crypt/RSA.php');

function checkPublicPrivateKey() {
	if (!file_exists(getcwd() . '/public_key.pem') || !file_exists(getcwd() . '/private_key.pem')) {
		return false;
	} else return true;
}

function createKey() {
	if (!checkPublicPrivateKey()) {
		// First, delete all key!
		unlink(getcwd() . '/public_key.pem');
		unlink(getcwd() . '/private_key.pem');

		$rsa = new Crypt_RSA();
		
		// Create new key
		$key = $rsa->createKey();

		// Save the public key and private key to files
		file_put_contents(getcwd() . '/public_key.pem', $key['publickey']);
		file_put_contents(getcwd() . '/private_key.pem', $key['privatekey']);
	}
}

function encryptData($plainData) {
	// Check the keys if they already existed
	if (!checkPublicPrivateKey()) {
		createKey();
	} else {
		$publicKey = file_get_contents(getcwd(). '/public_key.pem');
		$rsa = new Crypt_RSA();
		$rsa->loadKey($publicKey);
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$output = $rsa->encrypt($plainData);

		return base64_encode($output);
	}
}

function decryptData($encryptedData) {
	if (!checkPublicPrivateKey()) {
		return "500";
	} else {
		$privateKey = file_get_contents(getcwd() . '/private_key.pem');
		$rsa = new Crypt_RSA();

		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$cipherText = base64_decode($encryptedData);
		$rsa->loadKey($privateKey);
		$output = $rsa->decrypt($cipherText);

		return $output;
	}
}

?>
