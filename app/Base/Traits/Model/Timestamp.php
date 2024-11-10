<?php

namespace App\Base\Traits\Model;

use Carbon\Carbon;

trait Timestamp
{
    public function getCreatedAtDateAttribute()
    {
        $locale = app()->getLocale();
        return Carbon::parse($this->created_at)->locale($locale)->translatedFormat('j F Y');
    }

    public function getCreatedAtDateTimeAttribute()
    {
        $locale = app()->getLocale();
        return Carbon::parse($this->created_at)->locale($locale)->translatedFormat('j F Y - h:i A');
    }

    public function getUpdatedAtDateTimeAttribute()
    {
        $locale = app()->getLocale();
        return Carbon::parse($this->updated_at)->locale($locale)->translatedFormat('j F Y - h:i A');
    }

    public function getCreatedAtTimeAttribute()
    {
        $locale = app()->getLocale();
        return Carbon::parse($this->created_at)->locale($locale)->translatedFormat('h:i A');
    }

    public function getUpdatedAtDateAttribute()
    {
        $locale = app()->getLocale();
        return Carbon::parse($this->updated_at)->locale($locale)->translatedFormat('j F Y');
    }

    public function getDeletedAtDateAttribute()
    {
        $locale = app()->getLocale();
        return Carbon::parse($this->deleted_at)->locale($locale)->translatedFormat('j F Y');
    }
}
