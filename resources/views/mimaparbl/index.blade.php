@extends('layouts.app')

@section('title', 'MiMapaRBL')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">MiMapaRBL</h1>
    </div>

    @include('mimaparbl._recursos')

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @if ($rutas->isNotEmpty())
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label for="selectRutaRegistrada" class="form-label">Rutas registradas</label>
                        <select id="selectRutaRegistrada" class="form-select">
                            <option value="">Selecciona una ruta...</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id }}">{{ $ruta->origen }} → {{ $ruta->destino }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <button type="button" id="btnLimpiarRuta" class="btn btn-outline-secondary">
                            <i class="bi bi-eraser"></i> Limpiar
                        </button>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    Aún no hay rutas registradas. Crea una ruta en la sección <strong>Rutas</strong> para poder trazarla en el mapa.
                </div>
            @endif

            <div id="mapaInfoRuta" class="alert alert-info mt-3 mb-0" hidden></div>
        </div>
    </div>

    <div class="card shadow-sm mb-0">
        <div id="mapaPrincipal" style="height: calc(100vh - 232px); min-height: 480px; border-radius: .375rem; overflow: hidden;"></div>
    </div>

    <script>
        var infoRuta = document.getElementById('mapaInfoRuta');
        var selectorRuta = document.getElementById('selectRutaRegistrada');

        // Direcciones de las rutas registradas, para trazarlas al elegir una
        var rutasRegistradas = @json($rutas->mapWithKeys(function ($ruta) {
            return [$ruta->id => ['origen' => $ruta->origen, 'destino' => $ruta->destino]];
        }));

        MapaRBL.iniciar('mapaPrincipal');

        function mostrarInfo(clase, mensaje) {
            infoRuta.hidden = false;
            infoRuta.className = 'alert ' + clase + ' mt-3 mb-0';
            infoRuta.innerHTML = mensaje;
        }

        function trazarRutaRegistrada(ruta) {
            mostrarInfo('alert-info', '<span class="spinner-border spinner-border-sm"></span> Buscando la ruta...');

            MapaRBL.trazarRuta(ruta.origen, ruta.destino, function (resultado) {
                if (!resultado) {
                    mostrarInfo('alert-warning', 'No se encontró alguna de las direcciones de esta ruta. Revisa cómo está escrita en la sección Rutas.');
                    return;
                }

                mostrarInfo('alert-success',
                    'Distancia por carretera: <strong>' + resultado.distanciaKm.toFixed(1) + ' km</strong> · ' +
                    'Tiempo estimado: <strong>' + resultado.horas.toFixed(1) + ' h</strong>'
                );
            });
        }

        if (selectorRuta) {
            selectorRuta.addEventListener('change', function () {
                var ruta = rutasRegistradas[this.value];

                if (!ruta) {
                    MapaRBL.limpiarRuta();
                    infoRuta.hidden = true;
                    return;
                }

                trazarRutaRegistrada(ruta);
            });

            document.getElementById('btnLimpiarRuta').addEventListener('click', function () {
                MapaRBL.limpiarRuta();
                selectorRuta.value = '';
                infoRuta.hidden = true;
            });
        }
    </script>
@endsection
