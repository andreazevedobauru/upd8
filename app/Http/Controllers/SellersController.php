<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sellers\SellerSearchRequest;
use App\Http\Requests\sellers\StoreRequest;
use App\Http\Requests\sellers\UpdateRequest;
use App\Models\Client;
use App\Models\Seller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class SellersController extends Controller
{
    public function index(SellerSearchRequest $request)
    {
        try {
            $query = Seller::with('city.state');

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('city_id')) {
                $query->where('city_id', $request->city_id);
            }

            if ($request->filled('city')) {
                $query->whereHas('city', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->city . '%');
                });
            }

            if ($request->filled('state')) {
                $query->whereHas('city.state', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->state . '%');
                });
            }

            $clients = $query->paginate($request->per_page ?? 15);

            return response()->json($clients);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $seller = Seller::create($request->all());
            return response()->json($seller, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            $seller = Seller::with('city.state')->findOrFail($id);
            return response()->json($seller);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $seller = Seller::findOrFail($id);
            $seller->update($request->all());

            return response()->json($seller);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $seller = Seller::findOrFail($id);
            $seller->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSellersByCity($cityId)
    {
        $sellers = Seller::where('city_id', $cityId)->get();

        return response()->json($sellers);
    }
}
