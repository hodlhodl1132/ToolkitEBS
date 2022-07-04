<?php

namespace App\Http\Controllers;

use App\Models\QueuedPoll;
use Exception;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\ValidationException;
use Log;
use Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class QueuedPollController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:100|string',
                'provider_id' => 'required|integer',
                'options' => 'required|array|size:2',
                'options.*.def_name' => 'required|string|max:64',
                'options.*.mod_id' => 'required_with:options.*.def_name|string|max:128',
                'options.*.label' => 'required_with:options.*.def_name|string|max:64',
            ]);

            $user = $request->user();
            if (!$user->hasPermissionTo('settings.edit.'.$validated['provider_id'])) {
                throw new AccessDeniedHttpException('You do not have permission to create polls for this stream.');
            }

            $queuedPoll = new QueuedPoll();
            $queuedPoll->title = $validated['title'];
            $queuedPoll->options = $validated['options'];
            $queuedPoll->length = 2;
            $queuedPoll->created_by_id = $user->id;
            $queuedPoll->provider_id = $validated['provider_id'];
            $queuedPoll->save();

            return response()->json($queuedPoll, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation error',
                'errors' => $e->errors()
            ], 400);
        } catch (AccessDeniedHttpException $e) {
            return response()->json([
                'error' => 'You do not have permission to edit this poll.',
                'message' => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response([
                'error' => 'There was an error creating the poll',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate the specified resource from storage
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HttpRequest $request, QueuedPoll $queuedPoll)
    {
        try {
            $validated = $request->validate([
                'validated' => 'required|boolean',
                'validation_error' => 'nullable|string|max:255',
            ]);

            if ($request->user()->id !== $queuedPoll->streamUser()->first()->id) {
                throw new AccessDeniedHttpException('You do not have permission to edit this poll.');
            }

            $queuedPoll->validated = $validated['validated'];
            $queuedPoll->validation_error = $validated['validation_error'];
            $queuedPoll->save();

            return response()->json([], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->validator->errors()->first(),
            ], 400);
        } catch (AccessDeniedHttpException $e) {
            return response()->json([
                'error' => 'You do not have permission to edit this poll.',
                'message' => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => 'There was an error validating the poll',
            ], 500);
        }
    }

    /**
     * Delete the specified resource from storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(HttpRequest $request, QueuedPoll $queuedPoll) {
        if ($request->user()->id != $queuedPoll->streamUser()->first()->id) {
            return response()->json([
                'error' => 'You do not have permission to delete this poll.',
            ], 403);
        }

        $queuedPoll->delete();
        
        return response()->json(
            [
                'success' => 'Poll deleted successfully'
            ]
        );
    }
}