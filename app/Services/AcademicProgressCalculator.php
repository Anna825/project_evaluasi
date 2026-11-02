<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Semester;

class AcademicProgressCalculator
{
    /**
     * Menghitung semester studi berjalan dari kalender akademik dan periode Cuti.
     * Mengembalikan null jika angkatan atau kalender tidak cukup untuk dihitung.
     */
    public function semesterBerjalan(Mahasiswa $mahasiswa, ?Semester $semesterAcuan = null, bool $sedangKembaliDariCuti = false): ?int
    {
        if (! $mahasiswa->angkatan_masuk) {
            return null;
        }

        $semesterAcuan ??= Semester::with('tahunAkademik')
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>=', today())
            ->first();

        if (! $semesterAcuan) {
            return null;
        }

        $semesters = Semester::with('tahunAkademik')
            ->whereHas('tahunAkademik', function ($query) use ($mahasiswa) {
                $query->where('label', 'like', (string) $mahasiswa->angkatan_masuk . '%');
            })
            ->orWhereHas('tahunAkademik', function ($query) use ($mahasiswa) {
                $query->where('label', '>', (string) $mahasiswa->angkatan_masuk);
            })
            ->orderBy('tanggal_mulai')
            ->get()
            ->filter(fn ($semester) => $semester->tanggal_mulai <= $semesterAcuan->tanggal_mulai)
            ->values();

        if ($semesters->isEmpty() || ! $semesters->contains('id', $semesterAcuan->id)) {
            return null;
        }

        $cuti = $mahasiswa->riwayatCuti()->get();

        // Data Cuti lama tanpa tanggal mulai tidak boleh diasumsikan tidak pernah Cuti.
        $memilikiRiwayatCutiLama = $mahasiswa->statusLog()->where('status', 'cuti')->exists();
        if (($mahasiswa->status === 'cuti' && ! $cuti->contains(fn ($item) => is_null($item->tanggal_selesai)))
            || ($memilikiRiwayatCutiLama && $cuti->isEmpty())) {
            return null;
        }

        $semesterAktifTerhitung = 0;

        foreach ($semesters as $semester) {
            $mulaiCuti = $cuti->contains(function ($riwayat) use ($semester, $semesterAcuan, $sedangKembaliDariCuti) {
                if (! $riwayat->semester_mulai_id) {
                    return $riwayat->tanggal_mulai && $riwayat->tanggal_mulai <= $semester->tanggal_selesai
                        && (! $riwayat->tanggal_selesai || $riwayat->tanggal_selesai >= $semester->tanggal_mulai);
                }

                $start = (int) $riwayat->semester_mulai_id;
                $end = $riwayat->semester_selesai_id
                    ? (int) $riwayat->semester_selesai_id
                    : ($sedangKembaliDariCuti ? (int) $semesterAcuan->id : null);
                $orderedIds = Semester::orderBy('tanggal_mulai')->pluck('id')->values();
                $semesterIds = $orderedIds->all();
                $startPosition = array_search($start, $semesterIds, true);
                $currentPosition = array_search((int) $semester->id, $semesterIds, true);
                $endPosition = $end ? array_search($end, $semesterIds, true) : null;

                if ($startPosition === false || $currentPosition === false) {
                    return false;
                }

                return $currentPosition >= $startPosition
                    && ($endPosition === null || $currentPosition < $endPosition);
            });

            if (! $mulaiCuti) {
                $semesterAktifTerhitung++;
            }
        }

        return max(1, $semesterAktifTerhitung);
    }
}
