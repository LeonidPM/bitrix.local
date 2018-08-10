<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс обучение");
?>

<?$APPLICATION->IncludeComponent('ylab:users.list', '', []);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>