<?php

namespace App\Laravel\Traits;

use App\Laravel\Models\{Research,ResearchType};

use DB;

trait VerifyResearch{
    public function check_research_title($request){
        $exists = Research::where(DB::raw('LOWER(title)'), strtolower($request->input('title')))
            ->where('chapter', $request->input('chapter'))
            ->where('version', $request->input('version'))
            ->exists();

        if($exists){
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Same title with the same chapter and version already exists.");
            return true;
        }

        return false;
    }

    public function check_chapter_limit($request){
        $research_type = ResearchType::find($request->input('research_type'));

        if($request->input('chapter') > $research_type->max){
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg',  "You already exceed this research type's maximum chapter. This should be a maximum of {$research_type->max}.");
            return true;
        }

        return false;
    }

    public function check_chapter_version($request) {
        $value_chapter = Research::where(DB::raw('LOWER(title)'), strtolower($request->input('title')))->max('chapter');
        $value_version = Research::where(DB::raw('LOWER(title)'), strtolower($request->input('title')))->max('version');
    
        $chapter = $request->input('chapter');
        $version = $request->input('version');
    
        if ($chapter < $value_chapter || $version < $value_version) {
            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', "Chapters and versions must be submitted in order. You cannot submit a chapter or version less than the existing ones.");
            return true;
        }

        return false;
    }
}