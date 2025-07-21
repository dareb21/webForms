<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminServices;

class DcaController extends Controller
{
    private $adminServices;
    public function __construct(AdminServices $adminServices)
    {
        $this->adminServices = $adminServices;
    }
    public function dcaDashboard()
    {
        $dashboard = $this->adminServices->dashboard();
        return view("adminDCA.dcaDashboard", compact("dashboard"));
    }
    public function dcaResults()
    {
        $adminResults = $this->adminServices->adminResults();
        $years = Survey::selectRAW("Year(dateStart)")
            ->distinct()
            ->get();
        return view("adminDCA.dcaResults", compact("dashboard"));

    }
    public function dcaStudenView()
    {
        $adminStudentView = $this->adminServices->adminStudentView($courseId);
        $years = Survey::selectRAW("Year(dateStart)")
            ->distinct()
            ->get();
        return view('admin.adminStudentView', compact("years", "resultados", "data"));
    }

    public function dcaAnswerView()
    {
        return $this->adminServices->adminViewAnswer($submitId);
    }

}
