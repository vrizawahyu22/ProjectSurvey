<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\TokenManagement;
use App\Survey;
use App\Member;
use App\Pertanyaan;
class SurveyController extends Controller
{
    public function getSurvey($alamat = NULL)
    {
        if ($alamat == NULL)
        {
            $hasil = DB::table('surveys')
                ->join('members', 'surveys.Username', '=', 'members.Username')
                ->join('pertanyaans', 'pertanyaans.idSurvey', '=', 'surveys.idSurvey')
                ->select('surveys.*', 'members.Nama','pertanyaans.isiPertanyaan')
                ->get();

                return response()->json([
                    'success' => 'Berhasil Ditampilkan',
                    'data' => $hasil
                ], 200); 
        }
        else
        {
            $hasil = DB::table('surveys')
                ->join('members', 'surveys.Username', '=', 'members.Username')
                ->join('pertanyaans', 'pertanyaans.idSurvey', '=', 'surveys.idSurvey')
                ->select('surveys.*', 'members.Nama','pertanyaans.isiPertanyaan')
                ->where('surveys.Alamat','=', $alamat)
                ->get();

                return response()->json([
                    'success' => 'Berhasil Ditampilkan',
                    'data' => $hasil
                ], 200); 
        }
    }
    public function getSurveyMember($username = NULL, $idSurvey = NULL, Request $request)
    {
        $uname   = $this->checkToken($request->header('Authorization'));
        $member  = Member::find($uname);

        if ($username == NULL && $idSurvey == NULL){ //Melihat seluruh survey bagi Admin saja
            if ($member->Jenis == 'admin')
            {
                $hasil = DB::table('surveys')
                ->join('members', 'surveys.Username', '=', 'members.Username')
                ->join('pertanyaans', 'pertanyaans.idSurvey', '=', 'surveys.idSurvey')
                ->select('surveys.*', 'members.Nama','pertanyaans.isiPertanyaan')
                ->get();

                return response()->json([
                    'success' => 'Berhasil Ditampilkan',
                    'data' => $hasil
                ], 200); 
            }
            else
            {
                return response()->json(['error' => 'Permintaan Tidak Layak. Anda Bukan Administrator.'], 400);
            }
        }
        else if($idSurvey == NULL) //Melihat survey bagi akun tertentu
        {
            if ($member->Jenis == 'admin')
            {
                $hasil = DB::table('surveys')
                ->join('members', 'surveys.Username', '=', 'members.Username')
                ->join('pertanyaans', 'pertanyaans.idSurvey', '=', 'surveys.idSurvey')
                ->select('surveys.*', 'members.Nama','pertanyaans.*')
                ->where('members.Username','=', $username)
                ->get();

                return response()->json([
                    'success' => 'Berhasil Ditampilkan',
                    'data' => $hasil
                ], 200); 
            }
            else
            {
                if ($uname == $username)
                {
                    $hasil = DB::table('surveys')
                    ->join('members', 'surveys.Username', '=', 'members.Username')
                    ->join('pertanyaans', 'pertanyaans.idSurvey', '=', 'surveys.idSurvey')
                    ->select('surveys.*', 'members.Nama','pertanyaans.*')
                    ->where('members.Username','=', $username)
                    ->get();

                    return response()->json([
                        'success' => 'Berhasil Ditampilkan',
                        'data' => $hasil
                    ], 200); 
                }
                else
                {
                    return response()->json(['error' => 'Permintaan Tidak Layak. Anda Harus Login pada Akun Anda Sendiri.'], 400);
                }
            }
        }
        else  //Melihat survey bagi member tertentu dan survey tertentu bagi member dan admin
        { 
            if ($member->Jenis == 'admin')
            {
                $hasil = DB::table('surveys')
                ->join('members', 'surveys.Username', '=', 'members.Username')
                ->join('pertanyaans', 'pertanyaans.idSurvey', '=', 'surveys.idSurvey')
                ->select('surveys.*', 'members.Nama','pertanyaans.*')
                ->where('members.Username','=', $username)
                ->where('surveys.idSurvey','=', $idSurvey)
                ->get();

                return response()->json([
                    'success' => 'Berhasil Ditampilkan',
                    'data' => $hasil
                ], 200); 
            }
            else
            {
                if ($uname == $username)
                {
                    $hasil = DB::table('surveys')
                    ->join('members', 'surveys.Username', '=', 'members.Username')
                    ->join('pertanyaans', 'pertanyaans.idSurvey', '=', 'surveys.idSurvey')
                    ->select('surveys.*', 'members.Nama','pertanyaans.*')
                    ->where('members.Username','=', $username)
                    ->where('surveys.idSurvey','=', $idSurvey)
                    ->get();

                    return response()->json([
                        'success' => 'Berhasil Ditampilkan',
                        'data' => $hasil
                    ], 200); 
                }
                else
                {
                    return response()->json(['error' => 'Permintaan Tidak Layak. Anda Harus Login pada Akun Anda Sendiri.'], 400);
                }
            }
        }
    }

