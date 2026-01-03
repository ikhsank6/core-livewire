<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'from_role_id',
        'to_role_id',
        'message',
        'url',
        'id_reference',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function fromRole()
    {
        return $this->belongsTo(Role::class, 'from_role_id');
    }

    public function toRole()
    {
        return $this->belongsTo(Role::class, 'to_role_id');
    }
}
