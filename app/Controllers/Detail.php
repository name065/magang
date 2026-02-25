<?php

namespace App\Controllers;

use App\Models\Tiket;
use App\Models\Tiket_zoom;
use App\Models\Tiket_alat;
use App\Models\Tiket_aula;
use App\Models\Tiket_app;
use App\Models\Tiket_tte;
use App\Models\Tiket_hosting;
use App\Models\Tiket_upload;
use App\Models\Tiket_subdomain;

use App\Models\Tiket_jaringan;
use App\Models\Tiket_jaringan_foto;

use App\Models\Aula;

use App\Models\Log_tiket;

use App\Models\Tiket_magang;

class Detail extends BaseController{
  public function __construct(){
    date_default_timezone_set('Asia/Jakarta');
  }
  public function aula_page($id_tiket, $kode_tiket){
      $tiketModel = new Tiket();
      $tiket = $tiketModel->where(['id_tiket' => $id_tiket])->first();
      if (!$tiket) return view('errors/html/error_404');

      $db = \Config\Database::connect();

      // Ambil detail aula dari tb_tiket_detail
      $row = $db->query(
          "SELECT detail, mulai, selesai, judul
          FROM tb_tiket_detail
          WHERE id_tiket = ? AND tipe='aula'
          LIMIT 1",
          [$id_tiket]
      )->getRowArray();

      if (!$row) {
          // tiket ada tapi detail aula tidak ada
          return view('errors/html/error_404');
      }

      $detail = $row['detail'];
      if (is_string($detail)) $detail = json_decode($detail, true);

      // Ambil nama aula (join ke tabel aula)
      $aulaNama = null;
      $idAula = $detail['id_aula'] ?? null;
      if ($idAula) {
          $a = $db->query("SELECT nama_aula FROM ssaula WHERE id_aula = ?", [(int)$idAula])->getRowArray();
          $aulaNama = $a['nama_aula'] ?? null;
      }

      $data = [
          'title' => 'tiket',
          'id_tiket' => $id_tiket,
          'kode_tiket' => $tiket["kode_tiket"],
          'tgl_input' => $tiket["tgl_input"],
          'status' => $tiket["status"],
          'catatan' => $tiket["catatan"],
          'id_user' => $tiket["id_user"],

          // dari JSON detail
          'nama_pic' => $detail['nama_pic'] ?? null,
          'no_pic' => $detail['no_pic'] ?? null,
          'berkas_pengantar' => $detail['berkas_pengantar'] ?? null,

          // dari tb_tiket_detail
          'tgl_awal' => $row['mulai'] ?? null,
          'tgl_akhir' => $row['selesai'] ?? null,
          'nama_acara' => $row['judul'] ?? null,

          // nama aula untuk view detail/aula
          'aula' => $aulaNama,
      ];

      return view('detail/aula', $data);
  }

  public function zoom_page($id_tiket, $kode_tiket){
    $tiketModel = new Tiket();
    $tiket = $tiketModel->where(['id_tiket' => $id_tiket])->first();
    if (!$tiket) return view('errors/html/error_404');

    $db = \Config\Database::connect();

    // Ambil detail zoom dari tb_tiket_detail
    $row = $db->query(
      "SELECT detail, mulai, selesai, judul
      FROM tb_tiket_detail
      WHERE id_tiket = ? AND tipe='zoom'
      LIMIT 1",
      [$id_tiket]
    )->getRowArray();

    if (!$row) {
      // tiket ada tapi detail zoom tidak ada
      return view('errors/html/error_404');
    }

    $detail = $row['detail'];
    if (is_string($detail)) $detail = json_decode($detail, true);

    $data = [
      'title' => 'tiket',
      'id_tiket' => $id_tiket,
      'kode_tiket' => $tiket["kode_tiket"],
      'tgl_input' => $tiket["tgl_input"],
      'status' => $tiket["status"],
      'catatan' => $tiket["catatan"],
      'id_user' => $tiket["id_user"],

      // dari JSON detail
      'nama_pic' => $detail['nama_pic'] ?? null,
      'no_pic' => $detail['no_pic'] ?? null,
      'berkas_pengantar' => $detail['berkas_pengantar'] ?? null,
      'tempat' => $detail['tempat'] ?? null,
      'passcode' => $detail['passcode'] ?? null,
      'meeting_id' => $detail['meeting_id'] ?? null,
      'operator' => $detail['operator'] ?? null,
      'jenis_zoom' => $detail['jenis_zoom'] ?? null,

      // dari tb_tiket_detail
      'tgl_awal' => $row['mulai'] ?? null,
      'tgl_akhir' => $row['selesai'] ?? null,
      'nama_acara' => $row['judul'] ?? null,
    ];

    return view('detail/zoom', $data);
  }

