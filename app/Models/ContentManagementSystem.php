<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

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

    public function getSectionNameAttribute()
    {
        // if ($this->page == 'home') {
        //     if ($this->section == 1) {
        //         return 'Welcome Section';
        //     } elseif ($this->section == 2) {
        //         return 'About Section';
        //     } elseif ($this->section == 3) {
        //         return 'Resort Houses Section';
        //     } elseif ($this->section == 4) {
        //         return 'Quick Video Section';
        //     }
        // } elseif ($this->page == 'about') {
        //     if ($this->section == 1) {
        //         return 'Welcome Section';
        //     } elseif ($this->section == 2) {
        //         return 'Introduction Section';
        //     } elseif ($this->section == 3) {
        //         return 'Features Section';
        //     } elseif ($this->section == 4) {
        //         return 'History Section';
        //     } elseif ($this->section == 5) {
        //         return 'Highlighted FAQ Section';
        //     }
        // } elseif ($this->page == 'accommodation') {
        // } elseif ($this->page == 'amenities') {
        // } elseif ($this->page == 'restaurant_menu') {
        // } elseif ($this->page == 'contact_us') {
        // }

        $sectionNames = [
            'home' => [
                1 => 'Welcome Section',
                2 => 'About Section',
                3 => 'Resort Houses Section',
                4 => 'Quick Video Section',
            ],
            'about' => [
                1 => 'Welcome Section',
                2 => 'Introduction Section',
                3 => 'Features Section',
                4 => 'History Section',
                5 => 'Highlighted FAQ Section',
            ],
        ];

        return $sectionNames[$this->page][$this->section] ?? null;
    }

    public function scopeSearchBySectionName(Builder $query, string $search)
    {
        $sectionNames = [
            'home' => [
                1 => 'Welcome Section',
                2 => 'About Section',
                3 => 'Resort Houses Section',
                4 => 'Quick Video Section',
            ],
            'about' => [
                1 => 'Welcome Section',
                2 => 'Introduction Section',
                3 => 'Features Section',
                4 => 'History Section',
                5 => 'Highlighted FAQ Section',
            ],
        ];

        $sectionsToMatch = [];
        foreach ($sectionNames as $page => $sections) {
            foreach ($sections as $sectionId => $sectionName) {
                if (stripos($sectionName, $search) !== false) {
                    $sectionsToMatch[] = $sectionId;
                }
            }
        }

        return $query->whereIn('section', $sectionsToMatch);
    }
}
