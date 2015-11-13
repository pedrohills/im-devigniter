<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/third_party/facebook/autoload.php';
use Facebook\FacebookSession;

class Facebook {

    public function __construct()
    {
      FacebookSession::setDefaultApplication('1462030150691879', 'a7d848a66df982089cebd04dd9871df6');
      // Do something with $params
    }
    
    public function redirect_login()
    {
      echo FacebookSession::getLoginUrl();
    }
}