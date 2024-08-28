<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        return Guest::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'age' => 'required|integer',
        ]);

        $guest = Guest::create($request->all());
        return response()->json($guest, 201);
    }

    public function show(Guest $guest)
    {
        return $guest;
    }

    public function update(Request $request, Guest $guest)
    {
        $request->validate([
            'full_name' => 'sometimes|required|string',
            'age' => 'sometimes|required|integer',
        ]);

        $guest->update($request->all());
        return response()->json($guest);
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return response()->json(['message' => 'Guest deleted successfully']);
    }
}
