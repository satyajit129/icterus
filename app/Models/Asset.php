<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $guarded = [];
    protected $table = "assets";

    public function category()
{
    return $this->belongsTo(AssetCategory::class, 'category_id');
}
}