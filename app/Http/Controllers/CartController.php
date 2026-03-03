<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{

    /**
     * Ver carrito y total a pagar
     */
    public function index()
    {
        $items = Cart::where('user_id', Auth::id())->with('product')->get();
        
        // Calcular total a pagar
        $total = $items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return response()->json([
            'items' => $items,
            'total_to_pay' => $total
        ], 200);
    }

    /**
     * Agregar un producto al carrito 
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);

        // Validar Stock 
        if ($product->stock < $request->quantity) {
            return response()->json([
                'message' => 'Solo quedan ' . $product->stock . ' unidades disponibles.'
            ], 400);
        }

        // Buscar si ya existe en el carrito
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Si ya existe, sumar la cantidad
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Si no existe, crear la fila nueva
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json(['message' => 'Agregado al carrito'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Buscar el item verificnado que sea del usuario actual
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item no encontrado'], 404);
        }

        // Validar stock de nuevo aquí
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Cantidad actualizada correctamente'], 200);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item no encontrado'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Producto eliminado del carrito'], 200);
    }
}
