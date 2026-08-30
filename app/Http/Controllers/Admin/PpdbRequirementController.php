<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PpdbRequirementRequest;
use App\Models\PpdbPeriod;
use App\Models\PpdbRequirement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbRequirementController extends Controller
{
    public function index(Request $request): View
    {
        $periods = $this->periods();
        $selectedPeriodId = $request->integer('ppdb_period_id') ?: null;

        $requirements = PpdbRequirement::query()
            ->with('period')
            ->when($selectedPeriodId, fn ($query) => $query->where('ppdb_period_id', $selectedPeriodId))
            ->orderBy('ppdb_period_id')
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.ppdb-requirements.index', compact('periods', 'requirements', 'selectedPeriodId'));
    }

    public function create(): View
    {
        $periods = $this->periods();

        return view('admin.ppdb-requirements.create', compact('periods'));
    }

    public function store(PpdbRequirementRequest $request): RedirectResponse
    {
        $requirement = PpdbRequirement::create($request->validated());

        return redirect()
            ->route('admin.ppdb-requirements.index', ['ppdb_period_id' => $requirement->ppdb_period_id])
            ->with('success', 'Persyaratan PPDB berhasil ditambahkan.');
    }

    public function edit(PpdbRequirement $ppdb_requirement): View
    {
        $periods = $this->periods();

        return view('admin.ppdb-requirements.edit', compact('ppdb_requirement', 'periods'));
    }

    public function update(PpdbRequirementRequest $request, PpdbRequirement $ppdb_requirement): RedirectResponse
    {
        $ppdb_requirement->update($request->validated());

        return redirect()
            ->route('admin.ppdb-requirements.index', ['ppdb_period_id' => $ppdb_requirement->ppdb_period_id])
            ->with('success', 'Persyaratan PPDB berhasil diperbarui.');
    }

    public function destroy(PpdbRequirement $ppdb_requirement): RedirectResponse
    {
        $periodId = $ppdb_requirement->ppdb_period_id;
        $ppdb_requirement->delete();

        return redirect()
            ->route('admin.ppdb-requirements.index', ['ppdb_period_id' => $periodId])
            ->with('success', 'Persyaratan PPDB berhasil dihapus.');
    }

    /**
     * Get periods for the period selector.
     *
     * @return Collection<int, PpdbPeriod>
     */
    protected function periods(): Collection
    {
        return PpdbPeriod::query()
            ->orderByDesc('is_active')
            ->orderByDesc('registration_start_at')
            ->get();
    }
}
