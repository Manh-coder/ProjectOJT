<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatioEmployeeDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $ratio        = $this->getDataRatio();
        $ageAndGender = $this->getDataAgeAndGender();
        $gender       = $this->getGenderReport();

        // Biểu đồ thống kê số ngày công hợp lệ/ không hợp lệ của các nhân viên
        $workdayReport = $this->getWorkdayReport();
        // Biểu đồ thống kê lương thực nhận của các nhân viên
        $salaryReport = $this->getSalaryReport();
        return view('report.ratio-employee-department', compact('ratio', 'ageAndGender', 'gender', 'workdayReport', 'salaryReport'));
    }
    private function getSalaryReport()
    {
        $computedDetail = DB::table('salaries')
            ->selectRaw('
                SUM(salary) AS total_salary
            ')
            ->addSelect('user_id')
            ->groupBy('user_id')
            // ->first();
        ;
        $users = User::leftJoinSub($computedDetail, 'computed', function ($join) {
            $join->on('users.id', '=', 'computed.user_id');
        })
            ->selectRaw('
            COALESCE(total_salary,0) as total_salary
        ')
            ->addSelect('id', 'name', 'username')
            ->where('type', 2)
            ->when(request()->query('salary'), function ($q) {
                $q->where('created_at', '>=', request()->query('salary'));
            })
            ->get();

        $lables          = [];
        $dataValid       = [];
        $dataInvalid     = [];
        $backgroundColor = [];

        foreach ($users as $user) {
            $lables[]          = $user->name;
            $backgroundColor[] = $this->generateRandomColor();


            $dataValid[]   = (int) $user->total_salary;
            $dataInvalid[] = $user->invalid_days;
        }

        return Chartjs::build()
            ->name("Salary")
            ->type("line")
            ->size(["width" => 300, "height" => 300])
            ->labels($lables)
            ->datasets([
                [
                    'label'           => 'Salary',
                    "backgroundColor" => 'red',
                    "borderColor"     => "rgba(38, 185, 154, 0.7)",
                    "data"            => $dataValid,
                    "fill"            => 2,
                ]
            ])
            ->optionsRaw("{
                    scales: {
                        x: {
                            min: 0,
                           
                        },
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Salary',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            callbacks:{
                                label: function(context){
                                    const value = context.raw; 
                                    return value;
                                }
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }");
    }
    private function getWorkdayReport()
    {
        $computedDetail = DB::table('user_attendance')
            ->selectRaw('
                COUNT(CASE WHEN status = "valid" THEN 1 END) AS valid_days,
                COUNT(CASE WHEN status = "invalid" THEN 1 END) AS invalid_days
            ')
            ->addSelect('user_id')
            ->groupBy('user_id')
            // ->first();
        ;
        $users = User::leftJoinSub($computedDetail, 'computed', function ($join) {
            $join->on('users.id', '=', 'computed.user_id');
        })
            ->selectRaw('
            COALESCE(valid_days,0) as valid_days,
            COALESCE(invalid_days,0) as invalid_days
        ')
            ->addSelect('id', 'name', 'username')
            ->where('type', 2)
            ->when(request()->query('workday'), function ($q) {
                $q->where('created_at', '>=', request()->query('workday'));
            })
            ->get();

        $lables          = [];
        $dataValid       = [];
        $dataInvalid     = [];
        $backgroundColor = [];

        foreach ($users as $user) {
            $lables[]          = $user->name;
            $backgroundColor[] = $this->generateRandomColor();


            $dataValid[]   = $user->valid_days;
            $dataInvalid[] = $user->invalid_days;
        }

        return Chartjs::build()
            ->name("Workday")
            ->type("pie")
            ->size(["width" => 300, "height" => 300])
            ->labels($lables)
            ->datasets([
                [
                    'label'           => 'Valid',
                    "backgroundColor" => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                    ],
                    "data"            => $dataValid,
                    "hoverOffset"     => 4,
                ],
                [
                    'label'           => 'Invalid',
                    "backgroundColor" => [
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    "data"            => $dataInvalid,
                    "hoverOffset"     => 4,
                ]
            ])
            ->optionsRaw("{
                    scales: {
                        
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Workday',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            callbacks:{
                                label: function(context){
                                console.log(context);
                                    const value = context.raw; 
                                    return context.dataset.label+': '+value + ' days';
                                }
                            }
                        }
                    },
                    responsive: false,
                    maintainAspectRatio: false
                }");
    }
    private function getGenderReport()
    {

        $lables          = [];
        $dataMale        = [];
        $dataFemale      = [];
        $backgroundColor = [];


        $departments = Department::query()
            ->withCount([
                'users AS male_users'   => fn($q) => $q->where('type', 2)->where('gender', 'male'),
                'users AS female_users' => fn($q) => $q->where('type', 2)->where('gender', 'female'),

            ])
            ->when(request()->query('gender'), function ($q) {
                $q->where('created_at', '>=', request()->query('gender'));
            })
            ->get();
        $users       = User::where('type', 2)->get();
        foreach ($departments as $department) {
            $lables[]          = $department->name;
            $backgroundColor[] = $this->generateRandomColor();


            $dataMale[]   = $department->male_users;
            $dataFemale[] = $department->female_users;
        }


        return Chartjs::build()
            ->name("GenderReport")
            ->type("bar")
            ->size(["width" => 300, "height" => 300])
            ->labels($lables)
            ->datasets([
                [
                    'label'           => 'Male',
                    "backgroundColor" => 'red',
                    "borderColor"     => "rgba(38, 185, 154, 0.7)",
                    "data"            => $dataMale,
                    "fill"            => TRUE,
                ],
                [
                    'label'           => 'Female',
                    "backgroundColor" => 'pink',
                    "borderColor"     => "rgba(38, 185, 154, 0.7)",
                    "data"            => $dataFemale,
                    "fill"            => TRUE,
                ]
            ])
            ->optionsRaw("{
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        stepSize: 1, // Ensure step size is 1 for integer values
                        ticks: {
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        }
                    },
                    x: {
                        beginAtZero: true,
                        min: 1,
                        stepSize: 1 // Add step increment
                    },
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Gender',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks:{
                            label: function(context){
                                const value = context.raw; 
                                return value;
                            }
                        }
                    }
                },
                responsive: false,
                maintainAspectRatio: false
            }");
    }
    private function getDataAgeAndGender()
    {

        $lables          = [];
        $dataNumber      = [];
        $dataNumber2     = [];
        $dataNumber3     = [];
        $backgroundColor = [];

        $departments = Department::withCount([
                'users AS male_users'   => fn($q) => $q->where('type', 2)->where('gender', 'male'),
                'users AS female_users' => fn($q) => $q->where('type', 2)->where('gender', 'female'),
            ])
            ->withAvg('users', 'age')
            ->when(request()->query('gender_and_age'), function ($q) {
                $q->where('created_at', '>=', request()->query('gender_and_age'));
            })
            ->get();
        // $users       = User::where('type', 2)
        //     ->when(request()->query('gender_and_age'), function ($q) {
        //         $q->where('created_at', '>=', request()->query('gender_and_age'));
        //     })
        //     ->get();
        foreach ($departments as $department) {
            $lables[]      = $department->name;
            $dataNumber[]  = $department->male_users;
            $dataNumber2[] = $department->female_users;
            $dataNumber3[] = (int) $department->users_avg_age;
        }


        return Chartjs::build()
            ->name("AgeAndGenderEmployee")
            ->type("bar")
            ->size(["width" => 300, "height" => 300])
            ->labels($lables)
            ->datasets([
                [
                    'label'           => 'Age',
                    "backgroundColor" => 'red',
                    "borderColor"     => "rgba(38, 185, 154, 0.7)",
                    "data"            => $dataNumber3,
                    "fill"            => FALSE,
                ],
                [
                    'label'           => 'Male',
                    "backgroundColor" => 'pink',
                    "borderColor"     => "rgba(38, 185, 154, 0.7)",
                    "data"            => $dataNumber,
                    "fill"            => FALSE,
                ],
                [
                    'label'           => 'FMale',
                    "backgroundColor" => 'yellow',
                    "borderColor"     => "rgba(38, 185, 154, 0.7)",
                    "data"            => $dataNumber2,
                    "fill"            => FALSE,
                ]
            ])
            ->optionsRaw("{
                scales: {
    
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Age And Gender',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks:{
                            label: function(context){
                                const value = context.raw; 
                                return value;
                            }
                        }
                    }
                },
                responsive: false,
                maintainAspectRatio: false
            }");
    }
    private function getDataRatio()
    {

        $lables          = [];
        $dataNumber      = [];
        $backgroundColor = [];


        $departments = Department::withCount('users')
            ->when(request()->query('ratio'), function ($q) {
                $q->where('created_at', '>=', request()->query('ratio'));
            })
            ->get();
        $users       = User::where('type', 2)
            ->when(request()->query('ratio'), function ($q) {
                $q->where('created_at', '>=', request()->query('ratio'));
            })
            ->get();
        foreach ($departments as $department) {
            $lables[]          = $department->name;
            $backgroundColor[] = $this->generateRandomColor();


            $countUserByDepartment = $department->users_count;
            $department->ratio     = ($countUserByDepartment / $users->count()) * 100;
            $dataNumber[]          = $department->ratio;
        }


        return $chart = Chartjs::build()
            ->name("RatioEmployeeDepartment")
            ->type("doughnut")
            ->size(["width" => 300, "height" => 500])
            ->labels($lables)
            ->datasets([
                [
                    "backgroundColor" => $backgroundColor,
                    // "borderColor"     => "rgba(38, 185, 154, 0.7)",
                    "data"            => $dataNumber,
                    // 'cutout'          => $dataNumber
                ]
            ])
            ->optionsRaw("{
            scales: {

            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Ratio',
                    font: {
                        size: 16
                    }
                },
                tooltip: {
                    callbacks:{
                        label: function(context){
                            const value = context.raw; 
                            return value+' %';
                        }
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: true
        }");


        // ->options([
        //     'responsive'          => TRUE,  // Đảm bảo biểu đồ có thể thay đổi kích thước theo màn hình
        //     'maintainAspectRatio' => FALSE,
        //     'plugins'             => [
        //         'datalabels' => [
        //             'anchor'    => 'center',   // Đặt nhãn ở giữa phần của bánh
        //             'align'     => 'center',   // Căn chỉnh nhãn ở giữa
        //             'formatter' => function ($value) {
        //                 return number_format($value, 2) . '%'; // Định dạng tỉ lệ phần trăm
        //             },
        //             'color'     => '#000',  // Màu chữ
        //             'font'      => [
        //                 'weight' => 'bold',  // Cỡ chữ
        //                 'size'   => 14
        //             ],
        //         ]
        //     ],
        //     'scales'              => [
        //         'yAxes' => [
        //             [
        //                 'ticks' => [
        //                     'max' => 30,
        //                     'min' => 10,
        //                 ]
        //             ],
        //         ]
        //     ],
        // ]);
    }


    private function generateRandomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
