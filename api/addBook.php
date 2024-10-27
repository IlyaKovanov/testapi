<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'core/core.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

if($userLogin == ADMIN_LOGIN && $userPassword == ADMIN_PASSWORD){

    $data = json_decode(file_get_contents('php://input'), true);
    $res = BooksTable::getList(array(
        'select' => array('ID', 'NAME', 'PUBLISHED', 'AVAILABLE'),
        'filter' => array('NAME' => $data["name"]), 
    ));

    if($arr = $res->fetch()) {
        $arResult["message"] = $MESSAGES[208];
        $CODE = 208;
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
                ));
                
                while($arr = $res->fetch()) {
                    $authorId[] = $arr["ID"];
                    $authorName[] = base64_encode($arr["NAME"]);
                } 
                
                if(!$authorId || count($authorId) != count($data["author"])){
                    foreach($data["author"] as $author){
                        if($authorName && !in_array(base64_encode($author), $authorName) || !$authorName){
                            $authorId[] = AuthorsTable::add(array(
                                'NAME' => $author,
                            ))->getId();
                        }
                    }
                }
            }
            
            if($bookId && $authorId){
                foreach($authorId as $value){
                    $authorRes = WriteTable::add(array(
                        'BOOK_ID' => $bookId,
                        'AUTHOR_ID' => $value
                    ));
                }
            }
        
           
           /*
            if($bookId){
                $res = AuthorsTable::getList(array(
                    'select' => array('ID', 'NAME'),
                    'filter' => array('NAME' => $data["author"]),
                    'limit' => 1
                ));
                if($arr = $res->fetch()) {
                    $authorId[] = $arr["ID"];
                } else {
                    $authorId = AuthorsTable::add(array(
                        'NAME' => $data["author"],
                    ))->getId();
                }
            }
        
            if($bookId && $authorId){
                $authorRes = WriteTable::add(array(
                    'BOOK_ID' => $bookId,
                    'AUTHOR_ID' => $authorId
                ));
            }

            */
        
            $arResult["message"] = $MESSAGES[200];
            $CODE = 200;
        } else {
            $arResult["message"] = $MESSAGES[204];
            $CODE = 204;
        }
    }
} elseif(!$userLogin && !$userPassword) {
    $arResult["message"] = $MESSAGES[401];
    $CODE = 401;
} else {
    $arResult["message"] = $MESSAGES[403];
    $CODE = 403;
}
?>