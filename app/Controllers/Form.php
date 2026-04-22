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
use App\Models\Pelayanan;
use App\Models\PelayananField;
use App\Models\TiketDetail;

class Form extends BaseController {
    public function __construct()
    {
        helper(['form', 'url']);
        date_default_timezone_set('Asia/Jakarta');
    }

    private function parseDateTimeValue($value)
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        return date('Y-m-d H:i:s', strtotime($value));
    }

    private function moveUploadedFile(string $field, string $relativeDir, string $baseName): string
    {
        $file = $this->request->getFile($field);

        if (!$file || !$file->isValid()) {
            throw new \RuntimeException('File upload tidak valid untuk field: ' . $field);
        }

        $directory = ROOTPATH . 'public/' . trim($relativeDir, '/');
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $ext = $file->guessExtension();
        if (!$ext) {
            $ext = $file->getClientExtension();
        }
        if (!$ext) {
            $ext = 'bin';
        }

        $fileName = preg_replace('/\s+/', '', $baseName . '.' . $ext);
        $file->move($directory, $fileName, true);

        return $fileName;
    }

    private function createTicketHeader(int $idPelayanan, string $kode, string $tgl, int $status = 0, ?string $catatan = null): int
    {
        $tiketModel = new Tiket();
        $payload = [
            'kode_tiket'   => $kode,
            'tgl_input'    => $tgl,
            'id_pelayanan' => $idPelayanan,
            'id_user'      => session()->get('id_user'),
            'status'       => $status,
        ];

        if ($catatan !== null) {
            $payload['catatan'] = $catatan;
        }

        $tiketModel->insert($payload);

        return (int) $tiketModel->getInsertID();
    }

    private function createTicketLog(int $idTiket, string $tgl, string $aktifitas = 'Membuat tiket'): void
    {
        $logModel = new Log_tiket();
        $logModel->insert([
            'id_tiket'      => $idTiket,
            'id_user'       => session()->get('id_user'),
            'tgl_aktifitas' => $tgl,
            'aktifitas'     => $aktifitas,
            'color'         => 'warning',
            'icon'          => 'fas fa-ticket-alt',
        ]);
    }

    private function createLegacyDetail(int $idTiket, string $tipe, array $detail, ?string $judul = null, ?string $mulai = null, ?string $selesai = null): void
    {
        $db = \Config\Database::connect();
        $db->table('tb_tiket_detail')->insert([
            'id_tiket'  => $idTiket,
            'tipe'      => $tipe,
            'judul'     => $judul,
            'mulai'     => $mulai,
            'selesai'   => $selesai,
            'detail'    => json_encode($detail),
            'created_at'=> date('Y-m-d H:i:sP'),
        ]);
    }


    private function normalizeText($value): string
    {
        $value = trim((string) ($value ?? ''));
        if ($value === '') {
            return '';
        }

        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
    }

    private function getAulaInfoById(?int $idAula): ?array
    {
        if (!$idAula) {
            return null;
        }

        $db = \Config\Database::connect();
        $row = $db->table('ssaula')->where('id_aula', $idAula)->get()->getRowArray();

        return $row ?: null;
    }

    private function getAulaInfoByPlace(?string $place): ?array
    {
        $normalizedPlace = $this->normalizeText($place);
        if ($normalizedPlace === '') {
            return null;
        }

        $db = \Config\Database::connect();
        $rows = $db->table('ssaula')->get()->getResultArray();
        foreach ($rows as $row) {
            if ($this->normalizeText($row['nama_aula'] ?? '') === $normalizedPlace) {
                return $row;
            }
        }

        return null;
    }

    private function getBorrowingOverlapCandidates(string $mulai, string $selesai, array $types): array
    {
        if ($mulai === '' || $selesai === '' || empty($types)) {
            return [];
        }

        $db = \Config\Database::connect();
        $escapedMulai = $db->escape($mulai);
        $rows = $db->table('tb_tiket_detail d')
            ->select('d.id_tiket, d.tipe, d.judul, d.mulai, d.selesai, d.detail, t.kode_tiket, t.status')
            ->join('tb_tiket t', 't.id_tiket = d.id_tiket')
            ->whereIn('d.tipe', $types)
            ->whereIn('t.status', [0, 1])
            ->where('d.mulai <', $selesai)
            ->where("COALESCE(d.selesai, d.mulai) > {$escapedMulai}", null, false)
            ->orderBy('d.mulai', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $detail = $row['detail'] ?? [];
            if (is_string($detail)) {
                $decoded = json_decode($detail, true);
                $detail = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($detail)) {
                $detail = [];
            }
            $row['detail'] = $detail;
        }
        unset($row);

        return $rows;
    }

    private function findBorrowingConflict(string $resourceType, string $mulai, string $selesai, array $context = []): ?array
    {
        if ($mulai === '' || $selesai === '') {
            return null;
        }

        if ($resourceType === 'alat') {
            $alatIds = array_values(array_unique(array_map('strval', $context['alat_ids'] ?? [])));
            if (empty($alatIds)) {
                return null;
            }

            $candidates = $this->getBorrowingOverlapCandidates($mulai, $selesai, ['alat']);
            foreach ($candidates as $candidate) {
                $existingIds = array_values(array_unique(array_map('strval', $candidate['detail']['list_alat'] ?? [])));
                $sameAlat = array_values(array_intersect($alatIds, $existingIds));
                if (!empty($sameAlat)) {
                    $candidate['matched_alat'] = $sameAlat;
                    return $candidate;
                }
            }

            return null;
        }

        $contextAulaId = isset($context['id_aula']) && $context['id_aula'] !== '' ? (string) $context['id_aula'] : null;
        $contextPlace = $this->normalizeText($context['tempat'] ?? '');
        $candidates = $this->getBorrowingOverlapCandidates($mulai, $selesai, ['zoom', 'aula']);

        foreach ($candidates as $candidate) {
            if ($candidate['tipe'] === 'aula') {
                $existingAulaId = isset($candidate['detail']['id_aula']) ? (string) $candidate['detail']['id_aula'] : null;
                if ($contextAulaId !== null && $existingAulaId !== null && $contextAulaId === $existingAulaId) {
                    return $candidate;
                }
                continue;
            }

            if ($candidate['tipe'] === 'zoom') {
                $existingPlace = $this->normalizeText($candidate['detail']['tempat'] ?? '');
                if ($contextPlace !== '' && $existingPlace !== '' && $contextPlace === $existingPlace) {
                    return $candidate;
                }
            }
        }

        return null;
    }

    private function buildBorrowingConflictNote(string $label, array $conflict, array $context = []): string
    {
        $kodeTiket = trim((string) ($conflict['kode_tiket'] ?? '-'));
        $judul = trim((string) ($conflict['judul'] ?? '-'));
        $mulai = !empty($conflict['mulai']) ? date('d-m-Y H:i', strtotime((string) $conflict['mulai'])) : '-';
        $selesai = !empty($conflict['selesai']) ? date('d-m-Y H:i', strtotime((string) $conflict['selesai'])) : $mulai;
        $lokasi = trim((string) ($context['lokasi_label'] ?? ''));

        if ($label === 'Peralatan Zoom') {
            $matchedAlat = $context['matched_alat'] ?? [];
            $matchedText = empty($matchedAlat) ? '' : ' pada alat [' . implode(', ', $matchedAlat) . ']';
            return $label . ' otomatis ditolak karena jadwal peminjaman nabrak' . $matchedText . ' dengan tiket ' . $kodeTiket . ' (' . $judul . ') pada ' . $mulai . ' - ' . $selesai . '.';
        }

        $lokasiText = $lokasi !== '' ? ' di ' . $lokasi : '';
        return $label . ' otomatis ditolak karena jadwal peminjaman nabrak' . $lokasiText . ' dengan tiket ' . $kodeTiket . ' (' . $judul . ') pada ' . $mulai . ' - ' . $selesai . '.';
    }

    private function rejectTicket(int $idTiket, string $tgl, string $catatan): void
    {
        $tiketModel = new Tiket();
        $tiketModel->update($idTiket, [
            'status' => 2,
            'catatan' => $catatan,
        ]);

        $logModel = new Log_tiket();
        $logModel->insert([
            'id_tiket'      => $idTiket,
            'id_user'       => session()->get('id_user'),
            'tgl_aktifitas' => $tgl,
            'aktifitas'     => 'Memperbaharui catatan',
            'color'         => 'primary',
            'icon'          => 'far fa-edit',
        ]);
        $logModel->insert([
            'id_tiket'      => $idTiket,
            'id_user'       => session()->get('id_user'),
            'tgl_aktifitas' => $tgl,
            'aktifitas'     => 'Tiket telah ditolak',
            'color'         => 'danger',
            'icon'          => 'fa fa-times',
        ]);
    }

    private function respondTicketSuccess(int $idTiket, string $kode, string $route, string $message = 'Tiket Berhasil Dibuat.', array $extra = []): void
    {
        echo json_encode(array_merge([
            'status'       => 200,
            'message'      => $message,
            'id_tiket'     => $idTiket,
            'kode_tiket'   => $kode,
            'redirect_url' => base_url('/detail/' . $route . '/' . $idTiket . '/' . $kode),
        ], $extra));
    }

    private function respondTicketError(\Throwable $e): void
    {
        log_message('error', 'Gagal membuat tiket layanan: ' . $e->getMessage());
        echo json_encode([
            'status'  => 500,
            'message' => 'Internal server error',
        ]);
    }
    
    public function get_tiket()
    {
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

    public function get_count_tiket()
    {
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

    public function get_tiket_pelayanan()
    {
        $db = \Config\Database::connect();
        if($this->request->getVar('status')==4){
            $builder = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_pelayanan', $this->request->getVar('id_pelayanan'))->orderBy('tb_tiket.id_tiket', 'DESC')->get()->getResult();
        }else{
            $builder = $db->table('tb_tiket')->select('tb_tiket.id_tiket, tb_tiket.kode_tiket, tb_tiket.tgl_input, tb_tiket.status, sspelayanan.nama_pelayanan, ssuser.nama, ssopd.akronim_opd')->join('sspelayanan', 'tb_tiket.id_pelayanan = sspelayanan.id_pelayanan', 'left')->join('ssuser', 'ssuser.id_ssuser = tb_tiket.id_user', 'left')->join('ssopd', 'ssopd.id_opd = ssuser.id_opd', 'left')->where('tb_tiket.id_pelayanan', $this->request->getVar('id_pelayanan'))->where('tb_tiket.status', $this->request->getVar('status'))->where("date_part('year', tb_tiket.tgl_input)", $this->request->getVar('tahun'))->orderBy('tb_tiket.id_tiket', 'DESC')->get()->getResult();
        }
        
        echo json_encode($builder);
    }

    public function get_count_tiket_pelayanan()
    {
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
    // Dynamic Form (pelayanan dari DB)
    public function dynamic($route)
    {
        $pelayananModel = new Pelayanan();
        $pelayanan = $pelayananModel->where('route', $route)
            ->where('active', 1)
            ->first();

        // kalau tidak ada / tidak aktif
        if (!$pelayanan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // hanya tampilkan form dinamis jika sudah diset, dan punya field
        $fieldModel = new PelayananField();
        $fields = $fieldModel->where('id_pelayanan', $pelayanan['id_pelayanan'])
            ->where('active', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        if (count($fields) === 0) {
            // biar kompatibel: kalau pelayanan lama belum punya field dinamis
            // lempar 404 agar tidak membuat form kosong
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'tiket',
            'pelayanan' => $pelayanan,
            'fields' => $fields,
        ];
        return view('form_tiket/dynamic', $data);
    }

    public function submit_dynamic($route)
    {
        $pelayananModel = new Pelayanan();
        $pelayanan = $pelayananModel->where('route', $route)
            ->where('active', 1)
            ->first();

        if (!$pelayanan) {
            return $this->response->setJSON([
                'status'  => 404,
                'message' => 'Pelayanan tidak ditemukan'
            ]);
        }

        $fieldModel = new PelayananField();
        $fields = $fieldModel->where('id_pelayanan', $pelayanan['id_pelayanan'])
            ->where('active', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        if (count($fields) === 0) {
            return $this->response->setJSON([
                'status'  => 400,
                'message' => 'Form pelayanan belum diatur oleh admin'
            ]);
        }

        foreach ($fields as $f) {
            if ((int) $f['is_required'] === 1) {
                if ($f['type'] === 'file') {
                    $file = $this->request->getFile($f['field_key']);
                    if (!$file || !$file->isValid()) {
                        return $this->response->setJSON([
                            'status'  => 422,
                            'message' => 'Field wajib diisi: ' . $f['label']
                        ]);
                    }
                } else {
                    $val = $this->request->getVar($f['field_key']);
                    if ($val === null || trim((string) $val) === '') {
                        return $this->response->setJSON([
                            'status'  => 422,
                            'message' => 'Field wajib diisi: ' . $f['label']
                        ]);
                    }
                }
            }
        }

        $tgl  = date('Y-m-d H:i:s');
        $kode = trim((string) $this->request->getVar('kode_tiket'));
        if ($kode === '') {
            $kode = 'DL-' . date('YmdHis');
        }

        $tiketModel  = new Tiket();
        $detailModel = new TiketDetail();
        $logModel    = new Log_tiket();

        try {
            $tiketModel->insert([
                'kode_tiket'   => $kode,
                'tgl_input'    => $tgl,
                'id_pelayanan' => $pelayanan['id_pelayanan'],
                'id_user'      => session()->get('id_user'),
                'status'       => 0,
            ]);

            $id_tiket = $tiketModel->getInsertID();

            foreach ($fields as $f) {
                $valueText = null;
                $valueFile = null;

                if ($f['type'] === 'file') {
                    $file = $this->request->getFile($f['field_key']);

                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        $dir = FCPATH . 'assets/berkas/dynamic/' . $route . '/';
                        if (!is_dir($dir)) {
                            mkdir($dir, 0775, true);
                        }

                        $ext = $file->guessExtension();
                        if (!$ext) {
                            $ext = $file->getClientExtension();
                        }
                        if (!$ext) {
                            $ext = 'bin';
                        }

                        $fileName = md5($id_tiket . '-' . $f['id_field'] . '-' . time()) . '.' . $ext;
                        $fileName = str_replace(' ', '', $fileName);

                        $file->move($dir, $fileName);
                        $valueFile = $fileName;
                    }
                } else {
                    $valueText = trim((string) $this->request->getVar($f['field_key']));
                }

                if ($valueText !== null || $valueFile !== null) {
                    $detailModel->insert([
                        'id_tiket'   => $id_tiket,
                        'id_field'   => $f['id_field'],
                        'value_text' => $valueText,
                        'value_file' => $valueFile,
                        'tgl_input'  => $tgl,
                    ]);
                }
            }

            // simpan log lebih dulu
            $logModel->insert([
                'id_tiket'      => $id_tiket,
                'id_user'       => session()->get('id_user'),
                'tgl_aktifitas' => $tgl,
                'aktifitas'     => 'Membuat tiket',
                'color'         => 'warning',
                'icon'          => 'fas fa-ticket-alt',
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 500,
                'message' => 'Gagal membuat tiket: ' . $e->getMessage()
            ]);
        }

        // notifikasi jangan bikin request macet
        try {
            $this->notification($pelayanan['id_pelayanan']);
        } catch (\Throwable $e) {
            log_message('error', 'Notif submit_dynamic gagal: ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'status'       => 200,
            'message'      => 'Tiket berhasil dibuat',
            'id_tiket'     => $id_tiket,
            'kode_tiket'   => $kode,
            'redirect_url' => base_url('/detail/' . $route . '/' . $id_tiket . '/' . $kode),
        ]);
    }
    // -----------------------------------------------------------------------------------------------------------------------
    // Zoom
    public function zoom_page()
    {
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/zoom',$data);
    }
    public function add_zoom()
    {
        $db = \Config\Database::connect();
        $tgl = date("Y-m-d H:i:s");

        $kodeZoom = trim((string) $this->request->getVar('kode_zoom'));
        $acara = trim((string) $this->request->getVar('acara'));
        $tglAwal = $this->parseDateTimeValue($this->request->getVar('tgl_mulai'));
        $tglAkhir = $this->parseDateTimeValue($this->request->getVar('tgl_akhir'));
        $namaPic = $this->request->getVar('nama_pic');
        $noPic = $this->request->getVar('nomor_pic');
        $isAula = $this->request->getVar('is_aula') === '1';
        $isAlat = $this->request->getVar('is_alat') === '1';
        $tempatRequest = trim((string) $this->request->getVar('tempat'));
        $selectedAulaId = $isAula ? (int) $this->request->getVar('myAula') : null;
        $aulaInfo = $selectedAulaId ? $this->getAulaInfoById($selectedAulaId) : $this->getAulaInfoByPlace($tempatRequest);
        $tempatZoom = $aulaInfo['nama_aula'] ?? $tempatRequest;

        $alatList = $this->request->getVar('myAlat');
        if (is_string($alatList)) {
            $alatList = array_values(array_filter(array_map('trim', explode(',', $alatList)), static fn ($v) => $v !== ''));
        } elseif (!is_array($alatList)) {
            $alatList = [];
        }

        $zoomConflict = $this->findBorrowingConflict('zoom', (string) $tglAwal, (string) $tglAkhir, [
            'id_aula' => $aulaInfo['id_aula'] ?? null,
            'tempat' => $tempatZoom,
        ]);
        $aulaConflict = $isAula ? $this->findBorrowingConflict('aula', (string) $tglAwal, (string) $tglAkhir, [
            'id_aula' => $selectedAulaId,
            'tempat' => $aulaInfo['nama_aula'] ?? $tempatZoom,
        ]) : null;
        $alatConflict = $isAlat ? $this->findBorrowingConflict('alat', (string) $tglAwal, (string) $tglAkhir, [
            'alat_ids' => $alatList,
        ]) : null;

        $zoomNote = $zoomConflict ? $this->buildBorrowingConflictNote('Zoom Meeting', $zoomConflict, [
            'lokasi_label' => $tempatZoom,
        ]) : null;
        $aulaNote = $aulaConflict ? $this->buildBorrowingConflictNote('Pinjam Aula', $aulaConflict, [
            'lokasi_label' => $aulaInfo['nama_aula'] ?? $tempatZoom,
        ]) : null;
        $alatNote = $alatConflict ? $this->buildBorrowingConflictNote('Peralatan Zoom', $alatConflict, [
            'matched_alat' => $alatConflict['matched_alat'] ?? [],
        ]) : null;

        $createdNotes = [];
        $notifyIds = [];
        $zoomId = 0;

        try {
            $db->transBegin();
            $fileName = null;

            $zoomId = $this->createTicketHeader(4, $kodeZoom, $tgl);
            $fileName = $this->moveUploadedFile('berkas', 'assets/berkas/surat-pengantar', md5((string) $zoomId));

            $this->createLegacyDetail($zoomId, 'zoom', [
                'id_pelayanan_zoom' => null,
                'nama_pic'          => $namaPic,
                'no_pic'            => $noPic,
                'berkas_pengantar'  => $fileName,
                'jenis_zoom'        => (string) $this->request->getVar('jenis'),
                'meeting_id'        => $this->request->getVar('meeting_id'),
                'passcode'          => $this->request->getVar('passcode'),
                'tempat'            => $tempatZoom,
                'operator'          => (string) $this->request->getVar('is_operator'),
            ], $acara, $tglAwal, $tglAkhir);
            $this->createTicketLog($zoomId, $tgl);

            if ($zoomNote !== null) {
                $this->rejectTicket($zoomId, $tgl, $zoomNote);
                $createdNotes[] = $zoomNote;
            } else {
                $notifyIds[] = 4;
            }

            if ($isAula) {
                $kodeAula = trim((string) $this->request->getVar('kode_aula'));
                $aulaId = $this->createTicketHeader(6, $kodeAula, $tgl);
                $this->createLegacyDetail($aulaId, 'aula', [
                    'id_pelayanan_aula' => null,
                    'id_aula'           => $selectedAulaId,
                    'nama_pic'          => $namaPic,
                    'no_pic'            => $noPic,
                    'berkas_pengantar'  => $fileName,
                ], $acara, $tglAwal, $tglAkhir);
                $this->createTicketLog($aulaId, $tgl);

                if ($aulaNote !== null) {
                    $this->rejectTicket($aulaId, $tgl, $aulaNote);
                    $createdNotes[] = $aulaNote;
                } else {
                    $notifyIds[] = 6;
                }
            }

            if ($isAlat) {
                $kodeAlat = trim((string) $this->request->getVar('kode_alat'));
                $alatId = $this->createTicketHeader(13, $kodeAlat, $tgl);
                $this->createLegacyDetail($alatId, 'alat', [
                    'nama_pic'         => $namaPic,
                    'no_pic'           => $noPic,
                    'berkas_pengantar' => $fileName,
                    'list_alat'        => array_values($alatList),
                ], $acara, $tglAwal, $tglAkhir);
                $this->createTicketLog($alatId, $tgl);

                if ($alatNote !== null) {
                    $this->rejectTicket($alatId, $tgl, $alatNote);
                    $createdNotes[] = $alatNote;
                } else {
                    $notifyIds[] = 13;
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi penyimpanan zoom gagal');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->respondTicketError($e);
            return;
        }

        foreach (array_values(array_unique($notifyIds)) as $notifId) {
            try {
                $this->notification($notifId);
            } catch (\Throwable $e) {
                log_message('error', 'Notif tiket gagal: ' . $e->getMessage());
            }
        }

        if (!empty($createdNotes)) {
            $message = "Tiket peminjaman berhasil dibuat, tetapi ada yang otomatis ditolak karena jadwal nabrak:
- " . implode("
- ", $createdNotes);
            $this->respondTicketSuccess($zoomId, $kodeZoom, 'zoom', $message, [
                'is_conflict' => true,
                'conflict_notes' => $createdNotes,
            ]);
            return;
        }

        $this->respondTicketSuccess($zoomId, $kodeZoom, 'zoom');
        return;
    }

    public function add_zoom_calendar()
    {
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

    public function aula_page()
    {
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/aula',$data);
    }

    public function add_aula()
    {
        $db = \Config\Database::connect();
        $tgl = date("Y-m-d H:i:s");
        $kode = trim((string) $this->request->getVar('kode'));
        $acara = trim((string) $this->request->getVar('acara'));
        $tglAwal = $this->parseDateTimeValue($this->request->getVar('tgl_mulai'));
        $tglAkhir = $this->parseDateTimeValue($this->request->getVar('tgl_akhir'));
        $selectedAulaId = (int) $this->request->getVar('myAula');
        $aulaInfo = $this->getAulaInfoById($selectedAulaId);
        $aulaName = $aulaInfo['nama_aula'] ?? '';

        $aulaConflict = $this->findBorrowingConflict('aula', (string) $tglAwal, (string) $tglAkhir, [
            'id_aula' => $selectedAulaId,
            'tempat' => $aulaName,
        ]);
        $aulaNote = $aulaConflict ? $this->buildBorrowingConflictNote('Pinjam Aula', $aulaConflict, [
            'lokasi_label' => $aulaName,
        ]) : null;

        try {
            $db->transBegin();

            $id = $this->createTicketHeader(6, $kode, $tgl);
            $fileName = $this->moveUploadedFile('berkas', 'assets/berkas/surat-pengantar', md5((string) $id));

            $this->createLegacyDetail($id, 'aula', [
                'id_pelayanan_aula' => null,
                'id_aula'           => $selectedAulaId,
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $fileName,
            ], $acara, $tglAwal, $tglAkhir);

            $this->createTicketLog($id, $tgl);

            if ($aulaNote !== null) {
                $this->rejectTicket($id, $tgl, $aulaNote);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi penyimpanan aula gagal');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->respondTicketError($e);
            return;
        }

        if ($aulaNote === null) {
            try {
                $this->notification(6);
            } catch (\Throwable $e) {
                log_message('error', 'Notif tiket aula gagal: ' . $e->getMessage());
            }

            $this->respondTicketSuccess($id, $kode, 'aula');
            return;
        }

        $this->respondTicketSuccess($id, $kode, 'aula', $aulaNote, [
            'is_conflict' => true,
            'conflict_notes' => [$aulaNote],
        ]);
        return;
    }

    public function add_aula_calendar()
    {
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
    public function subdomain_page()
    {
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/sub-domain',$data);
    }

    public function add_subdomain()
    {
        $db = \Config\Database::connect();
        $tgl = date("Y-m-d H:i:s");

        try {
            $db->transBegin();

            $kode = trim((string) $this->request->getVar('kode'));
            $id = $this->createTicketHeader(5, $kode, $tgl);
            $fileName = $this->moveUploadedFile('berkas', 'assets/berkas/surat-pengantar', md5((string) $id));

            $this->createLegacyDetail($id, 'subdomain', [
                'nama_subdomain'    => $this->request->getVar('subdomain'),
                'ip_publik'         => $this->request->getVar('ip'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $fileName,
            ], trim((string) $this->request->getVar('subdomain')));

            $this->createTicketLog($id, $tgl);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi penyimpanan subdomain gagal');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->respondTicketError($e);
            return;
        }

        try {
            $this->notification(5);
        } catch (\Throwable $e) {
            log_message('error', 'Notif tiket subdomain gagal: ' . $e->getMessage());
        }

        $this->respondTicketSuccess($id, $kode, 'sub-domain');
        return;
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // Upload Dokumen
    
    public function upload_page()
    {
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/upload',$data);
    }

    public function add_upload()
    {
        $db = \Config\Database::connect();
        $tgl = date("Y-m-d H:i:s");

        try {
            $db->transBegin();

            $kode = trim((string) $this->request->getVar('kode'));
            $id = $this->createTicketHeader(7, $kode, $tgl);
            $fileName = $this->moveUploadedFile('berkas', 'assets/berkas/surat-pengantar', md5((string) $id));
            $uploadName = $this->moveUploadedFile('dokumen', 'assets/berkas/upload', md5((string) $id) . '_upload');

            $this->createLegacyDetail($id, 'upload', [
                'edisi'             => $this->request->getVar('edisi'),
                'jenis_dokumen'     => $this->request->getVar('jenis'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $fileName,
                'berkas_upload'     => $uploadName,
            ], 'Upload Dokumen');

            $this->createTicketLog($id, $tgl);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi penyimpanan upload dokumen gagal');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->respondTicketError($e);
            return;
        }

        try {
            $this->notification(7);
        } catch (\Throwable $e) {
            log_message('error', 'Notif tiket upload gagal: ' . $e->getMessage());
        }

        $this->respondTicketSuccess($id, $kode, 'upload-document');
        return;
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // Hosting
    
    public function hosting_page()
    {
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/hosting',$data);
    }

    public function add_hosting()
    {
        $db = \Config\Database::connect();
        $tgl = date("Y-m-d H:i:s");

        try {
            $db->transBegin();

            $kode = trim((string) $this->request->getVar('kode'));
            $id = $this->createTicketHeader(8, $kode, $tgl);
            $fileName = $this->moveUploadedFile('berkas', 'assets/berkas/surat-pengantar', md5((string) $id));

            $this->createLegacyDetail($id, 'hosting', [
                'nama_aplikasi'     => $this->request->getVar('nama'),
                'deskripsi'         => $this->request->getVar('deskripsi'),
                'spesifikasi'       => $this->request->getVar('spesifikasi'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'port'              => $this->request->getVar('port'),
                'db_access'         => $this->request->getVar('db_access'),
                'server_access'     => $this->request->getVar('server_access'),
                'berkas_pengantar'  => $fileName,
            ], trim((string) $this->request->getVar('nama')));

            $this->createTicketLog($id, $tgl);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi penyimpanan hosting gagal');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->respondTicketError($e);
            return;
        }

        try {
            $this->notification(8);
        } catch (\Throwable $e) {
            log_message('error', 'Notif tiket hosting gagal: ' . $e->getMessage());
        }

        $this->respondTicketSuccess($id, $kode, 'hosting');
        return;
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // TTE
    public function tte_page()
    {
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/tte',$data);
    }

    public function add_tte()
    {
        $db = \Config\Database::connect();
        $tgl = date("Y-m-d H:i:s");

        try {
            $db->transBegin();

            $kode = trim((string) $this->request->getVar('kode'));
            $id = $this->createTicketHeader(9, $kode, $tgl);
            $fileName = $this->moveUploadedFile('berkas', 'assets/berkas/surat-pengantar', md5((string) $id));
            $ktpName = $this->moveUploadedFile('ktp', 'assets/berkas/ktp', md5((string) $id) . '_ktp');

            $this->createLegacyDetail($id, 'tte', [
                'jenis_layanan'     => $this->request->getVar('jenis'),
                'nama'              => $this->request->getVar('nama'),
                'jabatan'           => $this->request->getVar('jabatan'),
                'nip'               => $this->request->getVar('nip'),
                'nik'               => $this->request->getVar('nik'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $fileName,
                'berkas_ktp'        => $ktpName,
            ], trim((string) $this->request->getVar('nama')));

            $this->createTicketLog($id, $tgl);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi penyimpanan TTE gagal');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->respondTicketError($e);
            return;
        }

        try {
            $this->notification(9);
        } catch (\Throwable $e) {
            log_message('error', 'Notif tiket TTE gagal: ' . $e->getMessage());
        }

        $this->respondTicketSuccess($id, $kode, 'sertifikat-tte');
        return;
    }

    // -----------------------------------------------------------------------------------------------------------------------
    // APP
    public function app_page()
    {
        $data = array(
            'title' => 'tiket'
        );
        return view('form_tiket/app',$data);
    }

    public function add_app()
    {
        $db = \Config\Database::connect();
        $tgl = date("Y-m-d H:i:s");

        try {
            $db->transBegin();

            $kode = trim((string) $this->request->getVar('kode'));
            $id = $this->createTicketHeader(10, $kode, $tgl);
            $fileName = $this->moveUploadedFile('berkas', 'assets/berkas/surat-pengantar', md5((string) $id));

            $this->createLegacyDetail($id, 'app', [
                'nama_aplikasi'      => $this->request->getVar('nama'),
                'deskripsi_aplikasi' => $this->request->getVar('deskripsi'),
                'tgl'                => $this->request->getVar('tgl'),
                'tempat'             => $this->request->getVar('tempat'),
                'agenda'             => $this->request->getVar('agenda'),
                'nama_pic'           => $this->request->getVar('nama_pic'),
                'no_pic'             => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'   => $fileName,
            ], trim((string) $this->request->getVar('nama')), $this->parseDateTimeValue($this->request->getVar('tgl')));

            $this->createTicketLog($id, $tgl);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi penyimpanan pendampingan aplikasi gagal');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->respondTicketError($e);
            return;
        }

        try {
            $this->notification(10);
        } catch (\Throwable $e) {
            log_message('error', 'Notif tiket pendampingan aplikasi gagal: ' . $e->getMessage());
        }

        $this->respondTicketSuccess($id, $kode, 'pendampingan-aplikasi');
        return;
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

    public function add_jaringan()
    {
        $db = \Config\Database::connect();
        $tgl = date("Y-m-d H:i:s");

        try {
            $db->transBegin();

            $kode = trim((string) $this->request->getVar('kode'));
            $id = $this->createTicketHeader(11, $kode, $tgl);
            $fileName = $this->moveUploadedFile('berkas', 'assets/berkas/surat-pengantar', md5((string) $id));

            $dokumentasi = [];
            $jumlahDokumentasi = (int) $this->request->getVar('jumlah_dokumentasi');
            for ($x = 0; $x < $jumlahDokumentasi; $x++) {
                $fieldName = 'dokumentasi_' . $x;
                $file = $this->request->getFile($fieldName);
                if (!$file || !$file->isValid()) {
                    continue;
                }

                $documentName = $this->moveUploadedFile($fieldName, 'assets/berkas/dokumentasi', md5((string) $id) . '_dokumentasi_' . $x);
                $dokumentasi[] = $documentName;
            }

            $this->createLegacyDetail($id, 'jaringan', [
                'tgl_kejadian'      => $this->request->getVar('tgl'),
                'keluhan'           => $this->request->getVar('keluhan'),
                'nama_pic'          => $this->request->getVar('nama_pic'),
                'no_pic'            => $this->request->getVar('nomor_pic'),
                'berkas_pengantar'  => $fileName,
                'tindak_lanjut'     => null,
                'foto'              => $dokumentasi,
                'dokumentasi'       => $dokumentasi,
            ], 'Pengaduan Jaringan', $this->parseDateTimeValue($this->request->getVar('tgl')));

            $this->createTicketLog($id, $tgl);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi penyimpanan pengaduan jaringan gagal');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            $this->respondTicketError($e);
            return;
        }

        try {
            $this->notification(11);
        } catch (\Throwable $e) {
            log_message('error', 'Notif tiket jaringan gagal: ' . $e->getMessage());
        }

        $this->respondTicketSuccess($id, $kode, 'pengaduan-jaringan');
        return;
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

        $pemohon = $db->table('ssuser')
            ->select('nama')
            ->where('id_ssuser', session()->get('id_user'))
            ->get()
            ->getRow();

        $opd = $db->table('ssopd')
            ->select('akronim_opd')
            ->where('id_opd', session()->get('id_opd'))
            ->get()
            ->getRow();

        $namaPemohon = $pemohon->nama ?? 'Pemohon';
        $namaOpd     = $opd->akronim_opd ?? '-';

        $verifikatorList = $db->table('verifikator_pelayanan vp')
            ->select('u.email')
            ->join('ssuser u', 'u.id_ssuser = vp.id_user', 'left')
            ->where('vp.id_pelayanan', $id_pelayanan)
            ->where('u.active', 1)
            ->get()
            ->getResult();

        helper('notification_helper');

        if (!empty($verifikatorList)) {
            foreach ($verifikatorList as $row) {
                if (empty($row->email)) {
                    continue;
                }

                $message = "Haloo Verifikator.\n"
                    . "Ayo login, ada permohonan tiket pelayanan yang harus kamu verifikasi.\n"
                    . "Nama : " . $namaPemohon . "\n"
                    . "OPD   : " . $namaOpd;

                telegram($row->email, $message);
            }
        } else {
            $adminList = $db->table('ssuser')
                ->select('email')
                ->where('active', 1)
                ->where('role_id', 0)
                ->get()
                ->getResult();

            foreach ($adminList as $row) {
                if (empty($row->email)) {
                    continue;
                }

                $message = "Haloo Admin.\n"
                    . "Ayo login, ada permohonan tiket pelayanan yang harus kamu verifikasi.\n"
                    . "Nama : " . $namaPemohon . "\n"
                    . "OPD   : " . $namaOpd;

                telegram($row->email, $message);
            }
        }
    }
}