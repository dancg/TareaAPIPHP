<?php 
require_once __DIR__."./../vendor/autoload.php";
use Src\Articulo;
header("Content-Type: application/json; charset=UTF-8");

$limit = (isset($_GET['count'])) ? (int)$_GET['count'] : 20;
$cat = (isset($_GET['cat'])) ? $_GET['cat'] : null;

echo Articulo::read($cat, $limit);