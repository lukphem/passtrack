<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Material;

class MaterialProgress extends Model
{
    protected $fillable = [
        'student_id',
        'material_id',
        'status',
        'progress_percent',
        'first_viewed_at',
        'last_viewed_at',
        'completed_at',
        'time_spent_seconds',
        'scroll_depth',
        'session_count',
        'last_session_started_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function markInProgress()
    {
        $this->update([
            'status' => 'in_progress',
            'last_viewed_at' => now(),
        ]);
    }

    public function updateEngagement($timeSpent, $scrollDepth)
    {
        $this->increment('time_spent_seconds', $timeSpent);

        // store best scroll depth
        if ($scrollDepth > $this->scroll_depth) {
            $this->scroll_depth = $scrollDepth;
        }

        $this->last_viewed_at = now();

        $this->calculateProgress();
        $this->save();
    }

    public function calculateProgress()
    {
        $timeScore = min(($this->time_spent_seconds / 180) * 40, 40);
        $scrollScore = min(($this->scroll_depth / 100) * 40, 40);
        $baseScore = ($this->status === 'completed') ? 20 : 0;

        $this->progress_percent = min(100, $timeScore + $scrollScore + $baseScore);

        if ($this->progress_percent >= 80) {
            $this->markCompleted();
        } else {
            $this->status = 'in_progress';
        }
    }

    public function markCompleted()
    {
        $this->status = 'completed';
        $this->progress_percent = 100;
        $this->completed_at = now();
    }
}
