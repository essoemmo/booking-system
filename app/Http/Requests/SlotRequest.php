<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SlotRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'start_time' => [Rule::requiredIf($this->routeIs('api.slots.store')),'date'],
            'end_time' => [Rule::requiredIf($this->routeIs('api.slots.store')),'date','after:start_time'],
            'is_available' => [Rule::requiredIf($this->routeIs('api.slots.update')),'boolean'],
        ];
    }
}