  public function subdomain_page($id_tiket, $kode_tiket){
    $tiketModel = new Tiket();
    $array = array('id_tiket' => $id_tiket);
    $tiket = $tiketModel->where($array)->findAll();
    if (count($tiket) == 0) {
      return view('errors/html/error_404');
    } else {
      $array = array('id_tiket' => $id_tiket);
      $tiket = $tiketModel->where($array)->first();

      $db = \Config\Database::connect();

      $pelayanan = $db->table('tb_tiket_detail')
          ->where('id_tiket', $id_tiket)
          ->where('tipe', 'subdomain')
          ->get()
          ->getRowArray();

      if (!$pelayanan) {
          return view('errors/html/error_404');
      }

      // kalau view kamu butuh field lama seperti nama_subdomain/ip_publik, mapping dari JSON:
      $detail = json_decode($pelayananRow['detail'] ?? '{}', true);

      $pelayanan = [
          'nama_pic'         => $detail['nama_pic'] ?? '',
          'no_pic'           => $detail['no_pic'] ?? '',
          'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
          'nama_subdomain'   => $pelayananRow['judul'] ?? ($detail['nama_subdomain'] ?? ''),
          'ip_publik'        => $detail['ip_publik'] ?? '',
      ];

      $data = array(
        'title' => 'tiket',
        'id_tiket' => $id_tiket,
        'kode_tiket' => $tiket["kode_tiket"],
        'tgl_input' => $tiket["tgl_input"],
        // 'status' => 2,
        'status' => $tiket["status"],
        'catatan' => $tiket["catatan"],
        'id_user' => $tiket["id_user"],
        'nama_pic' => $pelayanan["nama_pic"],
        'no_pic' => $pelayanan["no_pic"],
        'berkas_pengantar' => $pelayanan["berkas_pengantar"],
        'nama_subdomain' => $pelayanan["nama_subdomain"],
        'ip_publik' => $pelayanan["ip_publik"],
      );
      return view('detail/sub-domain', $data);
    }
  }

  public function upload_page($id_tiket, $kode_tiket) {
      // 1) Header tiket
      $tiketModel = new Tiket();
      $tiket = $tiketModel->where(['id_tiket' => $id_tiket])->first();

      if (!$tiket) {
          return view('errors/html/error_404');
      }

      if ($tiket['kode_tiket'] !== $kode_tiket) {
          return view('errors/html/error_404');
      }

      // 2) Detail layanan upload dari tb_tiket_detail
      $db = \Config\Database::connect();
      $row = $db->table('tb_tiket_detail')
          ->where('id_tiket', $id_tiket)
          ->where('tipe', 'upload')
          ->get()->getRowArray();

      if (!$row) {
          return view('errors/html/error_404');
      }

      $detail = json_decode($row['detail'] ?? '{}', true);

      // 3) Mapping agar view lama detail/upload tetap aman
      $pelayanan = [
          'nama_pic'         => $detail['nama_pic'] ?? '',
          'no_pic'           => $detail['no_pic'] ?? '',
          'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
          'jenis_dokumen'    => $detail['jenis_dokumen'] ?? ($row['judul'] ?? ''),
          'edisi'            => $detail['edisi'] ?? '',
          'berkas_upload'    => $detail['berkas_upload'] ?? '',
      ];

      $data = [
          'title' => 'tiket',
          'id_tiket' => $id_tiket,
          'kode_tiket' => $tiket["kode_tiket"],
          'tgl_input' => $tiket["tgl_input"],
          'status' => $tiket["status"],
          'catatan' => $tiket["catatan"],
          'id_user' => $tiket["id_user"],

          'nama_pic' => $pelayanan["nama_pic"],
          'no_pic' => $pelayanan["no_pic"],
          'berkas_pengantar' => $pelayanan["berkas_pengantar"],
          'jenis_dokumen' => $pelayanan["jenis_dokumen"],
          'edisi' => $pelayanan["edisi"],
          'berkas_upload' => $pelayanan["berkas_upload"],
      ];

      return view('detail/upload', $data);
  }

