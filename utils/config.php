<?php
// config.php 

define("BASE_URL", "http://localhost/pay/");
define("API_STATUS", "UAT");
define("MERCHANTIDLIVE", "your live key");
// define("MERCHANTIDUAT", "PGTESTPAYUAT");
// define("SALTKEYUAT", "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399");
define("SALTKEYLIVE", "you key");
define("SALTINDEX", "1");
define("REDIRECTURL", "paymentstatus.php");
define("SUCCESSURL", "success.php");
define("FAILUREURL", "failure.php");

define("SALTKEYUAT", "7b33559b-571d-4eb5-9b2b-858cb1c0a86b");
define("MERCHANTIDUAT", "M22YF3TMHL5NKUAT");

// Payment API URLs
define("UATURLPAY", "https://api-preprod.phonepe.com/apis/hermes/pg/v1/pay");
define("LIVEURLPAY", "https://api.phonepe.com/apis/hermes/pg/v1/pay");

// Status Check URLs
define("STATUSCHECKURL", "https://api-preprod.phonepe.com/apis/hermes/pg/v1/status/");
define("LIVESTATUSCHECKURL", "https://api.phonepe.com/apis/hermes/pg/v1/status/");
?>