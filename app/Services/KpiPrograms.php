<?php
namespace App\Services;

use App\Models\Role;

class KpiPrograms
{
    public static function typeFor(Role $role): ?string
    {
        $normalize = fn ($name) => strtolower(preg_replace('/[^a-z0-9]/i', '', $name));
        $mapping = [
            'Direktur' => 'program_direktur',
            'Wakil Direktur Investasi Dan Manajemen Risiko' => 'program_wakil_investasi',
            'Wakil Direktur Keuangan dan Akuntansi' => 'program_wakil_keuangan',
            'Wakil Direktur Bidang Keuangan dan Akuntansi' => 'program_wakil_keuangan',
            'Wakil Direktur Kepatuhan, Tata Kelola dan SDM' => 'program_wakil_kepatuhan_tata_kelola_sdm',
            'Wakil Direktur Bidang Kepatuhan, Tata Kelola dan SDM' => 'program_wakil_kepatuhan_tata_kelola_sdm',
            'Bidang Keuangan' => 'program_bidang_keuangan', 'Bidang Kepesertaan' => 'program_bidang_kepesertaan',
            'Bidang Sistem Informasi dan Digitalisasi Tata Kelola' => 'program_bidang_sistem_informasi',
            'Bidang Umum dan Administrasi' => 'program_bidang_umum_administrasi', 'Bidang Investasi' => 'program_bidang_investasi',
        ];
        foreach ($mapping as $name => $type) if ($normalize($role->role_name) === $normalize($name)) return $type;
        return null;
    }
}
