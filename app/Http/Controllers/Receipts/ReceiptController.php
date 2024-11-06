<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Receipts\IndexRequest;
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
}
