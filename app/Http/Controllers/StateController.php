<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateRequest;
use App\Models\State;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:states.index')->only('index');
        $this->middleware('checkPermission:states.create')->only('create', 'store');
        $this->middleware('checkPermission:states.show')->only('show');
        $this->middleware('checkPermission:states.edit')->only('edit', 'update');
        $this->middleware('checkPermission:states.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $states = State::with('country')->orderBy('created_at', 'desc')->paginate(100);

    //     return Inertia::render('States/Index', [
    //         'states' => $states->map(function ($state) {
    //             return [
    //                 'id' => $state->id,
    //                 'name' => $state->name,
    //                 'abrv' => $state->abrv,
    //                 'country' => [
    //                     'id' => $state->country->id,
    //                     'name' => $state->country->name,
    //                     'abrv' => $state->country->abrv,
    //                 ],
    //             ];
    //         }),
    //     ]);
    // }
    public function index()
    {
        $states = State::with('country')
            ->orderBy('created_at', 'desc')
            ->paginate(100)
            ->through(function ($state) {
                return [
                    'id' => $state->id,
                    'name' => $state->name,
                    'abrv' => $state->abrv,
                    'country' => [
                        'id' => $state->country->id,
                        'name' => $state->country->name,
                        'abrv' => $state->country->abrv,
                    ],
                ];
            });
        return Inertia::render('States/Index', [
            'states' => $states,
        ]);
    }
    
    public function getStatesByCountry($countryId)
    {
        $states = State::where('country_id', $countryId)->orderBy('name')->get();

        return response()->json([
            'states' => $states->map(function ($state) {
                return [
                    'id' => $state->id,
                    'name' => $state->name,
                    'country_id' => $state->country_id,
                ];
            }),
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('States/Create', [
            'countries' => $countries,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StateRequest $request)
    {
        DB::beginTransaction();

        $state = new State();
        $formData = $request->only($state->getFillable());

        State::create($formData);

        DB::commit();

        return redirect()->route('states.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(State $state)
    {
        $countries = Country::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('States/Create', [
            'state' => $state->only(['id', 'name', 'abrv', 'country_id']),
            'countries' => $countries,
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(StateRequest $request, State $state)
    {
        DB::beginTransaction();

        $formData = $request->only($state->getFillable());
        $state->update($formData);

        DB::commit();

        return redirect()->route('states.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        $state->delete();
        return Inertia::location(route('states.index'));
    }
}
