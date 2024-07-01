<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;
    protected $table= 'posts';
    protected $fillable=['cat_id','title','decription','image','views'];

    public function posts(){
        return $this->hasMany(Category::class);
    }
}
