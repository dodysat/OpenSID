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

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_penduduk_sementara extends Admin_Controller
{
    public $modul_ini            = 'buku-administrasi-desa';
    public $sub_modul_ini        = 'administrasi-penduduk';
    private array $_set_page     = ['10', '20', '50', '100'];
    private array $_list_session = ['filter_tahun', 'filter_bulan', 'filter', 'status_dasar', 'sex', 'agama', 'dusun', 'rw', 'rt', 'cari', 'umur_min', 'umur_max', 'umurx', 'pekerjaan_id', 'status', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk', 'judul_statistik', 'cacat', 'cara_kb_id', 'akta_kelahiran', 'status_ktp', 'id_asuransi', 'status_covid', 'bantuan_penduduk', 'log', 'warganegara', 'menahun', 'hubungan', 'golongan_darah', 'hamil', 'kumpulan_nik'];

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['pamong_model', 'penduduk_model']);
    }

    public function index($page_number = 1, $order_by = 0): void
    {
        // hanya menampilkan data status_dasar 1 (Hidup) dan status_penduduk 2 (Tidak Tetap)
        $this->session->status_dasar    = [1, 6];
        $this->session->status_penduduk = 2;

        if ($this->input->post('per_page')) {
            $this->session->per_page = $this->input->post('per_page');
        }

        $list_data = $this->penduduk_model->list_data($order_by, $page_number);
        $data      = [
            'main_content' => 'bumindes/penduduk/sementara/content_sementara',
            'subtitle'     => 'Buku Penduduk Sementara',
            'selected_nav' => 'sementara',
            'p'            => $page_number,
            'o'            => $order_by,
            'cari'         => $this->session->cari ?? '',
            'filter'       => $this->session->filter ?? '',
            'per_page'     => $this->session->per_page,
            'bulan'        => (! isset($this->session->filter_bulan)) ?: $this->session->filter_bulan,
            'tahun'        => (! isset($this->session->filter_tahun)) ?: $this->session->filter_tahun,
            'func'         => 'index',
            'set_page'     => $this->_set_page,
            'paging'       => $list_data['paging'],
            'list_tahun'   => $this->penduduk_log_model->list_tahun(),
        ];

        $data['main'] = $list_data['main'];

        $this->render('bumindes/penduduk/main', $data);
    }

    private function clear_session(): void
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->status_dasar = 1; // default status dasar = hidup
        $this->session->per_page     = $this->_set_page[0];
    }

    public function clear(): void
    {
        $this->clear_session();
        // Set default filter ke tahun dan bulan sekarang
        $this->session->filter_tahun = date('Y');
        $this->session->filter_bulan = date('m');
        redirect('bumindes_penduduk_sementara');
    }

    public function ajax_cetak($o = 0, $aksi = ''): void
    {
        $data = [
            'o'                   => $o,
            'aksi'                => $aksi,
            'form_action'         => site_url("bumindes_penduduk_sementara/cetak/{$o}/{$aksi}"),
            'form_action_privasi' => site_url("bumindes_penduduk_sementara/cetak/{$o}/{$aksi}/1"),
            'isi'                 => 'bumindes/penduduk/sementara/ajax_cetak_sementara',
        ];

        $this->load->view('global/dialog_cetak', $data);
    }

    public function cetak($o = 0, $aksi = '', $privasi_nik = 0): void
    {
        $data              = $this->modal_penandatangan();
        $data['aksi']      = $aksi;
        $data['main']      = $this->penduduk_model->list_data($o, 0);
        $data['config']    = $this->header['desa'];
        $data['bulan']     = $this->session->filter_bulan ?: date('m');
        $data['tahun']     = $this->session->filter_tahun ?: date('Y');
        $data['tgl_cetak'] = $this->input->post('tgl_cetak');
        $data['file']      = 'Buku Penduduk Sementara';
        $data['isi']       = 'bumindes/penduduk/sementara/content_sementara_cetak';
        $data['letak_ttd'] = ['2', '2', '9'];

        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }

        $this->load->view('global/format_cetak', $data);
    }

    public function autocomplete(): void
    {
        $data = $this->penduduk_model->autocomplete($this->input->post('cari'));
        $this->output->set_content_type('application/json')->set_output(json_encode($data, JSON_THROW_ON_ERROR));
    }

    public function filter($filter): void
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }

        $this->session->filter_tahun = $this->input->post('filter_tahun') ?: date('Y');
        $this->session->filter_bulan = $this->input->post('filter_bulan') ?: date('m');
        redirect('bumindes_penduduk_sementara');
    }
}
