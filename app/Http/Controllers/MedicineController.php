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
        $this->authorize('MasterMedicine.read');

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
        $this->authorize('MasterMedicine.create');

        return view('pages.masters.medicines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineStoreRequest $request): RedirectResponse
    {
        $this->authorize('MasterMedicine.create');

        Medicine::query()
            ->create($request->validated());

        return to_route('medicines.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $this->authorize('MasterMedicine.update');

        $medicine = Medicine::query()->findOrFail($id);

        return view('pages.masters.medicines.edit', compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MedicineUpdateRequest $request, int $id): RedirectResponse
    {
        $this->authorize('MasterMedicine.update');

        $medicine = Medicine::query()->findOrFail($id);

        $medicine->update($request->validated());

        return to_route('medicines.index')->with('success', 'Obat berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->authorize('MasterMedicine.delete');

        $medicine = Medicine::query()->findOrFail($id);

        $medicine->delete();

        return to_route('medicines.index')->with('success', 'Obat berhasil dihapus.');
    }
}
