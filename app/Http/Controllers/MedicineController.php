<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicineStoreRequest;
use App\Http\Requests\MedicineUpdateRequest;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $medicines = Medicine::query()
            ->select(['id', 'name', 'manufacture'])
            ->get();

        return view('pages.masters.medicines.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.masters.medicines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineStoreRequest $request): RedirectResponse
    {
        Medicine::query()
            ->create($request->validated());

        return to_route('medicines.index')->with('success', 'Medicine has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $medicine = Medicine::query()->findOrFail($id);

        return view('pages.masters.medicines.edit', compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MedicineUpdateRequest $request, int $id): RedirectResponse
    {
        $medicine = Medicine::query()->findOrFail($id);

        $medicine->update($request->validated());

        return to_route('medicines.index')->with('success', 'Medicine has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $medicine = Medicine::query()->findOrFail($id);

        $medicine->delete();

        return to_route('medicines.index')->with('success', 'Medicine has been deleted.');
    }
}
