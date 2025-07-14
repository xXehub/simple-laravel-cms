<?php

namespace App\View\Components;

use App\Models\MasterMenu;
use Illuminate\View\Component;

class SidebarMenu extends Component
{
    public MasterMenu $menu;

    /**
     * Create a new component instance.
     */
    public function __construct(MasterMenu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.layout.sidebar-menu');
    }
}
