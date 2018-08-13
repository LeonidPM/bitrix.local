<?php

/**
 * Class UsersListComponent
 * Класс для работы со списком пользователей
 */
class UsersListComponent extends \CBitrixComponent
{
    /**
     * Метод, исполняемый при вызове компонента
     * @return mixed|void
     */

    public function executeComponent()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        try {
            \Bitrix\Main\Loader::includeModule('iblock');
            $this->arResult = $this->getUsersList("users");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $this->includeComponentTemplate();
    }

    /**
     * Метод для получения списка пользователей из инфоблоков одного типа
     *
     * @param string $sIBlockType
     *
     * @throws Exception
     * @return array
     */
    protected function getUsersList($sIBlockType)
    {
        $arResult = [];

        $arIBT = Bitrix\Iblock\TypeTable::getList(array('select' => array('*', 'ID')))->FetchAll();

        foreach ($arIBT as $arV) {
            foreach ($arV as $v) {
                if ($v == $sIBlockType) {
                    $oRes = \Bitrix\Iblock\ElementTable::getList(array(
                        "select" => array('ID', 'NAME')
                    ));
                    $iIndex = 0;
                    while ($arRes = $oRes->fetch()) {
                        $arResult[$iIndex]["ID"] = (int)$arRes["ID"];
                        $arResult[$iIndex]["NAME"] = $arRes["NAME"];
                        $iIndex++;
                    };
                }
            }

        }

        return $arResult;
    }
}
