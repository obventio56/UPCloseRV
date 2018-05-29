<?php

namespace Statamic\Addons\BuildRegex;

use Statamic\Extend\Tags;

class BuildRegexTags extends Tags
{
    /**
     * The {{ build_regex }} tag
     *
     * @return string|array
     */
    public function index()
    {
        //
    }

    /**
     * The {{ build_regex:example }} tag
     *
     * @return string|array
     */
    public function generateRegex() {
      $regex = "";
      $exploded = explode('/', parse_url($_GET["uri"])['path']);
      $segments = array_slice($exploded, 1, count($exploded));
      while (count($segments) > 0) {
        $last_segment = array_pop($segments);
        $regex = '(/' . $last_segment . $regex . ')';
        if (count($segments) > 0) {
          $regex .= '?';
        }
      }
      return '~/$|' . $regex . '+~';
    }
 
}
