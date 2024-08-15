<?php

namespace App\View\Components\layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class vehicle extends Component
{
    public $destinations = [],$key;
    public $checkInDate;
    public function __construct($destinations,$date,$key)
    {
        $this->destinations=$destinations[0];
        $this->checkInDate=$date;
        $this->key=$key;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.vehicle');
    }
}
