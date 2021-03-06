<?php

namespace Laravel\Passport\Traits;

use Webpatser\Uuid\Uuid;
use Illuminate\Validation\ValidationException;

trait HasUuid
{
    /**
     * Set a uuid
     *
     * @return void
     */
    public static function bootHasUuid()
    {
        static::creating(function ($model) {
            if(!$model->{$model->getKeyName()}){
                $model->{$model->getKeyName()} = Uuid::generate(4)->string;
            }
        });
    }

    /**
     * Override the getCasts method to cast the UUID object to a string
     *
     * @return array
     */
    public function getCasts()
    {
        $this->casts = array_unique(array_merge($this->casts, [$this->getKeyName() => 'string']));

        return parent::getCasts();
    }

    /**
     * Override the resolveRouteBinding method to validate the parameter is a uuid
     *
     * @param \Illuminate\Database\Eloquent\Model
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws ValidationException
     */
    public function resolveRouteBinding($value)
    {
        $validator = app('validator')->make(
            ['id' => $value],
            ['id' => [new ValidateUuid]]
        );

        if (! $validator->passes()) {
            throw new ValidationException($validator);
        }

        return parent::resolveRouteBinding($value);
    }

}
