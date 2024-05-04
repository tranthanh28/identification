<?php

namespace App\Services\Backend;

use App\Models\Monitaz\Identification\Identification;
use App\Services\Core\BaseService;

class IdentificationService extends BaseService
{
    protected $per_page = 25;

    public function getAllIdentification()
    {
        // return Identification::orderByDesc('id')->paginate($this->per_page);
        // return Identification::with('identificationDetails')->orderByDesc('identifications.id')->paginate($this->per_page);
        return Identification::with('identificationDetail')->orderByDesc('identifications.id')->paginate($this->per_page);
    }

    public function getServiceBrand()
    {
        return ServiceBrand::where('status', 0)->pluck('name', 'id')->toArray();
    }

    public function getServiceBrandByBrandID($brand_id)
    {
        if (!$brand_id) $brand_id = 1;
        return ServiceBrand::whereRaw("(cid = $brand_id OR sid = $brand_id OR pid = $brand_id) AND status = 0 OR id = $brand_id")
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
//        var_dump($object);
    }

    public function getIdentificationByFilter($name, $status)
    {
        $filterModel = Identification::query();
        if ($name) $filterModel->where('name', 'like', '%' . $name . '%');

        if ($status != '') $filterModel->where('status', '=', $status);
        return $filterModel->orderByDesc('id')->paginate($this->per_page);
    }

    public function getObjectIdByBrand($id)
    {
        return Identification::where('id', $id)->select('brand_id')->first();
    }

    public function getOneIdentification($id)
    {
        return Identification::findOrFail($id);
    }

    public function update($id, $data)
    {
        try {
            Identification::findOrFail($id)->update($data);
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function delete($id)
    {
        try {
            // $identification = Identification::find($id);
            // $identification->delete();
            Identification::findOrFail($id)->delete();
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
