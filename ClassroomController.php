<?php

namespace App\Http\Controllers;


use App\Classroom;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;


class ClassroomController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'clname' => 'required|max:255',
        ], [
            'clname.required' => "Classroom name is required"
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $classroom = new Classroom();

            $classroom->cid = Uuid::generate()->string;
            $classroom->name = $request->clname;
            $classroom->tid = $request->session()->get('user')->tid;
            $classroom->save();
            $classes = $classroom->where('tid', '=', $request->session()->get('user')->tid)->get();

            $request->session()->put('selectedClass', $classroom->cid);
            $request->session()->put('classes', $classes);
//            return view('classroom')->with(['classes' => $classes, 'selectedClass' => $classroom->cid]);
            return redirect('/students');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom, Request $request)
    {
        //
        if($request->session()->get('user')) {
            $classes = $classroom->where('tid', '=', $request->session()->get('user')->tid)->get();
            if ((!$request->session()->exists('selectedClass')) && (count($classes))) {
                $request->session()->put('selectedClass', $classes->get(0)->cid);
            }
            $request->session()->put('classes', $classes);
            return view('home')->with(['classes' => $classes, 'selectedClass' => $request->session()->get('selectedClass')]);
        }
        return view('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function edit(Classroom $classroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classroom $classroom)
    {
        //
        $validator = Validator::make($request->all(), [
            'clname' => 'required|max:255',
        ], [
            'clname.required' => "Classroom name is required"
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $res = $classroom->where('cid', $request->selectedClass)->update(['name' => $request->clname]);
            $classes = $classroom->where('tid', '=', $request->session()->get('user')->tid)->get();
            $request->session()->put('classes', $classes);
            $selectedClass = $classroom->where([['tid', '=', $request->session()->get('user')->tid],['cid', '=', $request->selectedClass]])->pluck('cid');
            $request->session()->put('selectedClass', $selectedClass[0]);

            return view('classroom')->with(['classes' => $classes, 'selectedClass' => $selectedClass[0]]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom)
    {
        //
    }
}
