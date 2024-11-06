<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\StoreRequest;
use App\Models\Products\Product;
use App\Models\Receipts\Movement;
use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function create(Request $request)
    {
        return view('entities.purchases.create', [
            'warehouse_id' => $request->session()->get('purchase-warehouse')
        ]);
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $request->session()->put('purchase-warehouse', $validated['warehouse_id']);
        $receipt = $this->storeReceipt($validated);
        for($i = 0; $i < count($validated['product_ids']); $i++){
            $this->storeMovement([
                'amount' => $validated['amounts'][$i],
                'product_id' => $validated['product_ids'][$i],
                'receipt_id' => $receipt->id,
                'warehouse_id' => $validated['warehouse_id'],
            ]);
        }
        return 'Guardado';
    }

    private function storeMovement(array $data): void
    {
        $existences = Product::find($data['product_id'])
            ->lastMovementIn($data['warehouse_id'])
            ?->existences ?? 0;
        $data['existences'] = $existences + $data['amount'];
        Movement::create($data);
    }

    private function storeReceipt(array $validated): Receipt
    {
        return Receipt::create([
            'comment' => $validated['comment'] ?? null,
            'type_id' => ReceiptType::where('name', 'purchase')->first()->id,
            'warehouse_id' => $validated['warehouse_id'],
            'user_id' => Auth::user()->id
        ]);
    }
}
