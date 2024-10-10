<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\ResearchType;

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\ResearchTypeRequest;

use Carbon,DB;

class ResearchTypesController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Research Types";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Research Types";

        $this->data['keyword'] = strtolower($request->get('keyword'));

        $first_record = ResearchType::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");

        $this->data['record'] = ResearchType::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(type) LIKE '%{$this->data['keyword']}%'");
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

        return view('portal.cms.research-types.index', $this->data);
    }

    public function create(PageRequest $request){
        $this->data['page_title'] .= " - Create Research Type";

        return view('portal.cms.research-types.create', $this->data);
    }

    public function store(ResearchTypeRequest $request){
        DB::beginTransaction();
        try {
            $research_type = new ResearchType;
            $research_type->type = $request->input('type');
            $research_type->max = $request->input('max_chapter');
            $research_type->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "New research type has been created.");
            return redirect()->route('portal.cms.research_types.index');
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to create new research type.");
        return redirect()->back();
    }

    public function edit(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Update Research Type";
        $this->data['research_type'] = ResearchType::find($id);

        if(!$this->data['research_type']){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.cms.research_types.index');
		}

        return view('portal.cms.research-types.edit', $this->data);
    }

    public function update(ResearchTypeRequest $request,$id = null){
        $research_type = ResearchType::find($id);

        if(!$research_type){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.research_types.index');
        }

        DB::beginTransaction();
        try{
            $research_type->type = $request->input('type');
            $research_type->max = $request->input('max_chapter');
            $research_type->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Research type has been updated.");
            return redirect()->route('portal.cms.research_types.index');
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to update research type.");
        return redirect()->back();
    }

    public function destroy(PageRequest $request,$id = null){
        $research_type = ResearchType::find($id);

        if(!$research_type){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.research_types.index');
        }

        if($research_type->delete()){
            session()->flash('notification-status', 'success');
            session()->flash('notification-msg', "Research type has been deleted.");
            return redirect()->back();
        }
    }
}