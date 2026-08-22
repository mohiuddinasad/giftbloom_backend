<?php

namespace App\Models\Backend\Settings;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'site_name',
        'site_logo',
        'site_favicon',
        'contact_phone',
        'contact_email',
        'contact_address',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    // Always get the first (and only) row
    public static function instance(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }


}

