<?php

namespace App\Base\Traits\Model;

trait Localizable
{

    public function __get($attribute)
    {
        // We determine the current locale and return the associated
        // locale-specific attribute e.g. name_en
        if (in_array($attribute, $this->localizable)) {
            $localeSpecificAttribute = $attribute . '_' . app()->getLocale();

            return $this->{$localeSpecificAttribute};
        }

        return parent::__get($attribute);
    }
}
