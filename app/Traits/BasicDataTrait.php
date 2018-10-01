<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\BasicData;
use App\Models\BasicDataTranslation;
use Validator;
use DB;

trait BasicDataTrait {

    public function index(Request $request) {
        return $this->_view("basic_data/index", 'backend');
    }

    public function create(Request $request) {
        return $this->_view("basic_data/create", 'backend');
    }

    public function store(Request $request) {
        $this->rules = array_merge($this->rules, $this->lang_rules(['title' => 'required']));
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return _json('error', $errors);
        }
        DB::beginTransaction();
        try {
            $data = new BasicData;
            $data->active = $request->input('active');
            $data->type = $this->type;
            $data->this_order = $request->input('this_order');
            $data->save();
            $data_translations = array();
            $data_title = $request->input('title');
            foreach ($this->languages as $key => $value) {
                $data_translations[] = array(
                    'locale' => $key,
                    'title' => $data_title[$key],
                    'basic_data_id' => $data->id
                );
            }
            BasicDataTranslation::insert($data_translations);
            DB::commit();
            return _json('success', _lang('app.added_successfully'));
        } catch (\Exception $ex) {
            DB::rollback();
            return _json('error', _lang('app.error_is_occured'), 400);
        }
    }

    public function edit($id) {
        $data = BasicData::find($id);
        if (!$data) {
            return $this->err404();
        }
        $this->data['translations'] = BasicDataTranslation::where('basic_data_id', $id)->get()->keyBy('locale');
        $this->data['info'] = $data;
        return $this->_view('basic_data/edit', 'backend');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $basic_data = BasicData::find($id);
        if (!$basic_data) {
            return _json('error', _lang('app.error_is_occured'), 404);
        }
        $this->rules = array_merge($this->rules, $this->lang_rules(['title' => "required"]));
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return _json('error', $errors);
        }

        DB::beginTransaction();
        try {

            $basic_data->active = $request->input('active');
            $basic_data->this_order = $request->input('this_order');

            $basic_data->save();

            $basic_data_translations = array();

            BasicDataTranslation::where('basic_data_id', $basic_data->id)->delete();

            $basic_data_title = $request->input('title');

            foreach ($this->languages as $key => $value) {
                $basic_data_translations[] = array(
                    'locale' => $key,
                    'title' => $basic_data_title[$key],
                    'basic_data_id' => $basic_data->id
                );
            }
            BasicDataTranslation::insert($basic_data_translations);

            DB::commit();
            return _json('success', _lang('app.updated_successfully'));
        } catch (\Exception $ex) {
            DB::rollback();
            return _json('error', $ex, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $basic_data = BasicData::find($id);
        //dd($basic_data);
        if (!$basic_data) {
            return _json('error', _lang('app.error_is_occured'), 404);
        }
        try {
            $basic_data->delete();
            DB::commit();
            return _json('success', _lang('app.deleted_successfully'));
        } catch (\Exception $ex) {
            dd($ex);
            DB::rollback();
            if ($ex->getCode() == 23000) {
                return _json('error', _lang('app.this_record_can_not_be_deleted_for_linking_to_other_records'), 400);
            } else {
                return _json('error', _lang('app.error_is_occured'), 400);
            }
        }
    }
     

    public function data(Request $request) {
        $bathes = BasicData::Join('basic_data_translations', 'basic_data.id', '=', 'basic_data_translations.basic_data_id')
                ->where('basic_data_translations.locale', $this->lang_code)
                ->where('basic_data.type', $this->type)
                ->select([
            'basic_data.id', "basic_data_translations.title", "basic_data.this_order", 'basic_data.active','basic_data.permissions'
        ]);
        return \Datatables::eloquent($bathes)
                        ->addColumn('options', function ($item) {

                            $back = "";
                            if (\Permissions::check($this->controller, 'edit') || \Permissions::check($this->controller, 'delete')) {
                                $back .= '<div class="btn-group">';
                                $back .= ' <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> options';
                                $back .= '<i class="fa fa-angle-down"></i>';
                                $back .= '</button>';
                                $back .= '<ul class = "dropdown-menu" role = "menu">';
                                if (\Permissions::check($this->controller, 'edit')) {
                                    $back .= '<li>';
                                    $back .= '<a href="' . url('admin/' . $this->controller . '/' . $item->id) . '/edit">';
                                    $back .= '<i class = "icon-docs"></i>' . _lang('app.edit');
                                    $back .= '</a>';
                                    $back .= '</li>';
                                }

                                if (\Permissions::check($this->controller, 'delete')) {
                                    $back .= '<li>';
                                    $back .= '<a href="" data-toggle="confirmation" onclick = "BasicData.delete(this);return false;" data-id = "' . $item->id . '">';
                                    $back .= '<i class = "icon-docs"></i>' . _lang('app.delete');
                                    $back .= '</a>';
                                    $back .= '</li>';
                                }

                                $back .= '</ul>';
                                $back .= ' </div>';
                            }
                            return $back;
                        })
                        ->editColumn('active', function ($item) {
                            if ($item->active == 1) {
                                $message = _lang('app.active');
                                $class = 'label-success';
                            } else {
                                $message = _lang('app.not_active');
                                $class = 'label-danger';
                            }
                            $back = '<span class="label label-sm ' . $class . '">' . $message . '</span>';
                            return $back;
                        })
                        ->editColumn('permissions', function ($item) {
                            if ($item->permissions == 1) {
                                $message = _lang('app.active');
                                $class = 'btn-info';
                            } else {
                                $message = _lang('app.not_active');
                                $class = 'btn-danger';
                            }
                            $back = '<a class="btn ' . $class . '" onclick = "BasicData.permissions(this);return false;" data-id = "' . $item->id . '">' . $message . ' <a>';
                            return $back;
                        })
                        ->escapeColumns([])
                        ->make(true);
    }

}
