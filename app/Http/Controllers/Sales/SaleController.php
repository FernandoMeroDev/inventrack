<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\SetWarehouseRequest;
use App\Http\Requests\Sales\StoreRequest;
use App\Models\Products\Product;
use App\Models\Products\SalePrice;
use App\Models\Receipts\Movement;
use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptType;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function selectWarehouse()
    {
        return view('entities.sales.select-warehouse');
    }

    public function setWarehouse(SetWarehouseRequest $request)
    {
        $validated = $request->validated();
        $request->session()->put('sale-warehouse', $validated['warehouse_id']);
        return redirect()->route('sales.create');
    }

    public function create(Request $request)
    {
        if( ! $request->session()->has('sale-warehouse') ){
            return redirect()->route('sales.select-warehouse');
        }
        return view('entities.sales.create', [
            'warehouse' => Warehouse::find($request->session()->get('sale-warehouse')),
        ]);
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $receipt = $this->storeReceipt($validated);
        for($i = 0; $i < count($validated['product_ids']); $i++){
            $this->storeMovement([
                'amount' => $validated['amounts'][$i],
                'product_id' => $validated['product_ids'][$i],
                'sale_price_id' => $validated['sale_price_ids'][$i],
                'receipt_id' => $receipt->id,
                'warehouse_id' => $validated['warehouse_id'],
            ]);
        }
        return 'Guardado';
    }

    private function storeMovement(array $data): void
    {
        $data['price'] = SalePrice::find($data['sale_price_id'])->value;
        $existences = Product::find($data['product_id'])
            ->lastMovementIn($data['warehouse_id'])
            ->existences;
        $data['existences'] = $existences - $data['amount'];
        Movement::create($data);
    }

    private function storeReceipt(array $validated): Receipt
    {
        return Receipt::create([
            'comment' => $validated['comment'] ?? null,
            'consolidated' => false,
            'type_id' => ReceiptType::where('name', 'sale')->first()->id,
            'warehouse_id' => $validated['warehouse_id'],
            'user_id' => Auth::user()->id
        ]);
    }
}
