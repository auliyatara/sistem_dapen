<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\KpiPeriod;
use App\Models\KpiTask;
use App\Services\KpiEvidenceCompressor;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class KpiTaskController extends Controller
{
    public function store(Request $request, KpiPeriod $period)
    {
        Gate::authorize('manage-kpi', $period->role);
        return $this->saveTask($request, $period, new KpiTask());
    }

    public function update(Request $request, KpiTask $task)
    {
        Gate::authorize('manage-kpi', $task->period->role);
        return $this->saveTask($request, $task->period, $task);
    }

    private function saveTask(Request $request, KpiPeriod $period, KpiTask $task)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255', 'description' => 'nullable|string|max:5000',
            'progress' => 'required|integer|between:0,100',
            'evaluation' => 'nullable|string|max:5000',
            'evidence' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:40960',
            'evidences' => 'nullable|array|max:10',
            'evidences.*' => 'file|mimes:pdf,jpg,jpeg,png|max:40960',
            'links' => 'nullable|array|max:20',
            'links.*' => ['nullable', 'string', 'max:2048', 'url', 'regex:/^https?:\/\//i'],
            'remove_attachments' => 'nullable|array',
            'remove_attachments.*' => ['string', \Illuminate\Validation\Rule::in(array_map('strval', array_keys($task->all_attachments)))],
        ]);
        unset($data['evidence'], $data['evidences'], $data['remove_attachments']);
        if ($request->has('links')) $data['links'] = array_values(array_filter($data['links'] ?? []));
        $attachments = $task->attachments ?? [];
        $removedPaths = [];
        foreach ($request->input('remove_attachments', []) as $key) {
            $removedPaths[] = $task->all_attachments[$key]['path'];
            if ($key === 'legacy') { $data['evidence_path'] = null; $data['evidence_name'] = null; }
            else unset($attachments[$key]);
        }
        $oldPath = $task->evidence_path;
        $newPath = null;
        $newPaths = [];
        try {
        $files = $request->file('evidences', []);
        if ($request->hasFile('evidence')) $files['legacy'] = $request->file('evidence');
        foreach ($files as $key => $file) {
            $newPath = $file->store('kpi-evidence', 'local');
            abort_unless($newPath, 500, 'Bukti gagal disimpan.');
            $newPaths[] = $newPath;
            try {
                if ($file->getSize() > KpiEvidenceCompressor::MAX_SIZE) {
                    app(KpiEvidenceCompressor::class)->compress(
                        $file->getPathname(),
                        Storage::disk('local')->path($newPath),
                        $file->getMimeType()
                    );
                }
                clearstatcache(true, Storage::disk('local')->path($newPath));
                if (Storage::disk('local')->size($newPath) > KpiEvidenceCompressor::MAX_SIZE) {
                    throw new \RuntimeException('Bukti masih melebihi 2 MB setelah dikompres. Gunakan file yang lebih kecil.');
                }
            } catch (\Throwable $exception) {
                Storage::disk('local')->delete($newPath);
                throw ValidationException::withMessages(['evidence' => 'Bukti gagal dikompres hingga maksimal 2 MB. Silakan gunakan file yang lebih kecil.']);
            }
            $name = mb_substr($file->getClientOriginalName(), 0, 255);
            if ($key === 'legacy') {
                $data['evidence_path'] = $newPath;
                $data['evidence_name'] = $name;
                if ($oldPath) $removedPaths[] = $oldPath;
            } else {
                $attachments[(string) \Illuminate\Support\Str::uuid()] = ['path' => $newPath, 'name' => $name];
            }
        }
            $data['attachments'] = $attachments;
            $task->fill($data);
            $period->tasks()->save($task);
        } catch (\Throwable $exception) {
            foreach ($newPaths as $path) Storage::disk('local')->delete($path);
            throw $exception;
        }
        foreach ($removedPaths as $path) Storage::disk('local')->delete($path);
        return $this->backToPeriod($period)->with('success', 'Tugas KPI berhasil disimpan.');
    }

    public function destroy(KpiTask $task)
    {
        Gate::authorize('manage-kpi', $task->period->role);
        $period = $task->period;
        $paths = array_column($task->all_attachments, 'path');
        $task->delete();
        foreach ($paths as $path) Storage::disk('local')->delete($path);
        return $this->backToPeriod($period)->with('success', 'Tugas KPI berhasil dihapus.');
    }

    public function evidence(Request $request, KpiTask $task)
    {
        abort_unless($request->user() && $request->user()->role && $task->period->role && (int) $task->period->role->role_status === 1, 403);
        $request->validate(['attachment' => 'nullable|string']);
        if ($request->filled('attachment')) {
            $attachment = $task->all_attachments[$request->query('attachment')] ?? null;
            abort_unless($attachment, 404);
            $task->evidence_path = $attachment['path'];
            $task->evidence_name = $attachment['name'];
        }
        abort_unless($task->evidence_path && Storage::disk('local')->exists($task->evidence_path), 404);
        if ($request->boolean('preview')) {
            $mime = Storage::disk('local')->mimeType($task->evidence_path);
            abort_unless(in_array($mime, ['image/jpeg', 'image/png'], true), 415);
            return response()->file(Storage::disk('local')->path($task->evidence_path), [
                'Content-Type' => $mime, 'X-Content-Type-Options' => 'nosniff', 'Cache-Control' => 'private, no-store',
            ]);
        }
        return Storage::disk('local')->download($task->evidence_path, $task->evidence_name);
    }

    private function backToPeriod(KpiPeriod $period)
    {
        return redirect()->route('kpi.index', ['role_id' => $period->role_id, 'period_id' => $period->id]);
    }
}
