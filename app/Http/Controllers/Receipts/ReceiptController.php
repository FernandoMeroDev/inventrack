<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Receipts\IndexRequest;
use App\Http\Requests\Receipts\UpdateRequest;
use App\Models\Products\Product;
use App\Models\Products\SalePrice;
use App\Models\Receipts\Movement;
use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptType;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function ask()
    {
        return view('entities.receipts.ask', [
            'receiptTypes' => ReceiptType::all()
        ]);
    }

    public function index(IndexRequest $request)
    {
        $validated = $request->validated();
        $validated['type_name'] = ReceiptType::find($validated['type_id'])->label;
        if($validated['user_id'] === 'all') $validated['user_name'] = 'Todos';
        else $validated['user_name'] = User::find($validated['user_id'])->name;
        if($validated['warehouse_id'] === 'all') $validated['warehouse_name'] = 'Todos';
        else $validated['warehouse_name'] = Warehouse::find($validated['warehouse_id'])->name;
        $receipts = $this->queryIndex($validated);
        return view('entities.receipts.index', [
            'receipts' => $receipts,
            'data' => $validated
        ]);
    }

    private function queryIndex($validated)
    {
        $receipts = Receipt::select(
                'receipts.id', 'receipts.created_at', 'receipts.comment'
            )->where('receipts.created_at', '>=', $validated['initial_date'] . ' 00:00:00')
            ->where('receipts.created_at', '<=', $validated['end_date'] . ' 23:59:59')
            ->where('receipts.type_id', $validated['type_id']);
        if($validated['user_id'] !== 'all'){
            $receipts = $receipts->where('receipts.user_id', $validated['user_id']);
        }
        if($validated['warehouse_id'] !== 'all'){
            $receipts = $receipts->where('receipts.warehouse_id', $validated['warehouse_id']);
        }
        return $receipts->orderBy('created_at')
            ->simplePaginate(10)
            ->withQueryString()
            ->fragment('receipts');
    }

    public function show(Receipt $receipt)
    {
        
        $data = ['receipt' => $receipt];
        if($receipt->type->name == 'sale'){
            $data['total'] = '$' . number_format(
                $receipt->movements->sum('price'), 2, ',', ' '
            );
        }
        return view('entities.receipts.show', $data);
    }

    public function edit(Receipt $receipt)
    {
        $data = ['receipt' => $receipt];
        if($receipt->type->name == 'sale'){
            $data['total'] = '$' . number_format(
                $receipt->movements->sum('price'), 2, ',', ' '
            );
        }
        return view('entities.receipts.edit', $data);
    }

    public function update(UpdateRequest $request, Receipt $receipt)
    {
        $validated = $request->validated();
        $receipt->update(['comment' => $validated['comment'] ?? '']);
        for($i = 0; $i < count($validated['movement_ids']); $i++){
            if($validated['amounts'][$i] > 0){
                $this->updateMovement([
                    'movement_id' => $validated['movement_ids'][$i],
                    'amount' => $validated['amounts'][$i],
                    'sale_price_id' => $validated['sale_price_ids'][$i],
                    'receipt' => $receipt
                ]);
            } else {
                $this->deleteMovement($validated['movement_ids'][$i]);
            }
        }
        if($receipt->movements->count() < 1){
            $receipt->delete();
            return redirect()->route('receipts.ask');
        }
        return redirect()->route('receipts.show', $receipt->id);
    }

    private function updateMovement(array $data): void
    {
        $movement = Movement::find($data['movement_id']);
        $salePrice = SalePrice::find($data['sale_price_id']);
        $existences = Product::join('movements', 'movements.product_id', '=', 'products.id')
            ->join('receipts', 'movements.receipt_id', '=', 'receipts.id')
            ->join('warehouses', 'receipts.warehouse_id', '=', 'warehouses.id')
            ->select('movements.id', 'movements.existences')
            ->where('movements.id', '<', $movement->id)
            ->where('movements.product_id', $movement->product->id)
            ->where('receipts.warehouse_id', $data['receipt']->warehouse_id)
            ->orderBy('id', 'desc')
            ->first()?->existences ?? 0;
        $movement->update([
            'amount' => $data['amount'],
            'price' => $salePrice->value,
            'existences' => $existences - $data['amount']
        ]);
        $this->updateNextMovements($movement);
    }

    private function updateNextMovements(Movement $movementUpdated): void
    {
        $movements = Movement::join('products', 'movements.product_id', '=', 'products.id')
            ->join('receipts', 'movements.receipt_id', '=', 'receipts.id')
            ->join('warehouses', 'receipts.warehouse_id', '=', 'warehouses.id')
            ->select('movements.*')
            ->where('movements.id', '>', $movementUpdated->id)
            ->where('movements.product_id', $movementUpdated->product->id)
            ->where('receipts.warehouse_id', $movementUpdated->receipt->warehouse_id)
            ->get();
        $previousMovement = $movementUpdated;
        foreach($movements as $movement){
            if(
                $movement->receipt->type->name == 'sale'
                || $movement->receipt->type->name == 'retirement'
            ){
                $movement->update(['existences' => $previousMovement->existences - $movement->amount]);
            } else {
                $movement->update(['existences' => $previousMovement->existences + $movement->amount]);
            }
            $previousMovement = $movement;
        }
    }

    private function deleteMovement(int $movement_id): void
    {
        $movementTarget = Movement::find($movement_id);
        $nextMovements = Movement::join('products', 'movements.product_id', '=', 'products.id')
            ->join('receipts', 'movements.receipt_id', '=', 'receipts.id')
            ->join('warehouses', 'receipts.warehouse_id', '=', 'warehouses.id')
            ->select('movements.*')
            ->where('movements.id', '>', $movementTarget->id)
            ->where('movements.product_id', $movementTarget->product->id)
            ->where('receipts.warehouse_id', $movementTarget->receipt->warehouse_id)
            ->get();
        foreach($nextMovements as $movement){
            if(
                $movement->receipt->type->name == 'sale'
                || $movement->receipt->type->name == 'retirement'
            ){
                $movement->update(['existences' => $movement->existences + $movementTarget->amount]);
            } else {
                $movement->update(['existences' => $movement->existences - $movementTarget->amount]);
            }
        }
        $movementTarget->delete();
    }
}
