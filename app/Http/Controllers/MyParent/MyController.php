<?php

namespace App\Http\Controllers\MyParent;
use App\Http\Controllers\Controller;
use App\Repositories\StudentRepo;
use Illuminate\Support\Facades\Auth;

class MyController extends Controller
{
    protected $student;
    public function __construct(StudentRepo $student)
    {
        $this->student = $student;
    }

    public function children()
    {
        $data['students'] = $this->student->getRecord(['parent_id_no' => Auth::user()->id])->with(['my_class', 'section'])->get();
        $data['parent'] = Auth::user();
        return view('pages.parent.children', $data);
    }

}