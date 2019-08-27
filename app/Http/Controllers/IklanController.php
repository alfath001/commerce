<?php

namespace App\Http\Controllers;

use App\Iklan;
use App\User;
use App\Komen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class IklanController extends Controller
{
    public function index(){
        $baru = DB::table('iklans')
                ->where('verifikasi', '=', 1)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();
        return view('home', compact('baru'));
    }

    public function buatIklan(){
    	return view('buatIklan');
    }

    public function store(Request $request){
    	$this->validate($request, [
            'judul' => 'required',
            'harga'=> 'required',
            'kategori' => 'required',
            'gambar' => 'image|mimes:jpeg,bmp,png|max:15000',
        ]);

        $userd = session("login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d");
       
        $una = DB::table('users')
                ->select('username')
                ->where('id', '=', $userd)
                ->first();
        
        $iklan = new Iklan;
        $iklan->user_id = $userd;
        $iklan->username = $una->username;

        $iklan->judul = $request->judul;
        $iklan->deskripsi = $request->deskripsi;
        $iklan->harga = $request->harga;
        $iklan->kategori = $request->kategori;

        if ($request->hasFile('gambar')) {
            $request->file('gambar');
            $ext = $request->gambar->extension();
            $nafile = 'iklan_'.time().'.'.$ext;
            $request->gambar->storeAs('public/iklan', $nafile);
        }else{
            $nafile = 'default.png';
        }

        $iklan->gambar = $nafile;
        $iklan->save();

        return redirect('home')->with('message', 'Iklan berhasil dibuat!');
    }

    public function show($kode)
    {
        $iklan = Iklan::find($kode);
        $iklan->komen = Iklan::find($kode)->komen;
        foreach ($iklan->komen as $komen) {
            $iklan->komen->user = Komen::find($komen->id)->user;
        }
        return view('detailIklan')->with('iklan', $iklan);
    }

    public function edit($kode)
    {
        $iklan = DB::table('iklans')->where('id', '=', $kode)->first();

        return view('editIklan')->with('iklan', $iklan);        
    }

    public function update(Request $request, $id)
    {
        if (isset($request->ver)) {
            DB::table('iklans')
            ->where('id', $id)
            ->update(['verifikasi' => $request->ver]);
            return redirect('admin')->with('message', 'Verifikasi Iklan berhasil!');   
        }elseif (isset($request->laku)) {
            DB::table('iklans')
            ->where('id', $id)
            ->update(['laku' => $request->laku]);
            return redirect('/myIklan')->with('message', 'Data berhasil di Edit!');
        }else{
            $this->validate($request, [
                'judul' => 'required',
                'harga'=> 'required',
                'kategori' => 'required',
                'gambar' => 'image|mimes:jpeg,bmp,png|max:2048',
            ]);

            if ($request->hasFile('gambar')) {
                $request->file('gambar');
                $ext = $request->gambar->extension();
                $nafile = 'iklan_'.time().'.'.$ext;
                $request->gambar->storeAs('public/iklan', $nafile);
                $gambar = $nafile;
            }else{
                $klan = DB::table('iklans')->where('id', '=', $id)->first();
                foreach ($klan as $klan) {
                    $gambar = $klan->gambar;
                }
            }

            $judul = $request->judul;
            $deskripsi = $request->deskripsi;
            $harga = $request->harga;
            $kategori = $request->kategori;
            
            DB::table('iklans')
            ->where('id', $id)
            ->update(['judul' => $judul, 'deskripsi' => $deskripsi, 'harga' => $harga, 'kategori' => $kategori, 'gambar' => $gambar]);

            return redirect('/myIklan')->with('message', 'Data berhasil di Edit!');
        }

    }

    public function my(){
        $userid = session("login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d");

        $iklan = DB::table('iklans')->where('user_id', '=', $userid)->get();
        return view('myIklan')->with('iklan', $iklan);
    }

    public function search(Request $request){
        $key = $request->get('key');
        $iklans = Iklan::where('verifikasi', '=', 1)->where('judul', 'like', '%'.$request->key.'%')->paginate(5);
        return view('home', compact('iklans', 'key')); 
    }

    public function kategori(Request $request){
        $key = $request->get('kat');
        $iklans = Iklan::where('verifikasi', '=', 1)->where('kategori', '=', $key)->paginate(5);
        return view('home', compact('iklans', 'key')); 
    }

    public function destroy($kode)
    {
        DB::table('iklans')->where('id', '=', $kode)->delete();
        
        if (Auth::user()->admin) {
            return redirect('/admin')->with('message', 'Iklan berhasil dihapus!');   
        }else{
            return redirect('/myIklan')->with('message', 'Iklan berhasil dihapus!');
        }
    }

    public function verikl()
    {
        $iklan = DB::table('iklans')->where('verifikasi', '=', 0)->get();
     
        return view('admin')->with('iklan', $iklan);
    }
}
