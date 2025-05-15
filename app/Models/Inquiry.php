<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    use HasFactory;

    protected $guarded = ['user', 'respondedBy'];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function markAsInProgress(): void
    {
        $this->update(['status' => 'in_progress']);
    }

    public function resolve(string $response, User $admin): void
    {
        $this->update([
            'status' => 'resolved',
            'admin_response' => $response,
            'responded_by_id' => $admin->id,
            'responded_at' => now(),
        ]);
    }

    public function reject(string $response, User $admin): void
    {
        $this->update([
            'status' => 'rejected',
            'admin_response' => $response,
            'responded_by_id' => $admin->id,
            'responded_at' => now(),
        ]);
    }
}
