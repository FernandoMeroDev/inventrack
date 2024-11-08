<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function askAudit(Warehouse $warehouse)
    {
        return view('entities.inventory.audit.ask', [
            'warehouse' => $warehouse
        ]);
    }

    public function audit()
    {}
}
