<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function generateSlug($model, string $text){

        $plainSlug = \Str::slug($text);
        $sameSlugCount =  $model::where('slug', $plainSlug)->count();

        $slug = $plainSlug;
        if($sameSlugCount > 0){
            $slug = $plainSlug.'-'.($sameSlugCount);
            while($model::where('slug', $slug)->count() > 0){
                $slug = $plainSlug.'-'.(++$sameSlugCount);
            }
        }
        return $slug;

    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);
        // dd(Paginator::resolveCurrentPath());
        $options = (empty($options))? ['path' => Paginator::resolveCurrentPath()] : $options;

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
