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
        'section',
        'title_name',
        'subtitle',
        'background_image',
        'value',
        'icons',
    ];

    protected $casts = [
        'icons' => 'json',
    ];

    public function getSectionNameAttribute()
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
            'accommodation' => [
                1 => 'Welcome Section'
            ],
            'amenities' => [
                1 => 'Welcome Section'
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
