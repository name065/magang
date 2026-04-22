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
use App\Models\Pelayanan;
use App\Models\PelayananField;
use App\Models\TiketDetail;

class Detail extends BaseController {
  public function __construct()
  {
    date_default_timezone_set('Asia/Jakarta');
  }

  private function getTicketData($id_tiket, $kode_tiket = null)
  {
    $tiketModel = new Tiket();
    $criteria = ['id_tiket' => $id_tiket];
    if ($kode_tiket !== null) {
      $criteria['kode_tiket'] = $kode_tiket;
    }

    return $tiketModel->where($criteria)->first();
  }

  private function getLegacyDetailRow(int $id_tiket, string $tipe): ?array
  {
    $db = \Config\Database::connect();
    $row = $db->table('tb_tiket_detail')
      ->where('id_tiket', $id_tiket)
      ->where('tipe', $tipe)
      ->get()
      ->getRowArray();

    if (!$row) {
      return null;
    }

    $detail = $row['detail'] ?? [];
    if (is_string($detail)) {
      $decoded = json_decode($detail, true);
      $detail = is_array($decoded) ? $decoded : [];
    } elseif (!is_array($detail)) {
      $detail = [];
    }

    $row['detail'] = $detail;
    return $row;
  }

  private function mergeLegacyDetail(int $id_tiket, string $tipe, array $patch): void
  {
    $db = \Config\Database::connect();
    $row = $this->getLegacyDetailRow($id_tiket, $tipe);
    if (!$row) {
      throw new \RuntimeException('Detail tiket tidak ditemukan');
    }

    $detail = array_merge($row['detail'], $patch);

    $db->table('tb_tiket_detail')
      ->where('id_detail', $row['id_detail'])
      ->update(['detail' => json_encode($detail)]);
  }

