<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of active products.
     */
    public function index(): View
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }
}
