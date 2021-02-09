<?
use clases\Clients;
require_once __DIR__.'/vendor/autoload.php';
$re= new Clients("https://newaccount1612559556448.freshdesk.com","QsoKVke1gL5e05IR9Dr");
$re->createCsv();
//phpinfo();
//var_dump($re);
