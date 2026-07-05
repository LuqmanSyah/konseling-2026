<?php

namespace App\Filament\Admin\Pages;

use App\Support\KonselingOptions;
use Filament\Pages\Page;

class DataMaster extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Data Master';

    protected static ?string $title = 'Data Master';

    protected static ?int $navigationSort = -1;

    protected static string $view = 'filament.admin.pages.data-master';

    /**
     * @return array<string, array<string, string>>
     */
    public function getMasterData(): array
    {
        return [
            'Kategori Konseling' => KonselingOptions::kategoriKonseling(),
            'Metode Konseling' => KonselingOptions::metodeKonseling(),
            'Status User' => KonselingOptions::userStatuses(),
            'Status Konselor' => KonselingOptions::konselorStatuses(),
            'Status Jadwal' => KonselingOptions::jadwalStatuses(),
            'Status Booking' => KonselingOptions::bookingStatuses(),
            'Role Pengguna' => KonselingOptions::roles(),
        ];
    }
}
