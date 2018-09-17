<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Category;
use App\Product;
use App\Transaction;
use App\Seller;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //desactivamos la verificacion de las llaves foraneas
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        //Eliminamos todos los registros de cada tabal
        User::truncate();
        Category::truncate();
        Transaction::truncate();
        Product::truncate();
        DB::table('category_product')->truncate();

        //Llamamos a los factories 
        $cantidadUsuarios = 200;
        $cantidadCategories = 30;
        $cantidadProductos = 1000;
        $cantidadTransacciones = 1000;

        factory(User::class, $cantidadUsuarios)->create();
        factory(Category::class, $cantidadCategories)->create();

        //Creamos los productos y le asignamos unas categorias
        factory(Product::class, $cantidadProductos)->create()->each(
            function($producto){
                $categorias = Category::all()->random(mt_rand(1,5))->pluck('id');
                
                $producto->categories()->attach($categorias);
        });

        factory(Transaction::class, $cantidadTransacciones)->create();
    }
}
