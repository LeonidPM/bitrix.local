<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class Init
 * Стартовая миграция с настройками
 */
class Init extends AbstractMigration
{
    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function up()
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        // Проверка типа инфоблока на существование

        $sIblockTypeTableName = \Bitrix\Iblock\TypeTable::getTableName();
        $sIblockTypeLanguageTableName = \Bitrix\Iblock\TypeLanguageTable::getTableName();
        $arTypeIB = \Bitrix\Iblock\TypeTable::getList(array('select' => array('ID')))->FetchAll();
        $sIBType = 'users';
        if (!empty($arTypeIB)) {
            foreach ($arTypeIB as $value) {
                if ($value['ID'] == $sIBType) {
                    break;
                } else {
                    $arRow = [
                        'ID' => $sIBType
                    ];
                    $this->table($sIblockTypeTableName)->insert($arRow)->save();
                    $arRows = [
                        [
                            'IBLOCK_TYPE_ID' => $sIBType,
                            'LID' => 'ru',
                            'NAME' => 'Пользователи',
                            'SECTION_NAME' => '',
                            'ELEMENT_NAME' => 'Пользователь'
                        ],
                        [
                            'IBLOCK_TYPE_ID' => $sIBType,
                            'LID' => 'en',
                            'NAME' => $sIBType,
                            'SECTION_NAME' => '',
                            'ELEMENT_NAME' => 'user'
                        ]
                    ];
                    $this->table($sIblockTypeLanguageTableName)->insert($arRows)->save();
                }
            }
        } else {
            $arRow = [
                'ID' => $sIBType
            ];
            $this->table($sIblockTypeTableName)->insert($arRow)->save();
            $arRows = [
                [
                    'IBLOCK_TYPE_ID' => $sIBType,
                    'LID' => 'ru',
                    'NAME' => 'Пользователи',
                    'SECTION_NAME' => '',
                    'ELEMENT_NAME' => 'Пользователь'
                ],
                [
                    'IBLOCK_TYPE_ID' => $sIBType,
                    'LID' => 'en',
                    'NAME' => $sIBType,
                    'SECTION_NAME' => '',
                    'ELEMENT_NAME' => 'user'
                ]
            ];
            $this->table($sIblockTypeLanguageTableName)->insert($arRows)->save();
        }

        // Проверка имени инфблока на существование

        $sIblockTableName = \Bitrix\Iblock\IblockTable::getTableName();
        $arNameIB = \Bitrix\Iblock\IblockTable::getList(array('select' => array('NAME')))->FetchAll();
        if (!empty($arNameIB)) {
            foreach ($arNameIB as $value) {
                if ($value['NAME'] == 'Пользователи') {
                    break;
                } else {
                    $arRow = [
                        'IBLOCK_TYPE_ID' => $sIBType,
                        'LID' => 's1',
                        'NAME' => 'Пользователи'
                    ];
                    $this->table($sIblockTableName)->insert($arRow)->save();
                    $iId = $this->getAdapter()->getConnection()->lastInsertId();
                }
            }
        } else {
            $arRow = [
                'IBLOCK_TYPE_ID' => $sIBType,
                'LID' => 's1',
                'NAME' => 'Пользователи'
            ];
            $this->table($sIblockTableName)->insert($arRow)->save();
            $iId = $this->getAdapter()->getConnection()->lastInsertId();
        }

        // Добавление свойств инфоблока

        $sIblockPropertyTableName = \Bitrix\Iblock\PropertyTable::getTableName();
        $sIblockPropertyTableEnumName = \Bitrix\Iblock\PropertyEnumerationTable::getTableName();
        $arRows = [
            [
                'IBLOCK_ID' => $iId,
                'NAME' => 'День рождения',
                'CODE' => 'BIRTH_DAY',
                'IS_REQUIRED' => 'Y',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'Date'
            ],
            [
                'IBLOCK_ID' => $iId,
                'NAME' => 'Телефон',
                'CODE' => 'PHONE_NUMBER',
                'IS_REQUIRED' => 'Y',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => ''
            ],
            [
                'IBLOCK_ID' => $iId,
                'NAME' => 'Город',
                'CODE' => 'CITY',
                'IS_REQUIRED' => 'Y',
                'PROPERTY_TYPE' => 'L',
                'USER_TYPE' => ''
            ]
        ];
        $this->table($sIblockPropertyTableName)->insert($arRows)->save();

        $arPropertyTableEnum = $this->fetchAll('SELECT * FROM ' . $sIblockPropertyTableName);
        foreach ($arPropertyTableEnum as $value) {
            if ($value['PROPERTY_TYPE'] == 'L') {
                $iIbPropIndex = (int)$value['ID'];
                $arRows = [
                    [
                        'PROPERTY_ID' => $iIbPropIndex,
                        'VALUE' => 'Москва',
                        'XML_ID' => 'moscow'
                    ],
                    [
                        'PROPERTY_ID' => $iIbPropIndex,
                        'VALUE' => 'Санкт-Петербург',
                        'XML_ID' => 'st_petersburg'
                    ],
                    [
                        'PROPERTY_ID' => $iIbPropIndex,
                        'VALUE' => 'Казань',
                        'XML_ID' => 'kazan'
                    ]
                ];
                $this->table($sIblockPropertyTableEnumName)->insert($arRows)->save();
            }
        }
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    public function down()
    {
        $sIBType = 'users';
        \Bitrix\Main\Loader::includeModule('iblock');
        $sIblockTypeTableName = \Bitrix\Iblock\TypeTable::getTableName();
        $sIblockTypeLanguageTableName = \Bitrix\Iblock\TypeLanguageTable::getTableName();
        $this->execute('DELETE FROM ' . $sIblockTypeTableName . " WHERE `ID`= '$sIBType'");
        $this->execute('DELETE FROM ' . $sIblockTypeLanguageTableName . " WHERE `IBLOCK_TYPE_ID` ='$sIBType'");

        $sIblockTableName = \Bitrix\Iblock\IblockTable::getTableName();
        $this->execute('DELETE FROM ' . $sIblockTableName . " WHERE `IBLOCK_TYPE_ID` ='$sIBType'");

        $sIblockPropertyTableName = \Bitrix\Iblock\PropertyTable::getTableName();
        $arPropertyTable = $this->fetchAll('SELECT * FROM ' . $sIblockPropertyTableName);
        foreach ($arPropertyTable as $value) {
            if ($value['CODE'] == 'CITY') {
                $iId = $value['IBLOCK_ID'];
                $iIbPropIndex = (int)$value['ID'];
            }
        }
        $this->execute('DELETE FROM ' . $sIblockPropertyTableName . " WHERE `IBLOCK_ID` ='$iId'");

        $sIblockPropertyTableEnumName = \Bitrix\Iblock\PropertyEnumerationTable::getTableName();
        $this->execute('DELETE FROM ' . $sIblockPropertyTableEnumName . " WHERE `PROPERTY_ID` ='$iIbPropIndex'");
    }
}
