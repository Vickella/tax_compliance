<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->input('search').'%')
                    ->orWhere('sku', 'like', '%'.$request->input('search').'%');
            })
            ->orderBy('name')
            ->paginate(15);

        return view('inventory.items.index', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('inventory.items.create');
    }

    public function store(ItemRequest $request)
    {
        Item::create(array_merge(
            $request->validated(),
            ['company_id' => $request->user()?->company_id]
        ));

        return redirect()->route('inventory.items.index')
            ->with('status', 'Item created.');
    }

    public function show(Item $item)
    {
        return response()->json([
            'id' => $item->id,
            'sku' => $item->sku,
            'name' => $item->name,
            'selling_price' => $item->selling_price,
            'cost_price' => $item->cost_price,
            'vat_category' => $item->vat_category,
        ]);
    }
        return view('inventory.items.index');
    }

}
