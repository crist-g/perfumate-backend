<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Usuarios de Prueba
        
        // Vendedor (Admin)
        User::create([
            'name' => 'Cristian Admin',
            'email' => 'admin@perfumer.co',
            'password' => Hash::make('password'), // Contraseña genérica
            'role' => 1, // 1 = Vendedor
        ]);

        // Cliente de Prueba
        User::create([
            'name' => 'Cliente Pruebas',
            'email' => 'cliente@perfumer.co',
            'password' => Hash::make('password'),
            'role' => 0, // 0 = Cliente
        ]);

        // 2. Categorías
        $catCitricos = Category::create(['name' => 'Cítricos']);
        $catAmaderados = Category::create(['name' => 'Amaderados']);
        $catFlorales = Category::create(['name' => 'Florales']);
        $catOrientales = Category::create(['name' => 'Orientales']);

        //Productos 
        
        // Perfumes Cítricos
        Product::create([
            'name' => 'Acqua Di Gio',
            'description' => 'Notas marinas frescas con toques de bergamota y mandarina verde.',
            'price' => 2500.00,
            'stock' => 20,
            'image_url' => 'https://placehold.co/400x400?text=Acqua+Di+Gio',
            'category_id' => $catCitricos->id,
            'brand' => 'Giorgio Armani'
        ]);

        Product::create([
            'name' => 'CK One',
            'description' => 'Un clásico unisex. Frescura pura con té verde y papaya.',
            'price' => 1200.50,
            'stock' => 50,
            'image_url' => 'https://placehold.co/400x400?text=CK+One',
            'category_id' => $catCitricos->id,
            'brand' => 'Calvin Klein'
        ]);

        // Perfumes Amaderados
        Product::create([
            'name' => 'Bleu de Chanel',
            'description' => 'Elegancia profunda. Notas de cedro, sándalo y un toque de incienso.',
            'price' => 3800.00,
            'stock' => 10,
            'image_url' => 'https://placehold.co/400x400?text=Bleu+De+Chanel',
            'category_id' => $catAmaderados->id,
            'brand' => 'Chanel'
        ]);

        Product::create([
            'name' => 'Sauvage',
            'description' => 'Potente y noble. Bergamota de Calabria y pimienta de Sichuan.',
            'price' => 3200.00,
            'stock' => 15,
            'image_url' => 'https://placehold.co/400x400?text=Sauvage',
            'category_id' => $catAmaderados->id,
            'brand' => 'Dior'
        ]);
        
        // Perfumes Florales
        Product::create([
            'name' => 'La Vie Est Belle',
            'description' => 'Iris pallida, Jazmín Sambac y Azahar. Un perfume para sonreír.',
            'price' => 2900.00,
            'stock' => 25,
            'image_url' => 'https://placehold.co/400x400?text=La+Vie+Est+Belle',
            'category_id' => $catFlorales->id,
            'brand' => 'Lancome'
        ]);
    }
}