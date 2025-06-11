<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Customer;
use App\Models\Inventory\Product;
use App\Models\Finance\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = Invoice::orderBy('created_at', 'desc')->get();
        return Inertia::render('Finance/Invoice/Index', [
            'invoices' => $invoices,
        ]);
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();

        return Inertia::render('Finance/Invoice/Create', [
            'csrf_token' => csrf_token(),
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            // 'landline' => 'nullable|string|max:255',
            // 'address' => 'nullable|string|max:255',
            // 'postal_code' => 'nullable|string|max:255',
            // 'city_id' => 'nullable|exists:cities,id',
            // 'state_id' => 'nullable|exists:states,id',
            // 'country_id' => 'nullable|exists:countries,id',
            // 'logo' => 'nullable|string|max:255',
            // 'opening_balance' => 'nullable|numeric',
            // 'opening_type' => 'nullable|in:payable,receiveable',
            // 'tax_number' => 'nullable|string|max:255',
            // 'notes' => 'nullable|string',
            // 'status' => 'required|in:active,inactive',
        ]);

        $uniqueFilename = $request->logo;

        $supplierData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'landline' => $request->landline,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'city_id' => $request->city_id,
            'state_id' => $request->state_id,
            'country_id' => $request->country_id,
            'logo' => $uniqueFilename,
            'opening_balance' => $request->opening_balance,
            'opening_type' => $request->opening_type,
            'tax_number' => $request->tax_number,
            'notes' => $request->notes,
            'status' => $request->status,
        ];

        $supplier = Supplier::create($supplierData);

        if(!empty($uniqueFilename)) {
            if (!file_exists(public_path() . '/uploads/Inventory/Suppliers')) {
                mkdir(public_path() . '/uploads/Inventory/Suppliers');
            }

            $source = public_path('storage/uploads/Inventory/' . $uniqueFilename);
            $destination = public_path('uploads/Inventory/Suppliers/' . $uniqueFilename);

            // Check if source file exists before trying to copy
            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }
        }

        if (!empty($request->supplier_contact)) {
            $supplierContactData = [];

            foreach ($request->supplier_contact as $contact) {
                $supplierContactData[] = new SupplierContact($contact);
            }

            $supplier->supplier_contact()->saveMany($supplierContactData);
        }

        DB::commit();;
        
        return redirect()->route('suppliers.index');
    }


    public function edit(Supplier $supplier)
    {
        $countries = Country::orderBy('name')->get();
        $suppliers = Supplier::where('id', '!=', $supplier->id)->get();
        $supplier->load('supplier_contact');
        $supplier->setRelation('supplier_contact', $supplier->supplier_contact);
        $states = [];
        if ($supplier->state_id) {
            if ($supplier->state) {
                $states = State::where('country_id', $supplier->state->country_id)->orderBy('name')->get();
            } else {
                $states = [];
            }
        }
        $cities = [];
        if ($supplier->city_id) {
            if ($supplier->state) {
                $cities = City::where('state_id', $supplier->state_id)->orderBy('name')->get();
            } else {
                $cities = [];
            }
        }
        return Inertia::render('Inventory/Supplier/Edit', [
            'supplier' => $supplier,
            'countries' => $countries->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                ];
            }),
            'states' => collect($states)->map(function ($state) {
                return [
                    'id' => $state->id,
                    'name' => $state->name,
                    'country_id' => $state->country_id,
                ];
            }),
            'cities' => collect($cities)->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'state_id' => $city->state_id,
                ];
            }),
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'landline' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'state_id' => 'nullable|exists:states,id',
            'country_id' => 'nullable|exists:countries,id',
            'logo' => 'nullable|string|max:255',
            'opening_balance' => 'nullable|numeric',
            'opening_type' => 'nullable|in:payable,receiveable',
            'tax_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $oldImage = $supplier->logo;
        $newImage = $request->logo;

        if ($oldImage !== $newImage) {

            $source = public_path('storage/uploads/Inventory/' . $newImage);
            $destination = public_path('uploads/Inventory/Suppliers/' . $newImage);

            if (file_exists($source)) {
                copy($source, $destination);
                unlink($source);
            }

            // Delete old image if it exists
            if(!empty($oldImage)) {
            $oldImagePath = public_path('uploads/Inventory/Suppliers/' . $oldImage);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

            $supplier->logo = $newImage;
        }

        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->landline = $request->landline;
        $supplier->postal_code = $request->postal_code;
        $supplier->city_id = $request->city_id;
        $supplier->state_id = $request->state_id;
        $supplier->country_id = $request->country_id;
        $supplier->logo = $newImage;
        $supplier->opening_balance = $request->opening_balance;
        $supplier->opening_type = $request->opening_type;
        $supplier->tax_number = $request->tax_number;
        $supplier->notes = $request->notes;
        $supplier->status = $request->status;

        $supplier->save();

        $supplier->supplier_contact()->delete();
        foreach ($request->supplier_contact as $supplierContactData) {
            $supplier_contact = new SupplierContact($supplierContactData);
            $supplier->supplier_contact()->save($supplier_contact);
        }


        return redirect()->route('suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return Inertia::location(route('suppliers.index'));
    }
}
