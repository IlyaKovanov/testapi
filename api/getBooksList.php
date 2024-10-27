<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

if($userLogin == ADMIN_LOGIN && $userPassword == ADMIN_PASSWORD){  
    try {
        if($_REQUEST["page"] && is_numeric($_REQUEST["page"]) && $_REQUEST["page"] > 1){
            $offset = (int)$_REQUEST["page"]*10;
        } 

        $res = BooksTable::getList(array(
            'select' => array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE', 'USER'),
            // 'filter' => array('AVAILABLE' => "Y"),
            'order' => array('NAME' => 'ASC'), 
            'limit' => 10, 
            'offset' => $offset
        ));

        while ($arr = $res->fetch()) {
            $arBooks[] = $arr;
            $booksId[] = $arr["ID"];
        }
        
    } catch (Exception $e) {
        $arResult["message"] = $MESSAGES[404];
        $CODE = 404;
    }

    $bookAuthors = ApiCore::getAuthorByBookId($booksId);

    $i = 0;
    foreach($arBooks as $book){
        $arResult["items"][$i]["id"] = $book["ID"];
        $arResult["items"][$i]["name"] = $book["NAME"];
        $arResult["items"][$i]["author"] = $bookAuthors[$book["ID"]];
        $arResult["items"][$i]["published"] = $book["PUBLISHED"];
        if($userLogin == ADMIN_LOGIN && $userPassword == ADMIN_PASSWORD && $book["AVAILABLE"] == "N"){
            $arResult["items"][$i]["available"] = $book["AVAILABLE"];
            $arResult["items"][$i]["user"] = $book["USER"];
        } else {
            $arResult["items"][$i]["available"] = $book["AVAILABLE"];
        }
        
        $i++;
    }


    if(!$arResult["items"]){
        $arResult["message"] = $MESSAGES[404];
        $CODE = 404;
    } else {
        $arResult["message"] = $MESSAGES[200];
        $CODE = 200;
    }

} elseif(!$userLogin && !$userPassword) {
    $arResult["message"] = $MESSAGES[401];
    $CODE = 401;
} else {
    $arResult["message"] = $MESSAGES[403];
    $CODE = 403;
}


?>