@extends('layouts.guest')

@section('content')
@php
    $dashboardUrl = auth()->check() ? match (auth()->user()->role) {
        'super_admin' => route('platform.organizations.index'),
        'dg' => route('dg.dashboard'),
        'comptable' => route('comptable.dashboard'),
        'operateur' => route('operateur.dashboard'),
        'chef_commercial' => route('chef_commercial.dashboard'),
        'admin_technique' => route('admin.dashboard'),
        'client' => route('client.dashboard'),
        default => route('login'),
    } : null;
@endphp
<div class="landing text-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset || document.documentElement.scrollTop) > 50">
    {{-- Header : transparent → noir au scroll --}}
    <header
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300 ease-in-out"
        :class="scrolled
            ? 'bg-black/95 shadow-lg backdrop-blur-md'
            : 'bg-transparent'"
    >
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ url('/') }}" class="text-xl font-bold tracking-tight leading-none">
                <span class="text-white">DIGIT</span><span class="text-[#ff7200]"> BTP</span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ $dashboardUrl }}"
                       class="rounded-lg !bg-[#ff7200] px-4 py-2 text-sm font-semibold !text-white transition hover:!bg-[#e66500]">
                        Mon espace
                    </a>
                @else
                    <a href="{{ url('/login') }}"
                       class="rounded-lg !bg-white px-4 py-2 text-sm font-semibold !text-black shadow-sm transition hover:!bg-gray-100">
                        Connexion
                    </a>
                    <a href="{{ url('/register') }}"
                       class="rounded-lg !bg-[#ff7200] px-4 py-2 text-sm font-semibold !text-white transition hover:!bg-[#e66500]">
                        Inscription
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-28 pb-20 md:pt-36 md:pb-28 min-h-[85vh] flex items-center">
        <div
            class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('images/hardhat-blueprints-wooden-table.jpg') }}');"
        ></div>
        <div class="absolute inset-0 bg-gradient-to-br from-black/75 via-[#ff7200]/80 to-black/90"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(0,0,0,0.2)_0%,_rgba(0,0,0,0.5)_100%)]"></div>
        <div class="relative mx-auto w-full max-w-6xl px-6 text-center">
            <p class="mb-4 inline-block rounded-full border border-white/20 bg-white/10 px-4 py-1 text-sm font-medium text-white/90">
                Plateforme ERP pour promoteurs immobiliers & BTP
            </p>
            <h1 class="text-4xl font-bold leading-tight tracking-tight md:text-6xl lg:text-7xl">
                ERP immobilier & BTP<br><span class="text-white/90">en marque blanche</span>
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-white/80 md:text-xl">
                Gérez projets, souscriptions, lots et paiements. Personnalisez vos PDF — logo, couleurs, mentions légales — pour chaque entreprise.
            </p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ $dashboardUrl }}" class="rounded-xl bg-white px-8 py-3.5 text-base font-bold text-slate-900 shadow-lg hover:bg-white/95 transition">
                        Accéder à mon espace
                    </a>
                @else
                    <a href="{{ url('/login') }}" class="rounded-xl bg-white px-8 py-3.5 text-base font-bold text-slate-900 shadow-lg hover:bg-white/95 transition">
                        Connexion
                    </a>
                    <a href="{{ url('/register') }}" class="rounded-xl border-2 border-white/40 bg-white/10 px-8 py-3.5 text-base font-bold backdrop-blur hover:bg-white/20 transition">
                        Inscription
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Comment ça marche --}}
    <section id="comment-ca-marche" class="bg-slate-50 py-20 text-slate-900">
        <div class="mx-auto max-w-6xl px-6">
            <div class="text-center">
                <h2 class="text-3xl font-bold md:text-4xl">Comment ça marche</h2>
                <p class="mx-auto mt-4 max-w-2xl text-slate-600">Mettez votre promoteur en ligne en quelques étapes, sans développement sur mesure.</p>
            </div>
            <div class="mt-14 grid gap-8 md:grid-cols-4">
                @foreach([
                    ['1', 'Créez votre espace', 'Un super-admin provisionne votre organisation, votre sous-domaine et votre compte DG.'],
                    ['2', 'Personnalisez la marque', 'Uploadez logo, couleurs et mentions légales. Prévisualisez vos PDF en un clic.'],
                    ['3', 'Configurez vos projets', 'Ajoutez cités, lots, biens immobiliers et équipes (comptable, opérateurs, commercial).'],
                    ['4', 'Vendez & suivez', 'Souscriptions, paiements, attributions et documents automatiques à votre image.'],
                ] as [$num, $title, $desc])
                    <div class="relative text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#ff7200] text-xl font-bold text-white shadow-lg">{{ $num }}</div>
                        @if($num !== '4')
                            <div class="absolute left-[calc(50%+2rem)] top-7 hidden h-0.5 w-[calc(100%-4rem)] bg-slate-200 md:block"></div>
                        @endif
                        <h3 class="mt-5 text-lg font-bold">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Fonctionnalités --}}
    <section id="fonctionnalites" class="bg-black py-20 text-white">
        <div class="mx-auto max-w-6xl px-6">
            <div class="text-center">
                <h2 class="text-3xl font-bold md:text-4xl">Fonctionnalités</h2>
                <p class="mx-auto mt-4 max-w-2xl text-white/70">Tout le cycle de vente immobilière, centralisé dans une seule plateforme.</p>
            </div>
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    ['building', 'Gestion de projets', 'Programmes, îlots, lots, biens et prix par type de logement.'],
                    ['clipboard', 'Souscriptions', 'Dossiers clients complets avec workflow de validation DG.'],
                    ['banknotes', 'Paiements & comptabilité', 'Frais de dossier, apports initiaux, échéances et reçus PDF.'],
                    ['document', 'Documents white-label', 'Fiche, contrat, attestation, lettre définitive — à votre charte.'],
                    ['users', 'Multi-rôles', 'DG, comptable, opérateur, chef commercial, espace client dédié.'],
                    ['lock', 'Multi-tenant sécurisé', 'Données isolées par entreprise, URLs signées pour partage document.'],
                ] as [$icon, $title, $desc])
                    <div class="group rounded-2xl border border-gray-600 bg-black/40 p-6 backdrop-blur-sm transition hover:bg-black/60">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#ff7200] text-white">
                            <x-icon :name="$icon" class="h-7 w-7" />
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-white">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-white/70">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Ils nous font confiance --}}
    <section id="confiance" class="overflow-hidden bg-slate-100 py-16">
        <div class="mx-auto max-w-6xl px-6 text-center">
            <h2 class="text-2xl font-bold text-slate-900 md:text-3xl">Ils nous font confiance</h2>
            <p class="mt-3 text-slate-600">Des promoteurs et entreprises BTP en Côte d'Ivoire et en Afrique de l'Ouest</p>
        </div>
        @php $partners = ['Promo Habitat CI', 'BTP Atlantique', 'Cité Nouvelle', 'Immo Plus', 'Groupe Foncier Ouest', 'Digit BTP']; @endphp
        <div class="partner-marquee relative mt-10">
            <div class="partner-marquee-fade partner-marquee-fade-left"></div>
            <div class="partner-marquee-fade partner-marquee-fade-right"></div>
            <div class="partner-marquee-track">
                @foreach(array_merge($partners, $partners) as $name)
                    <div class="partner-marquee-item">
                        <span class="text-sm font-bold uppercase tracking-wide text-slate-500">{{ $name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <p class="mx-auto mt-8 max-w-6xl px-6 text-center text-sm text-slate-500">+ de 2 500 dossiers de souscription traités sur la plateforme</p>
    </section>

    {{-- Témoignages --}}
    <section id="temoignages" class="bg-white py-20 text-slate-900">
        <div class="mx-auto max-w-6xl px-6">
            <div class="text-center">
                <span class="inline-block rounded-full border border-[#ff7200]/20 bg-[#ff7200]/5 px-4 py-1 text-sm font-semibold text-[#ff7200]">
                    Avis clients
                </span>
                <h2 class="mt-4 text-3xl font-bold md:text-4xl">Témoignages</h2>
                <p class="mx-auto mt-3 max-w-xl text-slate-600">Ce que disent nos clients promoteurs</p>
            </div>
            <div class="mt-14 grid gap-8 md:grid-cols-3">
                @foreach([
                    ['Roland K.', 'RK', 'Directeur Général — Promoteur immobilier', 'Instrudie nous a permis de digitaliser tout notre parcours client. Les PDF sortent directement avec notre logo et nos couleurs — un gain de temps énorme.', 5],
                    ['Aminata D.', 'AD', 'Responsable commerciale', 'Les équipes saisissent les souscriptions en quelques minutes. Le suivi des paiements et des lots est enfin centralisé.', 5],
                    ['Kouassi M.', 'KM', 'Comptable', 'Frais de dossier, apports, reçus : tout est tracé. La comptabilité et le DG voient la même information en temps réel.', 5],
                ] as [$name, $initials, $role, $quote, $stars])
                    <blockquote class="group flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-7 shadow-md transition hover:-translate-y-1 hover:border-[#ff7200]/30 hover:shadow-xl">
                        <div class="flex items-start justify-between gap-4">
                            <x-star-rating :rating="$stars" class="h-4 w-4" />
                            <svg class="h-8 w-8 shrink-0 text-[#ff7200]/20 transition group-hover:text-[#ff7200]/40" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.45l.812 1.846C6.455 7.613 5.087 10.238 5.087 13c0 1.648.697 2.967 1.496 3.821.775.828 1.786 1.321 2.996 1.321 1.211 0 2.222-.493 2.996-1.321.8-.854 1.496-2.173 1.496-3.821 0-2.762-1.368-5.387-3.845-6.593l.812-1.846C18.543 6.374 21 9.511 21 13.011c0 1.989-.553 3.216-1.583 4.31-.985.944-2.347 1.489-4.167 1.489-1.82 0-3.182-.545-4.167-1.489z"/>
                            </svg>
                        </div>
                        <p class="mt-5 flex-1 text-sm leading-relaxed text-slate-600">« {{ $quote }} »</p>
                        <footer class="mt-6 flex items-center gap-4 border-t border-slate-100 pt-5">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#ff7200]/10 text-sm font-bold text-[#ff7200]">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <cite class="not-italic font-bold text-slate-900">{{ $name }}</cite>
                                <p class="truncate text-xs text-slate-500">{{ $role }}</p>
                            </div>
                        </footer>
                    </blockquote>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contact --}}
    <section id="contact" class="bg-slate-50 py-20 text-slate-900">
        <div class="mx-auto max-w-6xl px-6">
            <div class="grid gap-12 lg:grid-cols-2">
                <div>
                    <h2 class="text-3xl font-bold md:text-4xl">Contactez-nous</h2>
                    <p class="mt-4 text-slate-600">
                        Une démo, un devis ou une question sur la plateforme ? Notre équipe vous répond sous 48 h ouvrées.
                    </p>
                    <ul class="mt-8 space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <x-icon name="envelope" class="mt-0.5 h-5 w-5 shrink-0 text-[#ff7200]" />
                            <span>contact@instrudie.ci</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon name="phone" class="mt-0.5 h-5 w-5 shrink-0 text-[#ff7200]" />
                            <span>+225 07 00 00 00 00</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon name="map-pin" class="mt-0.5 h-5 w-5 shrink-0 text-[#ff7200]" />
                            <span>Abidjan, Côte d'Ivoire</span>
                        </li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-lg">
                    @if(session('success'))
                        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="mb-6 rounded-lg border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-800">
                            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Nom complet *</label>
                                <input type="text" name="nom" class="form-input" required value="{{ old('nom') }}">
                            </div>
                            <div>
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-input" required value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Entreprise</label>
                                <input type="text" name="entreprise" class="form-input" value="{{ old('entreprise') }}">
                            </div>
                            <div>
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="telephone" class="form-input" value="{{ old('telephone') }}">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Message *</label>
                            <textarea name="message" rows="4" class="form-input" required placeholder="Décrivez votre besoin (nombre de projets, utilisateurs, démo…)">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full py-3 text-base">Envoyer le message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-black text-white">
        <div class="mx-auto max-w-6xl px-6 py-16">
            <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
                {{-- Marque --}}
                <div class="lg:col-span-1">
                    <div class="text-2xl font-bold tracking-tight">
                        <span class="text-white">DIGIT</span><span class="text-[#ff7200]"> BTP</span>
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-white/60">
                        Plateforme ERP en marque blanche pour promoteurs immobiliers et entreprises BTP en Afrique de l'Ouest.
                    </p>
                </div>

                {{-- Navigation --}}
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Navigation</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li><a href="#comment-ca-marche" class="text-white/60 transition hover:text-[#ff7200]">Comment ça marche</a></li>
                        <li><a href="#fonctionnalites" class="text-white/60 transition hover:text-[#ff7200]">Fonctionnalités</a></li>
                        <li><a href="#confiance" class="text-white/60 transition hover:text-[#ff7200]">Ils nous font confiance</a></li>
                        <li><a href="#temoignages" class="text-white/60 transition hover:text-[#ff7200]">Témoignages</a></li>
                        <li><a href="#contact" class="text-white/60 transition hover:text-[#ff7200]">Contact</a></li>
                        @guest
                            <li><a href="{{ url('/register') }}" class="text-white/60 transition hover:text-[#ff7200]">Inscription</a></li>
                        @endguest
                    </ul>
                </div>

                {{-- Produit --}}
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Produit</h3>
                    <ul class="mt-4 space-y-3 text-sm text-white/60">
                        <li>Gestion de projets & lots</li>
                        <li>Souscriptions & paiements</li>
                        <li>Documents PDF white-label</li>
                        <li>Multi-rôles & multi-tenant</li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Contact</h3>
                    <ul class="mt-4 space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <x-icon name="envelope" class="mt-0.5 h-5 w-5 shrink-0 text-[#ff7200]" />
                            <a href="mailto:contact@instrudie.ci" class="text-white/60 transition hover:text-white">contact@instrudie.ci</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon name="phone" class="mt-0.5 h-5 w-5 shrink-0 text-[#ff7200]" />
                            <a href="tel:+2250700000000" class="text-white/60 transition hover:text-white">+225 07 00 00 00 00</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <x-icon name="map-pin" class="mt-0.5 h-5 w-5 shrink-0 text-[#ff7200]" />
                            <span class="text-white/60">Abidjan, Côte d'Ivoire</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-14 flex flex-col items-center justify-between gap-4 border-t border-gray-800 pt-8 md:flex-row">
                <p class="text-xs text-white/40">© {{ date('Y') }} Digit BTP. Tous droits réservés.</p>
                <div class="flex flex-wrap items-center justify-center gap-6 text-xs text-white/40">
                    <a href="#" class="transition hover:text-white/70">Mentions légales</a>
                    <a href="#" class="transition hover:text-white/70">Politique de confidentialité</a>
                    <a href="#" class="transition hover:text-white/70">Conditions d'utilisation</a>
                </div>
            </div>
        </div>
    </footer>
</div>

<style>
    html { scroll-behavior: smooth; }

    .partner-marquee {
        overflow: hidden;
        width: 100%;
    }

    .partner-marquee-track {
        display: flex;
        width: max-content;
        gap: 2rem;
        animation: partner-marquee 35s linear infinite;
    }

    .partner-marquee:hover .partner-marquee-track {
        animation-play-state: paused;
    }

    .partner-marquee-item {
        display: flex;
        height: 4rem;
        min-width: 11rem;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 0 1.5rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .partner-marquee-fade {
        pointer-events: none;
        position: absolute;
        top: 0;
        z-index: 10;
        height: 100%;
        width: 5rem;
    }

    .partner-marquee-fade-left {
        left: 0;
        background: linear-gradient(to right, #f1f5f9, transparent);
    }

    .partner-marquee-fade-right {
        right: 0;
        background: linear-gradient(to left, #f1f5f9, transparent);
    }

    @keyframes partner-marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
</style>
@endsection
