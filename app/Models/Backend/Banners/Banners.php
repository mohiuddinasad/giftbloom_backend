<?php

namespace App\Models\Backend\Banners;

use Illuminate\Database\Eloquent\Model;

class Banners extends Model
{
    protected $fillable = ['title', 'image', 'video'];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }

    public function getVideoUrlAttribute()
    {
        return $this->video ? asset($this->video) : null;
    }
}
