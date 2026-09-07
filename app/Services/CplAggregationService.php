<?php

namespace App\Services;

use App\Models\Cpl;
use App\Models\Mahasiswa;

class CplAggregationService
{
    /**
     * Hitung nilai capaian CPL untuk 1 mahasiswa, berdasarkan rata-rata
     * tertimbang dari nilai CPMK yang dipetakan ke CPL tersebut.
     */
    public function hitungCapaianCpl(Mahasiswa $mahasiswa, Cpl $cpl): ?float
    {
        $totalBobot = 0;
        $totalNilaiTertimbang = 0;

        foreach ($cpl->cpmk as $cpmk) {
            $bobot = $cpmk->pivot->bobot;

            $nilaiCpmk = $mahasiswa->nilaiCpmk()
                ->where('cpmk_id', $cpmk->id)
                ->avg('nilai');

            if ($nilaiCpmk === null) {
                continue;
            }

            $totalNilaiTertimbang += $nilaiCpmk * $bobot;
            $totalBobot += $bobot;
        }

        if ($totalBobot == 0) {
            return null;
        }

        return round($totalNilaiTertimbang / $totalBobot, 2);
    }

    /**
     * Hitung capaian semua CPL untuk 1 mahasiswa dalam 1 kurikulum.
     * Return: collection [cpl_id => nilai_capaian]
     */
    public function hitungSemuaCapaianCpl(Mahasiswa $mahasiswa, $kurikulumId)
    {
        $cplList = Cpl::where('kurikulum_id', $kurikulumId)->with('cpmk')->get();

        return $cplList->mapWithKeys(function ($cpl) use ($mahasiswa) {
            return [$cpl->id => [
                'kode' => $cpl->kode,
                'deskripsi' => $cpl->deskripsi,
                'nilai' => $this->hitungCapaianCpl($mahasiswa, $cpl),
            ]];
        });
    }
}