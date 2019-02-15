<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Setting::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return (array) Setting::getAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'site_title' => 'required',
            'like_enabled' => 'required|boolean',
            'comment_enabled' => 'required|boolean',
            'per_page' => 'required|integer|min:1',
            'self.name' => 'required',
            'self.email' => 'required|email',
            'self.password' => 'alpha_dash',
        ]);

        $setting = Setting::first();
        $user = User::first();

        $data = $setting->data;

        foreach ($request->except(['email', 'password']) as $key => $value) {
            if (isset($data->$key)) {
                $data->$key = $value;
            }
        }

        $setting->data = $data;

        $user->fill($request->input('self'));
        if ($request->filled('self.password')) {
            $user->password = Hash::make($request->input('self.password'));
        }

        DB::transaction(function() use ($user, $setting) {
            $setting->save();
            $user->save();
        });

        return (array) $setting->data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
