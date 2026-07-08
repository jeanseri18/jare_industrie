@if(!empty($chartDonut ?? null) || !empty($chartLine ?? null) || !empty($chartBar ?? null))
    @if(!empty($chartDonut) && empty($skipDonut))
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6">
                <x-dashboard-chart
                    id="chart-donut-status"
                    title="{{ $chartDonut['title'] ?? 'Répartition des dossiers' }}"
                    icon="fas fa-chart-pie"
                    type="doughnut"
                    :labels="$chartDonut['labels'] ?? []"
                    :values="$chartDonut['values'] ?? []"
                    class="mb-0"
                />
            </div>
        </div>
    @endif

    @if(!empty($chartLine))
        <div class="row mb-4">
            <div class="col-12">
                <x-dashboard-chart
                    id="chart-line-trend"
                    title="{{ $chartLine['title'] ?? 'Évolution des encaissements' }}"
                    icon="fas fa-chart-line"
                    type="line"
                    :labels="$chartLine['labels'] ?? []"
                    :values="$chartLine['values'] ?? []"
                    :datasets="$chartLine['datasets'] ?? null"
                    :chart-label="$chartLine['chartLabel'] ?? null"
                    :currency="($chartLine['currency'] ?? false)"
                    height="300px"
                    class="mb-0"
                />
            </div>
        </div>
    @endif

    @if(!empty($chartBar))
        <div class="row mb-4">
            <div class="col-12">
                <x-dashboard-chart
                    id="chart-bar-projects"
                    title="{{ $chartBar['title'] ?? 'Encaissements par projet' }}"
                    icon="fas fa-chart-column"
                    type="bar"
                    :labels="$chartBar['labels'] ?? []"
                    :values="$chartBar['values'] ?? []"
                    :chart-label="$chartBar['chartLabel'] ?? 'Montant (FCFA)'"
                    :currency="($chartBar['currency'] ?? true)"
                    height="300px"
                    class="mb-0"
                />
            </div>
        </div>
    @endif
@endif
