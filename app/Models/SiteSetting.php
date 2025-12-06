<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'site_name',
        'site_description',
        'footer_text',
        'site_logo',
        'favicon',
        'email_address',
        'phone_number',
        'office_address',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
