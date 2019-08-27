<?php

namespace App\Http\Controllers;

use App\User;
use App\Iklan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function index()
    {
        $members = User::paginate(7);

        return view('member', compact('members'));
    }

    public function show($id)
    {
        $user = User::find($id);
        
        $user->iklan = User::find($id)->iklan;
        return view('members')->with('member', $user);
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('editMember')->with('member', $user);        
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'tglLahir' => 'required|string',
            'noHP' => 'required|string|max:14',
            'profil' => 'image|mimes:jpeg,jpg,bmp,png|max:2048',
        ]);

         $user = User::find($id);

        if ($request->hasFile('profil')) {
            $request->file('profil');
            $ext = $request->profil->extension();
            $namafile = 'profil_'.time().'.'.$ext;
            $request->profil->storeAs('public/profil', $namafile);
            $user->foto = $namafile;
        }

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->tglLahir = $request->tglLahir;
        $user->noHP = $request->noHP;
        $user->save();

        return redirect('/member/'.session("login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d"))->with('message', 'Data berhasil di Edit!');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        DB::table('iklans')->where('user_id', '=', $id)->delete();

        return redirect('member')->with('message', 'Data berhasil dihapus!');
    }

}
