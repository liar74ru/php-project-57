<?php

return [
    'required' => 'The :attribute field is required.',
    'unique' => 'This :attribute already exists.',
    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'confirmed' => 'The password confirmation does not match.',
    'max' => [
        'string' => 'The :attribute field must not exceed :max characters.',
    ],
    'min' => [
        'numeric' => 'The :attribute field must be at least :min.',
        'file' => 'The :attribute file must be at least :min kilobytes.',
        'string' => 'The password must be at least :min characters.',
        'array' => 'The :attribute field must have at least :min items.',
    ],
    'attributes' => [
        'name' => 'name',
        'description' => 'description',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'status_id' => 'status'
    ],
    'custom' => [
        'name' => [
            'unique' => 'A status with this name already exists.',
        ],
    ],
];
