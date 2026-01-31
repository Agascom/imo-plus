<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()->with('images')->latest()->get();
        return response()->json($favorites);
    }

    public function toggle(Request $request, $id)
    {
        $user = $request->user();
        $property = Property::findOrFail($id);

        $favorites = $user->favorites();

        if ($favorites->where('property_id', $id)->exists()) {
            $favorites->detach($id);
            return response()->json(['message' => 'Removed from favorites', 'is_favorite' => false]);
        } else {
            $favorites->attach($id);
            return response()->json(['message' => 'Added to favorites', 'is_favorite' => true]);
        }
    }
}
