<?php

namespace App\Services\Etl;

use App\Models\User;

/**
 * ID (Konteks Bisnis / Domain BPS):
 * Service penyelesai (resolver) identitas pegawai BPS dari string nama bebas,
 * variasi gelar akademik, username, atau alias/nickname yang berasal dari data
 * warisan (legacy) PST, Statisfy, Pristi, dan Elena untuk dipetakan ke users.id.
 *
 * EN (Technical Specifications & Contract):
 * High-performance employee name and alias resolver that matches raw historical strings,
 * title variations, email prefixes, or nicknames into canonical User model instances.
 */
class EmployeeNameResolver
{
    /**
     * In-memory cache of User models keyed by user ID.
     *
     * @var array<int, User>
     */
    protected array $userCache = [];

    /**
     * Map of normalized clean name string => user ID.
     *
     * @var array<string, int>
     */
    protected array $cleanedNameMap = [];

    /**
     * Map of lowercase email prefix => user ID.
     *
     * @var array<string, int>
     */
    protected array $emailPrefixMap = [];

    /**
     * Map of 18-digit nip_baru or 9-digit nip_lama => user ID.
     *
     * @var array<string, int>
     */
    protected array $nipMap = [];

    /**
     * Predefined dictionary of frontline nicknames and usernames to canonical names.
     *
     * @var array<string, string>
     */
    protected array $aliasMap = [
        'nanang' => 'Yun Restianto',
        'herpas' => 'Hermawan Prasetyo',
        'yuli.purwita' => 'Yuli Purwitasari',
        'yuli' => 'Yuli Purwitasari',
        'indahpur' => 'Indah Purnamasari',
        'indah' => 'Indah Purnamasari',
        'pristiana' => 'Pristiana Diah Ariyantika',
        'pristi' => 'Pristiana Diah Ariyantika',
        'rizchi' => 'Rizchi Eka Wahyuni',
        'adi.prayitno' => 'Adi Prayitno',
        'adi' => 'Adi Prayitno',
        'dwiagus.wahyu' => 'Dwi Agus Wahyu Riyadi',
        'dwi agus' => 'Dwi Agus Wahyu Riyadi',
        'herry.kusmaiwanto' => 'Herry Kusmaiwanto',
        'herry' => 'Herry Kusmaiwanto',
        'evadian' => 'Eva Dian Widyastutie',
        'eva' => 'Eva Dian Widyastutie',
        'anargi' => 'Septyaji Bangun Anargi',
        'aji' => 'Septyaji Bangun Anargi',
        'muhammad.hanief' => 'Muhammad Hanief',
        'hanief' => 'Muhammad Hanief',
        'sukmaayu' => 'Sukma Ayu Kusumawardani',
        'sukma' => 'Sukma Ayu Kusumawardani',
        'fauziyah' => 'Fauziyah Selowati',
        'lolo' => 'Fauziyah Selowati',
        'mufti.ramadhan' => 'Mufti Ramadhan',
        'mufti' => 'Mufti Ramadhan',
        'asna.mp' => 'Asna Mega Prabandari',
        'asna' => 'Asna Mega Prabandari',
        'erlinda' => 'Erlinda',
        'ekta.surya' => 'Ekta Surya Fiqriani',
        'ekta' => 'Ekta Surya Fiqriani',
        'a.noor' => 'A. Noor Taqiyah Zamania',
        'azri' => 'A. Noor Taqiyah Zamania',
        'dyah.pipit' => 'Dyah Puspita Sari',
        'fitra.eka' => 'Fitra Eka Nurfarid',
        'farid' => 'Fitra Eka Nurfarid',
        'destya.winda' => 'Destya Winda Kholifah',
        'destya' => 'Destya Winda Kholifah',
        'srining' => 'Srining Wahyuningsih',
        'yayan.arum' => 'Yayan Arum Wulandari',
        'yayan' => 'Yayan Arum Wulandari',
        'linggar.farah' => 'Linggar Farah Hesti',
        'linggar' => 'Linggar Farah Hesti',
        'hesti_p' => 'Hesti Pramudyasti',
        'asysyifa.hanum' => 'Asy-Syifa Hanum Farida',
        'syifa' => 'Asy-Syifa Hanum Farida',
        'muhriz.alhakim' => 'Moh. Muhriz Alhakim',
        'muhriz' => 'Moh. Muhriz Alhakim',
        'tri_mulya' => 'Tri Mulyaningsih',
        'nining' => 'Tri Mulyaningsih',
        'medha' => 'Medha Wardhany',
        'lestania' => 'Lestania Jayanti',
        'nia' => 'Lestania Jayanti',
        'minanur.rohman' => 'Minanur Rohman',
        'minanur' => 'Minanur Rohman',
        'dina.rahmawati' => 'Dina Nur Rahmawati',
        'dina' => 'Dina Nur Rahmawati',
        'mugi' => 'Mugiyana',
        'anto' => 'Haryo Swasti Siantoto',
        'fadhila' => 'Fadhila Tsany Nur Rizky',
        'etika' => 'Etikawati Datiek Pratiwi',
        'buyung' => 'Buyung Firmansyah',
        'aning' => 'Aning Widiyatmi',
        'herlina' => 'Herlina',
        'evy' => 'Evy kusetyaningrum',
        'sisca' => 'Sisca Agustin Diani Budiman',
        'uswatun' => 'Uswatun Khasanah',
        'tita' => 'Tita Kartika',
        'dyastuti' => 'Pudyastuti Saptaningsih',
        'supri tuti' => 'Supri Tuti Ekawati',
        'anis' => 'Anis Ariffah Afanin',
    ];

