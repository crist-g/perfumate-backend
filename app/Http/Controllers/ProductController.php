<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * Lista de perfumes
     */
    public function index(Request $request)
    {
        // Traer los productos con su categoría
        $query = Product::with('category');

        // Filtro por búsqueda de texto (nombre del perfume)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // iltro por categoría (Si el frontend manda el ID)
        if ($request->has('category_id') && $request->category_id != ''){
            $query->where('category_id', $request->category_id);
        }

        // Filtro por categoría (Si el frontend manda el nombre )
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filtro por marca
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        // Filtro por rango de precio
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Retornar resultados en forma de json, 200 = OK
        return response()->json($query->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'required|string',
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' 
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('perfumes', 'public');
            $data['image_url'] = '/storage/' . $path; // URL que lee el front
        }

        $product = Product::create($data);

        return response()->json([
            'message' => '¡Producto guardado con éxito!',
            'product' => $product
        ], 201);
    }

    /**
     * Perfume especifico mediante ID
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->all();

        // Si se edita, reemplazar 
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('perfumes', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $product->update($data);
        return response()->json(['message' => 'Actualizado correctamente'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Eliminado correctamente'], 200);
    }
}
