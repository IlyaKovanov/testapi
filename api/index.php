<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if($_REQUEST["METHOD"]){
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    try{

        require_once $_REQUEST["METHOD"].'.php';
        http_response_code(200);
        echo json_encode($arResult);

    } catch (Error $e) {

        http_response_code(500);
        $arResult["message"] = 'unknown request '.$e->GetMessage();
        $arResult["status"] = false;
        echo json_encode($arResult);

    }
    
}

?>