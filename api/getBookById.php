<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

$data = json_decode(file_get_contents('php://input'), true);

if(is_numeric($data["id"])){
    try {
        $res = BooksTable::getList(array(
            'select' => array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE'),
            'filter' => array('ID' => $data["id"]),
            'order' => array('NAME' => 'ASC'), 
        ));
        
        if($arr = $res->fetch()) {
            $arBook = $arr;
        }
    } catch (Exception $e) {
       $MESSAGE = $e->getMessage();
    }
    
    
    if($arBook["ID"]){
        $res = WriteTable::getList(array(
            'select' => array('BOOK_ID', 'AUTHOR_ID'),
            'filter' => array('BOOK_ID' => $arBook["ID"]),
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
    
    if($arBook){
        $arResult["id"] = $arBook["ID"];
        $arResult["name"] = $arBook["NAME"];
        $arResult["author"] = $bookAuthors[$arBook["ID"]];
        $arResult["published"] = $arBook["PUBLISHED"];
    } else {
        $arResult["message"] = 'Запись не найдена';
        $arResult["status"] = false;
    }
} else {
    $arResult["message"] = 'Неверный формат id';
    $arResult["status"] = false;
}




?>