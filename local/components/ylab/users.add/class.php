<?php

namespace YLab\Validation\Components;

use Bitrix\Main\UserTable;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

/**
 * Class ValidationTestComponent
 * Компонент пример использования модуля ylab.validation в разработке
 * @package YLab\Validation\Components
 */
class UserAddComponent extends ComponentValidation
{
    /**
     * UserAddComponent constructor.
     *
     * @param \CBitrixComponent|null $component
     * @param string                 $sFile
     *
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */

    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        parent::__construct($component, $sFile);
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function executeComponent()
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        /**
         * Непосредственно валидация и действия при успехе и фейле
         */

        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $this->oValidator->setData($this->oRequest->toArray());

            if ($this->oValidator->passes()) {
                $this->arResult['SUCCESS'] = true;
            } else {
                $this->arResult['ERRORS'] = ValidatorHelper::errorsToArray($this->oValidator);
            }
        }

        $this->includeComponentTemplate();
    }

    /**
     * @return array
     */
    protected function rules()
    {
        /**
         * Перед формированием массива правил валидации мы можем вытащить все необходимые данные из различных источников
         */
        return [
            'name' => 'required',
            'birth_date' => 'required|date_format:d.m.Y',
            'phone_number' => 'required|regex:/^\+7\d{10}$/',
            'city' => 'required'
        ];
    }
}