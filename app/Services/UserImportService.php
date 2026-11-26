<?php

namespace App\Services;

use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use InvalidArgumentException;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Reader\CSV\Options as CsvOptions;
use OpenSpout\Reader\CSV\Reader as CsvReader;
use OpenSpout\Reader\XLSX\Options as XlsxOptions;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use Throwable;

/**
 * Bulk user import: writes the template, reads an uploaded file and checks every row.
 * Creating the users stays with UserRepository::createInvited.
 */
class UserImportService
{
    public const MAX_ROWS = 200;

    private const COLUMNS = ['nama', 'email', 'role'];

    /** Example rows of the template, email => name. Left unchanged they are never imported. */
    private const EXAMPLES = [
        'budi.contoh@example.com' => 'Budi Contoh',
        'sari.contoh@example.com' => 'Sari Contoh',
    ];

    /** Guards against a sheet that claims to be enormous but is mostly empty. */
    private const MAX_FILE_LINES = 5000;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
    ) {}

    /**
     * First sheet: the three columns that are read (nama, email, role), two example rows, and a
     * "keterangan" column that only explains and is never read. Second sheet: the full instructions.
     * Role names are the ones that exist now.
     */
    public function writeTemplate(string $path): void
    {
        $bold = (new Style)->setFontBold();
        $grey = (new Style)->setFontColor(Color::rgb(110, 110, 110));
        $greyBold = (new Style)->setFontBold()->setFontColor(Color::rgb(110, 110, 110));

        $roles = $this->roleRepository->all();
        $roleNames = $roles->pluck('name');
        // The least privileged role, so a copied example never grants more than that.
        $basicRole = $roles->firstWhere('slug', 'user')?->name
            ?? $roles->firstWhere('slug', '!=', 'super-admin')?->name
            ?? '';

        $notes = [
            'Contoh, tidak ikut diimpor. Timpa dengan data Anda atau tambahkan di bawahnya.',
            'Contoh, tidak ikut diimpor.',
            'nama: wajib.',
            'email: wajib, belum terdaftar, dan tidak boleh kembar.',
            'role: wajib. Lebih dari satu dipisah koma; yang pertama menjadi role default.',
            'Role tersedia: '.$roleNames->implode(', '),
            'Akun langsung aktif. Tiap pengguna menerima email verifikasi berisi kata sandi sementara.',
        ];

        $writer = new XlsxWriter;
        $writer->openToFile($path);

        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Pengguna');
        $sheet->setColumnWidth(28, 1);
        $sheet->setColumnWidth(32, 2);
        $sheet->setColumnWidth(18, 3);
        $sheet->setColumnWidth(90, 4);

        $writer->addRow(Row::fromValuesWithStyles(
            [...self::COLUMNS, 'keterangan (tidak diimpor)'],
            $bold,
            [3 => $greyBold],
        ));

        $examples = [];
        foreach (self::EXAMPLES as $email => $name) {
            $examples[] = [$name, $email, $basicRole];
        }

        foreach ($notes as $index => $note) {
            // A single space keeps note-only rows visible in viewers that drop rows starting past
            // column A (macOS Quick Look); the reader trims it, so those rows still count as empty.
            $writer->addRow(Row::fromValuesWithStyles(
                [...($examples[$index] ?? [' ', ' ', ' ']), $note],
                null,
                [3 => $grey],
            ));
        }

        $guide = $writer->addNewSheetAndMakeItCurrent();
        $guide->setName('Petunjuk');
        $guide->setColumnWidth(16, 1);
        $guide->setColumnWidth(95, 2);
        $writer->addRow(Row::fromValues(['Kolom', 'Cara mengisi'], $bold));
        $writer->addRows([
            Row::fromValues(['nama', 'Wajib. Nama yang ditampilkan. Contoh: Budi Santoso']),
            Row::fromValues(['email', 'Wajib. Belum terdaftar dan tidak boleh kembar di dalam file. Contoh: budi@domainanda.com']),
            Row::fromValues(['role', 'Wajib. Contoh satu role: '.$basicRole.'. Contoh dua role: '.$roleNames->take(-2)->implode(', ').' (yang pertama menjadi default).']),
            Row::fromValues(['', '']),
            Row::fromValues(['Kolom dibaca', 'Hanya nama, email, dan role. Kolom lain, termasuk keterangan, diabaikan.']),
            Row::fromValues(['Status akun', 'Langsung aktif. Tiap pengguna menerima email berisi tautan verifikasi dan kata sandi sementara.']),
            Row::fromValues(['Baris contoh', 'Budi Contoh dan Sari Contoh di lembar "Pengguna" hanya contoh dan tidak ikut diimpor.']),
            Row::fromValues(['', '']),
            Row::fromValues(['Role tersedia', $roleNames->implode(', ')]),
            Row::fromValues(['Batas', 'Maksimal '.self::MAX_ROWS.' baris per file. Isi data di lembar "Pengguna".']),
        ]);

        $writer->close();
    }

    /**
     * @return list<array{line:int, name:string, email:string, role:string}>
     *
     * @throws InvalidArgumentException with a message that can be shown to the user
     */
    public function read(string $path, string $extension): array
    {
        $columns = null;
        $rows = [];
        $skippedExamples = 0;

        try {
            $reader = $this->makeReader($path, strtolower($extension));
            $reader->open($path);

            foreach ($reader->getSheetIterator() as $sheet) {
                $line = 0;

                foreach ($sheet->getRowIterator() as $row) {
                    if (++$line > self::MAX_FILE_LINES) {
                        break;
                    }

                    $values = array_map($this->asText(...), $row->toArray());

                    if ($columns === null) {
                        $columns = $this->mapColumns($values);

                        continue;
                    }

                    $data = [
                        'line' => $line,
                        'name' => $values[$columns['nama']] ?? '',
                        'email' => $values[$columns['email']] ?? '',
                        'role' => $values[$columns['role']] ?? '',
                    ];

                    if (implode('', array_slice($data, 1)) === '') {
                        continue;
                    }

                    if ((self::EXAMPLES[mb_strtolower($data['email'])] ?? null) === $data['name']) {
                        $skippedExamples++;

                        continue;
                    }

                    if (count($rows) >= self::MAX_ROWS) {
                        throw new InvalidArgumentException('Maksimal '.self::MAX_ROWS.' baris per file. Bagi data menjadi beberapa file.');
                    }

                    $rows[] = $data;
                }

                break; // only the first sheet holds data
            }

            $reader->close();
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            throw new InvalidArgumentException('File tidak bisa dibaca. Gunakan template .xlsx atau file .csv.');
        }

        if ($rows === []) {
            throw new InvalidArgumentException($skippedExamples > 0
                ? 'File hanya berisi baris contoh. Timpa dengan data Anda atau tambahkan di bawahnya.'
                : 'File tidak berisi data pengguna.');
        }

        return $rows;
    }

    /**
     * Adds errors and role_ids to each row. Call again right before importing:
     * the database may have changed since the preview.
     */
    public function validate(array $rows): array
    {
        $rolesByKey = [];
        foreach ($this->roleRepository->all() as $role) {
            $rolesByKey[mb_strtolower($role->name)] = $role->id;
            $rolesByKey[mb_strtolower($role->slug)] = $role->id;
        }

        $taken = array_flip($this->userRepository->existingEmails(array_column($rows, 'email')));
        $seenAt = [];

        return array_map(function (array $row) use ($rolesByKey, $taken, &$seenAt) {
            $errors = [];
            $email = mb_strtolower($row['email']);

            if ($row['name'] === '') {
                $errors[] = 'Nama wajib diisi.';
            } elseif (mb_strlen($row['name']) > 255) {
                $errors[] = 'Nama terlalu panjang.';
            }

            if ($email === '') {
                $errors[] = 'Email wajib diisi.';
            } elseif (! filter_var($row['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255) {
                $errors[] = 'Format email tidak valid.';
            } elseif (isset(self::EXAMPLES[$email])) {
                $errors[] = 'Email contoh dari template. Ganti dengan email asli.';
            } elseif (isset($taken[$email])) {
                $errors[] = 'Email sudah terdaftar.';
            } elseif (isset($seenAt[$email])) {
                $errors[] = "Email sama dengan baris {$seenAt[$email]}.";
            } else {
                $seenAt[$email] = $row['line'];
            }

            $roleIds = [];
            $names = array_filter(array_map('trim', explode(',', $row['role'])), fn ($name) => $name !== '');

            if ($names === []) {
                $errors[] = 'Role wajib diisi.';
            }

            foreach ($names as $name) {
                if (isset($rolesByKey[mb_strtolower($name)])) {
                    $roleIds[] = $rolesByKey[mb_strtolower($name)];
                } else {
                    $errors[] = "Role tidak dikenal: {$name}.";
                }
            }

            return [
                ...$row,
                'errors' => $errors,
                'role_ids' => array_values(array_unique($roleIds)),
            ];
        }, $rows);
    }

    private function makeReader(string $path, string $extension): XlsxReader|CsvReader
    {
        if ($extension === 'xlsx') {
            $options = new XlsxOptions;
            $options->SHOULD_PRESERVE_EMPTY_ROWS = true; // keeps line numbers equal to the spreadsheet

            return new XlsxReader($options);
        }

        $options = new CsvOptions;
        $options->SHOULD_PRESERVE_EMPTY_ROWS = true;
        // Excel with an Indonesian locale writes CSV with semicolons.
        $firstLine = (string) fgets(fopen($path, 'r'));
        $options->FIELD_DELIMITER = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';

        return new CsvReader($options);
    }

    /** @return array<string, int> column name => position */
    private function mapColumns(array $header): array
    {
        $columns = [];

        foreach ($header as $position => $name) {
            $name = mb_strtolower(trim($name, " \t\n\r\0\x0B\u{FEFF}"));

            if (in_array($name, self::COLUMNS, true)) {
                $columns[$name] ??= $position;
            }
        }

        $missing = array_diff(self::COLUMNS, array_keys($columns));

        if ($missing !== []) {
            throw new InvalidArgumentException('Kolom wajib tidak ditemukan: '.implode(', ', $missing).'. Gunakan template yang disediakan.');
        }

        return $columns;
    }

    private function asText(mixed $value): string
    {
        return match (true) {
            $value instanceof \DateTimeInterface => $value->format('Y-m-d'),
            is_scalar($value) => trim((string) $value),
            default => '',
        };
    }
}
