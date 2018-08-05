<?php
CModule::IncludeModule("iblock");

/**
 * Class UsersListComponent
 * Класс для работы со списком пользователей
 */
class UsersListComponent extends \CBitrixComponent
{
    /**
     * Метод, исполняемый при вызове компонента
     * global \CMain $APPLICATION;
     * @return mixed|void
     */
    public function executeComponent()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        $this->arResult = $this->getUsersList('users');

        $this->includeComponentTemplate();
    }

    /**
     * Метод для получения списка пользователей из инфоблоков одного типа
     *
     * @param string $sIBlockType
     *
     * @return array
     */
    protected function getUsersList(string $sIBlockType)
    {

        if (CModule::IncludeModule("iblock")) {

            $oRes     = CIBlockElement::GetList(
                Array(),
                Array(
                    "!ID"         => "",
                    'IBLOCK_TYPE' => $sIBlockType
                )
            );
            $arResult = [];
            $iIndex   = 0;
            while ($arRes = $oRes->Fetch()) {
                $arResult[$iIndex]["ID"]   = (int)$arRes["ID"];
                $arResult[$iIndex]["NAME"] = mb_convert_encoding($arRes["NAME"], "UTF-8", "Windows-1251");
                $iIndex++;
            };

            return $arResult;
        }

    }
}
