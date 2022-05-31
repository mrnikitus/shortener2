<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'addresses';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['name', 'url', 'slug', 'not_in_use'];

    /**
     * Какому пользователю принадлежит адрес
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
