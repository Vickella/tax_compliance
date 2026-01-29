<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->input('search').'%')
                    ->orWhere('code', 'like', '%'.$request->input('search').'%');
            })
            ->orderBy('name')
            ->paginate(15);

        return view('crm.suppliers.index', [
            'suppliers' => $suppliers,
        ]);
    }

    public function create()
    {
        return view('crm.suppliers.create');
    }

    public function store(SupplierRequest $request)
    {
        $supplier = Supplier::create(array_merge(
            $request->validated(),
            ['company_id' => $request->user()?->company_id]
        ));

        return redirect()->route('crm.suppliers.index')
            ->with('status', "Supplier {$supplier->name} created.");
    }
}
