<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('login', $request->login)->first();
        if($user) {
            if(Hash::check($request->password, $user->password)) {
                $token = $user->createToken('userToken')->accessToken;
                return response()->json(['token' => $token, 'position' => $user->position], 200);
            }
            return response()->json(['errors' => 'password invalid'], 401);
        };
        return response()->json(['errors' => 'dont found login'], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'login' => 'required',
            'password' => 'required',
            'restaurant_id' => 'required',
        ]);

        if($validator->fails()) {
           return response()->json(['errors'=>$validator->errors()->all()], 401);
        }

        if($request->position == 'manager') {
            $user = User::create([
                'name' => $request->name,
                'login' => $request->login,
                'password' => bcrypt($request->password),
                'position' => $request->position,
                'restaurant_id' => $request->restaurant_id,
            ]);

            return response()->json(['data' => 'ok1'], 200);
        }
        $user = User::create([
            'name' => $request->name,
            'login' => $request->login,
            'password' => bcrypt($request->password),
            'restaurant_id' => $request->restaurant_id,
            'position' => $request->position,
            'color' => $request->color
        ]);

        return response()->json(['data' => 'ok'], 200);
    }

    public function logout (Request $request)
    {
        $request->user()->token()->revoke();
        $req = $request->user()->token()->delete();
        return response()->json(['data' => 'ok'], 200);
    }

    public function restaurantId ()
    {
        $restaurants = Restaurant::all();
        return response()->json(['data' => $restaurants]);
    }

    public function tables () {
        $tables = Table::all();
        return response()->json(['data' => $tables]);
    }

    public function users (Request $request) {
        $managers = User::where('position', 'manager')->get();
        $employees = User::where('position', 'employee')->get();

        return response()->json(['managers' => $managers, 'employees' => $employees]);
    }

    public function tableCreate (Request $request)
    {
        if(!$request->name || !$request->restaurant_id) {
            return response()->json(['errors' => 'ошибка'], 400);
        };

        $table = Table::create([
            'name' => $request->name,
            'restaurant_id' => (int)$request->restaurant_id
        ]);

        return response()->json(['data' => 'ok'], 200);
    }

    public function restaurantCreate(Request $request)
    {
       if(!$request->name) {
        return response()->json(['errors' => 'ошибка'], 400);
       }
        $restaurant = Restaurant::create([
            'name' => $request->name
        ]);

        return response()->json(['data' => 'ok'], 200);
    }
}
