<?php

namespace Statamic\Addons\BuildRegex;

use Statamic\Extend\Controller;

class BuildRegexController extends Controller
{
    /**
     * Maps to your route definition in routes.yaml
     *
     * @return mixed
     */
    public function index()
    {
        return $this->view('index');
    }
}
