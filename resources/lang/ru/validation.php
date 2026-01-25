<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'unique' => 'Такой :attribute уже существует.',
    'max' => [
        'string' => 'Поле :attribute не должно превышать :max символов.',
    ],
    'min' => [
        'numeric' => 'Поле :attribute должно быть не меньше :min.',
        'file' => 'Размер файла в поле :attribute должен быть не меньше :min килобайт.',
        'string' => 'Поле :attribute должно содержать не менее :min символов.',
        'array' => 'Поле :attribute должно содержать не менее :min элементов.',
    ],
    'attributes' => [
        'name' => 'имя',
        'description' => 'описание',
        'password' => 'пароль',
        'password_confirmation' => 'Подтверждение пароля'
    ],
];
