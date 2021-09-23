<?php

namespace Components\Constants;

final class CategoryConstants
{
    public const TRANSLATION_NAME = 'translation';
    public const CLEAN_NAME = 'clean';
    public const CARGO_NAME = 'cargo';
    public const NEO_NAME = 'neo';
    public const FLAT_NAME = 'flat';
    public const REPAIR_NAME = 'repair';
    public const BEAUTY_NAME = 'beauty';
    public const PHOTO_NAME = 'photo';

    public const TRANSLATION_NAME_FOR_USER = 'Переводы';
    public const CLEAN_NAME_FOR_USER = 'Уборка';
    public const CARGO_NAME_FOR_USER = 'Переезды';
    public const NEO_NAME_FOR_USER = 'Компьютерная помощь';
    public const FLAT_NAME_FOR_USER = 'Ремонт квартирный';
    public const REPAIR_NAME_FOR_USER = 'Ремонт техники';
    public const BEAUTY_NAME_FOR_USER = 'Красота';
    public const PHOTO_NAME_FOR_USER = 'Фото';

    public const NAME_MAP = [
        CategoryConstants::TRANSLATION_NAME => CategoryConstants::TRANSLATION_NAME_FOR_USER,
        CategoryConstants::CLEAN_NAME => CategoryConstants::CLEAN_NAME_FOR_USER,
        CategoryConstants::CARGO_NAME => CategoryConstants::CARGO_NAME_FOR_USER,
        CategoryConstants::NEO_NAME => CategoryConstants::NEO_NAME_FOR_USER,
        CategoryConstants::FLAT_NAME => CategoryConstants::FLAT_NAME_FOR_USER,
        CategoryConstants::REPAIR_NAME => CategoryConstants::REPAIR_NAME_FOR_USER,
        CategoryConstants::BEAUTY_NAME => CategoryConstants::BEAUTY_NAME_FOR_USER,
        CategoryConstants::PHOTO_NAME => CategoryConstants::PHOTO_NAME_FOR_USER,
    ];
}