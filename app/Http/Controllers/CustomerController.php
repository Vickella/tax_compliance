<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Currency;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->input('search').'%')
                    ->orWhere('code', 'like', '%'.$request->input('search').'%');
            })
            ->orderBy('name')
            ->paginate(15);

        return view('crm.customers.index', [
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        return view('crm.customers.create', [
            'currencies' => Currency::query()->orderBy('code')->get(),
        ]);
    }

    public function store(CustomerRequest $request)
    {
        $customer = Customer::create(array_merge(
            $request->validated(),
            ['company_id' => $request->user()?->company_id]
        ));

        return redirect()->route('crm.customers.index')
            ->with('status', "Customer {$customer->name} created.");
    }
}
