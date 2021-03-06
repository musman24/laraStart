<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->authorize('isAdmin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor'))
        {
            // for displaying the users
            return User::latest()->paginate(5);
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
        // for validating create user fields
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);

        // for creating the user
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // find the user in the table

        // for validating update user fields
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6'
        ]);

        $user->update($request->all());

        return ['message' => 'User Updated!'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if the user who sent this request is an Admin or not
        // $this->authorize('isAdmin');

        $user = User::findOrFail($id); // find the user in the table

        // delete the user
        $user->delete();

        return ['message' => 'User Deleted!'];
    }

    /**
     * Search the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        // if there is something in the query
        if ($search = \Request::get('q'))
        {
            $users = User::where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                      ->orWhere('email', 'LIKE', "%$search%")
                      ->orWhere('type', 'LIKE', "%$search%");
            })->paginate(5);
        }
        // if query is empty
        else
        {
            // get the top 5 users
            $users = User::latest()->paginate(5);
        }

        return $users;
    }

    /**
     * Return the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return auth('api')->user();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        // for validating update user fields
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:6'
        ]);

        $currentPhoto = $user->photo;
        if ($request->photo != $currentPhoto)
        {
            // generate unuique name for the file
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/').$name); // make the image from base64 string and save it
            $request->merge(['photo' => $name]); // update the photo
            
            $userPhoto = public_path('img/profile/').$currentPhoto; // name of photo on the server
            // if photo exists on the server
            if (file_exists($userPhoto))
            {
                @unlink($userPhoto); // delete photo
            }
        }

        // if password has to be updated
        if (!empty($request->password))
        {
            // encrypt the password
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
    }
}
