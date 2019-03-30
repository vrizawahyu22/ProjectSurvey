<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use App\Member;
use Carbon\Carbon;
use Image;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Models\TokenManagement;
use Mail;
use Crypt;

class MemberController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Username'      => 'required',
            'Email'         => 'required|email',
            'Password'      => 'required',
            'C_password'    => 'required|same:Password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $cek_member         = Member::where('Username', $request->Username)->first();
    	if($cek_member){
    		return [
    			'error'     => 'Username telah ada',
    			'data'      => false
    		];
        }

        $cek_email          = Member::where('Email', $request->Email)->first();
        if($cek_email){
    		return [
    			'error'     => 'Email telah ada',
    			'data'      => false
    		];
        }

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
        $member->save();

        Mail::to($member->Email)->send(new VerifyEmail($member));

        return response()->json(['success' => 'Anda telah berhasil mendaftar, silahkan verifikasi email anda']);
    }

    public function verify()
    {
        if (empty(request('token'))) {
            // if token is not provided
            return response()->json(['error' => 'Anda harus verifikasi email']);
        }
        // descrypt token as email
        $decryptedEmail = Crypt::decrypt(request('token'));
        // find user by email
        $member = Member::whereEmail($decryptedEmail)->first();
        if ($member->status == 'activated') {
            // user is already active, do something
        }
        // otherwise change user status to "activated"
        $member->EmailStatus = 'activated';
        $member->save();
        
        return response()->json(['success' => 'Verifikasi email sukses'], 200);
    }

    public function login(Request $request)
    {   
    	$validate = Validator::make($request->all(), [
    		'Username' => 'required|string',
    		'Password' => 'required|string'
        ]);
        
    	if($validate->fails()){
    		//kalau ada salah input, tampilkan error dalam format json
    		return [
    			'error' => $validate->errors(),
    			'data' => false
    		];
    	}

    	//cek username
    	$cek_member = Member::where('Username', $request->Username)->first();
    	if(empty($cek_member)){
    		return [
    			'error' => 'Username not found',
    			'data' => false
    		];
    	}

        //cek password
    	if(!Hash::check($request->Password,$cek_member->Password)){
    		return [
    			'error' => 'Invalid password provided',
    			'data' => false
            ];  
        }
        
    	//setelah melewati semua filter diatas, artinya email dan password sudah benar. 
    	$token_instance = $this->registerToken($cek_member, $request->Username);
    	return [
    		'error' => false,
    		'data' => $token_instance
    	];
    }

    protected function registerToken(Member $member, $username)
    {
    	//generate custom hash sebagai auth token
    	$generated_token = base64_encode(sha1(rand(1, 10000) . uniqid() . time()));
    	//manage token ini akan expired dalam jangka waktu berapa lama
    	$expired = date('Y-m-d H:i:s', strtotime('+1 day'));

    	//proses simpan token ke database
    	$token_instance = new TokenManagement;
    	$token_instance->member_username = $username;
    	$token_instance->access_token = $generated_token;
    	$token_instance->expired_at = $expired;
    	$token_instance->is_active = 1;
    	$token_instance->save();

    	//setelah token direcord ke database, kembalikan nilai token ke response
    	return $token_instance;
    }

    protected function checkToken($token)
    {
        $pecah_token        = explode(" ", $token);
        $token_management   = TokenManagement::where('access_token', $pecah_token[1])->first();
        return $token_management->member_username;
    }

    public function lihatProfil(Request $request)
    {
        $username   = $this->checkToken($request->header('Authorization'));
        $member     = Member::find($username);
        $member->Uname = $username;
        return $member;
    }
    
    public function updateProfil(Request $request)
    {
        $username = $this->checkToken($request->header('Authorization'));

        $member             = Member::find($username);
        $member->Nama       = $request->Nama;
        $member->Password   = bcrypt($request->Password);
        $member->Alamat     = $request->Alamat;
        $member->Provinsi   = $request->Provinsi;
        $member->Kabupaten  = $request->Kabupaten;
        $member->Kecamatan  = $request->Kecamatan;
        $member->NoTelepon  = $request->NoTelepon;
        $member->Status     = $request->Status;
        $member->Profesi    = $request->Profesi;
        $member->save();

        return response()->json(['success' => 'Profil Berhasil di Update']);
    }
    
    public function upload(Request $request)
    {
        $path           = storage_path('app/public/images');

        //JIKA FOLDERNYA BELUM ADA
        if (!File::isDirectory($path)) {
            //MAKA FOLDER TERSEBUT AKAN DIBUAT
            File::makeDirectory($path);
        }

        //MENGAMBIL FILE IMAGE DARI FORM
        $file           = $request->file('Foto');
        
        //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
        $fileName       = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        Image::make($file)->save($path . '/' . $fileName);

        $username       = $this->checkToken($request->header('Authorization'));
        $member         = Member::find($username);
        $member->Foto   = $fileName;
        $member->save();
        return response()->json(['success' => 'Profil Berhasil di Update']);
    }
    
    public function logout(Request $request)
    {
        $token                          = $request->header('Authorization');
        $pecah_token                    = explode(" ", $token);
        $token_management               = TokenManagement::where('access_token', $pecah_token[1])->first();
        $token_management->is_active    = 0;
        $token_management->save();
        return response()->json(['success' => 'Anda Berhasil Logout']);
    }

    public function deleteAkun(Request $request)
    {
        $token                          = $request->header('Authorization');
        $pecah_token                    = explode(" ", $token);
        $token_management               = TokenManagement::where('access_token', $pecah_token[1])->first();
        $token_management->is_active    = 0;
        $token_management->save();

        $username   = $this->checkToken($request->header('Authorization'));
        $member     = Member::find($username);
        $member->delete();

        return response()->json(['success' => 'Akun Anda Telah Terhapus']);
    }
}