<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(){
        $list = Task::all();

        return view('task',compact('list'));
    }

    public function taskTable(){
        $list = Task::all();

        return view('table',compact('list'));
    }

    public function createTask(Request $request){
        $validator = validator($request->all(), [
            'task' => 'required|string',
            'target_date' => 'date|required'
        ])->setAttributeNames([
            'task' => trans('general.task'),
            'target_date' => trans('general.target_date')
        ]);;

        if(!$validator->fails()){
            Task::create([
                'task' => $request->get('task'),
                'target_date' => $request->get('target_date')
            ]);
            return response()->json(['status' => true]);
        }else{
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function changeTaskStatus(Request $request){
        $task_id = $request->get('task_id');
        $now_status_id = $request->get('now_status');

        if($now_status_id == 2){ // Waiting  -> Confirm
            $status_id = 1;
        }else if($now_status_id == 1){ // Confirm  -> Cancel
            $status_id = 0;
        }else if($now_status_id == 0){ // Cancel  -> Confirm
            $status_id = 1;
        }

        $update = Task::where('id',$task_id)->update([
            'status' => $status_id
        ]);

        if($update){
            return response()->json(['status' => $status_id]);
        }
    }

    public function editTask(Request $request){
        $task_id = $request->get('task_id');

        $task_info = Task::find($task_id);
        if($task_info){
            return response()->json(['status' => true,"data" => $task_info]);
        }
    }

    public function updateTask(Request $request){
        $task_id = $request->get('modal_task_id');
        $modal_task_date = $request->get('modal_task_date');
        $modal_task = $request->get('modal_task');

        if(Task::where('id',$task_id)->update(['target_date' => $modal_task_date,'task' => $modal_task])){
            return response()->json(['status' => true]);
        }
    }

    public function deleteTask(Request $request){
        $task_id = $request->get('task_id');

        $delete = Task::find($task_id)->delete();

        if($delete){
            return response()->json(['status' => true]);
        }
    }

}
