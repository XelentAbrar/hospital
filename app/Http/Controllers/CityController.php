<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:cities.index')->only('index');
        $this->middleware('checkPermission:cities.create')->only('create', 'store');
        $this->middleware('checkPermission:cities.show')->only('show');
        $this->middleware('checkPermission:cities.edit')->only('edit', 'update');
        $this->middleware('checkPermission:cities.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $cities = City::with(['state', 'state.country'])->orderBy('created_at', 'desc')->paginate(100);

    //     return Inertia::render('Cities/Index', [
    //         'cities' => $cities->map(function ($city) {
    //             return [
    //                 'id' => $city->id,
    //                 'name' => $city->name,
    //                 'abrv' => $city->abrv,
    //                 'state_name' => $city->state->name,
    //                 'country_name' => $city->state->country->name,
    //                 'created_at' => $city->created_at->toDateTimeString(),
    //                 'updated_at' => $city->updated_at->toDateTimeString(),
    //             ];
    //         }),
    //     ]);
    // }
    public function index()
    {
        $cities = City::with(['state', 'state.country'])
            ->orderBy('created_at', 'desc')
            ->paginate(100)
            ->through(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'abrv' => $city->abrv,
                    'state_name' => $city->state->name,
                    'country_name' => $city->state->country->name,
                    'created_at' => $city->created_at->toDateTimeString(),
                    'updated_at' => $city->updated_at->toDateTimeString(),
                ];
            });
    
        return Inertia::render('Cities/Index', [
            'cities' => $cities,
        ]);
    }
    
    //     public function getCitiesByState($stateId)
    // {
    //     $cities = City::where('state_id', $stateId)->get();
    //     return response()->json(['cities' => $cities]);
    // }

    public function getCitiesByState($stateId)
    {
        Log::info('Requested cities for state id: ' . $stateId);

        $cities = City::where('state_id', $stateId)->get();

        Log::info('Found cities: ' . json_encode($cities));

        return response()->json(['cities' => $cities]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return Inertia::render('Cities/Create', [
            'countries' => $countries,
        ]);
    }

    public function getStatesByCountry(Request $request, $countryId)
    {
        $states = State::where('country_id', $countryId)->orderBy('name')->get();

        return response()->json([
            'states' => $states
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request)
    {
        DB::beginTransaction();

        $city = new City();
        $formData = $request->only($city->getFillable());

        City::create($formData);

        DB::commit();

        return redirect()->route('cities.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        $countries = Country::orderBy('name')->select('id', 'name')->get();
        
        $states = [];
        if ($city->country_id) {
            $states = State::where('country_id', $city->country_id)->orderBy('name')->select('id', 'name', 'country_id')->get();
        }

        return Inertia::render('Cities/Create', [
            'city' => $city,
            'countries' => $countries,
            'states' => $states,
        ]);
    }





    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, City $city)
    {
        DB::beginTransaction();

        $formData = $request->only($city->getFillable());
        $city->update($formData);

        DB::commit();

        return redirect()->route('cities.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $city->delete();
        return Inertia::location(route('cities.index'));
    }
}
