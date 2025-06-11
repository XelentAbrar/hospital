<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountryRequest;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CountryController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPermission:countries.index')->only('index');
        $this->middleware('checkPermission:countries.create')->only('create', 'store');
        $this->middleware('checkPermission:countries.show')->only('show');
        $this->middleware('checkPermission:countries.edit')->only('edit', 'update');
        $this->middleware('checkPermission:countries.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::orderBy('created_at', 'desc')->paginate(100);
        return Inertia::render('Countries/Index', [
            'countries' => $countries,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Countries/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CountryRequest $request)
    {
        DB::beginTransaction();

        $country = new Country();
        $formData = $request->only($country->getFillable());

        Country::create($formData);

        DB::commit();

        return redirect()->route('countries.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        return Inertia::render('Countries/Create', [
            'country' => $country,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CountryRequest $request, Country $country)
    {
        DB::beginTransaction();

        $formData = $request->only($country->getFillable());
        $country->update($formData);

        DB::commit();

        return redirect()->route('countries.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return Inertia::location(route('countries.index'));
    }
}