  public function hosting_page($id_tiket, $kode_tiket){
    $tiketModel = new Tiket();
    $array = array('id_tiket' => $id_tiket);
    $tiket = $tiketModel->where($array)->findAll();
    if (count($tiket) == 0) {
      return view('errors/html/error_404');
    } else {
      $array = array('id_tiket' => $id_tiket);
      $tiket = $tiketModel->where($array)->first();

      $array = array('id_tiket' => $id_tiket);
      $db = \Config\Database::connect();
      $row = $db->table('tb_tiket_detail')
          ->where('id_tiket', $id_tiket)
          ->where('tipe', 'hosting')
          ->get()->getRowArray();

      if (!$row) {
          return view('errors/html/error_404');
      }

      $detail = json_decode($row['detail'] ?? '{}', true);

      // bentukin array "pelayanan" mirip tabel lama supaya mapping data kamu tetap jalan
      $pelayanan = [
          'nama_pic'         => $detail['nama_pic'] ?? '',
          'no_pic'           => $detail['no_pic'] ?? '',
          'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
          'nama_aplikasi'    => $detail['nama_aplikasi'] ?? ($row['judul'] ?? ''),
          'deskripsi'        => $detail['deskripsi'] ?? '',
          'spesifikasi'      => $detail['spesifikasi'] ?? '',
          'port'             => $detail['port'] ?? '',
          'db_access'        => $detail['db_access'] ?? '',
          'server_access'    => $detail['server_access'] ?? '',
      ];

      $data = array(
        'title' => 'tiket',
        'id_tiket' => $id_tiket,
        'kode_tiket' => $tiket["kode_tiket"],
        'tgl_input' => $tiket["tgl_input"],
        // 'status' => 2,
        'status' => $tiket["status"],
        'catatan' => $tiket["catatan"],
        'id_user' => $tiket["id_user"],
        'nama_pic' => $pelayanan["nama_pic"],
        'no_pic' => $pelayanan["no_pic"],
        'berkas_pengantar' => $pelayanan["berkas_pengantar"],
        'nama_aplikasi' => $pelayanan["nama_aplikasi"],
        'deskripsi' => $pelayanan["deskripsi"],
        'spesifikasi' => $pelayanan["spesifikasi"],
        'port' => $pelayanan["port"],
        'db_access' => $pelayanan["db_access"],
        'server_access' => $pelayanan["server_access"],
      );
      return view('detail/hosting', $data);
    }
  }

