<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Employee extends MyModel
{
    protected $table = "employees";
    
    public static function getOneAdmin($id) {
        $employees = Employee::join('companies', 'companies.id', '=', 'employees.company_id')
                ->where('employees.id',$id)
                ->select(['employees.id', 'employees.fname', 'employees.lname', 'employees.email', 'employees.phone','employees.company_id', 'companies.name as company_name'])
                ->first();
  
        return $employees;
    }
}
