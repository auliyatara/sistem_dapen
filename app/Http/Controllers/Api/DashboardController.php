<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Kepesertaan;


class DashboardController extends Controller
{
    public function profilePhoto(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);
        $filename = basename((string) $user->user_foto);
        $paths = $filename !== '' ? [storage_path('app/public/user_foto/'.$filename), public_path('storage/app/public/user_foto/'.$filename), public_path('storage/user_foto/'.$filename)] : [];
        $paths[] = public_path('profile_default.png');
        foreach ($paths as $path) {
            if (is_file($path)) return response()->file($path, ['Cache-Control' => 'private, no-cache', 'X-Content-Type-Options' => 'nosniff']);
        }
        abort(404);
    }
    public function index(){
        $auth = Auth::id();
        $operation = User::with('role')->where('user_id',$auth)->get();
        return $this->responseFirst($operation);
    }

    public function pesertaStatistik()
    {
        $jabatan = Kepesertaan::query()
            ->selectRaw('kepesertaan_status_jabatan as jabatan, COUNT(*) as total')
            ->where('kepesertaan_status_kepesertaan', 'Peserta')
            ->whereIn('kepesertaan_status_jabatan', ['Dosen', 'Guru Besar', 'Karyawan'])
            ->groupBy('kepesertaan_status_jabatan')
            ->pluck('total', 'jabatan');

        $totalJabatan = $jabatan->sum();
        $statistikJabatan = collect(['Dosen', 'Guru Besar', 'Karyawan'])->map(function ($nama) use ($jabatan, $totalJabatan) {
            $total = (int) ($jabatan[$nama] ?? 0);

            return [
                'jabatan' => $nama,
                'total' => $total,
                'persentase' => $totalJabatan ? round(($total / $totalJabatan) * 100, 2) : 0,
            ];
        });

        $totalPeserta = $totalJabatan;

        $jenisPensiun = Kepesertaan::query()
            ->selectRaw('kepesertaan_jenis_pensiun as kategori, COUNT(*) as total')
            ->where('kepesertaan_status_kepesertaan', 'Pensiunan')
            ->whereIn('kepesertaan_jenis_pensiun', [
                'Pensiun Normal',
                'Pensiun Janda/Duda',
                'Pensiun Anak',
                'Pensiun Dipercepat',
            ])
            ->groupBy('kepesertaan_jenis_pensiun')
            ->pluck('total', 'kategori');
        $totalPensiunan = $jenisPensiun->sum();
        $statistikPensiunan = collect([
            'Pensiun Normal',
            'Pensiun Janda/Duda',
            'Pensiun Anak',
            'Pensiun Dipercepat',
        ])->map(function ($nama) use ($jenisPensiun, $totalPensiunan) {
            $total = (int) ($jenisPensiun[$nama] ?? 0);

            return [
                'kategori' => $nama,
                'total' => $total,
                'persentase' => $totalPensiunan ? round(($total / $totalPensiunan) * 100, 2) : 0,
            ];
        });

        $kategoriLain = [
            'Pensiun Ditunda',
            'Pensiun Sekaligus',
            'Pensiunan Yang Sudah Tidak Berhak Menerima MP',
        ];
        $pensiunLain = Kepesertaan::query()
            ->selectRaw('kepesertaan_status_kepesertaan as kategori, COUNT(*) as total')
            ->whereIn('kepesertaan_status_kepesertaan', $kategoriLain)
            ->groupBy('kepesertaan_status_kepesertaan')
            ->pluck('total', 'kategori');
        $totalPensiunLain = $pensiunLain->sum();
        $statistikPensiunLain = collect($kategoriLain)->map(function ($nama) use ($pensiunLain, $totalPensiunLain) {
            $total = (int) ($pensiunLain[$nama] ?? 0);

            return [
                'kategori' => $nama,
                'total' => $total,
                'persentase' => $totalPensiunLain ? round(($total / $totalPensiunLain) * 100, 2) : 0,
            ];
        });

        return $this->response([
            'total' => $totalJabatan,
            'peserta' => [
                'total' => $totalPeserta,
                'persentase' => $totalJabatan ? round(($totalPeserta / $totalJabatan) * 100, 2) : 0,
            ],
            'jabatan' => $statistikJabatan,
            'pensiunan' => [
                'total' => $totalPensiunan,
                'total_semua' => Kepesertaan::whereIn('kepesertaan_status_kepesertaan', array_merge(['Pensiunan'], $kategoriLain))->count(),
                'kategori' => $statistikPensiunan,
            ],
            'pensiun_lain' => [
                'total' => $totalPensiunLain,
                'kategori' => $statistikPensiunLain,
            ],
        ]);
    }
}
