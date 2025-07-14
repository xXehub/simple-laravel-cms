<?php

namespace App\View\Components;

use App\Models\MasterMenu;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $menus;

    /**
     * Create a new component instance.
     */
    public function __construct($menus = null)
    {
        $this->menus = $menus;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.sidebar');
    }
}
