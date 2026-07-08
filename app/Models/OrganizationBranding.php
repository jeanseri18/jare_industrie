<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class OrganizationBranding extends Model
{
    protected $table = 'organization_branding';

    protected $fillable = [
        'organization_id',
        'logo_path',
        'watermark_path',
        'favicon_path',
        'signature_path',
        'primary_color',
        'secondary_color',
        'accent_color',
        'legal_name',
        'tagline',
        'director_name',
        'director_title',
        'rccm',
        'cc',
        'tax_id',
        'address',
        'city',
        'country',
        'phone',
        'whatsapp',
        'email',
        'website',
        'footer_text',
        'contract_clauses',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function logoAbsolutePath(): ?string
    {
        return $this->assetAbsolutePath($this->logo_path);
    }

    public function watermarkAbsolutePath(): ?string
    {
        return $this->assetAbsolutePath($this->watermark_path);
    }

    public function signatureAbsolutePath(): ?string
    {
        return $this->assetAbsolutePath($this->signature_path);
    }

    public function logoUrl(): ?string
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return Storage::disk('public')->url($this->logo_path);
        }

        return null;
    }

    public function faviconUrl(): ?string
    {
        if ($this->favicon_path && Storage::disk('public')->exists($this->favicon_path)) {
            return Storage::disk('public')->url($this->favicon_path);
        }

        return $this->logoUrl();
    }

    public function toThemeVariables(): array
    {
        return [
            'primary' => $this->primary_color ?? '#003d82',
            'secondary' => $this->secondary_color ?? '#1e3a8a',
            'accent' => $this->accent_color ?? '#4CAF50',
        ];
    }

    public function displayName(): string
    {
        return $this->legal_name ?? $this->organization?->name ?? config('app.name');
    }

    public function logoDataUri(): ?string
    {
        return $this->pathToDataUri($this->logo_path);
    }

    public function watermarkDataUri(): ?string
    {
        return $this->pathToDataUri($this->watermark_path);
    }

    public function signatureDataUri(): ?string
    {
        return $this->pathToDataUri($this->signature_path);
    }

    public function assetSrc(?string $path): ?string
    {
        return $this->pathToDataUri($path) ?? $this->assetAbsolutePath($path);
    }

    private function assetAbsolutePath(?string $path, ?string $fallback = null): ?string
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->path($path);
        }

        if ($fallback && file_exists($fallback)) {
            return $fallback;
        }

        return null;
    }

    private function pathToDataUri(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $absolute = Storage::disk('public')->path($path);
        $mime = @mime_content_type($absolute) ?: 'image/png';

        return 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($absolute));
    }
}
