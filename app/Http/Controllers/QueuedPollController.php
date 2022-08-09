<?php

namespace App\Http\Controllers;

use App\Events\QueuedPollCreated;
use App\Events\QueuedPollDeleted;
use App\Events\QueuedPollValidated;
use App\Models\QueuedPoll;
use Auth;
use Exception;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\ValidationException;
use Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class QueuedPollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $providerId)
    {
        $user = Auth::user();
        if ($user->provider_id !== $providerId && !$user->hasPermissionTo('settings.edit.'.$providerId)) {
            throw new AccessDeniedHttpException();
        }

        return QueuedPoll::where('provider_id', $providerId)->paginate(10);  
    }

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
                'title' => 'max:100|string|nullable',
                'provider_id' => 'required|integer',
                'duration' => 'required|integer|max:5|min:1',
                'options' => 'required|array|size:2',
                'options.*.def_name' => 'required|string|max:64',
                'options.*.mod_id' => 'required_with:options.*.def_name|string|max:128',
                'options.*.label' => 'required_with:options.*.def_name|string|max:64',
            ]);

            $user = $request->user();
            if ($user->provider_id !== $validated['provider_id'] && !$user->hasPermissionTo('settings.edit.'.$validated['provider_id'])) {
                throw new AccessDeniedHttpException('You do not have permission to create polls for this stream.');
            }

            if (count($validated['options']) > 10) {
                throw new Exception('Too many options');
            }

            $queuedPolls = QueuedPoll::where('provider_id', $validated['provider_id'])->get();
            if (count($queuedPolls) >= 30) {
                throw new Exception('You cannot queue more than 30 polls');
            }

            $queuedPoll = new QueuedPoll();
            $queuedPoll->title = $validated['title'] ?? 'What event should happen next?';
            $queuedPoll->options = $validated['options'];
            $queuedPoll->length = $validated['duration'];
            $queuedPoll->created_by_id = $user->id;
            $queuedPoll->provider_id = $validated['provider_id'];
            $queuedPoll->save();

            QueuedPollCreated::dispatch($queuedPoll);

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

            if ($request->user()->provider_id !== $queuedPoll->provider_id) {
                throw new AccessDeniedHttpException('You do not have permission to edit this poll.');
            }

            $queuedPoll->validated = $validated['validated'];
            $queuedPoll->validation_error = $validated['validation_error'];
            $queuedPoll->save();

            QueuedPollValidated::dispatch($queuedPoll);

            return response()->json([
                'success' => 'Poll validated successfully.',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'There was an error validating the poll',
                'message' => $e->validator->errors(),
            ], 400);
        } catch (AccessDeniedHttpException $e) {
            return response()->json([
                'error' => 'You do not have permission to edit this poll.',
                'message' => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => 'There was a critical error validating the poll',
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
        try {
            $user = $request->user();
            if ($user->provider_id !== $queuedPoll->provider_id && !$user->hasPermissionTo('settings.edit.' . $queuedPoll->provider_id)) {
                return response()->json([
                    'error' => 'You do not have permission to delete this poll.',
                ], 403);
            }

            QueuedPollDeleted::dispatch($queuedPoll->id, $queuedPoll->provider_id);

            $queuedPoll->delete();

            return response()->json([
                'success' => 'Poll deleted successfully.',
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => 'There was an error deleting the poll',
                'data' => $e->getMessage()
            ], 500);
        }
        
        return response()->json(
            [
                'success' => 'Poll deleted successfully'
            ]
        );
    }
}