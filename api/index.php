<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/BooksTable.php';
require_once 'core/AuthorsTable.php';
require_once 'core/WriteTable.php';

use \Bitrix\Books\BooksTable,
    \Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;


$res = BooksTable::getList(array(
    'select' => array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE'),
    'limit' => 10, 
));

while ($arr = $res->fetch()) {
    $arBooks[] = $arr;
    $booksId[] = $arr["ID"];
}

$res = WriteTable::getList(array(
    'select' => array('BOOK_ID', 'AUTHOR_ID'),
    'filter' => array('BOOK_ID' => $booksId),
));

while ($arr = $res->fetch()) {
    $arWrite[$arr["BOOK_ID"]] = $arr["AUTHOR_ID"];
}


$res = AuthorsTable::getList(array(
    'select' => array('ID', 'NAME'),
    'filter' => array('ID' => $arWrite),
));

while ($arr = $res->fetch()) {
    $arAuthor[$arr["ID"]] = $arr;
}


$arResult = [];

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


echo json_encode($arResult);


?>