  public function zoom_page($id_tiket, $kode_tiket)
  {
    $tiketModel = new Tiket();
    $array = array('id_tiket' => $id_tiket);
    $tiket = $tiketModel->where($array)->findAll();
    if (count($tiket) == 0) {
      return view('errors/html/error_404');
    } else {
      $array = array('id_tiket' => $id_tiket);
      $tiket = $tiketModel->where($array)->first();

      $pelayananModel = new Tiket_zoom();
      $array = array('id_tiket' => $id_tiket);
      $pelayanan = $pelayananModel->where($array)->first();

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
        'tgl_akhir' => $pelayanan["tgl_akhir"],
        'tgl_awal' => $pelayanan["tgl_awal"],
        'nama_acara' => $pelayanan["nama_acara"],
        'tempat' => $pelayanan["tempat"],
        'passcode' => $pelayanan["passcode"],
        'meeting_id' => $pelayanan["meeting_id"],
        'operator' => $pelayanan["operator"],
        'jenis_zoom' => $pelayanan["jenis_zoom"],
      );
      return view('detail/zoom', $data);
    }
  }

  // -----------------------------------------------------------------------------------------------------------------------
  // Dynamic Detail (pelayanan dari DB)
  public function dynamic_page($route, $id_tiket, $kode_tiket)
  {
    $pelayananModel = new Pelayanan();
    $pelayanan = $pelayananModel->where('route', $route)->first();
    if (!$pelayanan) {
      return view('errors/html/error_404');
    }

    $tiketModel = new Tiket();
    $tiket = $tiketModel->where(['id_tiket' => $id_tiket, 'kode_tiket' => $kode_tiket])->first();
    if (!$tiket) {
      return view('errors/html/error_404');
    }

    $fieldModel = new PelayananField();
    $fields = $fieldModel->where('id_pelayanan', $pelayanan['id_pelayanan'])
      ->orderBy('sort_order', 'ASC')
      ->findAll();

    $detailModel = new TiketDetail();
    $details = $detailModel->where('id_tiket', $id_tiket)->findAll();
    $detailMap = [];
    foreach ($details as $d) {
      $detailMap[(string)$d['id_field']] = $d;
    }

    $data = [
      'title' => 'detail',
      'pelayanan' => $pelayanan,
      'tiket' => $tiket,
      'fields' => $fields,
      'detailMap' => $detailMap,
    ];

    return view('detail/dynamic', $data);
  }

  public function aula_page($id_tiket, $kode_tiket)
  {
    $tiketModel = new Tiket();
    $array = array('id_tiket' => $id_tiket);
    $tiket = $tiketModel->where($array)->findAll();
    if (count($tiket) == 0) {
      return view('errors/html/error_404');
    } else {
      $array = array('id_tiket' => $id_tiket);
      $tiket = $tiketModel->where($array)->first();

      $pelayananModel = new Tiket_aula();
      $array = array('id_tiket' => $id_tiket);
      $pelayanan = $pelayananModel->where($array)->first();

      $aulaModel = new Aula();
      $array = array('id_aula' => $pelayanan["id_aula"]);
      $aula = $aulaModel->where($array)->first();

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
        'tgl_akhir' => $pelayanan["tgl_akhir"],
        'tgl_awal' => $pelayanan["tgl_awal"],
        'nama_acara' => $pelayanan["nama_acara"],
        'aula' => $aula["nama_aula"],
      );
      return view('detail/aula', $data);
    }
  }

  public function subdomain_page($id_tiket, $kode_tiket)
  {
    $tiket = $this->getTicketData($id_tiket, $kode_tiket);
    $detailRow = $this->getLegacyDetailRow((int) $id_tiket, 'subdomain');

    if (!$tiket || !$detailRow) {
      return view('errors/html/error_404');
    }

    $detail = $detailRow['detail'];

    $data = array(
      'title' => 'tiket',
      'id_tiket' => $id_tiket,
      'kode_tiket' => $tiket['kode_tiket'],
      'tgl_input' => $tiket['tgl_input'],
      'status' => $tiket['status'],
      'catatan' => $tiket['catatan'],
      'id_user' => $tiket['id_user'],
      'nama_pic' => $detail['nama_pic'] ?? '',
      'no_pic' => $detail['no_pic'] ?? '',
      'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
      'nama_subdomain' => $detail['nama_subdomain'] ?? ($detailRow['judul'] ?? ''),
      'ip_publik' => $detail['ip_publik'] ?? '',
    );

    return view('detail/sub-domain', $data);
  }

  public function upload_page($id_tiket, $kode_tiket)
  {
    $tiket = $this->getTicketData($id_tiket, $kode_tiket);
    $detailRow = $this->getLegacyDetailRow((int) $id_tiket, 'upload');

    if (!$tiket || !$detailRow) {
      return view('errors/html/error_404');
    }

    $detail = $detailRow['detail'];

    $data = array(
      'title' => 'tiket',
      'id_tiket' => $id_tiket,
      'kode_tiket' => $tiket['kode_tiket'],
      'tgl_input' => $tiket['tgl_input'],
      'status' => $tiket['status'],
      'catatan' => $tiket['catatan'],
      'id_user' => $tiket['id_user'],
      'nama_pic' => $detail['nama_pic'] ?? '',
      'no_pic' => $detail['no_pic'] ?? '',
      'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
      'jenis_dokumen' => $detail['jenis_dokumen'] ?? '',
      'edisi' => $detail['edisi'] ?? '',
      'berkas_upload' => $detail['berkas_upload'] ?? '',
    );

    return view('detail/upload', $data);
  }

  public function hosting_page($id_tiket, $kode_tiket)
  {
    $tiket = $this->getTicketData($id_tiket, $kode_tiket);
    $detailRow = $this->getLegacyDetailRow((int) $id_tiket, 'hosting');

    if (!$tiket || !$detailRow) {
      return view('errors/html/error_404');
    }

    $detail = $detailRow['detail'];

    $data = array(
      'title' => 'tiket',
      'id_tiket' => $id_tiket,
      'kode_tiket' => $tiket['kode_tiket'],
      'tgl_input' => $tiket['tgl_input'],
      'status' => $tiket['status'],
      'catatan' => $tiket['catatan'],
      'id_user' => $tiket['id_user'],
      'nama_pic' => $detail['nama_pic'] ?? '',
      'no_pic' => $detail['no_pic'] ?? '',
      'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
      'nama_aplikasi' => $detail['nama_aplikasi'] ?? ($detailRow['judul'] ?? ''),
      'deskripsi' => $detail['deskripsi'] ?? '',
      'spesifikasi' => $detail['spesifikasi'] ?? '',
      'port' => $detail['port'] ?? '',
      'db_access' => $detail['db_access'] ?? '',
      'server_access' => $detail['server_access'] ?? '',
    );

    return view('detail/hosting', $data);
  }

  public function tte_page($id_tiket, $kode_tiket)
  {
    $tiket = $this->getTicketData($id_tiket, $kode_tiket);
    $detailRow = $this->getLegacyDetailRow((int) $id_tiket, 'tte');

    if (!$tiket || !$detailRow) {
      return view('errors/html/error_404');
    }

    $detail = $detailRow['detail'];

    $data = array(
      'title' => 'tiket',
      'id_tiket' => $id_tiket,
      'kode_tiket' => $tiket['kode_tiket'],
      'tgl_input' => $tiket['tgl_input'],
      'status' => $tiket['status'],
      'catatan' => $tiket['catatan'],
      'id_user' => $tiket['id_user'],
      'nama_pic' => $detail['nama_pic'] ?? '',
      'no_pic' => $detail['no_pic'] ?? '',
      'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
      'nama' => $detail['nama'] ?? ($detailRow['judul'] ?? ''),
      'jabatan' => $detail['jabatan'] ?? '',
      'nip' => $detail['nip'] ?? '',
      'nik' => $detail['nik'] ?? '',
      'jenis_layanan' => $detail['jenis_layanan'] ?? '',
      'berkas_ktp' => $detail['berkas_ktp'] ?? '',
    );

    return view('detail/tte', $data);
  }

  public function app_page($id_tiket, $kode_tiket)
  {
    $tiket = $this->getTicketData($id_tiket, $kode_tiket);
    $detailRow = $this->getLegacyDetailRow((int) $id_tiket, 'app');

    if (!$tiket || !$detailRow) {
      return view('errors/html/error_404');
    }

    $detail = $detailRow['detail'];

    $data = array(
      'title' => 'tiket',
      'id_tiket' => $id_tiket,
      'kode_tiket' => $tiket['kode_tiket'],
      'tgl_input' => $tiket['tgl_input'],
      'status' => $tiket['status'],
      'catatan' => $tiket['catatan'],
      'id_user' => $tiket['id_user'],
      'nama_pic' => $detail['nama_pic'] ?? '',
      'no_pic' => $detail['no_pic'] ?? '',
      'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
      'agenda' => $detail['agenda'] ?? '',
      'tempat' => $detail['tempat'] ?? '',
      'tgl' => $detail['tgl'] ?? '',
      'nama_aplikasi' => $detail['nama_aplikasi'] ?? ($detailRow['judul'] ?? ''),
      'deskripsi_aplikasi' => $detail['deskripsi_aplikasi'] ?? '',
    );

    return view('detail/app', $data);
  }

  public function jaringan_page($id_tiket, $kode_tiket)
  {
    $tiket = $this->getTicketData($id_tiket, $kode_tiket);
    $detailRow = $this->getLegacyDetailRow((int) $id_tiket, 'jaringan');

    if (!$tiket || !$detailRow) {
      return view('errors/html/error_404');
    }

    $detail = $detailRow['detail'];
    $foto = $detail['dokumentasi'] ?? ($detail['foto'] ?? []);
    if (!is_array($foto)) {
      $foto = [];
    }

    $data = array(
      'title' => 'tiket',
      'id_tiket' => $id_tiket,
      'kode_tiket' => $tiket['kode_tiket'],
      'tgl_input' => $tiket['tgl_input'],
      'status' => $tiket['status'],
      'catatan' => $tiket['catatan'],
      'id_user' => $tiket['id_user'],
      'nama_pic' => $detail['nama_pic'] ?? '',
      'no_pic' => $detail['no_pic'] ?? '',
      'tgl_kejadian' => $detail['tgl_kejadian'] ?? '',
      'keluhan' => $detail['keluhan'] ?? '',
      'tindak_lanjut' => $detail['tindak_lanjut'] ?? '',
      'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
      'foto' => $foto,
    );

    return view('detail/jaringan', $data);
  }

  public function alat_page($id_tiket, $kode_tiket)
  {
    $tiket = $this->getTicketData($id_tiket, $kode_tiket);
    $detailRow = $this->getLegacyDetailRow((int) $id_tiket, 'alat');

    if (!$tiket || !$detailRow) {
      return view('errors/html/error_404');
    }

    $detail = $detailRow['detail'];
    $alatIds = $detail['list_alat'] ?? [];
    if (is_string($alatIds)) {
      $alatIds = array_values(array_filter(array_map('trim', explode(',', $alatIds)), static fn ($v) => $v !== ''));
    } elseif (!is_array($alatIds)) {
      $alatIds = [];
    }

    $list = [];
    if (!empty($alatIds)) {
      $db = \Config\Database::connect();
      $list = $db->table('ssalat')
        ->select('nama_alat, merk')
        ->whereIn('id_alat', $alatIds)
        ->get()
        ->getResult();
    }

    $data = array(
      'title' => 'tiket',
      'id_tiket' => $id_tiket,
      'kode_tiket' => $tiket['kode_tiket'],
      'tgl_input' => $tiket['tgl_input'],
      'status' => $tiket['status'],
      'catatan' => $tiket['catatan'],
      'id_user' => $tiket['id_user'],
      'nama_pic' => $detail['nama_pic'] ?? '',
      'no_pic' => $detail['no_pic'] ?? '',
      'berkas_pengantar' => $detail['berkas_pengantar'] ?? '',
      'tgl_akhir' => $detailRow['selesai'],
      'tgl_awal' => $detailRow['mulai'],
      'nama_acara' => $detailRow['judul'] ?? '',
      'list' => $list,
    );

    return view('detail/alat', $data);
  }

  public function magang_page($id_tiket, $kode_tiket)
  {
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

      try {
        $tiket = $db->table('tb_tiket')
          ->select('tb_tiket.id_user')
          ->where("tb_tiket.id_tiket", $this->request->getVar('id_tiket'))
          ->get()
          ->getRow();

        if ($tiket) {
          $userTableFields = $db->getFieldNames('ssuser');
          $notifField = null;

          if (in_array('id_chat', $userTableFields, true)) {
            $notifField = 'id_chat';
          } elseif (in_array('email', $userTableFields, true)) {
            $notifField = 'email';
          }

          if ($notifField !== null) {
            $user = $db->table('ssuser')
              ->select('ssuser.' . $notifField)
              ->where("ssuser.id_ssuser", $tiket->id_user)
              ->get()
              ->getRow();

            if ($user && !empty($user->{$notifField})) {
              helper('notification_helper');
              telegram($user->{$notifField}, $message);
            }
          }
        }
      } catch (\Throwable $e) {
        log_message('error', 'Gagal mengirim notifikasi update tiket: ' . $e->getMessage());
      }
    }

    echo json_encode($response);
  }

  // -----------------------------------------------------------------------------------------------------------------------
  // Update

  public function update_meeting()
  {
    $logModel = new Log_tiket();

    try {
      $this->mergeLegacyDetail((int) $this->request->getVar('id_tiket'), 'zoom', [
        'meeting_id' => $this->request->getVar('meeting_id'),
        'passcode'   => $this->request->getVar('passcode'),
      ]);
    } catch (\Throwable $e) {
      log_message('error', 'Gagal update meeting zoom: ' . $e->getMessage());
      return $this->response->setJSON([
        'status' => 500,
        'message' => 'Internal server error',
      ]);
    }

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

    try {
      $this->mergeLegacyDetail((int) $this->request->getVar('id_tiket'), 'jaringan', [
        'tindak_lanjut' => $this->request->getVar('tindak_lanjut'),
      ]);
    } catch (\Throwable $e) {
      log_message('error', 'Gagal update tindak lanjut jaringan: ' . $e->getMessage());
      return $this->response->setJSON([
        'status' => 500,
        'message' => 'Internal server error',
      ]);
    }

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

    try {
      $this->mergeLegacyDetail((int) $this->request->getVar('id_tiket'), 'hosting', [
        'db_access' => $this->request->getVar('db'),
        'server_access' => $this->request->getVar('ssh'),
      ]);
    } catch (\Throwable $e) {
      log_message('error', 'Gagal update akses hosting: ' . $e->getMessage());
      return $this->response->setJSON([
        'status' => 500,
        'message' => 'Internal server error',
      ]);
    }

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