  public function tte_page($id_tiket, $kode_tiket){
      // 1) Ambil header tiket
      $tiketModel = new \App\Models\Tiket();
      $tiket = $tiketModel->where(['id_tiket' => $id_tiket])->first();

      if (!$tiket) {
          return view('errors/html/error_404');
      }

      // (opsional) validasi kode tiket dari URL kalau mau strict
      if ($tiket['kode_tiket'] !== $kode_tiket) {
          return view('errors/html/error_404');
      }

      // 2) Ambil detail TTE dari tb_tiket_detail
      $db = \Config\Database::connect();
      $row = $db->table('tb_tiket_detail')
          ->where('id_tiket', $id_tiket)
          ->where('tipe', 'tte')
          ->get()->getRowArray();

      if (!$row) {
          return view('errors/html/error_404');
      }

      $detail = json_decode($row['detail'] ?? '{}', true);

      // 3) Mapping agar view lama tetap aman
      $pelayanan = [
          'nama_pic'         => $detail['nama_pic'] ?? '',
          'no_pic'           => $detail['no_pic'] ?? '',
          'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
          'nama'             => $detail['nama'] ?? '',
          'jabatan'          => $detail['jabatan'] ?? '',
          'nip'              => $detail['nip'] ?? '',
          'nik'              => $detail['nik'] ?? '',
          'jenis_layanan'    => $detail['jenis_layanan'] ?? '',
          'berkas_ktp'       => $detail['berkas_ktp'] ?? '',
      ];

      // 4) Kirim ke view
      $data = [
          'title' => 'tiket',
          'id_tiket' => $id_tiket,
          'kode_tiket' => $tiket["kode_tiket"],
          'tgl_input' => $tiket["tgl_input"],
          'status' => $tiket["status"],
          'catatan' => $tiket["catatan"],
          'id_user' => $tiket["id_user"],

          'nama_pic' => $pelayanan["nama_pic"],
          'no_pic' => $pelayanan["no_pic"],
          'berkas_pengantar' => $pelayanan["berkas_pengantar"],
          'nama' => $pelayanan["nama"],
          'jabatan' => $pelayanan["jabatan"],
          'nip' => $pelayanan["nip"],
          'nik' => $pelayanan["nik"],
          'jenis_layanan' => $pelayanan["jenis_layanan"],
          'berkas_ktp' => $pelayanan["berkas_ktp"],
      ];

      return view('detail/tte', $data);
  }

  public function app_page($id_tiket, $kode_tiket){
      // 1) Header tiket
      $tiketModel = new Tiket();
      $tiket = $tiketModel->where(['id_tiket' => $id_tiket])->first();

      if (!$tiket) {
          return view('errors/html/error_404');
      }

      // optional: validasi kode tiket dari URL
      if ($tiket['kode_tiket'] !== $kode_tiket) {
          return view('errors/html/error_404');
      }

      // 2) Detail layanan dari tb_tiket_detail
      $db = \Config\Database::connect();
      $row = $db->table('tb_tiket_detail')
          ->where('id_tiket', $id_tiket)
          ->where('tipe', 'app')
          ->get()->getRowArray();

      if (!$row) {
          return view('errors/html/error_404');
      }

      $detail = json_decode($row['detail'] ?? '{}', true);

      // 3) Mapping agar view detail/app tetap aman
      $pelayanan = [
          'nama_pic' => $detail['nama_pic'] ?? '',
          'no_pic' => $detail['no_pic'] ?? '',
          'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
          'agenda' => $detail['agenda'] ?? '',
          'tempat' => $detail['tempat'] ?? '',
          'tgl' => $detail['tgl'] ?? '',
          'nama_aplikasi' => $detail['nama_aplikasi'] ?? ($row['judul'] ?? ''),
          'deskripsi_aplikasi' => $detail['deskripsi_aplikasi'] ?? '',
      ];

      $data = [
          'title' => 'tiket',
          'id_tiket' => $id_tiket,
          'kode_tiket' => $tiket["kode_tiket"],
          'tgl_input' => $tiket["tgl_input"],
          'status' => $tiket["status"],
          'catatan' => $tiket["catatan"],
          'id_user' => $tiket["id_user"],

          'nama_pic' => $pelayanan["nama_pic"],
          'no_pic' => $pelayanan["no_pic"],
          'berkas_pengantar' => $pelayanan["berkas_pengantar"],
          'agenda' => $pelayanan["agenda"],
          'tempat' => $pelayanan["tempat"],
          'tgl' => $pelayanan["tgl"],
          'nama_aplikasi' => $pelayanan["nama_aplikasi"],
          'deskripsi_aplikasi' => $pelayanan["deskripsi_aplikasi"],
      ];

      return view('detail/app', $data);
  }

