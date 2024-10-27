<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

$data = json_decode(file_get_contents('php://input'), true);    

if(is_numeric($data["id"])){
    $res = BooksTable::getList(array(
        'select' => array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE', 'USER'),
        'filter' => array('ID' => $data["id"]),
        'order' => array('NAME' => 'ASC'), 
    ));
    if($book = $res->fetch()) {
        if($book["AVAILABLE"] == "Y"){
            $res = BooksTable::update($data["id"], array(
                'AVAILABLE' => "N",
                'USER' => $_REQUEST["user"],
            ));
        
            $arResult["message"] = 'Книга успешно зарезервирована';
            $arResult["status"] = true;
        } else {
            if($book["USER"] == $_REQUEST["user"]){
                $arResult["message"] = 'Книга уже зарезервирована';
                $arResult["status"] = true;
            } else {
                $arResult["message"] = 'Книга недоступна для резерва';
            $arResult["status"] = true;
            }
            
        }
    }

    
} else {
    $$arResult["message"] = 'Неверный формат id';
    $arResult["status"] = false;
}
?>