    /**
     * Initializes the resolver with active users from database.
     */
    public function __construct()
    {
        $this->boot();
    }

    /**
     * ID (Konteks Bisnis):
     * Memuat seluruh master pegawai ke memori kerja untuk pencarian O(1).
     *
     * EN (Technical Specifications):
     * Builds in-memory hash maps from the users table for high-throughput lookup.
     */
    public function boot(): void
    {
        $users = User::all();
        foreach ($users as $u) {
            $this->userCache[$u->id] = $u;

            if (!empty($u->nip_baru)) {
                $this->nipMap[$u->nip_baru] = $u->id;
            }
            if (!empty($u->nip_lama)) {
                $this->nipMap[$u->nip_lama] = $u->id;
            }

            if (!empty($u->email)) {
                $prefix = strtolower(explode('@', $u->email)[0]);
                $this->emailPrefixMap[$prefix] = $u->id;
            }

            $rawClean = $this->normalizeString($u->name);
            $this->cleanedNameMap[$rawClean] = $u->id;

            if (!empty($u->name_without_title)) {
                $cleanWithoutTitle = $this->normalizeString($u->name_without_title);
                $this->cleanedNameMap[$cleanWithoutTitle] = $u->id;
            }
        }
    }

    /**
     * ID (Konteks Bisnis):
     * Menghapus gelar akademik depan dan belakang dari nama pegawai BPS.
     *
     * EN (Technical Specifications):
     * Strips academic titles (front and back) to yield a pure person name.
     */
    public function cleanAcademicTitle(string $name): string
    {
        $clean = trim($name);

        // Strip known honorific prefixes
        $clean = preg_replace('/^(Drs\.|Dra\.|Ir\.|Dr\.)\s+/i', '', $clean);

        // Common Indonesian academic degree titles
        $titles = [
            'S\.Tr\.Stat\.', 'S\.Tr\.Stat', 'S\.ST\.', 'S\.ST', 'SST\.', 'SST',
            'S\.Stat\.', 'S\.Stat', 'S\.Si\.', 'S\.Si', 'S\.E\.', 'S\.E', 'SE',
            'S\.Kom\.', 'S\.Kom', 'S\.Ak\.', 'S\.Ak', 'S\.Pt\.', 'S\.Pt', 'S\.Sos\.', 'S\.Sos',
            'S\.H\.', 'S\.H', 'S\.P\.', 'S\.P', 'S\.T\.', 'S\.T', 'ST',
            'A\.Md\.', 'A\.Md', 'AMd',
            'M\.Ec\.Dev\.', 'M\.Ec\.Dev', 'M\.Stat\.', 'M\.Stat', 'M\.Si\.', 'M\.Si',
            'M\.Kom\.', 'M\.Kom', 'M\.T\.', 'M\.T', 'MT\.', 'MT', 'M\.M\.', 'M\.M', 'M\.Sc\.', 'M\.Sc', 'M\.A\.', 'M\.A'
        ];

        $titlesPattern = implode('|', $titles);
        $regex = '/[, ]+\b(' . $titlesPattern . ')\b/i';

        $prev = '';
        while ($prev !== $clean) {
            $prev = $clean;
            $clean = preg_replace($regex, '', $clean);
        }

        return trim($clean, " \t\n\r\0\x0B,.");
    }

