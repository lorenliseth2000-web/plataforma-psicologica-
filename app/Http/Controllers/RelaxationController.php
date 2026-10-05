<?php

namespace App\Http\Controllers;

use App\Services\RelaxationCatalog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RelaxationController extends Controller
{
    public function __construct(protected RelaxationCatalog $catalog) {}

    public function index(): View
    {
        return view('relaxation.index', [
            'sessions' => $this->catalog->all(),
        ]);
    }

    public function show(Request $request, string $session): View
    {
        $data = $this->catalog->find($session);

        if (! $data) {
            throw new NotFoundHttpException;
        }

        return view('relaxation.show', [
            'session' => $data,
            'hideChrome' => true,
        ]);
    }
}
