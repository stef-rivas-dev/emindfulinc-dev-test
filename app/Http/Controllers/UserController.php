<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'handle'        => 'required|unique:users',
            'email'         => 'required|unique:users',
            'password'      => 'required|string|min:8',
        ]);

        $user = new User;
        $user->fill([
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'handle'        => $validated['handle'],
            'email'         => $validated['email'],
            'password'      => bcrypt($validated['password']),
        ])->save();

        return response()->json($user->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if ($user->id !== config('app.auth_user_id')) {
            abort(400, 'Cannot view other User info');
        }

        $userResource = $user->toArray();
        $userResource['messages'] = $user->messages;
        $userResource['channels'] = $user->channels;
        $userResource['mentions'] = $user->mentions;

        return response()->json($userResource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }
}
