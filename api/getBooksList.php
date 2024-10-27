<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;


try {
    $res = BooksTable::getList(array(
        'select' => array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE', 'USER'),
        // 'filter' => array('AVAILABLE' => "Y"),
        'order' => array('NAME' => 'ASC'), 
        'limit' => 10, 
        // 'offset' => $offset
    ));
    
    while ($arr = $res->fetch()) {
        $arBooks[] = $arr;
        $booksId[] = $arr["ID"];
    }
} catch (Exception $e) {
   $MESSAGE = $e->getMessage();
}

$bookAuthors = ApiCore::getAuthorByBookId($booksId);

$i = 0;
foreach($arBooks as $book){
    $arResult["items"][$i]["id"] = $book["ID"];
    $arResult["items"][$i]["name"] = $book["NAME"];
    $arResult["items"][$i]["author"] = $bookAuthors[$book["ID"]];
    $arResult["items"][$i]["published"] = $book["PUBLISHED"];
    if($_REQUEST["user"] == "admin" && $book["AVAILABLE"] == "N"){
        $arResult["items"][$i]["available"] = $book["AVAILABLE"];
        $arResult["items"][$i]["user"] = $book["USER"];
    } else {
        $arResult["items"][$i]["available"] = $book["AVAILABLE"];
    }
    
    $i++;
}

if(!$arResult["items"]){
    $arResult["message"] = 'Записей не найдено';
}



?>