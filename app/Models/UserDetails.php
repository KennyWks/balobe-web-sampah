<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    protected $primaryKey = 'user_detail_id';
    protected $table = 'user_details'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['user_detail_id'];
    
    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search){
            return $query->where('name', 'ILIKE', $search)->orWhere('no_hp', 'ILIKE', $search);
        });
    }

}
