<?php

namespace App\Http\Controllers;

use App\Models\State;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StateController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = State::with('country');

            if ($request->filled('name')) {
                $query = $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('country_id')) {
                $query->whereHas('country', function ($query) use ($request) {
                    $query->where('id', $request->country_id);
                });
            }

            $states = $query->paginate($request->per_page ?? 20);

            return response()->json($states);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
