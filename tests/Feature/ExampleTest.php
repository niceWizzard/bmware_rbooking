<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('patient.doctors'));
});
