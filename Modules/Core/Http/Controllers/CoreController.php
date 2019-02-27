<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Core\Supports\Response;

class CoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Modules\Core\Supports\Response
     */
    public function index()
    {
        $name = \Module::find('core')->name;
        $requirements = \Module::findRequirements('core');

        return Response::success(compact('name', 'requirements'));
    }
}
