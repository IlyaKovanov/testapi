<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

if($userLogin && $userPassword){
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
                    'USER' => $userLogin,
                ));
            
                $arResult["message"] = $MESSAGES[200];
                $CODE = 200;
            } else {
                if($book["USER"] == $userLogin){
                    $arResult["message"] = $MESSAGES[403];
                    $CODE = 403;
                } else {
                    $arResult["message"] = $MESSAGES[403];
                    $CODE = 403;
                }
                
            }
        }
        
    } else {
        $arResult["message"] = $MESSAGES[404];
        $CODE = 404;
    }
} else {
    $arResult["message"] = $MESSAGES[401];
    $CODE = 401;
}    


?>