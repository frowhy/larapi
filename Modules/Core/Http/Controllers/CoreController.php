<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Module;
use Modules\Core\Services\CourseService;
use Modules\Core\Supports\Response;

class CoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Modules\Core\Services\CourseService $service
     * @return \Modules\Core\Supports\Response
     */
    public function index(CourseService $service)
    {
        dd($service);
        $name = Module::find('core')->name;
        $requirements = collect(Module::findRequirements('core'));
        $requirements = $requirements->map(function ($item) {
            return $item->name;
        });
        $routes = get_routes('core');

        return Response::success(compact('name', 'requirements', 'routes'));
    }
}
