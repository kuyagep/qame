<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Yajra\Address\Entities\Barangay;
use Yajra\Address\Entities\City;
use Yajra\Address\Entities\Province;
use Yajra\Address\Entities\Region;

class AddressController extends Controller
{
    /**
     * Fetch all regions
     */
    public function getRegions()
    {
        $regions = Region::orderBy('name', 'asc')->get(['region_id', 'name']);
        return response()->json($regions);
    }

    /**
     * Fetch provinces belonging to a specific region
     */
    public function getProvinces(string $regionId)
    {
        $provinces = Province::where('region_id', $regionId)
            ->orderBy('name', 'asc')
            ->get(['province_id', 'name']);

        return response()->json($provinces);
    }

    /**
     * Fetch cities/municipalities belonging to a specific province
     */
    public function getCities(string $provinceId)
    {

        $cities = City::where('province_id', $provinceId)
            ->orderBy('name', 'asc')
            ->get(['city_id', 'name']);

        return response()->json($cities);
    }

    /**
     * Fetch barangays belonging to a specific city
     */
    public function getBarangays(string $cityId)
    {
        $barangays = Barangay::where('city_id', $cityId)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($barangays);
    }
}
