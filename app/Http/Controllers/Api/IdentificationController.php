<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Filters\App\Monitaz\Identification\IdentificationFilter;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\Identification\Identification;
use App\Models\Monitaz\Identification\IdentificationDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ScanGroup as ScanGroupJob;
use App\Http\Requests\Backend\IdentificationCreateRequest;
use App\Http\Requests\Backend\IdentificationUpdateRequest;
use App\Services\Backend\IdentificationService;
use App\Http\Controllers\Core\BasicHelper;
use Illuminate\Support\Facades\DB;
use LDAP\Result;

class IdentificationController extends Controller
{
    private $basicHelper;
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
        $identification = new Identification();
        $identification->name = str_replace( array('\\', '/', ':', '*', '"', '<', '>'), '', $identificationCreateRequest->get('name'));
        $identification->phone = null;
        if ($identificationCreateRequest->get('phone')) {
            $phone = $this->basicHelper->phoneFilter($identificationCreateRequest->get('phone'));
            if ($phone["code"] == 11){
                return $this->basicHelper->response_form(400, 0, "Phone length must be 10");
            } elseif ($phone["code"] == 12){
                return $this->basicHelper->response_form(400, 0, "Invalid first letter in phone");
            } elseif ($phone["code"] == 99){
                return $this->basicHelper->response_form(400, 0, "Something wrong. Please try again");
            }
            $data['phone'] = $phone["phone"];
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
            return $this->basicHelper->response_form(400, 0, "Missing required parameter");
        }
        $identification->user_created = auth()->user()->id;
        try {
            $identification->save();
            return $this->basicHelper->response_form(200, 1);
        } catch (\Exception $e) {
            $this->functionLog("store", "", $e->getMessage());
            return $this->basicHelper->response_form(500, 0, "Something wrong. Please try again");
        }
    }

    public function update(IdentificationUpdateRequest $identificationUpdateRequest, $id)
    {
        $row = Identification::find($id);
        if (!$row){
            return $this->basicHelper->response_form(400, 0, "Data not found");
        }
        $user_id = auth()->user()->id;
        $user_created = $row->user_created;

        if ($user_id != $user_created){
            return $this->basicHelper->response_form(400, 0, "Config not belong to user");
        }

        $service =  new IdentificationService();
        $data = $identificationUpdateRequest->all();
        $data['name'] = str_replace( array('\\', '/', ':', '*', '"', '<', '>'), '', $identificationUpdateRequest->get('name'));
        $data['phone'] = null;
        if ($identificationUpdateRequest->get('phone')) {
            $phone = $this->basicHelper->phoneFilter($identificationUpdateRequest->get('phone'));
            if ($phone["code"] == 11){
                return $this->basicHelper->response_form(400, 0, "Phone length must be 10");
            } elseif ($phone["code"] == 12){
                return $this->basicHelper->response_form(400, 0, "Invalid first letter in phone");
            } elseif ($phone["code"] == 99){
                return $this->basicHelper->response_form(400, 0, "Something wrong. Please try again");
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
            return $this->basicHelper->response_form(400, 0, "Missing required parameter");
        }

        if ($service->update($id, $data)) {
            return $this->basicHelper->response_form(200, 1);
        } else {
            $this->functionLog("update", "update failed", "", $data);
            return $this->basicHelper->response_form(500, 0, "Something wrong. Please try again");
        }
    }

    public function delete(Request $request, $id){
        $identification = new Identification();
        $row = $identification::find($id);
        if (!$row){
            return $this->basicHelper->response_form(400, 0, "Data not found");
        }
        $user_id = auth()->user()->id;
        $user_created = $row->user_created;

        if ($user_id != $user_created){
            return $this->basicHelper->response_form(400, 0, "Config not belong to user");
        }

        $identificationDetail = new IdentificationDetail();
        try {
            DB::beginTransaction();
            $row->delete();
            $identificationDetail::where("config_id", $row->id)->delete();
            DB::commit();
            return $this->basicHelper->response_form(200, 1);
        } catch (\Exception $e) {
            $this->functionLog("delete", "", $e->getMessage());
            DB::rollBack();
            return $this->basicHelper->response_form(500, 0, "Something wrong. Please try again");
        }
    }

    public function exportExcel(Request $request)
    {

    }
}
