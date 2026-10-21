<?php

test('customers get an api token with their credentials', function () {
    $customer = customer();

    $this->postJson(route('api.tokens.store'), [
        'email' => $customer->email,
        'password' => 'password',
        'device_name' => 'iPhone',
    ])
        ->assertCreated()
        ->assertJsonStructure(['token']);

    expect($customer->tokens()->sole()->name)->toBe('iPhone');
});

test('wrong credentials do not get a token', function () {
    $this->postJson(route('api.tokens.store'), [
        'email' => customer()->email,
        'password' => 'wrong-password',
        'device_name' => 'iPhone',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});
