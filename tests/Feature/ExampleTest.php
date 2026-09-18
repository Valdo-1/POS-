<?php

test('the root url redirects to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
