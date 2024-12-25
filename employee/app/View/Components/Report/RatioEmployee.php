<?php

namespace App\View\Components\Report;

use App\Models\Department;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RatioEmployee extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = $this->getData();
        return view('components.report.ratio-employee', $data);
    }
    private function getData()
    {

        $lables     = [];
        $dataNumber = [];


        $departments = Department::withCount('users')->get();
        $users       = User::where('type', 2)->get();
        foreach ($departments as $department) {
            $lables[] = $department->name;


            $countUserByDepartment = $department->users_count;
            $department->ratio     = ($countUserByDepartment / $users->count()) * 100;
            $dataNumber[]          = $department->ratio;
        }

        return compact('lables', 'dataNumber', 'users', 'departments');
    }
}
