<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Response;

class PostResponseController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'nullable',
            'email' => 'nullable',
            'message' => 'required',
            'thread_id' => 'required',
        ]);

        // nameの値を取得
        $name = $request->input('name');
        if (is_null($name)) {
            $tid = $request->input('thread_id');
            $name = Thread::find($tid)->board->default_response_name;
            $request->merge([
                'name' => $name,
            ]);
        }
        
        $request->merge([
            'ip' => $request->ip(),
        ]);
        Response::create($request->all());

        $responses = [];
        $responses['thread'] = Thread::find($request->input('thread_id'))->orderBy('sequence');
        $responses['board'] = Thread::find($request->input('thread_id'))->board;
        $responses['responses'] = Thread::find($request->input('thread_id'))->responses;

        return response()->json($responses, 200);
    }
}