<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\Yearlevel;

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\YearlevelRequest;

use Carbon,DB;

class YearlevelsController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Yearlevels";
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Yearlevel";

        $this->data['keyword'] = strtolower($request->get('keyword'));

        $first_record = Yearlevel::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");

        $this->data['record'] = Yearlevel::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                $query->whereRaw("LOWER(yearlevel_name) LIKE '%{$this->data['keyword']}%'");
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

        return view('portal.cms.yearlevels.index', $this->data);
    }

    public function create(PageRequest $request){
        $this->data['page_title'] .= " - Create Yearlevel";

        return view('portal.cms.yearlevels.create', $this->data);
    }

    public function store(YearlevelRequest $request){
        DB::beginTransaction();
        try {
            $yearlevel = new Yearlevel;
            $yearlevel->yearlevel_name = $request->input('yearlevel_name');
            $yearlevel->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "New yearlevel has been created.");
            return redirect()->route('portal.cms.yearlevels.index');
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to create new yearlevel.");
        return redirect()->back();
    }

    public function edit(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Update Yearlevel";
        $this->data['yearlevel'] = Yearlevel::find($id);

        if(!$this->data['yearlevel']){
			session()->flash('notification-status', "failed");
			session()->flash('notification-msg', "Record not found.");
			return redirect()->route('portal.cms.yearlevels.index');
		}

        return view('portal.cms.yearlevels.edit', $this->data);
    }

    public function update(YearlevelRequest $request,$id = null){
        $yearlevel = Yearlevel::find($id);

        if(!$yearlevel){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.yearlevels.index');
        }

        DB::beginTransaction();
        try{
            $yearlevel->yearlevel_name = $request->input('yearlevel_name');
            $yearlevel->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Yearlevel has been updated.");
            return redirect()->route('portal.cms.yearlevels.index');
        }catch(\Exception $e){
            DB::rollback();

            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to update yearlevel.");
        return redirect()->back();
    }

    public function destroy(PageRequest $request,$id = null){
        $yearlevel = Yearlevel::find($id);

        if(!$yearlevel){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.yearlevels.index');
        }

        if($yearlevel->delete()){
            session()->flash('notification-status', 'success');
            session()->flash('notification-msg', "Yearlevel has been deleted.");
            return redirect()->back();
        }
    }
}