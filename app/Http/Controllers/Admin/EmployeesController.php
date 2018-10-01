<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\EmployeesRequest;
use App\Http\Controllers\BackendController;
use App\Models\Employee;
use App\Models\Company;
use Validator;

class EmployeesController extends BackendController {

    public function __construct() {

        parent::__construct();
    }

    public function index() {
        $this->data['companies'] = Company::getAllAdmin();
        return $this->_view('employees/index', 'backend');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeesRequest $request) {


        try {

            $employee = new Employee;
            $employee->fname = $request->input('fname');
            $employee->lname = $request->input('lname');
            $employee->email = $request->input('email');
            $employee->phone = $request->input('phone');
            $employee->company_id = $request->input('company');
            $employee->save();

            return _json('success', _lang('app.added_successfully'));
        } catch (\Exception $e) {
            return _json('error', _lang('app.error_is_occured'), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $employee = Employee::getOneAdmin($id);
        if (!$employee) {
            return $this->err404();
        }
        //dd($user);
        $this->data['employee'] = $employee;
        return $this->_view('employees.view', 'backend');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $find = Employee::getOneAdmin($id);

        if ($find) {
            return _json('success', $find);
        } else {
            return _json('error', _lang('app.error_is_occured'), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $employee = Employee::find($id);
        if (!$employee) {
            return _json('error', _lang('app.error_is_occured'), 404);
        }


        try {
            $employee->fname = $request->input('fname');
            $employee->lname = $request->input('lname');
            $employee->email = $request->input('email');
            $employee->phone = $request->input('phone');
            $employee->company_id = $request->input('company');
            $employee->save();
            return _json('success', _lang('app.updated_successfully'));
        } catch (\Exception $e) {
            return _json('error', _lang('app.error_is_occured'), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $employee = Employee::find($id);
        if (!$employee) {
            return _json('error', _lang('app.error_is_occured'), 404);
        }
        try {
            $employee->delete();
            return _json('success', _lang('app.deleted_successfully'));
        } catch (\Exception $ex) {
            if ($ex->getCode() == 23000) {
                return _json('error', _lang('app.this_record_can_not_be_deleted_for_linking_to_other_records'), 400);
            } else {
                return _json('error', _lang('app.error_is_occured'), 400);
            }
        }
    }

    public function data() {

        $employees = Employee::join('companies', 'companies.id', '=', 'employees.company_id')
                ->select(['employees.id', 'employees.fname', 'employees.lname', 'employees.email', 'employees.phone', 'companies.name as company']);

        return \Datatables::eloquent($employees)
                        ->addColumn('options', function ($item) {

                            $back = "";
                            $back .= '<div class="btn-group">';
                            $back .= ' <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> ' . _lang('app.options');
                            $back .= '<i class="fa fa-angle-down"></i>';
                            $back .= '</button>';
                            $back .= '<ul class = "dropdown-menu" role = "menu">';

                            $back .= '<li>';
                            $back .= '<a href="' . route('employees.show', $item->id) . '" onclick = "" data-id = "' . $item->id . '">';
                            $back .= '<i class = "icon-docs"></i>' . _lang('app.view');
                            $back .= '</a>';
                            $back .= '</li>';

                            $back .= '<li>';
                            $back .= '<a href="" onclick = "Employees.edit(this);return false;" data-id = "' . $item->id . '">';
                            $back .= '<i class = "icon-docs"></i>' . _lang('app.edit');
                            $back .= '</a>';
                            $back .= '</li>';



                            $back .= '<li>';
                            $back .= '<a href="" data-toggle="confirmation" onclick = "Employees.delete(this);return false;" data-id = "' . $item->id . '">';
                            $back .= '<i class = "icon-docs"></i>' . _lang('app.delete');
                            $back .= '</a>';
                            $back .= '</li>';



                            $back .= '</ul>';
                            $back .= ' </div>';

                            return $back;
                        })
                        ->escapeColumns([])
                        ->make(true);
    }

}
