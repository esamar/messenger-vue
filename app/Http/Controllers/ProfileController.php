<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $user = auth()->user();
        $user->name = $request->name;
        
        if ( $request->password)
            $user->password = bcrypt($request->password);

        $file = $request->image;
        
        if ($file){
            $path = public_path('/users');
            $filename = time().".".$file->getClientOriginalExtension();    
            $moved = $file->move($path, $filename);
            $user->image = $filename;
        }

        $user->save();

        return back();

    }
}
