<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientStoreRequest;
use App\Http\Requests\PatientUpdateRequest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $patients = Patient::query()
            ->select(['id', 'name', 'address'])
            ->get();

        return view('pages.masters.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.masters.patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientStoreRequest $request): RedirectResponse
    {
        Patient::query()
            ->create($request->validated());

        return to_route('patients.index')->with('success', 'Patient has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $patient = Patient::query()->findOrFail($id);

        return view('pages.masters.patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientUpdateRequest $request, string $id): RedirectResponse
    {
        $patient = Patient::query()->findOrFail($id);

        $patient->update($request->validated());

        return to_route('patients.index')->with('success', 'Patient has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $patient = Patient::query()->findOrFail($id);

        $patient->delete();

        return to_route('patients.index')->with('success', 'Patient has been deleted.');
    }
}
