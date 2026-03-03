<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use App\Models\Traits\HasCamelSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormFieldMeta extends Model
{
    use HasCamelSlug, SoftDeletes, DisableSnakeAttributes;

    protected $table = 'form_field_metas';
    protected $guarded = [];

    /**
     * Get the form field that owns this meta
     */
    public function formField()
    {
        return $this->belongsTo(FormFields::class, 'ffId');
    }

    /**
     * Get parsed value with defaults
     */
    public function getParsedValue(): array
    {
        return array_merge([
            'category' => $this->key,
            'sub_category' => null,
            'sector' => null,
            'activity' => null,
            'sub_activity' => null,
            'entity' => null,
            'incubator' => null
        ], $this->value ?? []);
    }

    /**
     * Check if this rule matches given parameters
     */
    public function matches(array $params): bool
    {
        // Check category
        if ($this->key !== 'all' && $this->key !== ($params['category'] ?? null)) {
            return false;
        }

        $value = $this->getParsedValue();

        // Check sub category
        if (
            !empty($value['sub_category']) &&
            $value['sub_category'] !== ($params['sub_category'] ?? null)
        ) {
            return false;
        }

        // Check sector
        if (
            !empty($value['sector']) &&
            $value['sector'] !== ($params['sector'] ?? null)
        ) {
            return false;
        }

        // Check activity
        if (
            !empty($value['activity']) &&
            $value['activity'] !== ($params['activity'] ?? null)
        ) {
            return false;
        }

        // Check sub activity
        if (
            !empty($value['sub_activity']) &&
            $value['sub_activity'] !== ($params['sub_activity'] ?? null)
        ) {
            return false;
        }

        // Check entity
        if (
            !empty($value['entity']) &&
            $value['entity'] !== ($params['entity'] ?? null)
        ) {
            return false;
        }

        // Check incubator
        if (
            !empty($value['incubator']) &&
            $value['incubator'] !== ($params['incubator'] ?? null)
        ) {
            return false;
        }

        // Check onshore/offshore
        if (
            $this->onshoreOffShore !== 'both' &&
            $this->onshoreOffShore !== ($params['onshore_offshore'] ?? 'both')
        ) {
            return false;
        }

        return true;
    }
}
