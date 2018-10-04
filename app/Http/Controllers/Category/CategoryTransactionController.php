<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $transactions = $category->products()
                            //que contengan esta relacion
                            //para que no vayan vacios
                            ->has('transactions') //whereHas('transactions')
                            ->with('transactions')
                            ->get()
                            ->pluck('transactions')
                            ->collapse()
                            ->unique('id')
                            ->values();

        return $this->showAll($transactions);
    }
}
