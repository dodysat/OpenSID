<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\SHDKEnum;
use App\Models\Penduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Rtm extends Admin_Controller
{
    public $modul_ini            = 'kependudukan';
    public $sub_modul_ini        = 'rumah-tangga';
    private array $_set_page     = ['50', '100', '200'];
    private array $_list_session = ['status_dasar', 'cari', 'dusun', 'rw', 'rt', 'order_by', 'id_bos', 'kelas', 'judul_statistik', 'sex', 'bdt', 'penerima_bantuan'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['rtm_model', 'wilayah_model', 'program_bantuan_model']); // Session id_bos
    }

    public function clear(): void
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page     = $this->_set_page[0];
        $this->session->status_dasar = 1; // Rumah Tangga Aktif
        $this->session->order_by     = 1;

        redirect($this->controller);
    }

    public function index($page = 1, $order_by = 0): void
    {
        foreach ($this->_list_session as $list) {
            if (in_array($list, ['dusun', 'rw', 'rt'])) {
                ${$list} = $this->session->{$list};
            } else {
                $data[$list] = $this->session->{$list} ?: '';
            }
        }

        if (isset($dusun)) {
            $data['dusun']   = $dusun;
            $data['list_rw'] = $this->wilayah_model->list_rw($dusun);

            if (isset($rw)) {
                $data['rw']      = $rw;
                $data['list_rt'] = $this->wilayah_model->list_rt($dusun, $rw);

                $data['rt'] = $rt ?? '';
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = $data['rw'] = $data['rt'] = '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['pesan_rtm']        = $this->session->pesan_rtm ?: null; // Hasil impor rtm
        $this->session->pesan_rtm = null;
        $data['func']             = 'index';
        $data['set_page']         = $this->_set_page;
        $list_data                = $this->rtm_model->list_data($page);
        $data['paging']           = $list_data['paging'];
        $data['main']             = $list_data['main'];
        $data['keyword']          = $this->rtm_model->autocomplete();
        $data['list_dusun']       = $this->wilayah_model->list_dusun();
        $data['list_sex']         = $this->referensi_model->list_data('tweb_penduduk_sex');

        $this->render('rtm/rtm', $data);
    }

    // $aksi = cetak/unduh
    public function daftar($aksi = '', $privasi_nik = 0): void
    {
        $data['main'] = $this->rtm_model->list_data(0);
        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }
        $this->load->view("rtm/rtm_{$aksi}", $data);
    }

    public function edit_nokk($id = 0): void
    {
        $this->redirect_hak_akses('u');
        $data['kk']          = $this->rtm_model->get_rtm($id) ?? show_404();
        $data['form_action'] = site_url("{$this->controller}/update_nokk/{$id}");

        $this->load->view('rtm/ajax_edit_no_rtm', $data);
    }

    public function form_old($id = 0): void
    {
        $this->redirect_hak_akses('u');
        $data['form_action'] = site_url("{$this->controller}/insert/{$id}");

        $this->load->view('rtm/ajax_add_rtm', $data);
    }

    public function apipendudukrtm()
    {
        if ($this->input->is_ajax_request()) {
            $cari = $this->input->get('q');

            $penduduk = Penduduk::with('pendudukHubungan')
                ->select(['id', 'nik', 'nama', 'id_cluster', 'kk_level'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->where(static function ($query): void {
                    $query->where('id_rtm', '=', 0)
                        ->orWhere('id_rtm', '=', null);
                })
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->id,
                        'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun') . ' ' . $item->wilayah->dusun . ' - ' . $item->pendudukHubungan->nama),
                    ]),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    public function filter($filter = '', $order_by = ''): void
    {
        $value = $order_by ?: $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }

        redirect($this->controller);
    }

    public function dusun(): void
    {
        $this->session->unset_userdata(['rw', 'rt']);
        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $this->session->dusun = $dusun;
        } else {
            $this->session->unset_userdata('dusun');
        }

        redirect($this->controller);
    }

    public function rw(): void
    {
        $this->session->unset_userdata('rt');
        $rw = $this->input->post('rw');
        if ($rw != '') {
            $this->session->rw = $rw;
        } else {
            $this->session->unset_userdata('rw');
        }

        redirect($this->controller);
    }

    public function rt(): void
    {
        $rt = $this->input->post('rt');
        if ($rt != '') {
            $this->session->rt = $rt;
        } else {
            $this->session->unset_userdata('rt');
        }

        redirect($this->controller);
    }

    public function insert(): void
    {
        $this->redirect_hak_akses('u');
        $this->rtm_model->insert();
        $this->session->order_by = 6;

        redirect($this->controller);
    }

    public function insert_by_kk(): void
    {
        $this->redirect_hak_akses('u');
        $this->rtm_model->insert_by_kk();
        $this->session->order_by = 6;

        redirect($this->controller);
    }

    public function insert_a(): void
    {
        $this->redirect_hak_akses('u');
        $this->rtm_model->insert_a();
        $this->session->order_by = 6;

        redirect($this->controller);
    }

    public function insert_new(): void
    {
        $this->redirect_hak_akses('u');
        $this->rtm_model->insert_new();
        $this->session->order_by = 6;

        redirect($this->controller);
    }

    public function update($id = 0): void
    {
        $this->redirect_hak_akses('u');
        $this->rtm_model->update($id);

        redirect($this->controller);
    }

    public function update_nokk($id = 0): void
    {
        $this->redirect_hak_akses('u');
        $this->rtm_model->update_nokk($id);
        redirect($this->controller);
    }

    public function delete($id = 0): void
    {
        $this->redirect_hak_akses('h');
        $this->rtm_model->delete($id);
        redirect($this->controller);
    }

    public function delete_all(): void
    {
        $this->redirect_hak_akses('h');
        $this->rtm_model->delete_all();
        redirect($this->controller);
    }

    public function anggota($id = 0): void
    {
        $data['p']  = $this->session->per_page;
        $data['kk'] = $id;

        $data['main']      = $this->rtm_model->list_anggota($id);
        $data['kepala_kk'] = $this->rtm_model->get_kepala_rtm($id);
        $data['program']   = $this->program_bantuan_model->get_peserta_program(3, $data['kepala_kk']['no_kk']);

        $this->render('rtm/rtm_anggota', $data);
    }

    public function ajax_add_anggota($id = 0): void
    {
        $this->redirect_hak_akses('u');

        $data['form_action'] = site_url("{$this->controller}/add_anggota/{$id}");

        $this->load->view('rtm/ajax_add_anggota_rtm_form', $data);
    }

    public function datables_anggota($id_pend = null)
    {
        if ($this->input->is_ajax_request()) {
            $penduduk = Penduduk::with(['keluarga', 'keluarga.anggota'])
                ->where('kk_level', '=', 1)
                ->find($id_pend);
            $anggota = collect($penduduk->keluarga->anggota)->whereIn('id_rtm', ['0', null]);

            if ($anggota->count() > 1) {
                $keluarga = $anggota->map(static fn ($item, $key): array => [
                    'no'       => $key + 1,
                    'id'       => $item->id,
                    'nik'      => $item->nik,
                    'nama'     => $item->nama,
                    'kk_level' => SHDKEnum::valueOf($item->kk_level),
                ])->values();
            }

            return json([
                'data' => $keluarga,
            ]);
        }

        show_404();
    }

    public function edit_anggota($id_rtm = 0, $id = 0): void
    {
        $this->redirect_hak_akses('u');
        $data['hubungan']    = $this->rtm_model->list_hubungan();
        $data['main']        = $this->rtm_model->get_anggota($id) ?? show_404();
        $data['form_action'] = site_url("{$this->controller}/update_anggota/{$id_rtm}/{$id}");

        $this->load->view('rtm/ajax_edit_anggota_rtm', $data);
    }

    public function kartu_rtm($id = 0): void
    {
        $data['id_kk']    = $id;
        $data['desa']     = $this->header['desa'];
        $data['hubungan'] = $this->rtm_model->list_hubungan();
        $data['main']     = $this->rtm_model->list_anggota($id);
        $kk               = $this->rtm_model->get_kepala_rtm($id);

        $data['kepala_kk'] = $kk ?: null;

        $data['penduduk']    = $this->rtm_model->list_penduduk_lepas();
        $data['form_action'] = site_url("{$this->controller}/print");

        $this->render('rtm/kartu_rtm', $data);
    }

    public function cetak_kk($id = 0): void
    {
        $data['id_kk']     = $id;
        $data['desa']      = $this->header['desa'];
        $data['main']      = $this->rtm_model->list_anggota($id);
        $data['kepala_kk'] = $this->rtm_model->get_kepala_rtm($id);

        $this->load->view('rtm/cetak_rtm', $data);
    }

    public function add_anggota($id = 0): void
    {
        $this->redirect_hak_akses('u');
        $this->rtm_model->add_anggota($id);

        redirect("{$this->controller}/anggota/{$id}");
    }

    public function update_anggota($id_rtm = 0, $id = 0): void
    {
        $this->redirect_hak_akses('u');
        $this->rtm_model->update_anggota($id, $id_rtm);

        redirect("{$this->controller}/anggota/{$id_rtm}");
    }

    public function delete_anggota($kk = 0, $id = 0): void
    {
        $this->redirect_hak_akses('h');
        $this->rtm_model->rem_anggota($kk, $id);

        redirect("{$this->controller}/anggota/{$kk}");
    }

    public function delete_all_anggota($kk = 0): void
    {
        $this->redirect_hak_akses('h');
        $this->rtm_model->rem_all_anggota($kk);

        redirect("{$this->controller}/anggota/{$kk}");
    }

    public function ajax_cetak($aksi = ''): void
    {
        $data['aksi']                = $aksi;
        $data['form_action']         = site_url("{$this->controller}/daftar/{$aksi}");
        $data['form_action_privasi'] = site_url("{$this->controller}/daftar/{$aksi}/1");

        $this->load->view('sid/kependudukan/ajax_cetak_bersama', $data);
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null): void
    {
        if ($sex == null) {
            if ($nomor != 0) {
                $this->session->sex = $nomor;
            } else {
                $this->session->unset_userdata('sex');
            }
            $this->session->unset_userdata('judul_statistik');

            redirect($this->controller);
        }

        $this->session->unset_userdata('program_bantuan');
        $this->session->sex = ($sex == 0) ? null : $sex;

        switch ($tipe) {
            case 'bdt':
                $session  = 'bdt';
                $kategori = 'KLASIFIKASI BDT :';
                break;

            case $tipe > 50:
                $program_id                     = preg_replace('/^50/', '', $tipe);
                $this->session->program_bantuan = $program_id;

                // TODO: Sederhanakan query ini, pindahkan ke model
                $nama = $this->db
                    ->select('nama')
                    ->where('config_id', identitas('id'))
                    ->where('id', $program_id)
                    ->get('program')
                    ->row()
                    ->nama;

                if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                    $this->session->status_dasar = null; // tampilkan semua peserta walaupun bukan hidup/aktif
                    $nomor                       = $program_id;
                }
                $kategori = $nama . ' : ';
                $session  = 'penerima_bantuan';
                $tipe     = 'penerima_bantuan';
                break;
        }
        $this->session->{$session} = ($nomor != TOTAL) ? $nomor : null;

        $judul = $this->rtm_model->get_judul_statistik($tipe, $nomor, $sex);
        $this->session->unset_userdata('judul_statistik');
        if ($judul['nama']) {
            $this->session->judul_statistik = $kategori . $judul['nama'];
        }

        redirect($this->controller);
    }

    // Impor Pengelompokan Data Rumah Tangga
    public function impor(): void
    {
        $this->redirect_hak_akses('u');
        $this->rtm_model->impor();
        redirect($this->controller);
    }
}
