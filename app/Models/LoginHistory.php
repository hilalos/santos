<?php

namespace App\Models;

use App\Support\DeviceParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'location', 'created_at'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A best-effort, dependency-free device label parsed from the user agent.
     */
    public function device(): string
    {
        return DeviceParser::label($this->user_agent);
    }
}
