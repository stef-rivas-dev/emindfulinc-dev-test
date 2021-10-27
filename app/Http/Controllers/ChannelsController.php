<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $channel = \App\Models\Channel::where('name', $validated['name'])->exists();
        if ($channel) {
            abort(400, 'Channel name already exists');
        }

        $channel = new Channel;
        $channel->fill([
            'name' => $validated['name'],
            'user_id' => config('app.auth_user_id'),
        ])->save();

        return response()->json($channel->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $channel
     * @return \Illuminate\Http\Response
     */
    public function show($channelId)
    {
        $channel = \App\Models\Channel::find($channelId);
        if (!$channel) {
            $channel = \App\Models\Channel::where('name', $channelId)->first();

            if (!$channel) {
                abort(404, 'Channel not found');
            }
        }

        $channelResource = $channel->toArray();
        $channelResource['user'] = $channel->user;
        $channelResource['messages'] = $channel->messages;

        return response()->json($channelResource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Channel $channel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $channel
     * @return \Illuminate\Http\Response
     */
    public function destroy($channelId)
    {
        $channel = \App\Models\Channel::find($channelId);
        if (!$channel) {
            $channel = \App\Models\Channel::where('name', $channelId)->first();

            if (!$channel) {
                abort(404, 'Channel not found');
            }
        }

        $authUser = config('app.auth_user_id');
        if ($authUser !== $channel->user_id) {
            abort(400, 'Only the creator can delete a Channel');
        }

        \App\Models\Message::where('channel_id', $channel->id)->delete();
        \App\Models\Channel::where('id', $channel->id)->delete();

        return response()->json(null, 200);
    }
}
