<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('images', 'user');

        if ($request->has('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('neighborhood', 'like', "%{$search}%");
            });
        }

        // Filter by user (My Listings)
        if ($request->has('user_id')) {
             $query->where('user_id', $request->user_id);
        }

        if ($request->has('is_featured')) {
            $query->where('is_featured', filter_var($request->is_featured, FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'frequency' => 'required|string',
            'transaction_type' => 'required|string',
            'property_type' => 'required|string',
            'city' => 'required|string',
            'neighborhood' => 'required|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'area' => 'nullable|integer',
        ]);

        $property = $request->user()->properties()->create($validated);

        return response()->json($property, 201);
    }

    public function show($id)
    {
        $property = Property::with(['images', 'user'])->findOrFail($id);
        $property->increment('views_count');
        return response()->json($property);
    }

    public function uploadImage(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        if ($request->user()->id !== $property->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('properties', 'public');

            $image = $property->images()->create([
                'image_path' => $path,
                'is_primary' => $property->images()->count() === 0, // First image is primary
            ]);

            return response()->json($image, 201);
        }

        return response()->json(['message' => 'Upload failed'], 400);
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        if ($request->user()->id !== $property->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'frequency' => 'sometimes|string',
            'transaction_type' => 'sometimes|string',
            'property_type' => 'sometimes|string',
            'city' => 'sometimes|string',
            'neighborhood' => 'sometimes|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'area' => 'nullable|integer',
            'status' => 'sometimes|string',
        ]);

        $property->update($validated);

        return response()->json($property);
    }

    public function destroy(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        if ($request->user()->id !== $property->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $property->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
