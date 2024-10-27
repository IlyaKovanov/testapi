<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;
    
if($userLogin == ADMIN_LOGIN && $userPassword == ADMIN_PASSWORD){
    $data = json_decode(file_get_contents('php://input'), true);

    if(is_numeric($data["id"])){
        $res = BooksTable::getList(array(
            'select' => array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE'),
            'filter' => array('ID' => $data["id"]),
            'order' => array('NAME' => 'ASC'), 
        ));
        
        if($arr = $res->fetch()) {
            BooksTable::delete($data["id"]);

            $res = WriteTable::getList(array(
                'select' => array('ID', 'BOOK_ID'),
                'filter' => array('BOOK_ID' => $data["id"]),
            ));
            
            while($arr = $res->fetch()) {
                WriteTable::delete($arr["ID"]);
            }

            $arResult["message"] = $MESSAGES[200];
            $CODE = 200;
        } else {
            $arResult["message"] = $MESSAGES[404];
            $CODE = 404;
        }
        
    } else {
        $arResult["message"] = $MESSAGES[404];
        $CODE = 404;
    }
} elseif(!$userLogin && !$userPassword) {
    $arResult["message"] = $MESSAGES[401];
    $CODE = 401;
} else {
    $arResult["message"] = $MESSAGES[403];
    $CODE = 403;
}

?>