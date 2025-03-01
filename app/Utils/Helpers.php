<?php

use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

if (!function_exists('responseError')) {
    function responseError(Exception | string $th)
    {
        $message = 'Terjadi kesalahan, silahkan coba beberapa saat lagi.!';

        if ($th instanceof \Exception) {

            if (config('app.debug')) {
                $message = $th->getMessage();
                $message .= ' in line ' . $th->getLine() .  ' at ' . $th->getFile();
                $data = $th->getTrace();
            }
        } else {
            $message = $th;
        }

        return response()->json([
            'status'    => 'error',
            'message'   => $message,
            'errors'    => $data ?? null,
        ], 500);
    }
}

if (!function_exists('responseSuccess')) {
    function responseSuccess($isEdit = false)
    {
        return response()->json([
            'status'    => 'success',
            'message'   => $isEdit ? 'Ubah data berhasil' : 'Tambah data berhasil',
        ], 200);
    }
}

if (!function_exists('responseSuccessDelete')) {
    function responseSuccessDelete()
    {
        return response()->json([
            'status'    => 'success',
            'message'   => 'Data berhasil dihapus',
        ], 200);
    }
}

if (!function_exists('urlMenu')) {

    function urlMenu()
    {
        if (!Cache::has('urlMenu')) {

            $menus = menus()->flatMap(fn($item) => $item);

            $url = [];
            foreach ($menus as $mm) {
                $url[] = $mm->url;
                foreach ($mm->subMenus as  $sm) {
                    $url[] = $sm->url;
                }
            }

            Cache::forever('urlMenu', $url);
        } else {
            $url = Cache::get('urlMenu');
        }


        return $url;
    }

    if (!function_exists('menus')) {
        /**
         * @return Collection
         */
        function menus()
        {
            if (!Cache::has('menus')) {
                $menus = (new MenuRepository())->getMenus()->groupBy('category');

                Cache::forever('menus', $menus);
            } else {
                $menus = Cache::get('menus');
            }

            return $menus;
        }
    }
}

if (!function_exists('filterKata')) {
    function filterKata($nama)
    {
        // Menghapus tag HTML atau JavaScript yang mungkin disisipkan
        $nama = strip_tags($nama);

        // Mengonversi karakter khusus menjadi entitas HTML untuk mencegah eksekusi kode
        $nama = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');

        // Menghapus spasi berlebih
        $nama = trim($nama);

        return $nama;
    }
}

if (!function_exists('user')) {

    /**
     * @param string $id
     * @return \App\Models\User | string
     */

    function user($id = null)
    {
        if ($id) {
            return request()->user()->{$id};
        }

        return request()->user();
    }
}

if (!function_exists('dynamicImage')) {
    /**
     * Generate a dynamic img tag with conditional src attribute, default size, and shape.
     *
     * @param string|null $imagePath Path to the image or null.
     * @param string|null $defaultPath Default path if $imagePath is null.
     * @param array $attributes Additional HTML attributes for the <img> tag.
     * @param bool $isUser Indicates whether this is for a user profile (to handle gender logic).
     * @param int|string $width Default width of the image (can be overridden via attributes).
     * @param int|string $height Default height of the image (can be overridden via attributes).
     * @param string $shape Image shape ('square' or 'circle').
     * @return string
     */
    function dynamicImage(?string $imagePath, ?string $defaultPath = null, array $attributes = [], bool $isUser = false, $width = '100px', $height = '100px', string $shape = 'square'): string
    {
        // Handle default path logic for users
        if ($isUser && $defaultPath === null) {
            $gender = user('jk') ?? null;
            $defaultPath = $gender === 'L'
                ? 'img/avatar/boy.svg'
                : ($gender === 'P' ? 'img/avatar/girl.svg' : 'img/avatar/blank.png');
        }

        // Fallback for non-user entities
        $defaultPath = $defaultPath ?? 'img/noimage.png';

        // Determine the src based on the condition
        $src = $imagePath === null ? asset($defaultPath) : Storage::url($imagePath);

        // Set default width and height if not provided in attributes
        $attributes['width'] = $attributes['width'] ?? $width;
        $attributes['height'] = $attributes['height'] ?? $height;

        // Handle shape (square or circle)
        if (!isset($attributes['class'])) {
            $attributes['class'] = '';
        }
        if ($shape === 'circle') {
            $attributes['style'] = ($attributes['style'] ?? '') . 'border-radius: 50%; overflow: hidden;';
        }

        // Merge attributes and convert to HTML string
        $attributes['src'] = $src;
        $attributeString = collect($attributes)
            ->map(fn($value, $key) => htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"')
            ->join(' ');

        // Return the complete img tag
        return '<img ' . $attributeString . '>';
    }
}

if (!function_exists('tgl_indo')) {
    function tgl_indo($tgl, $tampil_hari = false, $tampil_jam = false)
    {
        $nama_hari  = array(
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jum\'at',
            'Sabtu'
        );
        $nama_bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $tahun   = substr($tgl, 0, 4);
        $bulan   = $nama_bulan[(int) substr($tgl, 5, 2)];
        $tanggal = substr($tgl, 8, 2);
        $text    = '';

        if ($tampil_hari) {
            $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
            $hari        = $nama_hari[$urutan_hari];
            $text       .= "$hari, $tanggal $bulan $tahun";
        } else {
            $text       .= "$tanggal $bulan $tahun";
        }

        if ($tampil_jam) {
            $jam = substr($tgl, 11, 5); // Mengambil jam dan menit dari input tanggal
            $text .= " - $jam";
        }

        return $text;
    }
}
