<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $primaryKey = 'menu_id';
    
    protected $fillable = [
        'menu_p_id', 'menu_route_type_id', 'menu_name', 'menu_icon',
        'menu_status', 'menu_sub_status', 'menu_route', 'menu_group', 'menu_sequence'
    ];
    
    protected $casts = [
        'menu_status' => 'boolean',
        'menu_sub_status' => 'integer',
        'menu_sequence' => 'integer',
    ];
    
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'menu_p_id', 'menu_id');
    }
    
    public function children()
    {
        return $this->hasMany(Menu::class, 'menu_p_id', 'menu_id')->orderBy('menu_sequence');
    }
    
    public function scopeActive($query)
    {
        return $query->where('menu_status', 1);
    }
    
    public function scopeParentMenus($query)
    {
        return $query->whereNull('menu_p_id')->orderBy('menu_sequence');
    }
    
    public function scopeSubMenus($query)
    {
        return $query->whereNotNull('menu_p_id')->where('menu_sub_status', 1)->orderBy('menu_sequence');
    }
}