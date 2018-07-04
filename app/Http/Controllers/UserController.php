<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\User;

class UserController extends Controller
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
     * Check available uname.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUname(Request $request)
    {
        $rules=[
          'uname'=>["bail","required","regex:/^([a-zA-Z]+([\._@\-]?[0-9a-zA-Z]+)*){3,}$/","unique:users,uname","max:255"]
        ];
        $error_messages=[
          'uname.required'=>'Please enter Username',
          'uname.regex'=>'"Please enter a valid Username that contains only alphabets, numbers, . , @ , _ or -, and not less than 3 alphabets, and starts with at least one alphabet"',
          'uname.unique'=>'This Username is already taken, please enter another one',
          'uname.max'=>'Username must not be more than 255 characters'
        ];
        $validator= Validator::make($request->all(), $rules, $error_messages);
        if($validator->fails()){
          return json_encode(["state"=>"NOK","error"=>$validator->errors()->getMessages(),"code"=>422]);
        }
        return json_encode(["state"=>"OK"]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role==1){
          return view("user.add");
        }else{
          return view("errors.404");
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
      if(Auth::user()->role==1){
        $rules=[
          'name'=>["required","regex:/^[a-zA-Z\s_]+$/"],
          'uname'=>["bail","required","regex:/^([a-zA-Z]+([\._@\-]?[0-9a-zA-Z]+)*){3,}$/","unique:users,uname","max:255"],
          'password'=>['required','min:8','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
          'confirm_password'=>'required|min:8|same:password',
          'phone'=>["required","regex:/^(\+)?[0-9]{8,15}$/"],
          'role'=>["required","regex:/^(0|1)+$/"],
          'photo'=>'image|mimes:jpeg,png,jpg,gif'
        ];
        $error_messages=[
          'name.required'=>'Please enter User\'s Full Name',
          'name.regex'=>'Please enter a valid Name that contains only alphabets , spaces and _',
          'uname.required'=>'Please enter Username',
          'uname.regex'=>"Please enter a valid Username that contains only alphabets, numbers, . , @ , _ or -, and not less than 3 alphabets, and starts with at least one alphabet",
          'uname.unique'=>'This Username is already taken, please enter another one',
          'uname.max'=>'Username must not be more than 255 characters',
          'password.required'=>'Please enter a password',
          'password.min'=>'Password must be at least 8 characters',
          'password.regex'=>'Password must contain at least one Uppercase letter, one Lowercase letter, a number, and a special character (#,?,!,@,$,%,^,&,* or -)',
          'confirm_password.required'=>'Please re-type the password',
          'confirm_password.min'=>'Password must be at least 8 characters',
          'confirm_password.same'=>'Password Confirmation must be exactly the same as Password',
          'phone.required'=>'Please enter Phone No.',
          'phone.regex'=>'Please enter a valid Phone No. that contains only numbers and can start with a (+)',
          'role.required'=>'Please select a role',
          'role.regex'=>'Please select a valid role',
          'photo.image'=>'Please upload a valid photo that has png, jpg, jpeg or gif extensions',
          'photo.mimes'=>'Please upload a valid photo that has png, jpg, jpeg or gif extensions'
        ];
        $validator =Validator::make($request->all(), $rules, $error_messages);
        if($validator->fails()){
          return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = new User;
        $user->name= mb_strtolower($request->name);
        $user->uname=mb_strtolower($request->uname);
        $user->password=bcrypt($request->password);
        $user->phone=$request->phone;
        $user->role=$request->role;
        if ($request->hasFile("photo")) {
          $user->photo=$request->photo->store("user_profile");
        }
        $saved=$user->save();
        if(!$saved){
          return redirect()->back()->withInput()->with("error","A server error happened during creating a new user <br /> please try again later");
        }
        return redirect()->route("showUser",['id'=>$user->id])->with("success","User '$user->uname' Created Successfully");
      }else{
        return view("errors.404");
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      if(Auth::user()->id==$id || Auth::user()->role==1){
        $user = User::findOrFail($id);
        $data=[
          'user'=>$user
        ];
        return view("user.show",$data);
      }else{
        return view("errors.404");
      }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if(Auth::user()->role==1){
        $user= User::findOrFail($id);
        $data=[
          "user"=>$user
        ];
        return view("user.edit",$data);
      }else{
        return view("errors.404");
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      if(Auth::user()->role==1){
      }else{
        return view("errors.404");
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      if(Auth::user()->role==1){
      }else{
        return view("errors.404");
      }
    }
}
