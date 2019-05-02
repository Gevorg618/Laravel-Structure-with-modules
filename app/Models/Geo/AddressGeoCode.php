<?php

namespace App\Models\Geo;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressGeoCode extends BaseModel
{
    protected $table = 'address_geo_code';
    protected $fillable = ['address', 'city', 'state', 'zip', 'country', 'lat', 'long','hash', 'service'];

    public function scopeByHash($query, $hash)
    {
      $query->where('hash', $hash);
    }

    public static function cachedAddress($hash)
    {
      $row = static::select(['address', 'city', 'state', 'zip', 'country', 'lat', 'long', 'service'])->byHash($hash)->first();

      if($row) {
        return $row->toArray();
      }

      return false;
    }

    public function getFullAddressAttribute()
    {
      return \App\Helpers\Address::getFullAddress(
        $this->address, '', $this->city, $this->state, $this->zip
      ); 
    }
}
