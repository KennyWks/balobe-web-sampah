<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    protected $primaryKey = 'role_id';
    protected $table = 'roles'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['role_id'];
    
    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search){
            return $query->where('role', 'ILIKE', $search)->orWhere('deskripsi', 'ILIKE', $search);
        });
    }

}
