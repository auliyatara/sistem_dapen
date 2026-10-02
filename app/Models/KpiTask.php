<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiTask extends Model
{
    protected $fillable = ['title', 'description', 'progress', 'evidence_path', 'evidence_name', 'evaluation', 'attachments', 'links'];
    protected $casts = ['progress' => 'integer', 'attachments' => 'array', 'links' => 'array'];

    public function getAllAttachmentsAttribute(): array
    {
        $files = $this->attachments ?? [];
        if ($this->evidence_path) $files['legacy'] = ['path' => $this->evidence_path, 'name' => $this->evidence_name];
        return $files;
    }

    public function period() { return $this->belongsTo(KpiPeriod::class, 'kpi_period_id'); }
}