  public function jaringan_page($id_tiket, $kode_tiket) {
      $tiketModel = new Tiket();
      $tiket = $tiketModel->where(['id_tiket' => $id_tiket])->first();

      if (!$tiket) {
          return view('errors/html/error_404');
      }

      if ($tiket['kode_tiket'] !== $kode_tiket) {
          return view('errors/html/error_404');
      }

      $db = \Config\Database::connect();
      $row = $db->table('tb_tiket_detail')
          ->where('id_tiket', $id_tiket)
          ->where('tipe', 'jaringan')
          ->get()->getRowArray();

      if (!$row) {
          return view('errors/html/error_404');
      }

      $detail = json_decode($row['detail'] ?? '{}', true);

      $data = [
          'title' => 'tiket',
          'id_tiket' => $id_tiket,
          'kode_tiket' => $tiket["kode_tiket"],
          'tgl_input' => $tiket["tgl_input"],
          'status' => $tiket["status"],
          'catatan' => $tiket["catatan"],
          'id_user' => $tiket["id_user"],

          'nama_pic' => $detail['nama_pic'] ?? '',
          'no_pic' => $detail['no_pic'] ?? '',
          'tgl_kejadian' => $detail['tgl_kejadian'] ?? '',
          'keluhan' => $detail['keluhan'] ?? '',
          'tindak_lanjut' => $detail['tindak_lanjut'] ?? '',
          'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
          'foto' => $detail['dokumentasi'] ?? [],
      ];

      return view('detail/jaringan', $data);
  }

  public function alat_page($id_tiket, $kode_tiket){
    $tiketModel = new Tiket();
    $tiket = $tiketModel->where(['id_tiket' => $id_tiket])->first();
    if (!$tiket) return view('errors/html/error_404');

    $db = \Config\Database::connect();

    // ambil detail alat dari tb_tiket_detail (JSONB)
    $row = $db->query(
      "SELECT detail, mulai, selesai, judul
      FROM tb_tiket_detail
      WHERE id_tiket = ? AND tipe='alat'
      LIMIT 1",
      [$id_tiket]
    )->getRowArray();

    if (!$row) {
      // tiket ada tapi tidak punya detail alat
      return view('errors/html/error_404');
    }

    // detail bisa string json atau sudah array, amankan:
    $detail = $row['detail'];
    if (is_string($detail)) $detail = json_decode($detail, true);

    // list alat dari alat_ids
    $list = $db->query(
      "SELECT a.id_alat, a.nama_alat, a.merk, a.nomor_seri
      FROM tb_tiket_detail d
      JOIN LATERAL jsonb_array_elements_text(d.detail->'alat_ids') AS x(id_alat_txt) ON TRUE
      JOIN ssalat a ON a.id_alat = x.id_alat_txt::int
      WHERE d.id_tiket = ? AND d.tipe = 'alat'",
      [$id_tiket]
    )->getResult();

    $data = [
      'title' => 'tiket',
      'id_tiket' => $id_tiket,
      'kode_tiket' => $tiket["kode_tiket"],
      'tgl_input' => $tiket["tgl_input"],
      'status' => $tiket["status"],
      'catatan' => $tiket["catatan"],
      'id_user' => $tiket["id_user"],

      // ambil dari JSON detail
      'nama_pic' => $detail['nama_pic'] ?? null,
      'no_pic' => $detail['no_pic'] ?? null,
      'berkas_pengantar' => $detail['berkas_pengantar'] ?? null,

      // ambil dari tb_tiket_detail (kalau dipakai di view)
      'tgl_awal' => $row['mulai'] ?? null,
      'tgl_akhir' => $row['selesai'] ?? null,
      'nama_acara' => $row['judul'] ?? null,

      'list' => $list,
    ];

    return view('detail/alat', $data);
  }

