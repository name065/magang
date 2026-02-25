<?php

namespace App\Controllers;

use App\Models\Tiket;
use App\Models\Log_tiket;
use App\Models\Log_tiket_magang;

use App\Models\Tiket_aula;
use App\Models\Tiket_subdomain;
use App\Models\Tiket_upload;
use App\Models\Tiket_hosting;
use App\Models\Tiket_tte;
use App\Models\Tiket_app;

use App\Models\Tiket_jaringan;
use App\Models\Tiket_jaringan_foto;

use App\Models\Tiket_alat;
use App\Models\Tiket_alat_list;
use App\Models\Tiket_zoom;

use App\Models\Tiket_magang;

class Form extends BaseController {
    public function __construct() {
        helper(['form', 'url']);
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function get_tiket(){
        $db = \Config\Database::connect();

        if(session()->get('id_role') == 0){
            if($this->request->getVar('status')==4){
                $builder = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, sspelayanan.route, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->orderBy('tb_tiket.id_tiket', 'DESC')->get()->getResult();
            }else{
                $builder = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, sspelayanan.route, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.status', $this->request->getVar('status'))->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->get()->getResult();
            }
        }else{
            if($this->request->getVar('status')==4){
                $builder = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, sspelayanan.route, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_user', session()->get('id_user'))->orderBy('tb_tiket.id_tiket', 'DESC')->get()->getResult();
            }else{
                $builder = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, sspelayanan.route, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', $this->request->getVar('status'))->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->get()->getResult();
            }
        }
        
        echo json_encode($builder);
    }

    public function get_count_tiket(){
        $db = \Config\Database::connect();

        if(session()->get('id_role') == 0){
            $proses = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.status', 0)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            $selesai = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.status', 1)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            $tolak = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.status', 2)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            $batal = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.status', 3)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            $semua = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            
        }else{
            $proses = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 0)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            $selesai = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 1)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            $tolak = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 2)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            $batal = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_user', session()->get('id_user'))->where('tb_tiket.status', 3)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            $semua = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_user', session()->get('id_user'))->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
            
        }
        
        $response = [
            'proses' => $proses,
            'selesai' => $selesai,
            'tolak' => $tolak,
            'batal' => $batal,
            'semua' => $semua,
        ];
        
        echo json_encode($response);
    }

    public function get_tiket_pelayanan() {
        $db = \Config\Database::connect();
        if($this->request->getVar('status')==4){
            $builder = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_pelayanan', $this->request->getVar('id_pelayanan'))->orderBy('tb_tiket.id_tiket', 'DESC')->get()->getResult();
        }else{
            $builder = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_pelayanan', $this->request->getVar('id_pelayanan'))->where('tb_tiket.status', $this->request->getVar('status'))->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->get()->getResult();
        }
        
        echo json_encode($builder);
    }

    public function get_count_tiket_pelayanan() {
        $db = \Config\Database::connect();
        $proses = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_pelayanan', $this->request->getVar('id_pelayanan'))->where('tb_tiket.status', 0)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
        $selesai = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_pelayanan', $this->request->getVar('id_pelayanan'))->where('tb_tiket.status', 1)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
        $tolak = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_pelayanan', $this->request->getVar('id_pelayanan'))->where('tb_tiket.status', 2)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
        $batal = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_pelayanan', $this->request->getVar('id_pelayanan'))->where('tb_tiket.status', 3)->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
        $semua = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_pelayanan', $this->request->getVar('id_pelayanan'))->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->countAllResults();
        
        $response = [
            'proses' => $proses,
            'selesai' => $selesai,
            'tolak' => $tolak,
            'batal' => $batal,
            'semua' => $semua,
        ];
        
        echo json_encode($response);
    }
    // -----------------------------------------------------------------------------------------------------------------------
    // Zoom
    public function zoom_page(){
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/zoom',$data);
    }
    public function add_zoom() {
        $db        = \Config\Database::connect();
        $tiketModel = new \App\Models\Tiket();
        $zoomModel  = new \App\Models\Tiket_zoom();
        $logModel   = new \App\Models\Log_tiket();

        $tgl = date("Y-m-d H:i:s");

        // ambil input
        $mulai  = date("Y-m-d H:i:s", strtotime($this->request->getVar('tgl_mulai')));
        $selesai = date("Y-m-d H:i:s", strtotime($this->request->getVar('tgl_akhir')));

        $is_aula = $this->request->getVar('is_aula') == "1";
        $is_alat = $this->request->getVar('is_alat') == "1";

        $id_aula = $is_aula ? (int)$this->request->getVar('myAula') : null;

        // myAlat dari JS bisa jadi "1,2,3" atau array ["1","2"]
        $alat_raw = $this->request->getVar('myAlat');
        if (is_array($alat_raw)) {
            $alat_ids = $alat_raw;
        } else {
            $alat_ids = explode(",", (string)$alat_raw);
        }
        $alat_ids = array_values(array_filter(array_map('trim', $alat_ids), fn($v) => $v !== ''));

        // =========================
        // TRANSACTION BEGIN
        // =========================
        $db->transBegin();

        try {
            // =========================
            // 0) CEK CONFLICT DULU (SEBELUM INSERT APAPUN)
            // =========================

            // helper overlap: (mulai < selesai_baru) AND (selesai > mulai_baru)
            // status tiket aktif: 0/1 (proses / disetujui). Tolak=2, batal=3 tidak dihitung bentrok.
            if ($is_aula) {
                $confAula = $db->query(
                    "SELECT 1
                    FROM tb_tiket_detail d
                    JOIN tb_tiket t ON t.id_tiket = d.id_tiket
                    WHERE d.tipe = 'aula'
                    AND t.status IN (0,1)
                    AND (d.detail->>'id_aula')::int = ?
                    AND d.mulai < ?
                    AND d.selesai > ?
                    LIMIT 1",
                    [$id_aula, $selesai, $mulai]
                )->getRowArray();

                if ($confAula) {
                    $db->transRollback();
                    return $this->response->setStatusCode(409)->setJSON([
                        'status' => 409,
                        'message' => 'Aula sudah dibooking di waktu tersebut. Silakan pilih jadwal / aula lain.'
                    ]);
                }
            }

            if ($is_alat && count($alat_ids) > 0) {
                // cek jika ada salah satu alat yang bentrok
                // d.detail->'alat_ids' adalah array json
                // cek intersect alat_ids (request) dengan alat_ids (existing)
                $confAlat = $db->query(
                    "SELECT 1
                    FROM tb_tiket_detail d
                    JOIN tb_tiket t ON t.id_tiket = d.id_tiket
                    WHERE d.tipe = 'alat'
                    AND t.status IN (0,1)
                    AND d.mulai < ?
                    AND d.selesai > ?
                    AND EXISTS (
                        SELECT 1
                        FROM jsonb_array_elements_text(d.detail->'alat_ids') x(id_alat_txt)
                        WHERE x.id_alat_txt = ANY(?)
                    )
                    LIMIT 1",
                    [$selesai, $mulai, $alat_ids]
                )->getRowArray();

                if ($confAlat) {
                    $db->transRollback();
                    return $this->response->setStatusCode(409)->setJSON([
                        'status' => 409,
                        'message' => 'Ada perlengkapan/alat yang sudah dibooking di waktu tersebut. Silakan ubah pilihan alat atau jadwal.'
                    ]);
                }
            }

            // =========================
            // 1) UPLOAD BERKAS (cek valid)
            // =========================
            $dataBerkas = $this->request->getFile('berkas');
            if (!$dataBerkas || !$dataBerkas->isValid()) {
                $db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 400,
                    'message' => 'File berkas tidak valid / tidak ada'
                ]);
            }

            // =========================
            // 2) INSERT TB_TIKET (sekali)
            // =========================
            $dataTiket = [
                'kode_tiket'   => $this->request->getVar('kode_zoom'),
                'tgl_input'    => $tgl,
                'id_pelayanan' => 4,
                'id_user'      => session()->get('id_user'),
                'status'       => 0,
            ];

            if (!$tiketModel->insert($dataTiket)) {
                $db->transRollback();
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 500,
                    'message' => 'Gagal insert tb_tiket',
                    'errors' => $tiketModel->errors(),
                ]);
            }
            $id_tiket = $tiketModel->getInsertID();

            // simpan file setelah punya id_tiket
            $fileName = md5($id_tiket) . "." . $dataBerkas->guessExtension();
            $fileName = str_replace(" ", "", $fileName);
            $dataBerkas->move('./public/assets/berkas/surat-pengantar', $fileName);

            // =========================
            // 3) INSERT ZOOM (sekali)
            // =========================
            $dataZoom = [
                'id_pelayanan_zoom' => 4, // penting untuk t_db
                'id_tiket' => $id_tiket,
                'nama_acara' => $this->request->getVar('acara'),
                'tgl_awal' => $mulai,
                'tgl_akhir' => $selesai,
                'nama_pic' => $this->request->getVar('nama_pic'),
                'no_pic' => $this->request->getVar('nomor_pic'),
                'jenis_zoom' => $this->request->getVar('jenis'),
                'meeting_id' => $this->request->getVar('meeting_id'),
                'passcode' => $this->request->getVar('passcode'),
                'tempat' => $this->request->getVar('tempat'),
                'operator' => $this->request->getVar('is_operator'),
                'berkas_pengantar' => $fileName,
            ];

            if (!$zoomModel->insert($dataZoom)) {
                $db->transRollback();
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 500,
                    'message' => 'Gagal insert tiket zoom',
                    'errors' => $zoomModel->errors(),
                ]);
            }

            // log zoom
            $logModel->insert([
                'id_tiket' => $id_tiket,
                'id_user' => session()->get('id_user'),
                'tgl_aktifitas' => $tgl,
                'aktifitas' => "Membuat tiket zoom",
                'color' => "warning",
                'icon' => "fas fa-video",
            ]);

            // =========================
            // 4) OPTIONAL: INSERT DETAIL AULA (tetap id_tiket yang sama)
            // =========================
            if ($is_aula) {
                $db->table('tb_tiket_detail')->insert([
                    'id_tiket' => $id_tiket,
                    'tipe'     => 'aula',
                    'judul'    => $this->request->getVar('acara'),
                    'mulai'    => $mulai,
                    'selesai'  => $selesai,
                    'detail'   => json_encode([
                        'id_aula' => $id_aula,
                        'nama_pic' => $this->request->getVar('nama_pic'),
                        'no_pic'   => $this->request->getVar('nomor_pic'),
                        'berkas_pengantar' => $fileName,
                    ]),
                ]);

                $logModel->insert([
                    'id_tiket' => $id_tiket,
                    'id_user' => session()->get('id_user'),
                    'tgl_aktifitas' => $tgl,
                    'aktifitas' => "Menambahkan peminjaman aula",
                    'color' => "warning",
                    'icon' => "fas fa-building",
                ]);
            }

            // =========================
            // 5) OPTIONAL: INSERT DETAIL ALAT (tetap id_tiket yang sama)
            // =========================
            if ($is_alat && count($alat_ids) > 0) {
                $db->table('tb_tiket_detail')->insert([
                    'id_tiket' => $id_tiket,
                    'tipe'     => 'alat',
                    'judul'    => $this->request->getVar('acara'),
                    'mulai'    => $mulai,
                    'selesai'  => $selesai,
                    'detail'   => json_encode([
                        'alat_ids' => $alat_ids,
                        'nama_pic' => $this->request->getVar('nama_pic'),
                        'no_pic'   => $this->request->getVar('nomor_pic'),
                        'berkas_pengantar' => $fileName,
                    ]),
                ]);

                $logModel->insert([
                    'id_tiket' => $id_tiket,
                    'id_user' => session()->get('id_user'),
                    'tgl_aktifitas' => $tgl,
                    'aktifitas' => "Menambahkan peminjaman peralatan",
                    'color' => "warning",
                    'icon' => "fas fa-tools",
                ]);
            }

            // =========================
            // COMMIT
            // =========================
            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 500,
                    'message' => 'Transaksi gagal (transStatus false)'
                ]);
            }

            $db->transCommit();

            // notif belakangan (kalau notif gagal, tidak merusak transaksi DB)
            $this->notification(4);
            if ($is_aula) $this->notification(6);
            if ($is_alat) $this->notification(13);

            return $this->response->setJSON([
                'status' => 200,
                'message' => "Tiket Berhasil Dibuat.",
                'id_tiket' => $id_tiket
            ]);
        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 500,
                'message' => 'Terjadi error server: ' . $e->getMessage()
            ]);
        }
    }

    public function add_zoom_calendar() {
        $db = \Config\Database::connect();
        // $builder = $db->table('tb_tiket_aula')->select('tb_tiket.id_tiket, tb_tiket_aula.tgl_awal as start, tb_tiket_aula.tgl_akhir as end, tb_tiket_aula.nama_acara as description, ssopd.akronim_opd as title, tb_tiket.status, tb_tiket.status as color, ssaula.nama_aula')->join('tb_tiket', 'tb_tiket.id_tiket = tb_tiket_aula.id_tiket', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->join('ssaula', 'ssaula.id_aula = tb_tiket_aula.id_aula', 'left')->where("date_part('month', tb_tiket_aula.tgl_awal)", date("m", strtotime($this->request->getVar('tgl'))))->where("date_part('year', tb_tiket_aula.tgl_awal)", date("Y", strtotime($this->request->getVar('tgl'))))->get()->getResult();
        
        // foreach ($builder as $row)
        // {
        //     if($row->status=="0"){
        //         $row->color = "#2B9DDE";
        //     }elseif($row->status=="1"){
        //         $row->color = "#2BDE77";
        //     }elseif($row->status=="2"){
        //         $row->color = "#DE2B2B";
        //     }else{
        //         $row->color = "#C9C9C9";
        //     }
        // }

        $builder_zoom = $db->table('tb_tiket_zoom')->select('tb_tiket.id_tiket, tb_tiket_zoom.tgl_awal as start, tb_tiket_zoom.tempat as nama_aula, tb_tiket_zoom.tgl_akhir as end, tb_tiket_zoom.nama_acara as description, ssopd.akronim_opd as title, tb_tiket.status, tb_tiket.status as color')->join('tb_tiket', 'tb_tiket.id_tiket = tb_tiket_zoom.id_tiket', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where("date_part('month', tb_tiket_zoom.tgl_awal)", date("m", strtotime($this->request->getVar('tgl'))))->where("date_part('year', tb_tiket_zoom.tgl_awal)", date("Y", strtotime($this->request->getVar('tgl'))))->get()->getResult();
        // array_merge_recursive
        foreach ($builder_zoom as $row)
        {
            if($row->status=="0"){
                $row->color = "#2B9DDE";
            }elseif($row->status=="1"){
                $row->color = "#2BDE77";
            }elseif($row->status=="2"){
                $row->color = "#DE2B2B";
            }else{
                $row->color = "#C9C9C9";
            }
        }

        $response = [
            'status' => $this->request->getVar('tgl'),
            'message' => $builder_zoom
        ];
        echo json_encode($builder_zoom);

    }

    // -----------------------------------------------------------------------------------------------------------------------
    // AULA

    public function aula_page(){
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/aula',$data);
    }

    public function add_aula(){
        // ==== ambil input & normalisasi tanggal ====
        $idAula   = $this->request->getVar('myAula');
        $mulai    = date("Y-m-d H:i:s", strtotime($this->request->getVar('tgl_mulai')));
        $selesai  = date("Y-m-d H:i:s", strtotime($this->request->getVar('tgl_akhir')));

        // validasi sederhana
        if (strtotime($selesai) <= strtotime($mulai)) {
            return $this->response->setJSON([
                'status'  => 422,
                'message' => 'Tanggal/jam selesai harus lebih besar dari mulai.'
            ]);
        }

        // ==== CEK TABRAKAN (overlap) ====
        // overlap jika: existing_mulai < request_selesai AND existing_selesai > request_mulai
        $db = \Config\Database::connect();
        $builder = $db->table('tb_tiket_aula a');
        $builder->select('a.id_tiket');
        $builder->join('tb_tiket t', 't.id_tiket = a.id_tiket');
        $builder->where('a.id_aula', $idAula);
        $builder->where('a.tgl_awal <', $selesai);
        $builder->where('a.tgl_akhir >', $mulai);

        // blokir hanya tiket yang masih "mengunci jadwal"
        // asumsi umum: 0=menunggu, 1=disetujui, 2=ditolak (sesuaikan kalau beda)
        $builder->whereIn('t.status', [0, 1]);

        $bentrok = $builder->countAllResults();

        if ($bentrok > 0) {
            return $this->response->setJSON([
                'status'  => 409,
                'message' => 'Aula sudah dipakai / ada pengajuan lain di tanggal dan jam tersebut.'
            ]);
        }

        // ==== kalau aman, lanjut kode kamu yang lama ====
        $userModel = new Tiket();

        $tgl = date("Y-m-d H:i:s");
        $data = [
            'kode_tiket' => $this->request->getVar('kode'),
            'tgl_input'    => $tgl,
            'id_pelayanan'  => 6,
            'id_user'       => session()->get('id_user'),
            'status'        => 0,
        ];

        $userModel->insert($data);
        $id = $userModel->getInsertID();

        $aulaModel = new Tiket_aula();

        $dataBerkas = $this->request->getFile('berkas');
        $fileName = md5($id).".".$dataBerkas->guessExtension();
        $fileName = str_replace(" ","",$fileName);
        $dataBerkas->move('./public/assets/berkas/surat-pengantar',$fileName);

        $data = [
            'id_tiket' => $id,
            'nama_acara' => $this->request->getVar('acara'),
            'tgl_awal' => $mulai,
            'tgl_akhir' => $selesai,
            'id_aula' => $idAula,
            'nama_pic' => $this->request->getVar('nama_pic'),
            'no_pic' => $this->request->getVar('nomor_pic'),
            'berkas_pengantar' => $fileName,
        ];

        $aulaModel->insert($data);

        $logModel = new Log_tiket();
        $data = [
            'id_tiket' => $id,
            'id_user' => session()->get('id_user'),
            'tgl_aktifitas' =>  $tgl,
            'aktifitas' => "Membuat tiket",
            'color' => "warning",
            'icon' => "fas fa-ticket-alt",
        ];

        $logModel->insert($data);


        $response = [
            'status' => 200,
            'message' => "Tiket Berhasil Dibuat."
        ];

        // NOTIFIKASI
        $this->notification(6);
        
        echo json_encode($response);
    }

    public function add_aula_calendar(){
        $db = \Config\Database::connect();
        // $builder = $db->table('tb_tiket_aula')->join('tb_tiket', 'tb_tiket.id_tiket = tb_tiket_aula.id_tiket', 'left')->get()->getResult();
        $builder = $db->table('tb_tiket_aula')->select('tb_tiket.id_tiket, tb_tiket_aula.tgl_awal as start, tb_tiket_aula.tgl_akhir as end, tb_tiket_aula.nama_acara as description, ssopd.akronim_opd as title, tb_tiket.status, tb_tiket.status as color, ssaula.nama_aula')->join('tb_tiket', 'tb_tiket.id_tiket = tb_tiket_aula.id_tiket', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->join('ssaula', 'ssaula.id_aula = tb_tiket_aula.id_aula', 'left')->where("date_part('month', tb_tiket_aula.tgl_awal)", date("m", strtotime($this->request->getVar('tgl'))))->where("date_part('year', tb_tiket_aula.tgl_awal)", date("Y", strtotime($this->request->getVar('tgl'))))->get()->getResult();
        
        foreach ($builder as $row)
        {
            if($row->status=="0"){
                $row->color = "#2B9DDE";
            }elseif($row->status=="1"){
                $row->color = "#2BDE77";
            }elseif($row->status=="2"){
                $row->color = "#DE2B2B";
            }else{
                $row->color = "#C9C9C9";
            }
        }
        $response = [
            'status' => $this->request->getVar('tgl'),
            'message' => $builder
        ];
        echo json_encode($builder);

    }

    // -----------------------------------------------------------------------------------------------------------------------
    // Subdomain
    public function subdomain_page(){
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/sub-domain',$data);
    }

    public function add_subdomain() {
        $tiketModel = new Tiket();
        $tgl = date("Y-m-d H:i:s");

        // 1) Insert header tiket
        $tiketModel->insert([
            'kode_tiket'   => $this->request->getVar('kode'),
            'tgl_input'    => $tgl,
            'id_pelayanan' => 5,
            'id_user'      => session()->get('id_user'),
            'status'       => 0,
        ]);

        $id = $tiketModel->getInsertID();

        // 2) Upload berkas
        $dataBerkas = $this->request->getFile('berkas');
        $fileName = md5($id) . "." . $dataBerkas->guessExtension();
        $fileName = str_replace(" ", "", $fileName);
        $dataBerkas->move('./public/assets/berkas/surat-pengantar', $fileName);

        // 3) Insert detail subdomain langsung ke tb_tiket_detail (JSONB)
        $db = \Config\Database::connect();
        $builder = $db->table('tb_tiket_detail');

        $builder->insert([
            'id_tiket' => $id,
            'tipe'     => 'subdomain',
            'judul'    => $this->request->getVar('subdomain'), // nama_subdomain disimpan di judul
            'mulai'    => null,
            'selesai'  => null,
            'detail'   => json_encode([
                'nama_subdomain'    => $this->request->getVar('subdomain'),
                'ip_publik'         => $this->request->getVar('ip'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $fileName,
            ]),
        ]);

        // 4) Log tiket
        $logModel = new Log_tiket();
        $logModel->insert([
            'id_tiket' => $id,
            'id_user' => session()->get('id_user'),
            'tgl_aktifitas' => $tgl,
            'aktifitas' => "Membuat tiket",
            'color' => "warning",
            'icon' => "fas fa-ticket-alt",
        ]);

        // NOTIFIKASI
        $this->notification(5);

        return $this->response->setJSON([
            'status' => 200,
            'message' => "Tiket Berhasil Dibuat."
        ]);
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // Upload Dokumen
    
    public function upload_page() {
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/upload',$data);
    }

    public function add_upload()
    {
        $userModel = new Tiket();
        $tgl = date("Y-m-d H:i:s");

        // 1) Header tiket
        $userModel->insert([
            'kode_tiket'   => $this->request->getVar('kode'),
            'tgl_input'    => $tgl,
            'id_pelayanan' => 7,
            'id_user'      => session()->get('id_user'),
            'status'       => 0,
        ]);

        $id = $userModel->getInsertID();

        // 2) Upload surat pengantar
        $berkas = $this->request->getFile('berkas');
        if (!$berkas || !$berkas->isValid()) {
            return $this->response->setJSON([
                'status'  => 422,
                'message' => 'Berkas surat pengantar wajib diupload.'
            ]);
        }

        $filePengantar = md5($id . '_pengantar') . "." . $berkas->guessExtension();
        $filePengantar = str_replace(" ", "", $filePengantar);
        $berkas->move('./public/assets/berkas/surat-pengantar', $filePengantar);

        // 3) Upload dokumen utama
        $dokumen = $this->request->getFile('dokumen');
        if (!$dokumen || !$dokumen->isValid()) {
            return $this->response->setJSON([
                'status'  => 422,
                'message' => 'Dokumen yang akan diupload wajib diupload.'
            ]);
        }

        $fileDokumen = md5($id . '_dokumen') . "." . $dokumen->guessExtension();
        $fileDokumen = str_replace(" ", "", $fileDokumen);
        $dokumen->move('./public/assets/berkas/upload', $fileDokumen);

        // 4) Insert detail ke tb_tiket_detail (JSONB)
        $db = \Config\Database::connect();
        $db->table('tb_tiket_detail')->insert([
            'id_tiket' => $id,
            'tipe'     => 'upload',
            'judul'    => $this->request->getVar('jenis') ?? 'Upload Dokumen',
            'mulai'    => null,
            'selesai'  => null,
            'detail'   => json_encode([
                'edisi'             => $this->request->getVar('edisi'),
                'jenis_dokumen'     => $this->request->getVar('jenis'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $filePengantar,
                'berkas_upload'     => $fileDokumen,
            ]),
        ]);

        // 5) Log
        $logModel = new Log_tiket();
        $logModel->insert([
            'id_tiket' => $id,
            'id_user' => session()->get('id_user'),
            'tgl_aktifitas' => $tgl,
            'aktifitas' => "Membuat tiket",
            'color' => "warning",
            'icon' => "fas fa-ticket-alt",
        ]);

        // NOTIFIKASI
        $this->notification(7);

        return $this->response->setJSON([
            'status'  => 200,
            'message' => "Tiket Berhasil Dibuat."
        ]);
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // Hosting
    
    public function hosting_page(){
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/hosting',$data);
    }

    public function add_hosting(){
        $tiketModel = new Tiket();
        $tgl = date("Y-m-d H:i:s");

        // 1) insert header tiket
        $tiketModel->insert([
            'kode_tiket'   => $this->request->getVar('kode'),
            'tgl_input'    => $tgl,
            'id_pelayanan' => 8,
            'id_user'      => session()->get('id_user'),
            'status'       => 0,
        ]);
        $id = $tiketModel->getInsertID();

        // 2) upload berkas
        $dataBerkas = $this->request->getFile('berkas');
        $fileName = md5($id) . "." . $dataBerkas->guessExtension();
        $fileName = str_replace(" ", "", $fileName);
        $dataBerkas->move('./public/assets/berkas/surat-pengantar', $fileName);

        // 3) simpan detail hosting ke tb_tiket_detail (jsonb)
        $db = \Config\Database::connect();
        $db->table('tb_tiket_detail')->insert([
            'id_tiket' => $id,
            'tipe'     => 'hosting',
            'judul'    => $this->request->getVar('nama'), // nama aplikasi jadi judul
            'mulai'    => null,
            'selesai'  => null,
            'detail'   => json_encode([
                'nama_aplikasi'     => $this->request->getVar('nama'),
                'deskripsi'         => $this->request->getVar('deskripsi'),
                'spesifikasi'       => $this->request->getVar('spesifikasi'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $fileName,

                // biar view detail kamu tetap aman walau belum ada inputnya
                'port'              => $this->request->getVar('port') ?? '',
                'db_access'         => $this->request->getVar('db_access') ?? '',
                'server_access'     => $this->request->getVar('server_access') ?? '',
            ]),
        ]);

        // 4) log
        $logModel = new Log_tiket();
        $logModel->insert([
            'id_tiket' => $id,
            'id_user' => session()->get('id_user'),
            'tgl_aktifitas' => $tgl,
            'aktifitas' => "Membuat tiket",
            'color' => "warning",
            'icon' => "fas fa-ticket-alt",
        ]);

        // NOTIFIKASI
        $this->notification(8);

        return $this->response->setJSON([
            'status' => 200,
            'message' => "Tiket Berhasil Dibuat."
        ]);
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // TTE
    public function tte_page(){
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/tte',$data);
    }

    public function add_tte(){
        $tiketModel = new Tiket();
        $tgl = date("Y-m-d H:i:s");

        // 1) Insert header tiket
        $tiketModel->insert([
            'kode_tiket'   => $this->request->getVar('kode'),
            'tgl_input'    => $tgl,
            'id_pelayanan' => 9,
            'id_user'      => session()->get('id_user'),
            'status'       => 0,
        ]);

        $id = $tiketModel->getInsertID();

        // 2) Upload surat pengantar
        $berkas = $this->request->getFile('berkas');
        if (!$berkas || !$berkas->isValid()) {
            return $this->response->setJSON([
                'status' => 422,
                'message' => 'Berkas surat pengantar wajib diupload.'
            ]);
        }
        $filePengantar = md5($id . '_pengantar') . "." . $berkas->guessExtension();
        $filePengantar = str_replace(" ", "", $filePengantar);
        $berkas->move('./public/assets/berkas/surat-pengantar', $filePengantar);

        // 3) Upload KTP
        $ktp = $this->request->getFile('ktp');
        if (!$ktp || !$ktp->isValid()) {
            return $this->response->setJSON([
                'status' => 422,
                'message' => 'Berkas KTP wajib diupload.'
            ]);
        }
        $fileKtp = md5($id . '_ktp') . "." . $ktp->guessExtension();
        $fileKtp = str_replace(" ", "", $fileKtp);
        $ktp->move('./public/assets/berkas/ktp', $fileKtp);

        // 4) Insert detail TTE ke tb_tiket_detail (JSONB)
        $db = \Config\Database::connect();
        $db->table('tb_tiket_detail')->insert([
            'id_tiket' => $id,
            'tipe'     => 'tte',
            // judul bebas: biar enak dibaca di list
            'judul'    => $this->request->getVar('nama') ?? 'Pengajuan TTE',
            'mulai'    => null,
            'selesai'  => null,
            'detail'   => json_encode([
                'jenis_layanan'     => $this->request->getVar('jenis'),
                'nama'              => $this->request->getVar('nama'),
                'jabatan'           => $this->request->getVar('jabatan'),
                'nip'               => $this->request->getVar('nip'),
                'nik'               => $this->request->getVar('nik'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $filePengantar,
                'berkas_ktp'        => $fileKtp,
            ]),
        ]);

        // 5) Log
        $logModel = new Log_tiket();
        $logModel->insert([
            'id_tiket' => $id,
            'id_user' => session()->get('id_user'),
            'tgl_aktifitas' => $tgl,
            'aktifitas' => "Membuat tiket",
            'color' => "warning",
            'icon' => "fas fa-ticket-alt",
        ]);

        // NOTIFIKASI
        $this->notification(9);

        return $this->response->setJSON([
            'status' => 200,
            'message' => "Tiket Berhasil Dibuat."
        ]);
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // APP
    public function app_page(){
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/app',$data);
    }

    public function add_app(){
        $userModel = new Tiket();
        $tgl = date("Y-m-d H:i:s");

        // 1) Header tiket
        $userModel->insert([
            'kode_tiket'   => $this->request->getVar('kode'),
            'tgl_input'    => $tgl,
            'id_pelayanan' => 10,
            'id_user'      => session()->get('id_user'),
            'status'       => 0,
        ]);
        $id = $userModel->getInsertID();

        // 2) Upload surat pengantar
        $dataBerkas = $this->request->getFile('berkas');
        if (!$dataBerkas || !$dataBerkas->isValid()) {
            return $this->response->setJSON([
                'status'  => 422,
                'message' => 'Berkas surat pengantar wajib diupload.'
            ]);
        }

        $fileName = md5($id . '_pengantar') . "." . $dataBerkas->guessExtension();
        $fileName = str_replace(" ", "", $fileName);
        $dataBerkas->move('./public/assets/berkas/surat-pengantar', $fileName);

        // 3) Insert detail ke tb_tiket_detail (JSONB)
        $db = \Config\Database::connect();
        $db->table('tb_tiket_detail')->insert([
            'id_tiket' => $id,
            'tipe'     => 'app', // konsisten untuk detail page
            'judul'    => $this->request->getVar('nama') ?? 'Pendampingan Aplikasi',
            'mulai'    => null,
            'selesai'  => null,
            'detail'   => json_encode([
                'nama_aplikasi'       => $this->request->getVar('nama'),
                'deskripsi_aplikasi'  => $this->request->getVar('deskripsi'),
                'tgl'                 => $this->request->getVar('tgl'),
                'tempat'              => $this->request->getVar('tempat'),
                'agenda'              => $this->request->getVar('agenda'),
                'nama_pic'            => $this->request->getVar('nama_pic'),
                'no_pic'              => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'    => $fileName,
            ]),
        ]);

        // 4) Log
        $logModel = new Log_tiket();
        $logModel->insert([
            'id_tiket' => $id,
            'id_user' => session()->get('id_user'),
            'tgl_aktifitas' =>  $tgl,
            'aktifitas' => "Membuat tiket",
            'color' => "warning",
            'icon' => "fas fa-ticket-alt",
        ]);

        // NOTIFIKASI
        $this->notification(10);

        return $this->response->setJSON([
            'status'  => 200,
            'message' => "Tiket Berhasil Dibuat."
        ]);
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // Jaringan jaringan
    public function jaringan_page() 
    {
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/jaringan',$data);
    }

    public function add_jaringan() {
        $userModel = new Tiket();
        $tgl = date("Y-m-d H:i:s");

        // 1) Header tiket
        $userModel->insert([
            'kode_tiket'   => $this->request->getVar('kode'),
            'tgl_input'    => $tgl,
            'id_pelayanan' => 11,
            'id_user'      => session()->get('id_user'),
            'status'       => 0,
        ]);

        $id = $userModel->getInsertID();

        // 2) Upload surat pengantar
        $berkas = $this->request->getFile('berkas');
        if (!$berkas || !$berkas->isValid()) {
            return $this->response->setJSON([
                'status' => 422,
                'message' => 'Berkas surat pengantar wajib diupload.'
            ]);
        }

        $filePengantar = md5($id . '_pengantar') . "." . $berkas->guessExtension();
        $filePengantar = str_replace(" ","",$filePengantar);
        $berkas->move('./public/assets/berkas/surat-pengantar', $filePengantar);

        // 3) Upload dokumentasi (bisa banyak)
        $dokumentasiList = [];

        for ($x = 0; $x < (int)$this->request->getVar('jumlah_dokumentasi'); $x++) {

            $dokumen = 'dokumentasi_' . $x;
            $file = $this->request->getFile($dokumen);

            if ($file && $file->isValid()) {
                $fileName = md5($id . '_dokumentasi_' . $x) . "." . $file->guessExtension();
                $fileName = str_replace(" ","",$fileName);
                $file->move('./public/assets/berkas/dokumentasi', $fileName);

                $dokumentasiList[] = $fileName;
            }
        }

        // 4) Insert ke tb_tiket_detail
        $db = \Config\Database::connect();
        $db->table('tb_tiket_detail')->insert([
            'id_tiket' => $id,
            'tipe'     => 'jaringan',
            'judul'    => 'Pengaduan Jaringan',
            'mulai'    => null,
            'selesai'  => null,
            'detail'   => json_encode([
                'tgl_kejadian'      => $this->request->getVar('tgl'),
                'keluhan'           => $this->request->getVar('keluhan'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $filePengantar,
                'dokumentasi'       => $dokumentasiList,
            ]),
        ]);

        // 5) Log
        $logModel = new Log_tiket();
        $logModel->insert([
            'id_tiket' => $id,
            'id_user' => session()->get('id_user'),
            'tgl_aktifitas' =>  $tgl,
            'aktifitas' => "Membuat tiket",
            'color' => "warning",
            'icon' => "fas fa-ticket-alt",
        ]);

        $this->notification(11);

        return $this->response->setJSON([
            'status' => 200,
            'message' => "Tiket Berhasil Dibuat."
        ]);
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // Magang

    public function add_magang()
    {
        
        $userModel = new Tiket_magang();

        $array = array('id_user' => session()->get('id_user'), 'status >' => 3);
        $users = $userModel->where($array)->findAll();

        if(count($users)==0){
            $tgl = date("Y-m-d H:i:s");
            $data = [
                'kode_tiket' => $this->request->getVar('kode'),
                'tgl_input'    => $tgl,
                'id_user'    => session()->get('id_user'),
                'status'    => 3,
                'id_opd' => $this->request->getVar('id_opd'),
                'tgl_awal' => $this->request->getVar('tgl_awal'),
                'tgl_akhir' => $this->request->getVar('tgl_akhir'),
                'nama_pembimbing' => $this->request->getVar('nama'),
                'no_pembimbing' => $this->request->getVar('wa'),
            ];

            $userModel->insert($data);
            $id = $userModel->getInsertID();
            
            $dataBerkas = $this->request->getFile('berkas');
            $fileName = md5($id).".".$dataBerkas->guessExtension();
            $fileName = str_replace(" ","",$fileName);
            $dataBerkas->move('./public/assets/berkas/magang/surat-pengantar',$fileName);

            $data = [
                'surat_pengantar' => $fileName,
            ];

            $userModel->update($id, $data);

            $logModel = new Log_tiket_magang();
            $data = [
                'id_tiket' => $id,
                'id_user' => session()->get('id_user'),
                'tgl_aktifitas' =>  $tgl,
                'aktifitas' => "Membuat tiket",
                'color' => "warning",
                'icon' => "fas fa-ticket-alt",
            ];

            $logModel->insert($data);

            // NOTIFIKASI TO OPERATOR
            $db = \Config\Database::connect();
            $builder = $db->table('ssuser')->select('ssuser.id_chat')->where("ssuser.active", 1)->where("ssuser.role_id", 1)->where("ssuser.id_opd", $this->request->getVar('id_opd'))->get()->getResult();
            $user = $db->table('ssuser')->select('ssuser.nama, ssuser_magang.civitas')->join('ssuser_magang', 'ssuser_magang.id_ssuser = ssuser.id_ssuser', 'left')->where("ssuser.id_ssuser", session()->get('id_user'))->get()->getRow();
            
            foreach ($builder as $row)
            {
                $message = "Haloo Operator.
                \nAyo login, ada permohonan pelaksanaan magang yang harus kamu verifikasi. \nNama : ".$user->nama."\nCivitas : ". $user->civitas;
                helper('notification_helper');
                $hasil = telegram($row->id_chat,$message);
            }

            $response = [
                'status' => 200,
                'message' => "Tiket Berhasil Dibuat."
            ];
            
        }else{
            $response = [
                'status' => 500,
                'message' => "Anda masih menjalani proses magang."
            ];
        }
        
        
        echo json_encode($response);
    }

    public function get_count_magang()
    {
        $db = \Config\Database::connect();

        if( (int) session()->get('id_role') == 0){
            $proses = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.status >=', 3)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $selesai = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.status', 1)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $tolak = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.status', 0)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $batal = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.status', 2)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $semua = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
           
        }elseif ( (int) session()->get('id_role') == 1){
            $proses = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_opd', session()->get('id_opd'))->where('tb_tiket_magang.status >=', 3)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $selesai = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_opd', session()->get('id_opd'))->where('tb_tiket_magang.status', 1)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $tolak = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_opd', session()->get('id_opd'))->where('tb_tiket_magang.status', 0)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $batal = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_opd', session()->get('id_opd'))->where('tb_tiket_magang.status', 2)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $semua = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_opd', session()->get('id_opd'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
        
        }elseif ( (int) session()->get('id_role') == 3){
            $proses = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status >=', 3)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $selesai = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 1)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $tolak = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 0)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $batal = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where('tb_tiket_magang.status', 2)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $semua = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
        
        }else{
            $proses = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status >=', 3)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $selesai = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 1)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $tolak = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 0)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $batal = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_user', session()->get('id_user'))->where('tb_tiket_magang.status', 2)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
            $semua = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket')->where('tb_tiket_magang.id_user', session()->get('id_user'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->countAllResults();
                
        }
        
        $response = [
            'proses' => $proses,
            'selesai' => $selesai,
            'tolak' => $tolak,
            'batal' => $batal,
            'semua' => $semua,
        ];
        
        echo json_encode($response);
    }

    public function get_tiket_magang()
    {
        $db = \Config\Database::connect();

        if(session()->get('id_role') == 0){
            if($this->request->getVar('status') < 3){
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.status', $this->request->getVar('status'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }elseif($this->request->getVar('status') == 1000){
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }else{
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.status >=', 3)->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }
        }elseif (session()->get('id_role') == 1){
            if($this->request->getVar('status') < 3){
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.status', $this->request->getVar('status'))->where('tb_tiket_magang.id_opd', session()->get('id_opd'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }elseif($this->request->getVar('status') == 1000){
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.id_opd', session()->get('id_opd'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }else{
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.status >=', 3)->where('tb_tiket_magang.id_opd', session()->get('id_opd'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }
        }elseif (session()->get('id_role') == 3){
            if($this->request->getVar('status') < 3){
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.status', $this->request->getVar('status'))->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }elseif($this->request->getVar('status') == 1000){
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }else{
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.status >=', 3)->where('tb_tiket_magang.id_pembina_lapangan', session()->get('id_user'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }
        }else{
            if($this->request->getVar('status') < 3){
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.status', $this->request->getVar('status'))->where('tb_tiket_magang.id_user', session()->get('id_user'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }elseif($this->request->getVar('status') == 1000){
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.id_user', session()->get('id_user'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }else{
                $builder = $db->table('tb_tiket_magang')->select('tb_tiket_magang.id_tiket, tb_tiket_magang.kode_tiket, tb_tiket_magang.tgl_input, tb_tiket_magang.status, ssuser.nama, ssopd.akronim_opd, ssuser_magang.civitas')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->join('ssopd', 'ssopd.id_opd = tb_tiket_magang.id_opd', 'left')->join('ssuser_magang', 'ssuser_magang.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.status >=', 3)->where('tb_tiket_magang.id_user', session()->get('id_user'))->where("date_part('year', tb_tiket_magang.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket_magang.id_tiket', 'DESC')->get()->getResult();
            }
        }
        
        echo json_encode($builder);
    }

    public function get_opd_operator()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ssuser')->select('ssuser.id_opd, ssopd.nama_opd')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('ssuser.role_id', 1)->distinct('ssuser.id_opd')->get()->getResult();
        
        echo json_encode($builder);
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // Notifikasi
    public function notification($id_pelayanan)
    {
        $db = \Config\Database::connect();
        $pemohon = $db->table('ssuser')->select('ssuser.nama')->where('ssuser.id_ssuser', session()->get('id_user'))->get()->getRow();
        $opd = $db->table('ssopd')->select('ssopd.akronim_opd')->where('ssopd.id_opd', session()->get('id_opd'))->get()->getRow();

        $user = $db->table('verifikator_pelayanan')->select('verifikator_pelayanan.id_user')->where("verifikator_pelayanan.id_pelayanan", $id_pelayanan)->get()->getResult();
        if(count($user)!=0){
            // NOTIFIKASI TO Verifikator
            foreach ($user as $row)
            {
                $verifikator = $db->table('ssuser')->select('ssuser.id_chat')->where('ssuser.id_ssuser', $row->id_user)->get()->getRow();

                $message = "Haloo Verifikator.
                \nAyo login, ada permohonan tiket pelayanan yang harus kamu verifikasi. \nNama : ". $pemohon->nama."\nOPD   : ". $opd->akronim_opd;
                helper('notification_helper');
                $hasil = telegram($verifikator->id_chat,$message);
            }
        }else{
            // NOTIFIKASI TO Admin
            $builder = $db->table('ssuser')->select('ssuser.id_chat')->where("ssuser.active", 1)->where("ssuser.role_id", 0)->get()->getResult();
            foreach ($builder as $row)
            {
                $message = "Haloo Admin.
                \nAyo login, ada permohonan tiket pelayanan yang harus kamu verifikasi. \nNama : ". $pemohon->nama."\nOPD   : ". $opd->akronim_opd;
                helper('notification_helper');
                $hasil = telegram($row->id_chat,$message);
            }
        }
    }
}