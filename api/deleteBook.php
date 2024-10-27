<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

$data = json_decode(file_get_contents('php://input'), true);

if(is_numeric($data["id"])){
    BooksTable::delete($data["id"]);

    $res = WriteTable::getList(array(
        'select' => array('ID', 'BOOK_ID'),
        'filter' => array('BOOK_ID' => $data["id"]),
    ));
    
    while($arr = $res->fetch()) {
        WriteTable::delete($arr["ID"]);
    }

    $arResult["message"] = 'Книга успешно удалена';
    $arResult["status"] = true;
} else {

    $$arResult["message"] = 'Неверный формат id';
    $arResult["status"] = false;
}


?>