  public function magang_page($id_tiket, $kode_tiket){
    $tiketModel = new Tiket_magang();
    $array = array('id_tiket' => $id_tiket);
    $tiket = $tiketModel->where($array)->findAll();
    if (count($tiket) == 0) {
      return view('errors/html/error_404');
    } else {
      $tiket = $tiketModel->where($array)->first();

      $db = \Config\Database::connect();
      $ssuser = $db->table('tb_tiket_magang')->select('ssuser.nama, ssuser.id_opd, ssuser.file_foto, ssuser.id_ssuser')->join('ssuser', 'ssuser.id_ssuser = tb_tiket_magang.id_user', 'left')->where('tb_tiket_magang.id_tiket', $id_tiket)->get()->getRowArray();

      $magang = $db->table('ssuser_magang')->select('ssuser_magang.wa, ssuser_magang.gender, ssuser_magang.jenis, ssuser_magang.nomor_induk, ssuser_magang.jurusan, ssuser_magang.civitas, ssuser_magang.ktp')->where('ssuser_magang.id_ssuser', $ssuser["id_ssuser"])->get()->getRowArray();

      if ($tiket["id_pembina_lapangan"] != null) {
        $sub = $db->table('ssuser_pembimbing')->select('ssuser_pembimbing.id_sub')->where('ssuser_pembimbing.id_ssuser', $tiket["id_pembina_lapangan"])->get()->getRowArray();
        $id_pembina_lapangan = $tiket["id_pembina_lapangan"];
        $id_sub = $sub["id_sub"];
      } else {
        $id_sub = "0";
        $id_pembina_lapangan = "0";
      }

      $nilai = $db->table('tb_tiket_magang_nilai')->where('tb_tiket_magang_nilai.id_tiket', $id_tiket)->countAllResults();
      if ($nilai == 0) {
        $nilai_performance = 0;
        $nilai_sikap = 0;
        $nilai_kerjasama = 0;
        $nilai_disiplin = 0;
        $nilai_komunikasi = 0;
        $nilai_tanggung_jawab = 0;
        $nilai_teknis = 0;
        $catatan_nilai = "";
      } else {
        $nilai = $db->table('tb_tiket_magang_nilai')->where('tb_tiket_magang_nilai.id_tiket', $id_tiket)->get()->getRowArray();

        $nilai_performance = $nilai["nilai_performance"];
        $nilai_sikap = $nilai["nilai_sikap"];
        $nilai_kerjasama = $nilai["nilai_kerjasama"];
        $nilai_disiplin = $nilai["nilai_disiplin"];
        $nilai_komunikasi = $nilai["nilai_komunikasi"];
        $nilai_tanggung_jawab = $nilai["nilai_tanggung_jawab"];
        $nilai_teknis = $nilai["nilai_teknis"];
        $catatan_nilai = $nilai["catatan_nilai"];
      }

      $data = array(
        'title' => 'magang',
        'id_tiket' => $id_tiket,
        'kode_tiket' => $tiket["kode_tiket"],
        'tgl_input' => $tiket["tgl_input"],
        // 'status' => 3,
        'status' => $tiket["status"],
        'catatan' => $tiket["catatan"],
        'id_user' => $tiket["id_user"],
        'nama_pic' => $tiket["nama_pembimbing"],
        'no_pic' => $tiket["no_pembimbing"],
        'berkas_pengantar' => $tiket["surat_pengantar"],
        'tgl_akhir' => $tiket["tgl_akhir"],
        'tgl_awal' => $tiket["tgl_awal"],
        'nama_project' => $tiket["nama_project"],
        'deskripsi_project' => $tiket["deskripsi_project"],
        'berkas_project' => $tiket["berkas_project"],
        'id_opd' => $tiket["id_opd"],
        'id_pembina_lapangan' => $id_pembina_lapangan,
        'id_sub' => $id_sub,
        // 'id_opd' => 5,
        'nilai_performance' => $nilai_performance,
        'nilai_sikap' => $nilai_sikap,
        'nilai_kerjasama' => $nilai_kerjasama,
        'nilai_disiplin' => $nilai_disiplin,
        'nilai_komunikasi' => $nilai_komunikasi,
        'nilai_tanggung_jawab' => $nilai_tanggung_jawab,
        'nilai_teknis' => $nilai_teknis,
        'catatan_nilai' => $catatan_nilai,
        'nama' => $ssuser["nama"],
        'file_foto' => $ssuser["file_foto"],
        'wa' => $magang["wa"],
        'gender' => $magang["gender"],
        'jenis' => $magang["jenis"],
        'nomor_induk' => $magang["nomor_induk"],
        'jurusan' => $magang["jurusan"],
        'civitas' => $magang["civitas"],
        'ktp' => $magang["ktp"],
      );

      return view('admin/magang/magang', $data);
    }
  }

