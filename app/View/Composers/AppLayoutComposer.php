<?php

namespace App\View\Composers;

use App\Support\CurrentOrganization;
use Illuminate\View\View;

class AppLayoutComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();
        $role = $user?->role ?? 'guest';
        $navItems = config("navigation.{$role}", []);

        $branding = CurrentOrganization::branding();
        $theme = $branding?->toThemeVariables() ?? [
            'primary' => '#003d82',
            'secondary' => '#1e3a8a',
            'accent' => '#4CAF50',
        ];

        $view->with([
            'navItems' => $navItems,
            'navRole' => $role,
            'hideSidebar' => in_array($role, ['operateur', 'client'], true),
            'topNavLinks' => $role === 'client',
            'branding' => $branding,
            'theme' => $theme,
            'organization' => CurrentOrganization::get(),
        ]);
    }
}
