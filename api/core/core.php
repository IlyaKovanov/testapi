<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once 'AuthorsTable.php';
require_once 'BooksTable.php';
require_once 'WriteTable.php';

use Bitrix\Books\BooksTable,
    Bitrix\Authors\AuthorsTable,
    Bitrix\Write\WriteTable;

define('ADMIN_LOGIN', 'admin');
define('ADMIN_PASSWORD', '555555');

$userLogin = $_SERVER["PHP_AUTH_USER"];
$userPassword = $_SERVER["PHP_AUTH_PW"];

$MESSAGES = array(
    '200' => 'Запрос был выполнен успешно',
    '204' => 'Нет содержимого',
    '208' => 'Уже добавлено',
    '401' => 'Пользователь не авторизован',
    '403' => 'Операция не доступна пользователю',
    '404' => 'Книга не найдена',
    // '423' => 'Зарезервировано',
);


class ApiCore {

    public static function getAuthorByBookId($booksId){
        if($booksId){
            $res = WriteTable::getList(array(
                'select' => array('BOOK_ID', 'AUTHOR_ID'),
                'filter' => array('BOOK_ID' => $booksId),
            ));
            
            while ($arr = $res->fetch()) {
                $arWrite[$arr["BOOK_ID"]][] = $arr["AUTHOR_ID"];
                $writeId[] = $arr["AUTHOR_ID"];
            }
        }

        if($writeId){
            $res = AuthorsTable::getList(array(
                'select' => array('ID', 'NAME'),
                'filter' => array('ID' => array_unique($writeId)),
            ));
            
            while ($arr = $res->fetch()) {
                $arAuthor[$arr["ID"]] = $arr;
            }
        }
        
        foreach($arWrite as $key=>$write){
            foreach($write as $item){
                $bookAuthors[$key][] = $arAuthor[$item]["NAME"];
            }
        }

        return $bookAuthors;
    }

}
?>