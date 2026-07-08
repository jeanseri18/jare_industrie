<?php

return [
    'dg' => [
        ['label' => 'Tableau de bord', 'route' => 'dg.dashboard', 'icon' => 'home'],
        ['label' => 'Clients', 'route' => 'dg.clients.index', 'icon' => 'users'],
        ['label' => 'Projets', 'route' => 'dg.projets.index', 'icon' => 'building'],
        ['label' => 'Souscriptions', 'route' => 'dg.souscriptions.index', 'icon' => 'file'],
        ['label' => 'Mutuelles', 'route' => 'dg.mutuelles.index', 'icon' => 'handshake'],
        ['label' => 'Attribution', 'route' => 'dg.attribution.index', 'icon' => 'map'],
        ['label' => 'Confirmation', 'route' => 'dg.confirmation.index', 'icon' => 'check'],
        ['label' => 'Équipes', 'route' => 'dg.equipes.index', 'icon' => 'team'],
        ['label' => 'Identité visuelle', 'route' => 'dg.parametres.identite', 'icon' => 'palette'],
        ['label' => 'Frais dossier', 'route' => 'dg.frais-dossier', 'icon' => 'cash'],
        ['label' => 'Apports initiaux', 'route' => 'dg.apports-initiaux', 'icon' => 'cash'],
        ['label' => 'Suivi paiements', 'route' => 'dg.suivi-paiements-projet', 'icon' => 'chart'],
        ['label' => 'Dossiers annulés', 'route' => 'dg.dossiers-annules', 'icon' => 'archive'],
    ],
    'comptable' => [
        ['label' => 'Tableau de bord', 'route' => 'comptable.dashboard', 'icon' => 'home'],
        ['label' => 'Clients', 'route' => 'comptable.clients.index', 'icon' => 'users'],
        ['label' => 'Frais dossier', 'route' => 'comptable.frais-dossier', 'icon' => 'cash'],
        ['label' => 'Apports initiaux', 'route' => 'comptable.apports-initiaux', 'icon' => 'cash'],
        ['label' => 'Paiements souscription', 'route' => 'comptable.paiements-souscription', 'icon' => 'file'],
        ['label' => 'Suivi paiements', 'route' => 'comptable.suivi-paiements-projet', 'icon' => 'chart'],
        ['label' => 'Projets soldés', 'route' => 'comptable.projets-soldes', 'icon' => 'check'],
        ['label' => 'Dossiers annulés', 'route' => 'comptable.dossiers-annules', 'icon' => 'archive'],
    ],
    'operateur' => [
        ['label' => 'Tableau de bord', 'route' => 'operateur.dashboard', 'icon' => 'home'],
        ['label' => 'Nouvelle souscription', 'route' => 'operateur.souscriptions.create', 'icon' => 'plus'],
    ],
    'chef_commercial' => [
        ['label' => 'Tableau de bord', 'route' => 'chef_commercial.dashboard', 'icon' => 'home'],
        ['label' => 'Nouvelle souscription', 'route' => 'chef_commercial.souscriptions.create', 'icon' => 'plus'],
        ['label' => 'Corrigées', 'route' => 'chef_commercial.souscriptions.corrigees', 'icon' => 'check'],
        ['label' => 'À corriger', 'route' => 'chef_commercial.souscriptions.corrige', 'icon' => 'edit'],
    ],
    'admin_technique' => [
        ['label' => 'Tableau de bord', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Utilisateurs', 'route' => 'admin.users.index', 'icon' => 'users'],
        ['label' => 'Clients', 'route' => 'admin.clients.index', 'icon' => 'user'],
        ['label' => 'Historique', 'route' => 'admin.history.index', 'icon' => 'clock'],
    ],
    'super_admin' => [
        ['label' => 'Organisations', 'route' => 'platform.organizations.index', 'icon' => 'building'],
        ['label' => 'Nouvelle organisation', 'route' => 'platform.organizations.create', 'icon' => 'plus'],
    ],
    'client' => [
        ['label' => 'Accueil', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Dossiers', 'route' => 'client.souscriptions', 'icon' => 'file'],
        ['label' => 'Paiements', 'route' => 'client.historique', 'icon' => 'clock'],
        ['label' => 'Documents', 'route' => 'client.documents', 'icon' => 'document'],
        ['label' => 'Alertes', 'route' => 'client.notifications', 'icon' => 'bell'],
        ['label' => 'Profil', 'route' => 'client.profile', 'icon' => 'user'],
    ],
];
