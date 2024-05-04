<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Filters\App\Monitaz\Identification\IdentificationFilter;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\Identification\Identification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ScanGroup as ScanGroupJob;
use App\Http\Requests\Backend\IdentificationCreateRequest;
use App\Http\Requests\Backend\IdentificationUpdateRequest;
use App\Services\Backend\IdentificationService;
use App\Http\Controllers\Core\BasicHelper;
use LDAP\Result;

class IdentificationController extends Controller
{
    public function __construct(IdentificationFilter $filter)
    {
        $this->filter = $filter;
        $this->basicHelper = new BasicHelper();
    }

    private function functionLog($functionName, $logMessage="", $logError = "", $logData=[]){
        \Log::info(["source"=>"API IdentificationController", "function"=>$functionName, "message"=>$logMessage, "error"=>$logError, "data"=>$logData]);
    }

    public function index()
    {
        $data = Identification::filters($this->filter)
            ->with('identificationDetail')->paginate(10);

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $data
        ], 200);
    }

    public function store(IdentificationCreateRequest $identificationCreateRequest)
    {
        $result = [];
        $identification = new Identification();
        $identification->name = str_replace( array('\\', '/', ':', '*', '"', '<', '>'), '', $identificationCreateRequest->get('name'));
        $identification->phone = null;
        if ($identificationCreateRequest->get('phone')) {
            $identification->phone = preg_replace('/\s+/', '', $identificationCreateRequest->get('phone'));
        }
        $identification->facebook_uid = null;
        if ($identificationCreateRequest->get('facebook_uid')) {
            $identification->facebook_uid = preg_replace('/\s+/', '', $identificationCreateRequest->get('facebook_uid'));
        }
        $identification->tiktok_unique = null;
        if ($identificationCreateRequest->get('tiktok_unique')) {
            $identification->tiktok_unique = $identificationCreateRequest->get('tiktok_unique');
        }
        $identification->status = 0;
        
        if (!$identification->phone && !$identification->facebook_uid && !$identification->tiktok_unique) {
            $result["code"] = 0;
            $result["message"] = "Missing required parameter";
            return $result;
        }
        $identification->user_created = auth()->user()->id;
        try {
            $identification->save();
            $result['code'] = 1;
        } catch (\Exception $e) {
            $result['code'] = 0;
            $result['message'] = 'Something wrong. Please try again';
            $this->functionLog("store", "", $e->getMessage());
        }

        return $result;
    }

    public function update(IdentificationUpdateRequest $identificationUpdateRequest, $id)
    {
        $result = [];
        $row = Identification::find($id);
        if (!$row){
            $result['code'] = 0;
            $result["message"] = "Data not found";
            return $result;
        }

        $user_id = auth()->user()->id;
        $user_created = $row->user_created;

        if ($user_id != $user_created){
            $result["code"] = 0;
            $result["message"] = "Config not belong to user";
        }

        $service =  new IdentificationService();
        $data = $identificationUpdateRequest->all();
        $data['name'] = str_replace( array('\\', '/', ':', '*', '"', '<', '>'), '', $identificationUpdateRequest->get('name'));
        $data['phone'] = null;
        if ($identificationUpdateRequest->get('phone')) {
            $phone = $this->basicHelper->phoneFilter($identificationUpdateRequest->get('phone'));
            if ($phone["code"] == 11){
                $result["code"] = 0;
                $result["message"] = "Phone length must be 10";
                return $result;
            } elseif ($phone["code"] == 12){
                $result["code"] = 0;
                $result["message"] = "Invalid first letter in phone";
                return $result;
            } elseif ($phone["code"] == 99){
                $result["code"] = 0;
                $result["message"] = "Something wrong. Please try again";
                return $result;
            }
            $data['phone'] = $phone["phone"];
        }
        $data['facebook_uid'] = null;
        if ($identificationUpdateRequest->get('facebook_uid')) {
            $data['facebook_uid'] = preg_replace('/\s+/', '', $identificationUpdateRequest->get('facebook_uid'));
        }
        $data['tiktok_unique'] = null;
        if ($identificationUpdateRequest->get('tiktok_unique')) {
            $data['tiktok_unique'] = $identificationUpdateRequest->get('tiktok_unique');
        }
        $data['status'] = 0;

        if (!$data['phone'] && !$data['facebook_uid'] && !$data['tiktok_unique']) {
            $result['code'] = 0;
            $result['message'] = 'Missing required parameter';
            return $result;
        }

        if ($service->update($id, $data)) {
            $result['code'] = 1;
        } else {
            $result['code'] = 0;
            $result['message'] = 'Something wrong. Please try again';
            $this->functionLog("update", "update failed", "", $data);
        }
        return $result;
    }

    public function exportExcel(Request $request)
    {

    }
}
