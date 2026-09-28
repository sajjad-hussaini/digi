<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class VisaType extends Model
{
    protected $fillable = ['name'];

    public static function options(?string $current = null): array
    {
        $options = static::orderBy('name')->pluck('name', 'name')->all();
        if ($current !== null && $current !== '' && !isset($options[$current])) {
            $options[$current] = $current . ' (removed)';
        }
        return $options;
    }

    public static function rules(?string $current = null): array
    {
        return ['required', 'string', 'max:100', \Illuminate\Validation\Rule::in(array_keys(static::options($current)))];
    }
}