    public function makeSurvey(Request $request)
    {
        $username   = $this->checkToken($request->header('Authorization'));
        $idSurvey   = uniqid();
        DB::table('surveys')->insert([
            'IdSurvey' => $idSurvey,
            'Username' => $username,
            'Judul' => $request->Judul,
            'Kategori' => $request->Kategori,
            'Status' => "On",
            'Alamat' => uniqid()
        ]);
        
        for ($x = 0; $x < $request->Jumlah; $x++) {
            $pertanyaan = 'IsiPertanyaan'.$x;
            $tipe       = 'Tipe'.$x;
            $opsi       = 'Opsi'.$x;
            DB::table('pertanyaans')->insert([
                'IdSurvey'      => $idSurvey,
                'IsiPertanyaan' => $request->$pertanyaan,
                'Tipe'          => $request->$tipe,
                'Opsi'          => $request->$opsi
            ]);
        }
        return response()->json(['success' => 'Sukses Membuat Survey'], 200); 
    }

    public function editSurvey(Request $request, $idSurvey)
    {
        $username   = $this->checkToken($request->header('Authorization'));
        $member     = Member::find($username);
        $survey     = Survey::find($idSurvey);

        if($username == $survey->Username || $member->Jenis == 'admin')
        {
            $survey->Judul      = $request->Judul;
            $survey->Kategori   = $request->Kategori;
            $survey->Status     = $request->Status;
            $survey->save();

            $pertanyaans         = DB::table('pertanyaans')
                                ->where('IdSurvey','=', $idSurvey)
                                ->select('IdPertanyaan')
                                ->get();
            // return $pertanyaans;
            $i = 0;
            foreach($pertanyaans as $pertanyaans)
            {
                $idPertanyaan   = 'IdPertanyaan'.$i;
                $isiPertanyaan  = 'IsiPertanyaan'.$i;
                $tipe           = 'Tipe'.$i;
                $opsi           = 'Opsi'.$i;
                $pertanyaans    = Pertanyaan::find($request->$idPertanyaan);
                $pertanyaans->IsiPertanyaan = $request->$isiPertanyaan;
                $pertanyaans->Tipe          = $request->$tipe;
                $pertanyaans->Opsi          = $request->$opsi;
                $pertanyaans->save();
                $i+=1;
            }
            return response()->json(['success' => 'Sukses Memperbarui Survey'], 200);
        }
        else
        {
            return response()->json(['error' => 'Permintaan Tidak Layak. Anda Harus Login pada Akun Anda Sendiri.'], 400);
        }
    }

    public function hapusSurvey($idSurvey, Request $request)
    {
        $username   = $this->checkToken($request->header('Authorization'));
        $member     = Member::find($username);
        $survey     = Survey::find($idSurvey);

        if($survey->Username == $username || $member->Jenis == 'admin')
        {
            $pertanyaans    = DB::table('pertanyaans')
                    ->where('IdSurvey','=', $request->IdSurvey)
                    ->select('IdPertanyaan')
                    ->get();
            foreach($pertanyaans as $p)
            {
                $deletePertanyaan = Pertanyaan::find($p->IdPertanyaan);
                $deletePertanyaan->delete();
            }
            $survey->delete();
            return response()->json(['success' => 'Berhasil menghapus survey'], 200);
        }
        else{
            return response()->json(['error' => 'Method Not Allowed'], 405);
        }
    }

    public function isiSurvey($idSurvey, Request $request)
    {

    }
    protected function checkToken($token)
    {
        $pecah_token        = explode(" ", $token);
        $token_management   = TokenManagement::where('access_token', $pecah_token[1])->first();
        return $token_management->member_username;
    }
}
