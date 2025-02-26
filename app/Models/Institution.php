<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $primaryKey = 'institution_id';
    protected $fillable = ['name', 'address', 'representative_id'];
    public function representative()
    {
        return $this->belongsTo(Representative::class, 'representative_id', 'representative_id');
    }
}
