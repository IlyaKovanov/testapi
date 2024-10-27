<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if($_REQUEST["METHOD"]){
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    try{

        $method = explode('?', $_REQUEST["METHOD"]);
        require_once $method[0].'.php';
        http_response_code($CODE);
        echo json_encode($arResult);

    } catch (Error $e) {

        http_response_code(500);
        $arResult["message"] = 'unknown request '.$e->GetMessage();
        echo json_encode($arResult);

    }
    
}

?>