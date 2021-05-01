<?php

namespace App\Http\Controllers;

use App\Models\RestaurantUserTable;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
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

    public function logout (Request $request)
    {
        $request->user()->token()->revoke();
        $req = $request->user()->token()->delete();
        return response()->json(['data' => 'ok'], 200);
    }

    public function shemaUser(Request $request) {

        $user = $request->user();
        $users = User::all();
        $tables = Table::all();
        $data = RestaurantUserTable::where('restaurant_id', $user->restaurant_id)
                                    ->where('date', $request->date)
                                    ->get();
        for($i = 0; $i < count($data); $i++) {
            for($j = 0; $j < count($tables); $j++) {
                if($tables[$j]->id == $data[$i]->table_id) {
                    $data[$i]['table'] = $tables[$j]->name;
                }
            }
            for($h = 0; $h < count($users); $h++) {
                if($users[$h]->id == $data[$i]->user_id) {
                    $data[$i]['user'] = $users[$h]->name;
                    $data[$i]['color'] = $users[$h]->color;
                }
            }
        }
        return response()->json(['data' => $data]);
    }
}
