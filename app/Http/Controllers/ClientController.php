<?php

namespace App\Http\Controllers;

use App\Models\ClientModel;
use App\Models\ClientAddressModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = ClientModel::with('addresses')->orderBy('created_at', 'desc')->get();
        return $this->showAll($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'name' => 'required|string|max:250',
            'addresses.*.formatted_address' => 'required|string|max:250',
            'addresses.*.lat' => 'numeric',
            'addresses.*.lng' => 'numeric',
        ]);

        // Crear el cliente
        $client = ClientModel::create([
            'name' => $request->input('name'),
        ]);

        // Crear y asociar las direcciones al cliente
        foreach ($request->input('addresses') as $addressData) {
            $address = new ClientAddressModel($addressData);
            $client->addresses()->save($address);
        }
        $client->load('addresses');

        return $this->showOne($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de datos
        $request->validate([
            'name' => 'required|string|max:250',
            'addresses.*.formatted_address' => 'required|string|max:250',
            'addresses.*.lat' => 'numeric',
            'addresses.*.lng' => 'numeric',
        ]);

        $client = ClientModel::find($id);
        $client->fill($request->all());
        $client->save();

        // Delete all existing addresses
        $client->addresses()->delete();

        // Create and associate new addresses
        foreach ($request->input('addresses') as $addressData) {
            $newAddress = new ClientAddressModel($addressData);
            $client->addresses()->save($newAddress);
        }
        $client->load('addresses');

        return $this->showOne($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = ClientModel::find($id);
        $client->addresses()->delete();
        $client->delete();

        return $this->showOne($client);
    }

    public function getAddressTypes()
    {
        $addressTypes = DB::table('address_type')->get();
        return $this->showAll($addressTypes);
    }
}
