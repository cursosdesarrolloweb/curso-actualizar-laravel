<?php

use Illuminate\Support\Facades\Schema;

test('the priority is nullable and normal by default', function () {
    $priority = collect(Schema::getColumns('tickets'))->firstWhere('name', 'priority');

    expect($priority['nullable'])->toBeTrue()
        ->and($priority['default'])->toBe('normal');
});

test('the estimated hours keep 2 decimal places', function () {
    $estimatedHours = collect(Schema::getColumns('tickets'))->firstWhere('name', 'estimated_hours');

    expect($estimatedHours['type'])->toBe('decimal(8,2)');
});
