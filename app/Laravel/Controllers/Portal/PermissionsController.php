<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\Permission;

use App\Laravel\Requests\PageRequest;

use Carbon;

class PermissionsController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Permissions";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Permission";

        $this->data['keyword'] = strtolower($request->get('keyword'));

        $first_record = Permission::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");

        $this->data['record'] = Permission::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                return $query
                    ->whereRaw("LOWER(name) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereRaw("LOWER(description) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereRaw("LOWER(module_name) LIKE '%{$this->data['keyword']}%'");
            }
        })
        ->where(function ($query) {
            return $query->where(function ($q) {
                if(strlen($this->data['start_date']) > 0) {
                    return $q->whereDate('created_at', '>=', Carbon::parse($this->data['start_date'])->format("Y-m-d"));
                }
            })->where(function ($q) {
                if(strlen($this->data['end_date']) > 0) {
                    return $q->whereDate('created_at', '<=', Carbon::parse($this->data['end_date'])->format("Y-m-d"));
                }
            });
        })
        ->orderBy('created_at','DESC')
        ->paginate($this->per_page);

        return view('portal.cms.permissions.index', $this->data);
    }
}