<?php


namespace mywishlist\model;


use Illuminate\Database\Eloquent\Model;

class Images extends Model {
    public $timestamps = false;
    protected $table = "images";
    protected $primaryKey = "id_image";

    public function Item() {
        return $this->belongsTo('mywishlist\model\Item', 'id_item');
    }


}