<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Menu;

class SubMenuCard extends Component
{
    public $menu;
    /**
     * Create a new component instance.
     */
    public function __construct($moduleName = null)
    {
        $url = last(explode('/', url()->current()));
        $this->menu = Menu::with(['SubMenus' => function ($query) {
            $query->where('is_active', 1);
        }])
        ->where('name', $url)
        ->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        // dd($this->menu);

        return view('components.sub-menu-card', [
            'menu' => $this->menu
        ]);
    }
}
