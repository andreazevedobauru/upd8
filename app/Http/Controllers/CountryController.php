<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Country::query();

            if ($request->filled('name')) {
                $query = $query->where('name', 'like', '%' . $request->name . '%');
            }

            $countries = $query->paginate($request->per_page ?? 20);

            return response()->json($countries);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
