<?php defined('BASEPATH') OR exit('No direct script access allowed');
// This helper is used to encrypt any string

function encryption_key(){
    return 'pbR^cHZ3CDpcJVCZ*oyq3H$TjL&GukerFz3E9ydGT2t&xgJ*^tfkQPKBF8anbJ7Qtrpv$#AreunEEhrvWi4NHhpqKkVhG2ard832pB5h%Uz!fB*cKmVdw9TEE%tDYYKQ';
}

function encrypt ($pure_string, $encryption_key) {
    $cipher     = 'AES-256-CBC';
    $options    = OPENSSL_RAW_DATA;
    $hash_algo  = 'sha256';
    $sha2len    = 64;
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($pure_string, $cipher, $encryption_key, $options, $iv);
    $hmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
    return $iv.$hmac.$ciphertext_raw;
}
function decrypt ($encrypted_string, $encryption_key) {
    $cipher     = 'AES-256-CBC';
    $options    = OPENSSL_RAW_DATA;
    $hash_algo  = 'sha256';
    $sha2len    = 64;
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($encrypted_string, 0, $ivlen);
    $hmac = substr($encrypted_string, $ivlen, $sha2len);
    $ciphertext_raw = substr($encrypted_string, $ivlen+$sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $encryption_key, $options, $iv);
    $calcmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
    if(function_exists('hash_equals')) {
        if (hash_equals($hmac, $calcmac)) return $original_plaintext;
    } else {
        if ($this->hash_equals_custom($hmac, $calcmac)) return $original_plaintext;
    }
}
/**
 * (Optional)
 * hash_equals() function polyfilling.
 * PHP 5.6+ timing attack safe comparison
 */
function hash_equals_custom($knownString, $userString) {
    if (function_exists('mb_strlen')) {
        $kLen = mb_strlen($knownString, '8bit');
        $uLen = mb_strlen($userString, '8bit');
    } else {
        $kLen = strlen($knownString);
        $uLen = strlen($userString);
    }
    if ($kLen !== $uLen) {
        return false;
    }
    $result = 0;
    for ($i = 0; $i < $kLen; $i++) {
        $result |= (ord($knownString[$i]) ^ ord($userString[$i]));
    }
    return 0 === $result;
}

/* This Code taken from
https://stackoverflow.com/questions/16600708/how-do-you-encrypt-and-decrypt-a-php-string
夏期劇場 answered Jul 29 '19 at 8:15
*/