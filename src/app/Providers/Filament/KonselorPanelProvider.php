<?php

namespace App\Providers\Filament;

use App\Http\Middleware\FilamentAuthenticate;
use App\Http\Middleware\FilamentAuthenticateSession;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class KonselorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('konselor')
            ->path('konselor')
            ->spa()
            ->brandName('Konselor')
            ->defaultThemeMode(ThemeMode::Light)
            ->font('Montserrat')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->maxContentWidth(MaxWidth::SevenExtraLarge)
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Konselor/Resources'), for: 'App\\Filament\\Konselor\\Resources')
            ->discoverPages(in: app_path('Filament/Konselor/Pages'), for: 'App\\Filament\\Konselor\\Pages')
            ->pages([
                \App\Filament\Konselor\Pages\Dashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                FilamentAuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                FilamentAuthenticate::class,
            ]);
    }
}
