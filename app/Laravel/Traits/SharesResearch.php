<?php

namespace App\Laravel\Traits;

use App\Laravel\Models\SharedResearch;

use DB;

trait SharesResearch{
    /**
     * Share research with specified authors.
     *
     * @param
     * @param  array
     * @return void
     */

    public function share($research, array $authors){
        foreach ($authors as $author) {
            if ($author != $research->submitted_to_id) {
                $checkResearch = SharedResearch::where('research_id', $research->id)->where('user_id', $author)->first();

                if (!$checkResearch) {
                    $share = new SharedResearch;
                    $share->research_id = $research->id;
                    $share->user_id = $author;
                    $share->save();
                }
            }
        }
    }
}