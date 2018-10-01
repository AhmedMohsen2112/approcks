<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Http\Requests\Admin\CompaniesRequest;
use App\Models\Company;
use Validator;
use DB;

class CompaniesController extends BackendController {

    public function __construct() {

        parent::__construct();
    }

    public function index() {

        return $this->_view('companies.index', 'backend');
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
    public function store(CompaniesRequest $request) {


        try {

            $company = new Company;
            $company->name = $request->input('name');
            $company->website = $request->input('website');
            $company->email = $request->input('email');
            $company->logo = Company::upload($request->file('image'), 'companies', true);

            $company->save();


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
        $company = Company::getOneAdmin($id);
        if (!$company) {
            return $this->err404();
        }
        //dd($user);
        $this->data['company'] = $company;
        return $this->_view('companies.view', 'backend');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $find = Company::getOneAdmin($id);
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
    public function update(CompaniesRequest $request, $id) {
        //dd($request->all());
        $company = Company::find($id);
        if (!$company) {
            return _json('error', _lang('app.error_is_occured'), 404);
        }
        try {
            $company->name = $request->input('name');
            $company->website = $request->input('website');
            $company->email = $request->input('email');
            if ($request->file('image')) {
                $company->logo = Company::upload($request->file('image'), 'companies', true);
            }

            $company->save();

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
        $company = Company::find($id);
        if (!$company) {
            return _json('error', _lang('app.error_is_occured'), 404);
        }
        try {
            $company->delete();
            return _json('success', _lang('app.deleted_successfully'));
        } catch (\Exception $ex) {
            //dd($ex->getCode());
            if ($ex->getCode() == 23000) {
                return _json('error', _lang('app.this_record_can_not_be_deleted_for_linking_to_other_records'), 400);
            } else {
                return _json('error', _lang('app.error_is_occured'), 400);
            }
        }
    }

    

    public function data() {

        $companies = Company::select(['id', 'name', 'logo']);

        return \Datatables::eloquent($companies)
                        ->addColumn('options', function ($item) {

                            $back = "";
                            $back .= '<div class="btn-group">';
                            $back .= ' <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> ' . _lang('app.options');
                            $back .= '<i class="fa fa-angle-down"></i>';
                            $back .= '</button>';
                            $back .= '<ul class = "dropdown-menu" role = "menu">';
                            $back .= '<li>';
                            $back .= '<a href="' . route('companies.show', $item->id) . '" onclick = "" data-id = "' . $item->id . '">';
                            $back .= '<i class = "icon-docs"></i>' . _lang('app.view');
                            $back .= '</a>';
                            $back .= '</li>';

                            $back .= '<li>';
                            $back .= '<a href="" onclick = "Companies.edit(this);return false;" data-id = "' . $item->id . '">';
                            $back .= '<i class = "icon-docs"></i>' . _lang('app.edit');
                            $back .= '</a>';
                            $back .= '</li>';



                            $back .= '<li>';
                            $back .= '<a href="" data-toggle="confirmation" onclick = "Companies.delete(this);return false;" data-id = "' . $item->id . '">';
                            $back .= '<i class = "icon-docs"></i>' . _lang('app.delete');
                            $back .= '</a>';
                            $back .= '</li>';



                            $back .= '</ul>';
                            $back .= ' </div>';

                            return $back;
                        })
                        ->editColumn('logo', function ($item) {
                            $back = '<img src="' . url('public/uploads/companies/' . $item->logo) . '" style="height:64px;width:64px;"/>';
                            return $back;
                        })
                        ->escapeColumns([])
                        ->make(true);
    }

    private function add_rules() {
        $rules = array(
            'password' => 'required',
            'ssn' => 'required',
            'mobile' => 'required|unique:companies',
            'email' => 'required|email|unique:companies',
            'birthdate' => 'required',
            'work_type_id' => 'required',
            'department_id' => 'required',
            'category_id' => 'required',
            'image' => 'image|mimes:gif,png,jpeg|max:1000',
            'active' => 'required',
        );
        return $rules;
    }

    private function edit_rules($id) {
        $rules = array(
            'ssn' => 'required',
            'mobile' => 'required|unique:companies,mobile,' . $id,
            'email' => 'required|email|unique:companies,email,' . $id,
            'birthdate' => 'required',
            'work_type_id' => 'required',
            'department_id' => 'required',
            'category_id' => 'required',
            'image' => 'image|mimes:gif,png,jpeg|max:1000',
            'active' => 'required',
        );
        return $rules;
    }

}
