<?php

namespace App\Http\Controllers\Admin\v1;


use App\Enums\AccessType;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
   public function show(){
       $users = QueryBuilder::for(User::class)
           ->select('id', 'first_name', 'last_name','email', 'created_at', 'updated_at')
           ->where(['access_type' => AccessType::ADMIN])
           ->allowedFilters(['first_name', 'last_name','email'])
           ->defaultSort('-updated_at')
           ->allowedSorts(['id', 'first_name', 'last_name', 'created_at', 'updated_at'])
           ->paginate(request('per_page', 100))
           ->appends(request()->query());

       return response(['success' => true, 'data' => $users]);
   }
}