    /**
     * ID (Konteks Bisnis):
     * Menormalisasi string menjadi huruf kecil alfanumerik tunggal untuk perbandingan aman.
     *
     * EN (Technical Specifications):
     * Cleans titles and strips non-alphanumeric symbols for uniform string comparison.
     */
    public function normalizeString(string $str): string
    {
        $c = $this->cleanAcademicTitle($str);
        $c = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $c));
        return trim(preg_replace('/\s+/', ' ', $c));
    }

    /**
     * ID (Konteks Bisnis):
     * Mencari dan mengembalikan objek User yang sesuai berdasarkan string input.
     *
     * EN (Technical Specifications):
     * Resolves raw employee name, username, NIP, or nickname to a User model.
     *
     * @param string|null $rawInput Raw input string from legacy database.
     * @return User|null Found User instance or null if unmapped.
     */
    public function resolve(?string $rawInput): ?User
    {
        if ($rawInput === null) {
            return null;
        }

        $trimmed = trim($rawInput);
        if ($trimmed === '' || $trimmed === '-' || $trimmed === 'NULL' || $trimmed === 'null') {
            return null;
        }

        // 1. Direct NIP match (18 or 9 digits)
        if (ctype_digit($trimmed)) {
            if (isset($this->nipMap[$trimmed])) {
                return $this->userCache[$this->nipMap[$trimmed]];
            }
        }

        // 2. Predefined alias check (e.g., 'nanang', 'herpas', 'yuli.purwita')
        $lower = strtolower($trimmed);
        if (isset($this->aliasMap[$lower])) {
            $mappedName = $this->aliasMap[$lower];
            $norm = $this->normalizeString($mappedName);
            if (isset($this->cleanedNameMap[$norm])) {
                return $this->userCache[$this->cleanedNameMap[$norm]];
            }
        }

        // 3. Email prefix match
        if (isset($this->emailPrefixMap[$lower])) {
            return $this->userCache[$this->emailPrefixMap[$lower]];
        }

        // 4. Cleaned name match (exact title-stripped comparison)
        $norm = $this->normalizeString($trimmed);
        if (isset($this->cleanedNameMap[$norm])) {
            return $this->userCache[$this->cleanedNameMap[$norm]];
        }

        // 5. Partial Substring / Token matching
        foreach ($this->cleanedNameMap as $candName => $uid) {
            if (str_starts_with($candName, $norm) && strlen($candName) - strlen($norm) <= 5) {
                return $this->userCache[$uid];
            }
            if (str_starts_with($norm, $candName) && strlen($norm) - strlen($candName) <= 5) {
                return $this->userCache[$uid];
            }
        }

        return null;
    }

    /**
     * ID (Konteks Bisnis):
     * Mengembalikan ID pengguna (users.id) atau null jika tidak ditemukan.
     *
     * EN (Technical Specifications):
     * Resolves raw employee input directly to integer users.id or null.
     */
    public function resolveUserId(?string $rawInput): ?int
    {
        $user = $this->resolve($rawInput);
        return $user ? $user->id : null;
    }

    /**
     * Alias for resolveUserId.
     */
    public function resolveId(?string $rawInput): ?int
    {
        return $this->resolveUserId($rawInput);
    }

    /**
     * Gets registered alias dictionary.
     *
     * @return array<string, string>
     */
    public function getAliasMap(): array
    {
        return $this->aliasMap;
    }
}
