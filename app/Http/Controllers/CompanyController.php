<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with(['accounts' => function ($q) {
            $q->select('id', 'account_id', 'name', 'logo');
        }])->orderBy('id', 'desc');

        return $companies->paginate();
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $company = auth()->user()->companies()->save(new Company([
            'name' => $request->name
        ]));

        return $company;
    }
}
