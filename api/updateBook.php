<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

$data = json_decode(file_get_contents('php://input'), true);    

if(is_numeric($data["id"])){
    $res = BooksTable::update($data["id"], array(
        'NAME' => $data["name"],
        'PUBLISHED' => $data["published"],
    ));

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

    $res = WriteTable::getList(array(
        'select' => array('ID', 'BOOK_ID', 'AUTHOR_ID'),
        'filter' => array('BOOK_ID' => $data["id"]),
    ));
    while($arr = $res->fetch()) {
        $authorId = $arr["ID"];
        $res = WriteTable::update($arr["ID"], array(
            'AUTHOR_ID' => $authorId,
        ));
    }
    

} else {
    $arResult["message"] = 'Неверный формат id';
    $arResult["status"] = false;
}

?>