<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'name',
    ];

    static public $rules = [
        'name' => 'required|string|max:250',
    ];

    public function addresses()
    {
        return $this->hasMany(ClientAddressModel::class, 'client_id');
    }
}
