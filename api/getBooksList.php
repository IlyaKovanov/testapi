<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;


try {
    $res = BooksTable::getList(array(
        'select' => array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE'),
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


if($booksId){
    $res = WriteTable::getList(array(
        'select' => array('BOOK_ID', 'AUTHOR_ID'),
        'filter' => array('BOOK_ID' => $booksId),
    ));
    
    while ($arr = $res->fetch()) {
        $arWrite[$arr["BOOK_ID"]][] = $arr["AUTHOR_ID"];
        $writeId[] = $arr["AUTHOR_ID"];
    }
}


if($writeId){
    $res = AuthorsTable::getList(array(
        'select' => array('ID', 'NAME'),
        'filter' => array('ID' => array_unique($writeId)),
    ));
    
    while ($arr = $res->fetch()) {
        $arAuthor[$arr["ID"]] = $arr;
    }
}

foreach($arWrite as $key=>$write){
    foreach($write as $item){
        $bookAuthors[$key][] = $arAuthor[$item]["NAME"];
    }
}

$i = 0;
foreach($arBooks as $book){
    $arResult["items"][$i]["id"] = $book["ID"];
    $arResult["items"][$i]["name"] = $book["NAME"];
    $arResult["items"][$i]["author"] = $bookAuthors[$book["ID"]];
    $arResult["items"][$i]["published"] = $book["PUBLISHED"];
    $i++;
}

if(!$arResult["items"]){
    $arResult["message"] = 'Записей не найдено';
}



?>