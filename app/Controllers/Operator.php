<?php

namespace App\Controllers;

use App\Models\Pelayanan;
use App\Models\Otp;
use App\Models\User;
use App\Models\Opd;
use App\Models\Verifikator;
use App\Models\Peralatan;
use App\Models\Aula;
use App\Models\Sub;
use App\Models\Faq;

use App\Models\Tiket;

use App\Models\User_pembimbing;

class Operator extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    // -----------------------------------------------------------------------------------------------------------------------
    // Dashboard
    public function orders_user()
    {
        $db = \Config\Database::connect();

        if(session()->get('id_role')==1){
            $count_pembimbing = $db->table('ssuser')->where('ssuser.active', 1)->where('ssuser.role_id', 3)->where('ssuser.id_opd', session()->get('id_opd'))->countAllResults();
            $count_operator = $db->table('ssuser')->where('ssuser.active', 1)->where('ssuser.role_id', 1)->where('ssuser.id_opd', session()->get('id_opd'))->countAllResults();
            $count_magang = $db->table('tb_tiket_magang')->select('count(tb_tiket_magang.id_user)')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->where('ssuser.role_id', 4)->where('ssuser.active', 1)->where('tb_tiket_magang.id_opd', session()->get('id_opd'))->groupBy('ssuser.id_ssuser')->countAllResults();
            $count_all = $count_pembimbing + $count_operator + $count_magang;

            $data = array(
                'count_pembimbing' => $count_pembimbing,
                'count_operator' => $count_operator,
                'count_magang' => $count_magang,
                'count_all' => $count_all,
            );
        }elseif(session()->get('id_role')==2){
            $verifikator_pelayanan = $db->table('verifikator_pelayanan')->select('verifikator_pelayanan.id_pelayanan')->where('verifikator_pelayanan.id_user', session()->get('id_user'))->get()->getResult();
            $pelayanan = [];
            foreach ($verifikator_pelayanan as $row) {
                array_push($pelayanan, $row->id_pelayanan);
            }
            
            $instansi = $db->table('ssuser')->join('ssopd', 'ssuser.id_opd = ssopd.id_opd', 'left')->where('ssuser.id_ssuser', session()->get('id_user'))->get()->getRow();

            $data = array(
                'instansi' => $instansi->akronim_opd,
                'list_pelayanan' => $pelayanan,
                'count_pelayanan' => count($pelayanan),
            );
        } elseif(session()->get('id_role')==3){
            $instansi = $db->table('ssuser')->join('ssopd', 'ssuser.id_opd = ssopd.id_opd', 'left')->where('ssuser.id_ssuser', session()->get('id_user'))->get()->getRow();
            $ssuser_pembimbing = $db->table('ssuser_pembimbing')->where('ssuser_pembimbing.id_ssuser', session()->get('id_user'))->get()->getRow();
            $sub = null;
            if ($ssuser_pembimbing && !empty($ssuser_pembimbing->id_sub)) {
                $sub = $db->table('sub_bagian')->where('sub_bagian.id_sub', $ssuser_pembimbing->id_sub)->where('sub_bagian.active', 1)->get()->getRow();
            }

            $data = array(
                'instansi' => $instansi->akronim_opd ?? '-',
                'count_pelayanan' =>  $sub->nama_sub ?? '-',
            );
        }else{
            $user = $db->table('ssuser_magang')->where('ssuser_magang.id_ssuser', session()->get('id_user'))->get()->getRow();
            
            if($user->gender == 0){
                $gender = "Perempuan";
            }else{
                $gender = "Laki-laki";
            }

            if($user->jenis == 0){
                $jenis = "Siswa";
            }else{
                $jenis = "Mahasiswa";
            }

            $data = array(
                'gender' => $gender,
                'civitas' =>  $user->civitas,
                'jurusan' =>  $jenis,
            );
        }

        echo json_encode($data);
    }
    
    public function orders_tiket()
    {
        $tgl = date("Y-m-d H:i:s");
        
        $db = \Config\Database::connect();

        if(session()->get('id_role')==1){
            if($this->request->getVar('id')==0){
                $count_proses = $db->table('tb_tiket')->where("date_part('day', tb_tiket.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 0)->countAllResults();
                $count_selesai = $db->table('tb_tiket')->where("date_part('day', tb_tiket.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 1)->countAllResults();
                $count_tolak = $db->table('tb_tiket')->where("date_part('day', tb_tiket.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 2)->countAllResults();
                $count_semua = $db->table('tb_tiket')->where("date_part('day', tb_tiket.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->countAllResults();

            }elseif($this->request->getVar('id')==1){
                $count_proses = $db->table('tb_tiket')->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 0)->countAllResults();
                $count_selesai = $db->table('tb_tiket')->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 1)->countAllResults();
                $count_tolak = $db->table('tb_tiket')->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 2)->countAllResults();
                $count_semua = $db->table('tb_tiket')->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->countAllResults();

            }else{
                $count_proses = $db->table('tb_tiket')->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 0)->countAllResults(); 
                $count_selesai = $db->table('tb_tiket')->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 1)->countAllResults(); 
                $count_tolak = $db->table('tb_tiket')->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 2)->countAllResults(); 
                $count_semua = $db->table('tb_tiket')->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.id_user', session()->get('id_user'))->countAllResults(); 
            }
        }elseif(session()->get('id_role')==2){
            $verifikator_pelayanan = $db->table('verifikator_pelayanan')->select('verifikator_pelayanan.id_pelayanan')->where('verifikator_pelayanan.id_user', session()->get('id_user'))->get()->getResult();
            $pelayanan = [];
            foreach ($verifikator_pelayanan as $row) {
                array_push($pelayanan, $row->id_pelayanan);
            }
            
            // $test = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->countAllResults(); 
            // $test_list = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->get()->getResult(); 


            if($this->request->getVar('id')==0){
                $count_proses = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('day', tb_tiket.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.status', 0)->countAllResults();
                $count_selesai = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('day', tb_tiket.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.status', 1)->countAllResults();
                $count_tolak = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('day', tb_tiket.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.status', 2)->countAllResults();
                $count_semua = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('day', tb_tiket.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->countAllResults();

            }elseif($this->request->getVar('id')==1){
                $count_proses = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket.status', 0)->countAllResults();
                $count_selesai = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket.status', 1)->countAllResults();
                $count_tolak = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket.status', 2)->countAllResults();
                $count_semua = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))->countAllResults();

            }else{
                $count_proses = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.status', 0)->countAllResults(); 
                $count_selesai = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.status', 1)->countAllResults(); 
                $count_tolak = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket.status', 2)->countAllResults(); 
                $count_semua = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('year', tb_tiket.tgl_input)", date("Y", strtotime($tgl)))->countAllResults(); 
            }
        } elseif(session()->get('id_role')==3){
            if($this->request->getVar('id')==0){
                $count_proses = $db->table('tb_tiket_magang')->where("date_part('day', tb_tiket_magang.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 0)->countAllResults();
                $count_selesai = $db->table('tb_tiket_magang')->where("date_part('day', tb_tiket_magang.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 1)->countAllResults();
                $count_tolak = $db->table('tb_tiket_magang')->where("date_part('day', tb_tiket_magang.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 2)->countAllResults();
                $count_semua = $db->table('tb_tiket_magang')->where("date_part('day', tb_tiket_magang.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->countAllResults();

            }elseif($this->request->getVar('id')==1){
                $count_proses = $db->table('tb_tiket_magang')->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 0)->countAllResults();
                $count_selesai = $db->table('tb_tiket_magang')->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 1)->countAllResults();
                $count_tolak = $db->table('tb_tiket_magang')->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 2)->countAllResults();
                $count_semua = $db->table('tb_tiket_magang')->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->countAllResults();

            }else{
                $count_proses = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 0)->countAllResults(); 
                $count_selesai = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 1)->countAllResults(); 
                $count_tolak = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 2)->countAllResults(); 
                $count_semua = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->countAllResults(); 
            }
        } else{
            if($this->request->getVar('id')==0){
                $count_proses = $db->table('tb_tiket_magang')->where("date_part('day', tb_tiket_magang.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 0)->countAllResults();
                $count_selesai = $db->table('tb_tiket_magang')->where("date_part('day', tb_tiket_magang.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 1)->countAllResults();
                $count_tolak = $db->table('tb_tiket_magang')->where("date_part('day', tb_tiket_magang.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 2)->countAllResults();
                $count_semua = $db->table('tb_tiket_magang')->where("date_part('day', tb_tiket_magang.tgl_input)", date("d", strtotime($tgl)))->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->countAllResults();

            }elseif($this->request->getVar('id')==1){
                $count_proses = $db->table('tb_tiket_magang')->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 0)->countAllResults();
                $count_selesai = $db->table('tb_tiket_magang')->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 1)->countAllResults();
                $count_tolak = $db->table('tb_tiket_magang')->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 2)->countAllResults();
                $count_semua = $db->table('tb_tiket_magang')->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->countAllResults();

            }else{
                $count_proses = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 0)->countAllResults(); 
                $count_selesai = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 1)->countAllResults(); 
                $count_tolak = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 2)->countAllResults(); 
                $count_semua = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", date("Y", strtotime($tgl)))->where('tb_tiket_magang.id_user', session()->get('id_user'))->countAllResults(); 
            }
        }
        $data = array(
            'count_proses' => $count_proses,
            'count_selesai' => $count_selesai,
            'count_tolak' => $count_tolak,
            'count_semua' => $count_semua,
        );
        
        
        echo json_encode($data);
    }
    
    public function orders_tiket_tahunan()
    {
        $start = (int) $this->request->getVar('tahun');
        $end = $start - 2;

        $db = \Config\Database::connect();

        $tahun = [];
        $proses = [];
        $selesai = [];
        $tolak = [];
        $batal = [];

        $roleId = (int) session()->get('id_role');
        $pelayanan = [];
        if ($roleId === 2) {
            $verifikator_pelayanan = $db->table('verifikator_pelayanan')
                ->select('verifikator_pelayanan.id_pelayanan')
                ->where('verifikator_pelayanan.id_user', session()->get('id_user'))
                ->get()->getResult();
            foreach ($verifikator_pelayanan as $row) {
                $pelayanan[] = $row->id_pelayanan;
            }
        }

        for ($x = $end; $x <= $start; $x++) {
            $tahun[] = $x;

            if ($roleId === 1) {
                $base = $db->table('tb_tiket')->where("date_part('year', tb_tiket.tgl_input)", $x)->where('tb_tiket.id_user', session()->get('id_user'));
                $proses[] = (clone $base)->where('tb_tiket.status', 0)->countAllResults();
                $selesai[] = (clone $base)->where('tb_tiket.status', 1)->countAllResults();
                $tolak[] = (clone $base)->where('tb_tiket.status', 2)->countAllResults();
                $batal[] = (clone $base)->where('tb_tiket.status', 3)->countAllResults();
            } elseif ($roleId === 2) {
                if (empty($pelayanan)) {
                    $proses[] = 0; $selesai[] = 0; $tolak[] = 0; $batal[] = 0;
                } else {
                    $base = $db->table('tb_tiket')->whereIn('tb_tiket.id_pelayanan', $pelayanan)->where("date_part('year', tb_tiket.tgl_input)", $x);
                    $proses[] = (clone $base)->where('tb_tiket.status', 0)->countAllResults();
                    $selesai[] = (clone $base)->where('tb_tiket.status', 1)->countAllResults();
                    $tolak[] = (clone $base)->where('tb_tiket.status', 2)->countAllResults();
                    $batal[] = (clone $base)->where('tb_tiket.status', 3)->countAllResults();
                }
            } elseif ($roleId === 3) {
                $base = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", $x)->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'));
                $proses[] = (clone $base)->where('tb_tiket_magang.status', 0)->countAllResults();
                $selesai[] = (clone $base)->where('tb_tiket_magang.status', 1)->countAllResults();
                $tolak[] = (clone $base)->where('tb_tiket_magang.status', 2)->countAllResults();
                $batal[] = (clone $base)->where('tb_tiket_magang.status', 3)->countAllResults();
            } else {
                $base = $db->table('tb_tiket_magang')->where("date_part('year', tb_tiket_magang.tgl_input)", $x)->where('tb_tiket_magang.id_user', session()->get('id_user'));
                $proses[] = (clone $base)->where('tb_tiket_magang.status', 0)->countAllResults();
                $selesai[] = (clone $base)->where('tb_tiket_magang.status', 1)->countAllResults();
                $tolak[] = (clone $base)->where('tb_tiket_magang.status', 2)->countAllResults();
                $batal[] = (clone $base)->where('tb_tiket_magang.status', 3)->countAllResults();
            }
        }

        $data = array(
            'tahun' => $tahun,
            'proses' => $proses,
            'selesai' => $selesai,
            'tolak' => $tolak,
            'batal' => $batal,
        );

        echo json_encode($data);
    }

    public function orders_tiket_harian()
    {
        $tgl = date("Y-m-d H:i:s");
        $db = \Config\Database::connect();
        $roleId = (int) session()->get('id_role');

        if ($roleId === 1) {
            $count_semua = $db->table('tb_tiket')
                ->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))
                ->where('tb_tiket.id_user', session()->get('id_user'))
                ->get()->getResult();
        } elseif ($roleId === 2) {
            $verifikator_pelayanan = $db->table('verifikator_pelayanan')
                ->select('verifikator_pelayanan.id_pelayanan')
                ->where('verifikator_pelayanan.id_user', session()->get('id_user'))
                ->get()->getResult();
            $pelayanan = [];
            foreach ($verifikator_pelayanan as $row) {
                $pelayanan[] = $row->id_pelayanan;
            }

            if (empty($pelayanan)) {
                $count_semua = [];
            } else {
                $count_semua = $db->table('tb_tiket')
                    ->whereIn('tb_tiket.id_pelayanan', $pelayanan)
                    ->where("date_part('month', tb_tiket.tgl_input)", date("m", strtotime($tgl)))
                    ->get()->getResult();
            }
        } elseif ($roleId === 3) {
            $count_semua = $db->table('tb_tiket_magang')
                ->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))
                ->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))
                ->get()->getResult();
        } else {
            $count_semua = $db->table('tb_tiket_magang')
                ->where("date_part('month', tb_tiket_magang.tgl_input)", date("m", strtotime($tgl)))
                ->where('tb_tiket_magang.id_user', session()->get('id_user'))
                ->get()->getResult();
        }

        echo json_encode($count_semua);
    }

    public function orders_tiket_kalender()
    {
        $db = \Config\Database::connect();
        $roleId = (int) session()->get('id_role');
        $tgl = (string) $this->request->getVar('tgl');
        $month = date("m", strtotime($tgl));
        $year = date("Y", strtotime($tgl));

        if ($roleId === 1) {
            $builder = $db->table('tb_tiket')
                ->select('tb_tiket.id_tiket, tb_tiket.tgl_input as start, tb_tiket.tgl_input as end, sspelayanan.nama_pelayanan as description, ssopd.akronim_opd as title, tb_tiket.status, tb_tiket.status as color, sspelayanan.nama_pelayanan')
                ->join('sspelayanan', 'sspelayanan.id_pelayanan = tb_tiket.id_pelayanan', 'left')
                ->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')
                ->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')
                ->where('tb_tiket.id_user', session()->get('id_user'))
                ->where("date_part('month', tb_tiket.tgl_input)", $month)
                ->where("date_part('year', tb_tiket.tgl_input)", $year)
                ->get()->getResult();
        } elseif ($roleId === 2) {
            $verifikator_pelayanan = $db->table('verifikator_pelayanan')->select('id_pelayanan')->where('id_user', session()->get('id_user'))->get()->getResult();
            $pelayanan = [];
            foreach ($verifikator_pelayanan as $row) {
                $pelayanan[] = $row->id_pelayanan;
            }
            if (empty($pelayanan)) {
                echo json_encode([]);
                return;
            }
            $builder = $db->table('tb_tiket')
                ->select('tb_tiket.id_tiket, tb_tiket.tgl_input as start, tb_tiket.tgl_input as end, sspelayanan.nama_pelayanan as description, ssopd.akronim_opd as title, tb_tiket.status, tb_tiket.status as color, sspelayanan.nama_pelayanan')
                ->join('sspelayanan', 'sspelayanan.id_pelayanan = tb_tiket.id_pelayanan', 'left')
                ->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')
                ->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')
                ->whereIn('tb_tiket.id_pelayanan', $pelayanan)
                ->where("date_part('month', tb_tiket.tgl_input)", $month)
                ->where("date_part('year', tb_tiket.tgl_input)", $year)
                ->get()->getResult();
        } elseif ($roleId === 3) {
            $builder = $db->table('tb_tiket_magang')
                ->select('tb_tiket_magang.id_tiket, tb_tiket_magang.tgl_awal as start, tb_tiket_magang.tgl_akhir as end, "Magang" as description, ssuser.nama as title, tb_tiket_magang.status, tb_tiket_magang.status as color, "Magang" as nama_pelayanan')
                ->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')
                ->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))
                ->where("date_part('month', tb_tiket_magang.tgl_awal)", $month)
                ->where("date_part('year', tb_tiket_magang.tgl_awal)", $year)
                ->get()->getResult();
        } else {
            $builder = $db->table('tb_tiket_magang')
                ->select('tb_tiket_magang.id_tiket, tb_tiket_magang.tgl_awal as start, tb_tiket_magang.tgl_akhir as end, "Magang" as description, ssopd.akronim_opd as title, tb_tiket_magang.status, tb_tiket_magang.status as color, "Magang" as nama_pelayanan')
                ->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')
                ->where('tb_tiket_magang.id_user', session()->get('id_user'))
                ->where("date_part('month', tb_tiket_magang.tgl_awal)", $month)
                ->where("date_part('year', tb_tiket_magang.tgl_awal)", $year)
                ->get()->getResult();
        }

        foreach ($builder as $row) {
            if ($row->status == "0") {
                $row->color = "#3C75F0";
            } elseif ($row->status == "1") {
                $row->color = "#0F6B35";
            } elseif ($row->status == "2") {
                $row->color = "#ff0000";
            } else {
                $row->color = "#000000";
            }
        }

        echo json_encode($builder);
    }
}
