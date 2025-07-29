<?php

namespace App\View\Components;

use Illuminate\View\Component;

class App extends Component
{
    public $title;
    public $pakaiSidebar;
    public $pakaiTopBar;
    public $pakaiFluid;
    public $hasDivPage;

    /**
     * Create a new component instance.
     *
     * @param string $title
     * @param bool $pakaiSidebar
     * @param bool $pakaiTopBar
     * @param bool $pakaiFluid
     * @param bool $hasDivPage
     */
    public function __construct($title = 'anjay CMS', $pakaiSidebar = false, $pakaiTopBar = null, $pakaiFluid = true, $hasDivPage = true)
    {
        $this->title = $title;
        $this->pakaiSidebar = $pakaiSidebar;
        $this->pakaiTopBar = $pakaiTopBar;
        $this->pakaiFluid = $pakaiFluid;
        $this->hasDivPage = $hasDivPage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.layout.app');
    }
}
