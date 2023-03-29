<?php

namespace App\View\Components;

use Illuminate\View\Component;

use Illuminate\Support\Arr;
use Illuminate\Contracts\View\View;

class NavLink extends Component
{
    public string $href;
    public $active;

    public function __construct($href, $active = null)
    {
        $this->href = $href;
        $this->active = $active ?? $href;
    }

    public function render() : View
    {
        $classes = [ 'nav-link', 'font-weight-bold', 'text-white' => $this->isActive() ];

        return view('components.nav-link', [
            'classes' => Arr::toCssClasses($classes),
        ]);
    }

    protected function isActive() : bool
    {
        if (is_bool($this->active))
        {
            return $this->active;
        }
        if (request()->is($this->active))
        {
            return true;
        }
        if (request()->fullUrlIs($this->active))
        {
            return true;
        }

        return request()->routeIs($this->active);
    }
}
