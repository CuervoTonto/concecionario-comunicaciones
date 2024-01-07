<?php

namespace Src\App\Controllers;

use Src\App\Controllers\Controller;
use Src\Exceptions\ResponseException;
use Src\Http\Response;
use Src\View\View;

class SomeController extends Controller
{
    public function index(): View
    {
        $this->validate([], [
            'name' => 'required'
        ], [], 'errors');

        return new View(fromViews('home.php'));
    }

    public function err(): void
    {
        var_dump(session()->allErrors());
    }
}