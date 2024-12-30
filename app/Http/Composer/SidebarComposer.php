<?php

namespace App\Http\Composer;

use App\Models\Core\Builder\Table\CustomTable;
use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view)
    {
        $table = CustomTable::all();

        $menu = [
            [
                'icon' => 'pie-chart',
                'name' => 'Upload file',
                'url' => request()->root() . '/mr',
                'permission' => true
            ],
        ];


        $view->with(['data' => $menu]);
    }
}
