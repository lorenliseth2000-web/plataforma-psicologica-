<?php

namespace App\Http\Controllers;

use App\Services\EmergencyDirectory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmergencyController extends Controller
{
    public function __construct(protected EmergencyDirectory $directory) {}

    public function index()
    {
        $countries = $this->directory->countryOptions();
        return view('emergencias.index', compact('countries'));
    }

    public function lookup(Request $request): JsonResponse
    {
        $request->validate(['country_code' => 'nullable|string|max:3']);
        $code = strtoupper(trim((string) $request->input('country_code', '')));
        $data = $this->directory->forCountry($code);
        return response()->json($data);
    }
}
