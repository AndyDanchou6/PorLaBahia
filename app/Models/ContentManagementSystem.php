<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentManagementSystem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'page',
        'title',
        'section',
        'value',
        'icons',
    ];

    protected $casts = [
        'icons' => 'json',
    ];

    // public function getSectionAttribute()
    // {
    //     if ($this->page === 'home') {
    //         if ($this->section === 1) {
    //             return 'Welcome Section';
    //         } elseif ($this->section === 2) {
    //             return 'About Section';
    //         } elseif ($this->section === 3) {
    //             return 'Resort Houses Section';
    //         } elseif ($this->section === 4) {
    //             return 'Quick Video Section';
    //         }
    //     }
    // }
}
