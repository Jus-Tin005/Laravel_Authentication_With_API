<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{

    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return response()->json();
        // return Task::all();
        return TasksResource::collection(
            Task::where('user_id', Auth::user()->id)->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        $request->validated($request->all());

        $task = Task::create([
            'user_id' =>Auth::user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return new TasksResource($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        /* if(Auth::user()->id !== $task->user_id){
            return $this->error(' ', 'You are not authorized to make this request', 403);
        } */

        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) :  new TaskResource($task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {

        if(Auth::user()->id !== $task->user_id){
            return $this->error(' ', 'You are not authorized to make this request', 403);
        }

        $task->update($request->all());
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
       /*  $task->delete();
        return response(null, 204); */

        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : $task->delete();
    }

    private function isNotAuthorized($task){
        if(Auth::user()->id !== $task->user_id){
            return $this->error(' ', 'You are not authorized to make this request', 403);
        }
    }
}
