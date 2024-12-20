<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with(['accounts' => function ($q) {
            $q->select('id', 'medium', 'company_id', 'account_id', 'name', 'logo');
        }])->orderBy('id', 'desc');

        $companies->where('user_id', auth()->id());

        $companies->when(request('query'), function ($query) {
            $query->where('name', 'like', '%' . request('query') . '%');
        });

        $companies->when(request('id'), function ($query, $id) {
            $id = is_array($id) ? $id : [$id];
            $query->whereIn('id', $id);
        });

        return $companies->paginate(request('limit', 15));
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

    public function find($id)
    {
        return Company::with(['accounts' => function ($q) {
            $q->select('id', 'medium', 'company_id', 'account_id', 'name', 'logo');
        }])->findOrFail($id);
    }
}
