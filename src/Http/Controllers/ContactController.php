<?php

declare(strict_types=1);

namespace ARKEcosystem\UserInterface\Http\Controllers;

use Illuminate\Contracts\View\View;

final class ContactController extends Controller
{
    public function __invoke(): View
    {
        return view('app.contact');
    }
}
