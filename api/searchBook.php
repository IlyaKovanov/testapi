<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

$data = json_decode(file_get_contents('php://input'), true);


if($data["author"] || $data["name"]){

    if($data["author"]){
        $res = AuthorsTable::getList(array(
            'select' => array('ID', 'NAME'),
            'filter' => array('NAME' => "%".$data["author"]."%"),
        ));
        while ($arr = $res->fetch()) {
            $arAuthor[$arr["ID"]] = $arr["NAME"];
            $autorId[] = $arr["ID"];
        }
    }

    if($autorId){
        $res = WriteTable::getList(array(
            'select' => array('BOOK_ID', 'AUTHOR_ID'),
            'filter' => array('AUTHOR_ID' => $autorId),
        ));
        while ($arr = $res->fetch()) {
            $arWrite[] = $arr["BOOK_ID"];
        }
    }

    $param["select"] = array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE');
    $param["order"] = array('NAME' => 'ASC');
    
    if($arWrite){
        $param["filter"] = array("ID" => $arWrite);
    }

    if($data["name"]){
        $param["filter"] = array("NAME" => "%".$data["name"]."%");
    }

    if($arWrite && $data["name"]){
        $param["filter"] = array("LOGIC" =>"AND", "NAME" => "%".$data["name"]."%", "ID" => $arWrite);
    }
    

    $res = BooksTable::getList($param);
    while($arr = $res->fetch()) {
        $arBooks[] = $arr;
        $booksId[] = $arr["ID"];
    }

    if($arBooks){
        $bookAuthors = ApiCore::getAuthorByBookId($booksId);
    
        foreach($arBooks as $book){
            $book["author"] = $bookAuthors[$book["ID"]];
            $arResult["items"][] = $book;
        }
    } else {
        $arResult["message"] = 'Записей не найдено';
        $arResult["status"] = false;
    }
    
    
    
} else {
    $arResult["message"] = 'Отсутствуют обязательные параметры name и author';
    $arResult["status"] = false;
}




?>