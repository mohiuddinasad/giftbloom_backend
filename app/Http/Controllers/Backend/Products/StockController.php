<?php

namespace App\Http\Controllers\Backend\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Products\StockMovementRequest;
use App\Models\Backend\Products\Product;
use App\Models\Backend\Products\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // full in/out history for one product ($product resolved by slug)
    public function index(Product $product)
    {
        $movements = $product->stockMovements()
            ->with('user:id,name')
            ->latest()
            ->paginate(20);

        return view('backend.products.products.stock', compact('product', 'movements'));
    }

    // handles both "stock in" and "stock out" from one form (type = in|out)
    public function store(StockMovementRequest $request, Product $product)
    {
        try {
            StockMovement::record(
                $product,
                $request->type,
                (int) $request->quantity,
                $request->reason,
                $request->note
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Stock updated. Current qty: '.$product->fresh()->qty);
    }
}
