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

class Surat_keluar extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-umum';

    public function __construct()
    {
        parent::__construct();
        // Untuk bisa menggunakan helper force_download()
        $this->load->helper('download');
        $this->load->model(['surat_keluar_model', 'klasifikasi_model', 'pamong_model', 'penomoran_surat_model']);
        $this->list_session = ['cari', 'filter'];
    }

    public function clear($id = 0): void
    {
        $this->session->per_page = 20;
        $this->session->surat    = $id;
        $this->session->unset_userdata($this->list_session);
        redirect('surat_keluar');
    }

    public function index($p = 1, $o = 0): void
    {
        $data['p'] = $p ?? 1;
        $data['o'] = $o ?? 0;

        $data['cari'] = $this->session->has_userdata('cari') ? $this->session->cari : '';

        $data['filter'] = $this->session->has_userdata('filter') ? $this->session->filter : '';

        if ($this->session->has_userdata('per_page')) {
            $this->session->per_page = $this->input->post('per_page');
        }

        $data['per_page']     = $this->session->per_page;
        $data['paging']       = $this->surat_keluar_model->paging($p, $o);
        $data['main']         = $this->surat_keluar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['tahun_surat']  = $this->surat_keluar_model->list_tahun_surat();
        $data['keyword']      = $this->surat_keluar_model->autocomplete();
        $data['main_content'] = 'surat_keluar/table';
        $data['subtitle']     = 'Buku Agenda - Surat Keluar';
        $data['selected_nav'] = 'agenda_keluar';

        $this->render('bumindes/umum/main', $data);
    }

    public function form($p = 1, $o = 0, $id = ''): void
    {
        $this->redirect_hak_akses('u');
        $data['tujuan']      = $this->surat_keluar_model->autocomplete();
        $data['klasifikasi'] = $this->klasifikasi_model->list_kode();
        $data['p']           = $p;
        $data['o']           = $o;

        if ($id) {
            $data['surat_keluar'] = $this->surat_keluar_model->get_surat_keluar($id) ?? show_404();
            $data['form_action']  = site_url("surat_keluar/update/{$p}/{$o}/{$id}");
        } else {
            $last_surat                         = $this->penomoran_surat_model->get_surat_terakhir('surat_keluar');
            $data['surat_keluar']['nomor_urut'] = $last_surat['no_surat'] + 1;
            $data['form_action']                = site_url('surat_keluar/insert');
        }

        // Buang unique id pada link nama file
        $berkas                              = explode('__sid__', $data['surat_keluar']['berkas_scan']);
        $namaFile                            = $berkas[0];
        $ekstensiFile                        = explode('.', end($berkas));
        $ekstensiFile                        = end($ekstensiFile);
        $data['surat_keluar']['berkas_scan'] = $namaFile . '.' . $ekstensiFile;

        $this->render('surat_keluar/form', $data);
    }

    public function form_upload($p = 1, $o = 0, $url = ''): void
    {
        $data['form_action'] = site_url("surat_keluar/upload/{$p}/{$o}/{$url}");
        $this->load->view('surat_keluar/ajax-upload', $data);
    }

    public function search(): void
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $this->session->cari = $cari;
        } else {
            $this->session->unset_userdata('cari');
        }
        redirect('surat_keluar');
    }

    public function filter(): void
    {
        $filter = $this->input->post('filter');
        if ($filter != 0) {
            $this->session->filter = $filter;
        } else {
            $this->session->unset_userdata('filter');
        }
        redirect('surat_keluar');
    }

    public function insert(): void
    {
        $this->redirect_hak_akses('u');
        $this->surat_keluar_model->insert();
        redirect('surat_keluar');
    }

    public function update($p = 1, $o = 0, $id = ''): void
    {
        $this->redirect_hak_akses('u');
        $this->surat_keluar_model->update($id);
        redirect("surat_keluar/index/{$p}/{$o}");
    }

    public function upload($p = 1, $o = 0, $url = ''): void
    {
        $this->redirect_hak_akses('u');
        $this->surat_keluar_model->upload($url);
        redirect("surat_keluar/index/{$p}/{$o}");
    }

    public function delete($p = 1, $o = 0, $id = ''): void
    {
        $this->redirect_hak_akses('h');
        $this->surat_keluar_model->delete($id);
        redirect("surat_keluar/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0): void
    {
        $this->redirect_hak_akses('h');
        $this->surat_keluar_model->delete_all();
        redirect("surat_keluar/index/{$p}/{$o}");
    }

    public function dialog_cetak($o = 0): void
    {
        $data['aksi']        = 'Cetak';
        $data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
        $data['form_action'] = site_url("surat_keluar/dialog/cetak/{$o}");

        $this->load->view('surat_keluar/ajax_cetak', $data);
    }

    public function dialog_unduh($o = 0): void
    {
        $data['aksi']        = 'Unduh';
        $data['tahun_surat'] = $this->surat_keluar_model->list_tahun_surat();
        $data['form_action'] = site_url("surat_keluar/dialog/unduh/{$o}");
        $this->load->view('surat_keluar/ajax_cetak', $data);
    }

    public function dialog($aksi = 'unduh', $o = 0): void
    {
        // TODO :: gunakan view global penandatangan
        $ttd                    = $this->modal_penandatangan();
        $data['pamong_ttd']     = $this->pamong_model->get_data($ttd['pamong_ttd']->pamong_id);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($ttd['pamong_ketahui']->pamong_id);

        $data['input']         = $this->input->post();
        $this->session->filter = $data['input']['tahun'];
        $data['desa']          = $this->header['desa'];
        $data['main']          = $this->surat_keluar_model->list_data($o, 0, 10000);

        if ($aksi == 'unduh') {
            $this->load->view('surat_keluar/surat_keluar_excel', $data);
        } else {
            $this->load->view('surat_keluar/surat_keluar_print', $data);
        }
    }

    /**
     * Unduh berkas scan berdasarkan kolom surat_keluar.id
     *
     * @param int $idSuratKeluar Id berkas scan pada koloam surat_keluar.id
     * @param int $tipe
     */
    public function berkas($idSuratKeluar = 0, $tipe = 0): void
    {
        // Ambil nama berkas dari database
        $berkas = $this->surat_keluar_model->getNamaBerkasScan($idSuratKeluar);
        ambilBerkas($berkas, 'surat_keluar', '__sid__', LOKASI_ARSIP, $tipe == 1);
    }

    public function nomor_surat_duplikat(): void
    {
        if ($this->input->post('nomor_urut') == $this->input->post('nomor_urut_lama')) {
            $hasil = false;
        } else {
            $hasil = $this->penomoran_surat_model->nomor_surat_duplikat('surat_keluar', $this->input->post('nomor_urut'));
        }
        echo $hasil ? 'false' : 'true';
    }

    public function untuk_ekspedisi($p, $o, $id): void
    {
        $this->surat_keluar_model->untuk_ekspedisi($id, $masuk = 1);
        redirect("ekspedisi/index/{$p}/{$o}");
    }
}
