<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']); // Catalogo
Route::get('/products/{id}', [ProductController::class, 'show']); // Detalles
Route::post('/register', [AuthController::class, 'register']);

// Rutas públicas de listado y detalle

Route::middleware('auth:sanctum')->group(function () {

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // Perfil
    Route::get('/user', function(Request $request){
        return $request->user();
    });

    // actualizar perfil (nombre/email, dirección, pago)
    Route::match(['put','post'], '/user/{section}', function(Request $request, $section){
        $user = $request->user();

        switch ($section) {
            case 'personal':
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                ]);

                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->save();
                return response()->json([ 'name' => $user->name, 'email' => $user->email ]);
            case 'address':
            case 'payment':
                $data = $request->all();
                // Elimina campos no deseados
                unset($data['_method']);
                unset($data['_token']);
                $user->{$section} = $data;
                $user->save();
                return response()->json([$section => $data]);

            default:
                return response()->json(['message' => 'Sección desconocida'], 404);
        }
    });

    // Rutas protegidas para administradores / vendedores
    Route::middleware('admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']); // Crear
        Route::put('/products/{id}', [ProductController::class, 'update']); // Editar
        Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Eliminar
    });

    // Ver carrito
    Route::get('/cart', [CartController::class, 'index']);

    // Agregar al carrito
    Route::post('/cart', [CartController::class, 'store']);

    // "Respuesta de PP"
    Route::post('/checkout', [OrderController::class, 'checkout']);

    // Historial de pedidos para el cliente
    Route::get('/orders', [OrderController::class, 'index']);

    // Rutas administrativas sobre pedidos
    Route::middleware('admin')->group(function () {
        Route::get('/admin/orders', [OrderController::class, 'allOrders']);
        Route::put('/admin/orders/{id}/status', [OrderController::class, 'updateStatus']);
    });

    // Modificar cantidad en el carrito
    Route::put('/cart/{id}', [CartController::class, 'update']);

    // Eliminar producto del carrito
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
} );
