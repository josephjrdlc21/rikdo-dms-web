<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Models\{Page,AuditTrail};

use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Portal\PagesRequest;

use Carbon,DB;

class PagesController extends Controller{
    protected $data;

    public function __construct(){
        parent::__construct();
        array_merge($this->data?:[], parent::get_data());
        $this->data['page_title'] .= " - Pages";
        $this->data['types'] = ['' => "Select Page", 'about' => "About", 'contact' => "Contact"];
        $this->per_page = env("DEFAULT_PER_PAGE", 10);
    }

    public function index(PageRequest $request){
        $this->data['page_title'] .= " - List of Page";

        $this->data['keyword'] = strtolower($request->get('keyword'));

        $first_record = Page::orderBy('created_at', 'ASC')->first();
        $start_date = $request->get('start_date', now()->startOfMonth());
        if ($first_record) {
            $start_date = $request->get('start_date', $first_record->created_at->format("Y-m-d"));
        }

        $this->data['start_date'] = Carbon::parse($start_date)->format("Y-m-d");
        $this->data['end_date'] = Carbon::parse($request->get('end_date', now()))->format("Y-m-d");

        $this->data['record'] = Page::where(function ($query) {
            if (strlen($this->data['keyword']) > 0) {
                return $query
                    ->whereRaw("LOWER(type) LIKE '%{$this->data['keyword']}%'")
                    ->orWhereRaw("LOWER(title) LIKE '%{$this->data['keyword']}%'");
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

        return view('portal.cms.pages.index', $this->data);
    }

    public function create(PageRequest $request){
        $this->data['page_title'] .= " - Create Page";

        return view('portal.cms.pages.create', $this->data);
    }

    public function store(PagesRequest $request){
        DB::beginTransaction();
        try{
            $page = new Page;
            $page->user_id = $this->data['auth']->id;
            $page->type = $request->input('type');
            $page->title = $request->input('title');
            $page->content = $request->input('content');
            $page->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "CREATE_PAGE";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has created a new page.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "New page has been created.");
            return redirect()->route('portal.cms.pages.index');
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to create new page.");
        return redirect()->back();
    }

    public function edit(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Edit Page";
        $this->data['page'] = Page::find($id);

        if(!$this->data['page']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.pages.index');
        }

        return view('portal.cms.pages.edit', $this->data);
    }

    public function update(PagesRequest $request,$id = null){
        $page = Page::find($id);

        if(!$page){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.pages.index');
        }
        
        DB::beginTransaction();
        try{
            $page->user_id = $this->data['auth']->id;
            $page->title = $request->input('title');
            $page->content = $request->input('content');
            $page->save();

            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "UPDATE_PAGE";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has updated a page.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            DB::commit();

            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Page has been updated.");
            return redirect()->route('portal.cms.pages.index');
        }catch(\Exception $e){
            DB::rollback();
            
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Server Error: Code #{$e->getLine()}");
            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Unable to update page.");
        return redirect()->back();
    }

    public function show(PageRequest $request,$id = null){
        $this->data['page_title'] .= " - Show Page";
        $this->data['page'] = Page::find($id);

        if(!$this->data['page']){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.pages.index');
        }

        return view('portal.cms.pages.show', $this->data);
    }

    public function destroy(PageRequest $request,$id = null){
        $page = Page::find($id);

        if(!$page){
            session()->flash('notification-status', "failed");
            session()->flash('notification-msg', "Record not found.");
            return redirect()->route('portal.cms.pages.index');
        }

        if($page->delete()){
            $audit_trail = new AuditTrail;
            $audit_trail->user_id = $this->data['auth']->id;
            $audit_trail->process = "DELETE_PAGE";
            $audit_trail->ip = $this->data['ip'];
            $audit_trail->remarks = "{$this->data['auth']->name} has deleted a page.";
            $audit_trail->type = "USER_ACTION";
            $audit_trail->save();

            session()->flash('notification-status', 'success');
            session()->flash('notification-msg', "Page has been deleted.");
            return redirect()->back();
        }
    }
}