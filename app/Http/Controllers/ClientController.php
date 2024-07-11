<?php

namespace App\Http\Controllers;

use App\Http\Requests\Clients\ClientSearchRequest;
use App\Http\Requests\Clients\StoreRequest;
use App\Http\Requests\Clients\UpdateRequest;
use App\Models\Client;
use App\Models\Seller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function index(ClientSearchRequest $request)
    {
        try {
            $query = Client::with('city.state');

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('cpf')) {
                $query->where('cpf', 'like', '%' . $request->cpf . '%');
            }

            if ($request->filled('email')) {
                $query->where('email', $request->email);
            }

            if ($request->filled('city_id')) {
                $query->where('city_id', $request->city_id);
            }

            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }

            if ($request->filled('address')) {
                $query->where('address', 'like', '%' . $request->address . '%');
            }

            if ($request->filled('city')) {
                $query->whereHas('city', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->city . '%');
                });
            }

            if ($request->filled('state_name')) {
                $query->whereHas('city.state', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->state_name . '%');
                });
            }

            $clients = $query->paginate($request->per_page ?? 15);

            return response()->json($clients);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $client = Client::create($request->all());

            return response()->json($client, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            $client = Client::with('city.state')->findOrFail($id);
            return response()->json($client);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->update($request->all());

            return response()->json($client);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSellersByClientCity($clientId)
    {
        try {
            $client = Client::with('city.state')->findOrFail($clientId);
            $sellers = Seller::where('city_id', $client->city_id)->get();

            return response()->json($sellers);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
