<?php
namespace app\Config;

define('PRODUCTION', true); // application mode
ini_set('display_errors', 1);
define('MYSQL_HOST', 'mysql:host=sql302.epizy.com;dbname=epiz_21616898_db');
define('MYSQL_LOGIN', 'epiz_21616898');
define('MYSQL_PASSWORD', 'gn1ROPKGWiVg');
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'].'/app/');
// Router
define('Admin', '/^[\/]{1}[aAdDmMiInN]{5}[\/]{0,1}[a-zA-Z\/\-\_0-9]{1,500}$/');
define('Firewall', '/^[a-zA-Z\/\-\_0-9\ ]{1,500}$/');
// ���������/�������� ���. �� �������� ������� ����� � ���������� app/cache. ���� �����-�� ����� ��� �� ��������.
define('cache', true);
