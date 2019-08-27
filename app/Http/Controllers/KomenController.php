<?php

namespace App\Http\Controllers;

use App\User;
use App\Iklan;
use App\Komen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KomenController extends Controller
{

    public function index()
    {
        $komen = Komen::all();

        // return view('member', ['member' => $user]);
    }

    public function store(Request $request){
        $this->validate($request, [
            'koment' => 'required',
        ]);

        $userd = session("login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d");
        
        $komen = new Komen;
        $komen->iklan_id = $request->kode_iklan;
        $komen->user_id = $userd;
        $komen->komentar = $request->koment;
        $komen->save();

        return redirect('/iklan/'.$request->kode_iklan.'/')->with('message', 'Komentar berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $komen = Komen::find($id);
        $komen->delete();

        return redirect()->back()->with('message', 'Komentar berhasil dihapus!');
    }

}
