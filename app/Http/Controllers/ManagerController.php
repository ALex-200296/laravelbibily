<?php

namespace App\Http\Controllers;

use App\Models\RestaurantUserTable;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller
{

    public function userCreate(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'login' => 'required',
            'password' => 'required',
            'color' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json(['errors' => 'ошибка'], 400);
        }
        $user = $request->user();
        $data = User::create([
            'name' => $request->name,
            'login' => $request->login,
            'password' => $request->password,
            'restaurant_id' => $user->restaurant_id,
            'color' => $request->color,
        ]);
        return response()->json(['data' => 'ok'], 200);
    }

    public function userDelete(Request $request, $id) {
        $user = User::find($id);
        if(!empty($user)) {
          $user->delete();
          return response()->json([ 'message' => 'ok' ], 200);
        }
        return response('error', 400);
    }


    public function user (Request $request) {
        $user = $request->user();
        return response()->json(['data' => $user]);
    }

    public function usersAll (Request $request) {
        $user = $request->user();
        if(!$user) {
            return response()->json(['errors' => 'пользователей ресторана не существует'], 400);
        }
        $users = User::where('restaurant_id', $user->restaurant_id)
                    ->where('position', 'employee')
                    ->get();
        return response()->json(['data' => $users]);
    }

    public function tablesAll (Request $request) {
        $user = $request->user();
        if(!$user) {
            return response()->json(['errors' => 'пользователей ресторана не существует'], 400);
        };
        $tables = Table::where('restaurant_id', $user->restaurant_id)->get();

        return response()->json(['data' => $tables]);
    }

    public function resUsTabCreate (Request $request) {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'date' => 'required',
            'table_id' => 'required',
            'time_of_day' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }
        $user = $request->user();
        $data = RestaurantUserTable::create([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'restaurant_id' => $user->restaurant_id,
            'table_id' => $request->table_id,
            'time_of_day' => $request->time_of_day,
        ]);

        return response()->json(['data' => $data]);
    }

    public function resUsTabUpdate (Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'date' => 'required',
            'table_id' => 'required',
            'time_of_day' => 'required',
        ]);
        $user = $request->user();
        $data = RestaurantUserTable::where('restaurant_id', $user->restaurant_id)
                                    ->where('time_of_day', $request->time_of_day)
                                    ->where('date', $request->date)
                                    ->where('table_id', $request->table_id)
                                    ->update(['user_id' => $request->user_id]);
        return response()->json(['data' => 'ok'],200);
    }



    public function resUsTab (Request $request)
    {
        $user = $request->user();
        $data = RestaurantUserTable::where('restaurant_id', $user->restaurant_id)
                                    ->where('time_of_day', $request->time_of_day)
                                    ->where('date', $request->date)
                                    ->get();
        return response()->json(["data" => $data]);
    }
}
