<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    protected $fillable = [
        'site_name',
        'logo',
        'manager_name',
        'address',
        'contact_info',
        'theme_primary_color',
        'theme_secondary_color',
        'app_background_color',
        'background_overlay_opacity',
        'sidebar_bg_color',
        'topbar_bg_color',
        'footer_text',
        'background_image',
    ];
}
