<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'code',
    'description',
    'category',
    'instructor',
    'department',
    'enrolled_students',
    'capacity',
    'units',
])]
class Course extends Model
{
    use HasFactory;

    public const STUDENT_ALERT_LIMIT = 40;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'enrolled_students' => 'integer',
            'capacity' => 'integer',
            'units' => 'integer',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class)->latest();
    }

    public function isAtStudentAlert(): bool
    {
        return $this->enrolled_students >= self::STUDENT_ALERT_LIMIT;
    }

    public function isFull(): bool
    {
        return $this->enrolled_students >= $this->capacity;
    }

    public function remainingSlots(): int
    {
        return max(0, $this->capacity - $this->enrolled_students);
    }

    public function enrollmentStatus(): string
    {
        if ($this->isAtStudentAlert() || $this->isFull()) {
            return 'Full (40 students)';
        }

        if ($this->enrolled_students === 0) {
            return 'No Students';
        }

        if ($this->enrolled_students >= 30) {
            return 'Almost Full';
        }

        return 'Open';
    }

    /**
     * @return list<string>
     */
    public static function categories(): array
    {
        return ['Programming', 'Networking', 'Database', 'Systems', 'Web', 'Security'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toLiveArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'category' => $this->category,
            'instructor' => $this->instructor,
            'department' => $this->department,
            'units' => $this->units,
            'enrolled_students' => $this->enrolled_students,
            'capacity' => $this->capacity,
            'status' => $this->enrollmentStatus(),
            'alert' => $this->isAtStudentAlert(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
