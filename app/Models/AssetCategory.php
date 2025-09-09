<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    protected $guarded = [];
    protected $table = "asset_categories";

    public function assets()
{
    return $this->hasMany(Asset::class, 'category_id');
}
}