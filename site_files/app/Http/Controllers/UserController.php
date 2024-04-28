<?php

namespace App\Http\Controllers;


use App\Models\Level;
use App\Models\Progress;
use App\Models\User;
use App\Resources\LevelResource;
use App\Resources\ProgressUsersResource;
use App\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return parent::sendError(parent::error_processor($validator), 403);
        }


        $user = \App\Models\User::
        where('email', $request->username)->
        first();

        if (!$user || ($user && ($user->role_id != 3 || !Hash::check($request->password, $user->password)))) {
            return parent::sendError([['message' => 'خطأ في كلمة المرور أو اسم المستخدم']], 403);
        }

        $token = $user->createToken('apiToken')->plainTextToken;
        return parent::sendSuccess(trans('تم تسجيل الدخول'), [
            'token' => $token,
            'user' => UserResource::make($user),
        ]);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return parent::sendError(parent::error_processor($validator), 403);
        }


        $user = new \App\Models\User();
        $user->name = $request->get('name');
        $user->email = $request->get('username');
        $user->role_id = 3;
        $user->password = bcrypt($request->get('password'));
        $user->save();

        $token = $user->createToken('apiToken')->plainTextToken;
        return parent::sendSuccess(trans('تم تسجيل الحساب بنجاح'), [
            'token' => $token,
            'user' => UserResource::make($user),
        ]);
    }

    public function update(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user->name = $request->has('name') ? $request->get('name') : $user->name;
        $user->email = $request->has('username') ? $request->get('username') : $user->email;
        $user->password = $request->has('password') ? bcrypt($request->get('password')) : $user->password;
        $user->update();

        return parent::sendSuccess(trans('تم تعديل بيانات الحساب بنجاح'), UserResource::make($user));
    }


    public function getData(Request $request)
    {
        return parent::sendSuccess(trans('تم جلب البيانات'), [
            'user' => UserResource::make(auth()->user()),
            'points_to_online' => 75,
            'can_play_online' => (Progress::where('user_id', auth()->user()->id)->sum('points') > 75),
            'offline_levels' => LevelResource::collection(Level::where('type', 'offline')->get()),
            'online_levels' => LevelResource::collection(Level::where('type', 'online')->get()),
        ]);
    }

    public function getUserData(Request $request)
    {
        return parent::sendSuccess(trans('تم جلب البيانات'), UserResource::make(auth()->user()));
    }

    public function getUsersData(Request $request)
    {
        return parent::sendSuccess(trans('تم جلب البيانات'), ProgressUsersResource::collection(
            User::select(DB::raw('users.id,sum(progress.points) as sump'))->
            join('progress', 'progress.user_id', '=', 'users.id')->
            where('users.role_id', 3)->
            orderBy('sump', 'desc')->
            groupBy('users.id')->
            get())
        );
    }

    public function saveProgress(Request $request)
    {

        if (!Progress::where([
            'level_id' => $request->get('level_id'),
            'user_id' => auth()->user()->id,
        ])->first()) {
            $progress = new Progress();
            $progress->level_id = $request->get('level_id');
            $progress->q1 = $request->get('q1');
            $progress->q2 = $request->get('q2');
            $progress->q3 = $request->get('q3');
            $progress->q4 = $request->get('q4');
            $progress->points = $request->get('points');
            $progress->user_id = auth()->user()->id;
            $progress->save();
            return parent::sendSuccess(trans('تم حفظ البيانات'), NULL);
        } else {
            return parent::sendSuccess(trans('تم حفظ البيانات مسبقاً في هذه المرحلة'), NULL);
        }

    }

}
