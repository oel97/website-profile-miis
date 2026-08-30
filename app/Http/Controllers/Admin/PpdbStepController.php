<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PpdbStepRequest;
use App\Models\PpdbPeriod;
use App\Models\PpdbStep;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbStepController extends Controller
{
    public function index(Request $request): View
    {
        $periods = $this->periods();
        $selectedPeriodId = $request->integer('ppdb_period_id') ?: null;

        $steps = PpdbStep::query()
            ->with('period')
            ->when($selectedPeriodId, fn ($query) => $query->where('ppdb_period_id', $selectedPeriodId))
            ->orderBy('ppdb_period_id')
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.ppdb-steps.index', compact('periods', 'steps', 'selectedPeriodId'));
    }

    public function create(): View
    {
        $periods = $this->periods();

        return view('admin.ppdb-steps.create', compact('periods'));
    }

    public function store(PpdbStepRequest $request): RedirectResponse
    {
        $step = PpdbStep::create($request->validated());

        return redirect()
            ->route('admin.ppdb-steps.index', ['ppdb_period_id' => $step->ppdb_period_id])
            ->with('success', 'Langkah pendaftaran PPDB berhasil ditambahkan.');
    }

    public function edit(PpdbStep $ppdb_step): View
    {
        $periods = $this->periods();

        return view('admin.ppdb-steps.edit', compact('ppdb_step', 'periods'));
    }

    public function update(PpdbStepRequest $request, PpdbStep $ppdb_step): RedirectResponse
    {
        $ppdb_step->update($request->validated());

        return redirect()
            ->route('admin.ppdb-steps.index', ['ppdb_period_id' => $ppdb_step->ppdb_period_id])
            ->with('success', 'Langkah pendaftaran PPDB berhasil diperbarui.');
    }

    public function destroy(PpdbStep $ppdb_step): RedirectResponse
    {
        $periodId = $ppdb_step->ppdb_period_id;
        $ppdb_step->delete();

        return redirect()
            ->route('admin.ppdb-steps.index', ['ppdb_period_id' => $periodId])
            ->with('success', 'Langkah pendaftaran PPDB berhasil dihapus.');
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
