<?php

namespace SmartCms\Lang\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 *
 * @property int $id The unique identifier for the model.
 * @property string $name The name of the language.
 * @property string $slug The slug of the language.
 * @property string $locale The locale code of the language.
 * @property bool $is_default The default language.
 * @property bool $is_admin_active The status of the language in the admin panel.
 * @property bool $is_frontend_active The status of the language in the frontend.
 * @property \DateTime $created_at The date and time when the model was created.
 * @property \DateTime $updated_at The date and time when the model was last updated.
 */
class Language extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->is_default) {
                static::query()->where('id', '!=', $model->id)->update(['is_default' => false]);
            }
            if ($model->is_frontend_active && !$model->is_admin_active) {
                static::query()->where('id', '!=', $model->id)->update(['is_admin_active' => true]);
            }
        });
    }
}
