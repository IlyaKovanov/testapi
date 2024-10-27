<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

$data = json_decode(file_get_contents('php://input'), true);

$res = BooksTable::getList(array(
    'select' => array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE'),
    'filter' => array('NAME' => $data["name"]), 
    'limit' => 10, 
    // 'offset' => $offset
));
if($arr = $res->fetch()) {
    $arResult["message"] = 'книга уже существует';
    $arResult["status"] = false;
} else {
    if($data["name"] && $data["published"] && $data["author"]){
        $bookId = BooksTable::add(array(
            'NAME' => $data["name"],
            'PUBLISHED' => $data["published"],
            'AVAILABLE' => 'Y',
        ))->getId();
    
        if($bookId){
            $res = AuthorsTable::getList(array(
                'select' => array('ID', 'NAME'),
                'filter' => array('NAME' => $data["author"]),
                'limit' => 1
            ));
            if($arr = $res->fetch()) {
                $authorId = $arr["ID"];
            } else {
                $authorId = AuthorsTable::add(array(
                    'NAME' => $data["author"],
                ))->getId();
            }
        }
    
        if($bookId && $authorId){
            $authorId = WriteTable::add(array(
                'BOOK_ID' => $bookId,
                'AUTHOR_ID' => $authorId
            ));
        }
    
        $arResult["message"] = 'Книга успешно добавлена';
        $arResult["status"] = true;
    } else {
    
        $arResult["message"] = 'отсутствуют обязательные данные name и published';
        $arResult["status"] = false;
    }
}

?>