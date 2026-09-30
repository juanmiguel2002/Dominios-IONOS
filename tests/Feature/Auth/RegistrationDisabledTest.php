<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('el registro público está desactivado', function () {
    $this->get('/register')->assertNotFound();
});
