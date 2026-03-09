<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Traits\DisableSnakeAttributes;
use App\Models\Traits\HasCamelSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormFields extends Model
{
    use HasCamelSlug, SoftDeletes, DisableSnakeAttributes;

    protected $table = 'form_fields';
    protected $guarded = [];

    /**
     * Get the meta data for this field (category rules)
     */
    public function formMetas()
    {
        return $this->hasMany(FormFieldMeta::class, 'ffId');
    }

    /**
     * Get field options from meta
     */
    protected function options(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->meta['options'] ?? []
        );
    }

    /**
     * Get field validations from meta
     */
    protected function validations(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->meta['validations'] ?? []
        );
    }

    /**
     * Get field placeholders from meta
     */
    protected function placeholders(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'en' => $this->meta['placeholderEn'] ?? null,
                'ar' => $this->meta['placeholderAr'] ?? null
            ]
        );
    }

    /**
     * Get field help text from meta
     */
    protected function helpText(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'en' => $this->meta['helpTextEn'] ?? null,
                'ar' => $this->meta['helpTextAr'] ?? null
            ]
        );
    }

    /**
     * Get field tooltip from meta
     */
    protected function tooltip(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'en' => $this->meta['tooltipEn'] ?? null,
                'ar' => $this->meta['tooltipAr'] ?? null
            ]
        );
    }

    /**
     * Check if field has a specific option
     */
    public function hasOption(string $key): bool
    {
        return isset($this->meta[$key]);
    }

    /**
     * Get a specific meta value
     */
    public function getMetaValue(string $key, $default = null)
    {
        return $this->meta[$key] ?? $default;
    }

    /**
     * Scope for active fields
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for section
     */
    public function scopeInSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope for group
     */
    public function scopeInGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope ordered by field_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('fieldOrder');
    }

    public function getCategoryRulesAttribute()
    {
        return $this->formMetas->map(function ($meta) {
            $value = $meta->value ? json_decode($meta->value, true) : [];
            return [
                'categorySlug' => $meta->key,
                'subCategorySlug' => $value['sub_category'] ?? null,
                'sectorSlug' => $value['sector'] ?? null,
                'activitySlug' => $value['activity'] ?? null,
                'subActivitySlug' => $value['sub_activity'] ?? null,
                'entitySlug' => $value['entity'] ?? null,
                'incubatorSlug' => $value['incubator'] ?? null,
                'onshoreOffshore' => $meta->onshoreOffShore,
                'isRequired' => $meta->isRequired,
            ];
        });
    }
}