  // -----------------------------------------------------------------------------------------------------------------------
  // History

  public function get_list_history()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('log_aktifitas_pelayanan')->select('log_aktifitas_pelayanan.tgl_aktifitas as tgl, log_aktifitas_pelayanan.aktifitas, log_aktifitas_pelayanan.color, log_aktifitas_pelayanan.icon, ssuser.nama, ssuser.role_id')->join('ssuser', 'ssuser.id_ssuser = log_aktifitas_pelayanan.id_user', 'left')->where('log_aktifitas_pelayanan.id_tiket', $this->request->getVar('id_tiket'))->orderBy('log_aktifitas_pelayanan.id_log', 'ASC')->get()->getResult();

    echo json_encode($builder);
  }

  public function get_magang_history()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('log_aktifitas_magang')->select('log_aktifitas_magang.tgl_aktifitas as tgl, log_aktifitas_magang.aktifitas, log_aktifitas_magang.color, log_aktifitas_magang.icon, ssuser.nama, ssuser.role_id')->join('ssuser', 'ssuser.id_ssuser = log_aktifitas_magang.id_user', 'left')->where('log_aktifitas_magang.id_tiket', $this->request->getVar('id_tiket'))->orderBy('log_aktifitas_magang.id_log', 'ASC')->get()->getResult();

    echo json_encode($builder);
  }

  // -----------------------------------------------------------------------------------------------------------------------
  // Catatan

  public function update_catatan()
  {
    $tiketModel = new Tiket();
    $logModel = new Log_tiket();

    if ($this->request->getVar('status') == 1) {
      $status = 'Tiket telah disetujui';
      $warna = 'success';
      $icon = 'fa fa-check';
      $message = "Haloo Operator.\n\nSelamat ya, permohonan tiket anda sudah diverifikasi loh. Ayo buruan login ke Aplikasi PELUIT.";
    } elseif ($this->request->getVar('status') == 2) {
      $status = 'Tiket telah ditolak';
      $warna = 'danger';
      $icon = 'fa fa-times';
      $message = "Haloo Operator.\n\nMaaf ya, permohonan tiket anda telah ditolak. Yuk intip alasan tiket kamu ditolak, SEMANGAT !!";
    } else {
      $status = 'Tiket telah dibatalkan';
      $warna = 'dark';
      $icon = 'fa fa-times';
      $message = "Haloo Operator.\n\nMaaf ya, ada permohonan tiket dibatalkan.";
    }

    if ($this->request->getVar('status') == 1) {
      $data = [
        'status' => 1,
      ];
      $tiketModel->update($this->request->getVar('id_tiket'), $data);
    } else {
      $data = [
        'status' => $this->request->getVar('status'),
        'catatan' => $this->request->getVar('catatan'),
      ];
      $tiketModel->update($this->request->getVar('id_tiket'), $data);

      $tgl = date("Y-m-d H:i:s");

      $data = [
        'id_tiket' => $this->request->getVar('id_tiket'),
        'id_user' => session()->get('id_user'),
        'tgl_aktifitas' =>  $tgl,
        'aktifitas' => "Memperbaharui catatan",
        'color' => "primary",
        'icon' => "far fa-edit",
      ];
      $logModel->insert($data);
    }


    $tgl = date("Y-m-d H:i:s");
    $data = [
      'id_tiket' => $this->request->getVar('id_tiket'),
      'id_user' => session()->get('id_user'),
      'tgl_aktifitas' =>  $tgl,
      'aktifitas' => $status,
      'color' => $warna,
      'icon' => $icon,
    ];

    $logModel->insert($data);

    $response = [
      'status' => 200,
      'message' => "Status berhasil di update."
    ];

    if ($this->request->getVar('status') != 3) {
      // NOTIFIKASI TO USER
      $db = \Config\Database::connect();
      $tiket = $db->table('tb_tiket')->select('tb_tiket.id_user')->where("tb_tiket.id_tiket", $this->request->getVar('id_tiket'))->get()->getRow();
      $user = $db->table('ssuser')->select('ssuser.id_chat')->where("ssuser.id_ssuser", $tiket->id_user)->get()->getRow();
      helper('notification_helper');
      $hasil = telegram($user->id_chat, $message);
    }

    echo json_encode($response);
  }

  // -----------------------------------------------------------------------------------------------------------------------
  // Update

  public function update_meeting()
  {
    $logModel = new Log_tiket();
    $pelayananModel = new Tiket_zoom();

    $data = [
      'meeting_id' => $this->request->getVar('meeting_id'),
      'passcode' => $this->request->getVar('passcode'),
    ];
    $pelayananModel->update($this->request->getVar('id_tiket'), $data);

    $tgl = date("Y-m-d H:i:s");

    $data = [
      'id_tiket' => $this->request->getVar('id_tiket'),
      'id_user' => session()->get('id_user'),
      'tgl_aktifitas' =>  $tgl,
      'aktifitas' => "Memperbaharui Meeting Id dan/atau Passcode",
      'color' => "primary",
      'icon' => "far fa-edit",
    ];
    $logModel->insert($data);

    $response = [
      'status' => 200,
      'message' => "Meeting Id dan/atau Passcode berhasil di update."
    ];

    echo json_encode($response);
  }

  public function update_tindak_lanjut()
  {
    $logModel = new Log_tiket();
    $pelayananModel = new Tiket_jaringan();

    $data = [
      'tindak_lanjut' => $this->request->getVar('tindak_lanjut'),
    ];
    $pelayananModel->update($this->request->getVar('id_tiket'), $data);

    $tgl = date("Y-m-d H:i:s");

    $data = [
      'id_tiket' => $this->request->getVar('id_tiket'),
      'id_user' => session()->get('id_user'),
      'tgl_aktifitas' =>  $tgl,
      'aktifitas' => "Memperbaharui isian tindak lanjut",
      'color' => "primary",
      'icon' => "far fa-edit",
    ];
    $logModel->insert($data);

    $response = [
      'status' => 200,
      'message' => "Tindak Lanjut berhasil di update."
    ];

    echo json_encode($response);
  }

  public function update_hosting()
  {
    $logModel = new Log_tiket();
    $pelayananModel = new Tiket_hosting();

    $data = [
      'db_access' => $this->request->getVar('db'),
      'server_access' => $this->request->getVar('ssh'),
    ];
    $pelayananModel->update($this->request->getVar('id_tiket'), $data);

    $tgl = date("Y-m-d H:i:s");

    $data = [
      'id_tiket' => $this->request->getVar('id_tiket'),
      'id_user' => session()->get('id_user'),
      'tgl_aktifitas' =>  $tgl,
      'aktifitas' => "Memperbaharui SSH dan/atau DB Akses",
      'color' => "primary",
      'icon' => "far fa-edit",
    ];
    $logModel->insert($data);

    $response = [
      'status' => 200,
      'message' => "SSH dan/atau DB Akses berhasil di update."
    ];

    echo json_encode($response);
  }

  // -----------------------------------------------------------------------------------------------------------------------
  // Notifikasi
  // public function notification($chat_id,$message)
  // {
  //     $apiToken = "6315272274:AAHOrEVzKuxP7Cz_5y21YvKR8SNPQavAW34";
  //     $data = [
  //     'chat_id' => $chat_id,
  //     'text' => $message 
  //     ];
  //     $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );
  //     $response = json_decode($response);
  //     // return $response->ok;
  //     return $response;
  // }
}
