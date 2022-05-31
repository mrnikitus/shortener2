<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'statistics';
    protected $primaryKey = 'id';
    protected $fillable = ['address_id', 'ip'];

}
