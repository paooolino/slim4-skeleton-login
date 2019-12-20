<?php
namespace WebApp;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;

class LoginService {
  private $token;
  
  /**
   * The password used for encryption function.
   */
  const ENCRYPTION_KEY = "Qof}zzW7;NAt(#l&BBxWI0T=9XR]dL8-)z~g_J+pFPbMdzuQPf({E_ysC?_{lHvq";
  
  /**
   * The enryption method.
   */
  const ENCRYPTION_METHOD = "AES-256-CBC";
  
  /**
   * The authentication cookie name.
   */
  const AUTH_COOKIE_NAME = "token";
  
  /**
   * Validity of session for generated access token
   */
  const TOKEN_TIME_LIMIT = 60 * 30;
  
  public function __construct() {
    // do nothing
  }

  public function setAuthToken($infos, $response) {
    $token = $this->createToken(time(), $infos);
    
    $this->token = $token;
    $response = $this->setAuthCookie($token, $response);
    
    return $response;
  }
  
  public function checkAuth($request) {
    $auth_cookie = FigRequestCookies::get($request, self::AUTH_COOKIE_NAME);
    if ($auth_cookie->getValue() !== null) {
      return $this->is_valid_token($auth_cookie->getValue());
    }
    
    return false;
  }
  
  private function createToken($time_info, $user_info) {
    $token = $this->encrypt(json_encode([
      "t" => $time_info,
      "u" => $user_info
    ]));
    
    return $token;
  }
  
  private function setAuthCookie($token, $response) {
    $response = FigResponseCookies::set($response, 
      SetCookie::create(self::AUTH_COOKIE_NAME)
        ->withValue($token)
        ->withPath('/')
    );
    
    return $response;
  }
  
  private function is_valid_token($s) {
    $json_string = $this->decrypt($s);
    $json = json_decode($json_string, true); 
    if ($json !== false) {
      return $json;
    }
    
    return false;
  }
  
  private function encrypt($s) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::ENCRYPTION_METHOD));
    $encrypted = openssl_encrypt($s, self::ENCRYPTION_METHOD, self::ENCRYPTION_KEY, 0, $iv);
    $encrypted .= ':' . base64_encode($iv);
    return $encrypted;
  }
  
  private function decrypt($s) {
    $parts = explode(':', $s);
    if (count($parts) != 2)
      return false;
    $decrypted = openssl_decrypt($parts[0], self::ENCRYPTION_METHOD, self::ENCRYPTION_KEY, 0, base64_decode($parts[1]));
    return $decrypted;
  }
}