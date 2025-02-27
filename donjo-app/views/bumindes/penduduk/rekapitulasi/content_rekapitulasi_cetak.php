<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Administrasi Desa > Buku Rekapitulasi Jumlah Penduduk
 *
 * donjo-app/views/bumindes/penduduk/rekapitulasi/content_rekapitulasi_cetak.php,
 */

/*
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<table>
	<tbody>
		<tr>
			<td>
				<?php if ($aksi == 'cetak') : ?>
					<img class="logo" src="<?= gambar_desa($config['logo']); ?>" alt="logo-desa">
				<?php elseif ($aksi == 'pdf') : ?>
					<div style="text-align: center;">
						<img class="logo" src="<?= gambar_desa($config['logo'], false, $file = true); ?>" alt="logo-desa">
					</div>
				<?php endif; ?>
				<h1 class="judul">
					PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten'] . ' <br>' . $this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan'] . ' <br>' . $this->setting->sebutan_desa . ' ' . $config['nama_desa']); ?>
				</h1>
			</td>
		</tr>
		<tr>
			<td>
				<hr class="garis">
			</td>
		</tr>
		<tr>
			<td class="text-center">
				<h4>B3. BUKU REKAPITULASI JUMLAH PENDUDUK</h4>
			</td>
		</tr>
		<tr>
			<td class="text-center">
				<h4>BUKU REKAPITULASI JUMLAH PENDUDUK BULAN <?= strtoupper(getBulan($bulan)) ?> TAHUN <?= $tahun ?></h4>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th rowspan="4">NOMOR URUT</th>
							<th rowspan="4">NAMA DUSUN / LINGKUNGAN</th>
							<th colspan="7">JUMLAH PENDUDUK AWAL BULAN</th>
							<th colspan="8">TAMBAHAN BULAN INI</th>
							<th colspan="8">PENGURANGAN BULAN INI</th>
							<th rowspan="2" colspan="7">JML PENDUDUK AKHIR BULAN</th>
							<th rowspan="4">KET</th>
						</tr>
						<tr class="border thick">
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th rowspan="3">JLH KK</th>
							<th rowspan="3">JML ANGGOTA KELUARGA</th>
							<th rowspan="3">JML JIWA (7+8)</th>
							<th colspan="4">LAHIR</th>
							<th colspan="4">DATANG</th>
							<th colspan="4">MENINGGAL</th>
							<th colspan="4">PINDAH</th>
						</tr>
						<tr class="border thick">
							<th rowspan="2">L</th>
							<th rowspan="2">P</th>
							<th rowspan="2">L</th>
							<th rowspan="2">P</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th rowspan="2">JML KK</th>
							<th rowspan="2">JML ANGGOTA KELUARGA</th>
							<th rowspan="2">JML JIWA (30+31)</th>
						</tr>
						<tr class="border thick">
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
						</tr>
						<tr class="border thick">
							<th>1</th>
							<th>2</th>
							<th>3</th>
							<th>4</th>
							<th>5</th>
							<th>6</th>
							<th>7</th>
							<th>8</th>
							<th>9</th>
							<th>10</th>
							<th>11</th>
							<th>12</th>
							<th>13</th>
							<th>14</th>
							<th>15</th>
							<th>16</th>
							<th>17</th>
							<th>18</th>
							<th>19</th>
							<th>20</th>
							<th>21</th>
							<th>22</th>
							<th>23</th>
							<th>24</th>
							<th>25</th>
							<th>26</th>
							<th>27</th>
							<th>28</th>
							<th>29</th>
							<th>30</th>
							<th>31</th>
							<th>32</th>
							<th>33</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($main):
							if ($tampil_jumlah) {
								$totals = [
									'WNA_L_AWAL'          => 0,
									'WNA_P_AWAL'          => 0,
									'WNI_L_AWAL'          => 0,
									'WNI_P_AWAL'          => 0,
									'KK_JLH'              => 0,
									'KK_ANG_KEL'          => 0,
									'WNA_L_TAMBAH_LAHIR'  => 0,
									'WNA_P_TAMBAH_LAHIR'  => 0,
									'WNI_L_TAMBAH_LAHIR'  => 0,
									'WNI_P_TAMBAH_LAHIR'  => 0,
									'WNA_L_TAMBAH_MASUK'  => 0,
									'WNA_P_TAMBAH_MASUK'  => 0,
									'WNI_L_TAMBAH_MASUK'  => 0,
									'WNI_P_TAMBAH_MASUK'  => 0,
									'WNA_L_KURANG_MATI'   => 0,
									'WNA_P_KURANG_MATI'   => 0,
									'WNI_L_KURANG_MATI'   => 0,
									'WNI_P_KURANG_MATI'   => 0,
									'WNA_L_KURANG_KELUAR' => 0,
									'WNA_P_KURANG_KELUAR' => 0,
									'WNI_L_KURANG_KELUAR' => 0,
									'WNI_P_KURANG_KELUAR' => 0,
									'WNA_L_AKHIR'         => 0,
									'WNA_P_AKHIR'         => 0,
									'WNI_L_AKHIR'         => 0,
									'WNI_P_AKHIR'         => 0,
									'KK_AKHIR_JML'        => 0,
									'KK_AKHIR_ANG_KEL'    => 0,
								];
							}

							foreach ($main as $key => $data):
								if ($tampil_jumlah) {
									$data['JLH_JIWA_1'] = $data['KK_JLH'] + $data['KK_ANG_KEL'];
									$data['JLH_JIWA_2'] = $data['KK_AKHIR_JML'] + $data['KK_AKHIR_ANG_KEL'];

									$totals['WNA_L_AWAL'] += (int) ($data['WNA_L_AWAL']);
									$totals['WNA_P_AWAL'] += (int) ($data['WNA_P_AWAL']);
									$totals['WNI_L_AWAL'] += (int) ($data['WNI_L_AWAL']);
									$totals['WNI_P_AWAL'] += (int) ($data['WNI_P_AWAL']);
									$totals['KK_JLH'] += (int) ($data['KK_JLH']);
									$totals['KK_ANG_KEL'] += (int) ($data['KK_ANG_KEL']);
									$totals['JLH_JIWA_1'] += (int) ($data['JLH_JIWA_1']);
									$totals['WNA_L_TAMBAH_LAHIR'] += (int) ($data['WNA_L_TAMBAH_LAHIR']);
									$totals['WNA_P_TAMBAH_LAHIR'] += (int) ($data['WNA_P_TAMBAH_LAHIR']);
									$totals['WNI_L_TAMBAH_LAHIR'] += (int) ($data['WNI_L_TAMBAH_LAHIR']);
									$totals['WNI_P_TAMBAH_LAHIR'] += (int) ($data['WNI_P_TAMBAH_LAHIR']);
									$totals['WNA_L_TAMBAH_MASUK'] += (int) ($data['WNA_L_TAMBAH_MASUK']);
									$totals['WNA_P_TAMBAH_MASUK'] += (int) ($data['WNA_P_TAMBAH_MASUK']);
									$totals['WNI_L_TAMBAH_MASUK'] += (int) ($data['WNI_L_TAMBAH_MASUK']);
									$totals['WNI_P_TAMBAH_MASUK'] += (int) ($data['WNI_P_TAMBAH_MASUK']);
									$totals['WNA_L_KURANG_MATI'] += (int) ($data['WNA_L_KURANG_MATI']);
									$totals['WNA_P_KURANG_MATI'] += (int) ($data['WNA_P_KURANG_MATI']);
									$totals['WNI_L_KURANG_MATI'] += (int) ($data['WNI_L_KURANG_MATI']);
									$totals['WNI_P_KURANG_MATI'] += (int) ($data['WNI_P_KURANG_MATI']);
									$totals['WNA_L_KURANG_KELUAR'] += (int) ($data['WNA_L_KURANG_KELUAR']);
									$totals['WNA_P_KURANG_KELUAR'] += (int) ($data['WNA_P_KURANG_KELUAR']);
									$totals['WNI_L_KURANG_KELUAR'] += (int) ($data['WNI_L_KURANG_KELUAR']);
									$totals['WNI_P_KURANG_KELUAR'] += (int) ($data['WNI_P_KURANG_KELUAR']);
									$totals['WNA_L_AKHIR'] += (int) ($data['WNA_L_AKHIR']);
									$totals['WNA_P_AKHIR'] += (int) ($data['WNA_P_AKHIR']);
									$totals['WNI_L_AKHIR'] += (int) ($data['WNI_L_AKHIR']);
									$totals['WNI_P_AKHIR'] += (int) ($data['WNI_P_AKHIR']);
									$totals['KK_AKHIR_JML'] += (int) ($data['KK_AKHIR_JML']);
									$totals['KK_AKHIR_ANG_KEL'] += (int) ($data['KK_AKHIR_ANG_KEL']);
									$totals['JLH_JIWA_2'] += (int) ($data['JLH_JIWA_2']);
								}
						?>
								<tr>
									<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
									<td><?= strtoupper($data['DUSUN']) ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_L_AWAL'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_P_AWAL'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_L_AWAL'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_P_AWAL'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['KK_JLH'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['KK_ANG_KEL'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['KK_JLH'] + $data['KK_ANG_KEL'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_L_TAMBAH_LAHIR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_P_TAMBAH_LAHIR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_L_TAMBAH_LAHIR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_P_TAMBAH_LAHIR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_L_TAMBAH_MASUK'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_P_TAMBAH_MASUK'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_L_TAMBAH_MASUK'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_P_TAMBAH_MASUK'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_L_KURANG_MATI'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_P_KURANG_MATI'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_L_KURANG_MATI'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_P_KURANG_MATI'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_L_KURANG_KELUAR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_P_KURANG_KELUAR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_L_KURANG_KELUAR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_P_KURANG_KELUAR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_L_AKHIR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNA_P_AKHIR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_L_AKHIR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['WNI_P_AKHIR'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['KK_AKHIR_JML'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['KK_AKHIR_ANG_KEL'], '-') ?></td>
									<td class="padat"><?= show_zero_as($data['KK_AKHIR_JML'] + $data['KK_AKHIR_ANG_KEL'], '-') ?></td>
									<td class="padat">-</td>
								</tr>
							<?php endforeach; ?>
							<?php if ($tampil_jumlah) : ?>
					<tfoot>
						<tr class="bg-gray color-palette">
							<th class="padat" colspan="2">TOTAL</th>
							<th class="padat"><?= show_zero_as($totals['WNA_L_AWAL'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_P_AWAL'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_L_AWAL'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_P_AWAL'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['KK_JLH'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['KK_ANG_KEL'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['JLH_JIWA_1'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_L_TAMBAH_LAHIR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_P_TAMBAH_LAHIR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_L_TAMBAH_LAHIR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_P_TAMBAH_LAHIR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_L_TAMBAH_MASUK'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_P_TAMBAH_MASUK'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_L_TAMBAH_MASUK'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_P_TAMBAH_MASUK'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_L_KURANG_MATI'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_P_KURANG_MATI'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_L_KURANG_MATI'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_P_KURANG_MATI'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_L_KURANG_KELUAR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_P_KURANG_KELUAR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_L_KURANG_KELUAR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_P_KURANG_KELUAR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_L_AKHIR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNA_P_AKHIR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_L_AKHIR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['WNI_P_AKHIR'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['KK_AKHIR_JML'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['KK_AKHIR_ANG_KEL'], '-') ?></th>
							<th class="padat"><?= show_zero_as($totals['JLH_JIWA_2'], '-') ?></th>
							<th class="padat">-</th>
						</tr>
					</tfoot>
				<?php endif; ?>
			<?php endif; ?>
	</tbody>
</table>
</td>
</tr>
</tbody>
</table>