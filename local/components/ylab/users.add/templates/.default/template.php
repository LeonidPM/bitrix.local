<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Application;

try {
    $context = Application::getInstance()->getContext();
    $request = $context->getRequest();
    $flag = $request->isPost();
    $oRes = \Bitrix\Iblock\IblockTable::getList(array('select' => array('ID', 'NAME')));
} catch (\Exception $e) {
    echo $e->getMessage();
}
if ($flag) {
    $sName = $request->getPost("name");
    $sBirth_date = $request->getPost("birth_date");
    $sPhone_number = $request->getPost("phone_number");
    $sCity = $request->getPost("city");
}

$arProps = array(
    '34' => $sBirth_date,
    '35' => $sPhone_number,
    '36' => $sCity
);

$iIBlockID = 1;
while ($arr = $oRes->fetch()) {
    if ($arr['NAME'] == "Пользователи") {
        $iIBlockID = (int)$arr['ID'];
    }
}
$arLoadUserArray = array(
    'IBLOCK_ID' => $iIBlockID,
    'NAME' => $sName,
    'ACTIVE' => 'Y',
    'PROPERTY_VALUES' => $arProps
);
$oEl = new CIBlockElement;
if ($iUserID = $oEl->Add($arLoadUserArray)) {
    echo "Пользователь успешно добавлен <br/> ID нового пользователя: " . $iUserID . "<br/>";
}
?>
<div style="padding-left:30px;">
    <h2>Добавление нового пользователя</h2>

    <form action="<?= POST_FORM_ACTION_URI ?>" method="post" class="form form-block">
        <?= bitrix_sessid_post() ?>
        <? if (count($arResult['ERRORS'])): ?>
            <p><?= implode('<br/>', $arResult['ERRORS']) ?></p>
        <? elseif ($arResult['SUCCESS']): ?>
            <p>Успешная валидация</p>
        <? endif; ?>

        <div>
            <label>
                Имя<br>
                <input type="text" name="name"/>
            </label>
        </div>
        <div>
            <label>
                Дата рождения<br>
                <input type="text" name="birth_date"/>
            </label>
        </div>
        <div>
            <label>
                Номер телефона<br>
                <input type="text" name="phone_number"/>
            </label>
        </div>
        <div>
            <label>
                Город<br>
                <select name="city">
                    <option value="">Выбрать</option>
                    <option value="1">Москва</option>
                    <option value="2">Санкт-Петербург</option>
                    <option value="3">Казань</option>
                </select>
            </label>
        </div>
        <div class="btn green">
            <button type="submit" name="submit">Добавить пользователя</button>
        </div>
    </form>
</div>