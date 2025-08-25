<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\District;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
// use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     use AuthenticatesUsers;

    public function index()
    {
        $user_profile = User::find(Auth::user()->id);

        // if (!empty($user_profile)) {
        //     $user_photo = Media::where('model_id', Auth::user()->id)->first();
        // } else {
        //     $user_photo = '';
        // }

        return view('profile.index-profile', compact('user_profile'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {

        $profile = User::find(Auth::user()->id);
        $user = Auth::user();
        if (auth()->user()->group_name == 'admin') {
            $groups = Group::select('id', 'group_name')->get();
        } else {
            $groups = Group::select('id', 'group_name')->where('id', '=', auth()->user()->group_id)->get();
        }
        
        // $groups = Group::select('id', 'group_name')->where('id', '=', auth()->user()->group_id)->get();
        

        $roles = Role::select(['id', 'name'])
            ->where('group_id', $user->group_id)
            ->get();
        

        return view('profile.create-profile', compact('groups', 'user', 'roles')); //compact('profile','user')
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->photo);
        $required = $request->form_type != 'Update Profile' ? 'required' : '';

        $validatedData =   $request->validate(
            [
                'first_name'    => 'required',
                'last_name'    => 'required',
                'cnic' => 'required',
                'phone' => 'required',
                'dob' => 'required',
                'address' => 'required',
                'photo'  => $required
            ],
            [
                'first_name.required' => 'Please Enter First Name',
                'last_name.required' => 'Please Enter Last Name',
                'photo.required' => 'Please Upload Image',
                'photo.image' => 'Please Upload a valid Image',
                'photo.mimes' => 'Please Upload a valid Image format (jpg, png, jpeg)',
                'photo.max' => 'Image size should be less than 2MB',
            ]
        );
        // if ($validator->fails()) {
        //     return redirect()->back()->with('error', $validator->errors());
        // }
        $data = [
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'dob' => $validatedData['dob'],
            'address' => $validatedData['address'],
            'cnic' => $validatedData['cnic'],
            'phone' => $validatedData['phone'] ?? ''
        ];

        // if ($request->form_type != 'Update Profile' || !empty($request->file('photo'))) {
        if ($request->file('photo') !== null && !empty($request->file('photo'))) {
            $user_id = Auth::user()->id;
            $media = Media::where('model_id', $user_id)->first();
            if ($media) {
                $media->delete();
            }
            $model_type = "User";
            fileUpload($request->file('photo'), $model_type, $user_id, $user_id, $request->created_by, null);
        }
        $user = User::updateOrCreate(['user_id' => Auth::user()->id], $data);
        // Redirect or return response
        // return response()->json(['message' => 'Success'], 200);
        return redirect('Profile/profile')->with('success', 'Updated Successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        $rules = [
            'name'   => 'required',
        ];

        $this->validate($request, $rules);

        $input = $request->all();

        $user = User::findOrFail($id);
        $user->update([
            'name'     => $input['name'],

        ]);

        return  redirect()->back()
            ->with('alert-success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ChangePassword()
    {

        $id = Auth::user()->id;

        $user_current_password = true;
        return view('profile.changepassword', compact('id' , 'user_current_password'));
    }
    public function update_user_password($id)
    {
        $user_current_password = false;
        return view('profile.changepassword', compact('id', 'user_current_password'));
    }
    public function UpdatePassword(Request $request)
    {

        $logout = false;

        $Id = Auth::user()->id;

        if ($request->has('user_old_password') && $request->user_old_password == 'true') {
            $request->validate(
                [
                    'old_password' => 'required',
                ],
                [
                    'old_password.required' => 'Please Enter Current Password',
                ]
            );
        }

        $request->validate(
            [
                'password' => 'required|confirmed',
            ],
            [
                'password.required' => 'Please Enter New Password',
                'password.confirmed' => 'New Password and Confirm Password Do Not Match',
            ]
        );

        $id = $request->user_id ?? Auth::user()->id;
        $user = User::find($id);


        // dd($user);

        if($Id == $user->id){
            $logout = true;
        }

        $user_current_password = true;

        if (isset($user->password) && !empty($user->password)) {
            if ($request->has('user_old_password') && $request->user_old_password == 'true') {
                if (Hash::check($request->old_password, $user->password)) {
                    $user->password = Hash::make($request->password);
                    $user->save();


                    if($logout)
                    {
                        $this->logout($request);
                    }elseif(!$logout)
                    {
                        session()->flash('success', 'Password Updated Successfully');
                        return redirect()->route('Profile.ChangePassword'); // Replace with your route name
                    }


                } else {
                    return redirect()->back()->with('error', 'Current password does not match');
                }
            } else {
                $user->password = Hash::make($request->password);
                $user->save();

                session()->flash('success', 'Password Updated Successfully');
                return redirect()->route('users.edit', $user->id); // Replace with your route name
            }
        }

        return redirect()->back()->with('error', 'Unable to update password');
    }

    public function logout(Request $request)
    {

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/login');
    }

}
