{{-- Consulta opcional de la ruta en el mapa. No interfiere con el guardado del formulario. --}}
@include('mimaparbl._recursos')

<div class="card shadow-sm mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0"><i class="bi bi-map"></i> Consultar la ruta en el mapa</h5>
            <div class="d-flex gap-2">
                <button type="button" id="btnVerRutaForm" class="btn btn-sm btn-primary">
                    <i class="bi bi-signpost-2"></i> Trazar ruta
                </button>
                <button type="button" id="btnUsarDistancia" class="btn btn-sm btn-outline-secondary" hidden>
                    <i class="bi bi-check2-circle"></i> Usar datos calculados
                </button>
            </div>
        </div>

        <div id="mapaFormRutas" style="height: 380px; border-radius: .375rem; overflow: hidden;"></div>
        <div id="infoFormRutas" class="alert alert-info mt-3 mb-0" hidden></div>
    </div>
</div>

<script>
    MapaRBL.iniciar('mapaFormRutas');

    var infoForm = document.getElementById('infoFormRutas');
    var btnUsarDistancia = document.getElementById('btnUsarDistancia');
    var datosCalculados = null;

    function mostrarInfo(clase, mensaje) {
        infoForm.hidden = false;
        infoForm.className = 'alert ' + clase + ' mt-3 mb-0';
        infoForm.innerHTML = mensaje;
    }

    document.getElementById('btnVerRutaForm').addEventListener('click', function () {
        var origen = document.querySelector('input[name="origen"]').value.trim();
        var destino = document.querySelector('input[name="destino"]').value.trim();

        if (!origen || !destino) {
            mostrarInfo('alert-warning', 'Primero escribe el origen y el destino de la ruta.');
            return;
        }

        mostrarInfo('alert-info', '<span class="spinner-border spinner-border-sm"></span> Buscando la ruta...');
        btnUsarDistancia.hidden = true;

        MapaRBL.trazarRuta(origen, destino, function (resultado) {
            if (!resultado) {
                mostrarInfo('alert-warning', 'No se encontró alguna de las direcciones en el mapa. Revisa el origen y el destino.');
                return;
            }

            datosCalculados = resultado;
            btnUsarDistancia.hidden = false;

            mostrarInfo('alert-success',
                'Distancia por carretera: <strong>' + resultado.distanciaKm.toFixed(1) + ' km</strong> · ' +
                'Tiempo estimado: <strong>' + resultado.horas.toFixed(1) + ' h</strong>'
            );
        });
    });

    btnUsarDistancia.addEventListener('click', function () {
        if (!datosCalculados) {
            return;
        }

        document.querySelector('input[name="distancia_km"]').value = datosCalculados.distanciaKm.toFixed(2);
        document.querySelector('input[name="tiempo_estimado_horas"]').value = datosCalculados.horas.toFixed(2);

        mostrarInfo('alert-success', 'La distancia y el tiempo del formulario se actualizaron con los datos del mapa.');
    });

    // Si el formulario ya trae origen y destino (al editar), se muestra la ruta de una vez
    var origenInicial = document.querySelector('input[name="origen"]').value.trim();
    var destinoInicial = document.querySelector('input[name="destino"]').value.trim();

    if (origenInicial && destinoInicial) {
        document.getElementById('btnVerRutaForm').click();
    }
</script>
