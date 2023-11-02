<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAddressModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'client_address';

    protected $fillable = [
        'formatted_address',
        'lat',
        'lng',
        'client_id',
        'type_id',
        'type',
    ];

    static public $rules = [
        'formatted_address' => 'required|string|max:250',
        'lat' => 'numeric',
        'lng' => 'numeric',
        'client_id' => 'required|integer',
    ];

    public function client()
    {
        return $this->belongsTo(ClientModel::class, 'client_id');
    }
}
