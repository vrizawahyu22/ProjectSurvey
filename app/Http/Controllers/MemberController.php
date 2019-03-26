<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use Carbon\Carbon;
use Image;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class MemberController extends Controller
{
    public function index()
    {
        return Member::all();
    }

    public function register(Request $request)
    {
        $member             = new Member;
        $member->Username   = $request->Username;
        $member->Nama       = $request->Nama;
        $member->Email      = $request->Email;
        $member->Password   = bcrypt($request->Password);
        $member->Alamat     = $request->Alamat;
        $member->Provinsi   = $request->Provinsi;
        $member->Kabupaten  = $request->Kabupaten;
        $member->Kecamatan  = $request->Kecamatan;
        $member->NoTelepon  = $request->NoTelepon;
        $member->Status     = $request->Status;
        $member->Profesi    = $request->Profesi;
        $member->Poin       = 0;
        $member->Foto       = $this->upload($request);
        $member->save();

        return response()->json(['Sukses' => 'Anda telah berhasil mendaftar']);
    }

    public function login(Request $request)
    {
        $username       = $request->Username;
        $password       = $request->Password;
        $data           = Member::where('Username',$username)->first();
        
        if($data){ //apakah email tersebut ada atau tidak
            if(Hash::check($password,$data->Password)){
                Session::put('Nama',$data->Nama);
                Session::put('Email',$data->Email);
                Session::put('Login',TRUE);
                return session()->all();
            }
            else{
                return response()->json(['Hasil'=>'Password Salah!']);
            }
        }
        else{
            return response()->json(['Hasil'=>'Username Salah!']);
        }
    }

    public function updateProfil(Request $request)
    {
        $member             = Member::find($request->Username);
        $member->Nama       = $request->Nama;
        $member->Password   = bcrypt($request->Password);
        $member->Alamat     = $request->Alamat;
        $member->Provinsi   = $request->Provinsi;
        $member->Kabupaten  = $request->Kabupaten;
        $member->Kecamatan  = $request->Kecamatan;
        $member->NoTelepon  = $request->NoTelepon;
        $member->Status     = $request->Status;
        $member->Profesi    = $request->Profesi;
        $member->Foto       = $this->upload($request);
        $member->save();

        return response()->json(['Sukses' => 'Profil Berhasil di Update']);
    }

    public function upload($request)
    {
        $path = storage_path('app/public/images');

        //JIKA FOLDERNYA BELUM ADA
        if (!File::isDirectory($path)) {
            //MAKA FOLDER TERSEBUT AKAN DIBUAT
            File::makeDirectory($path);
        }

        //MENGAMBIL FILE IMAGE DARI FORM
        $file = $request->file('Foto');
        
        //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        Image::make($file)->save($path . '/' . $fileName);
        return $fileName;
    }
    
    public function logout(){
        Session::flush();
        return response()->json(['Logout' => 'Anda Berhasil Logout']);
    }

    public function deleteAkun($username)
    {
        $member = Member::find($username);
        $member->delete();

        return response()->json(['Hapus' => 'Akun Anda Telah Terhapus']);
    }
}
