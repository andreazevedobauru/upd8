<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cities\GetRequest;
use App\Models\City;
use App\Models\Client;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CityController extends Controller
{
    public function index(GetRequest $request)
    {
        try {
            $query = City::with('state.country');

            if ($request->filled('name')) {
                $query = $query->where('name', 'like', '%' . $request->name . '%')->with('state');
            }

            $cities = $query->paginate($request->per_page ?? 15);

            return response()->json($cities);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $city = City::create($request->all());

            return response()->json($city, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            $city = City::with('state.country')->findOrFail($id);
            return response()->json($city);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $city = City::findOrFail($id);
            $city->update($request->all());

            return response()->json($city);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $city = City::findOrFail($id);
            $city->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
