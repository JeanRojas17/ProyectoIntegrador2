// Funciones del mapa para Transportes RBL.
// Utiliza Leaflet (mapa), Nominatim (busqueda de direcciones) y OSRM (rutas por carretera).
// Son servicios publicos de OpenStreetMap: no requieren API key.

var MapaRBL = {

    mapa: null,
    capaRuta: null,
    marcadores: [],

    // Prepara el mapa dentro del contenedor indicado
    iniciar: function (idContenedor) {
        this.mapa = L.map(idContenedor).setView([4.7110, -74.0721], 6);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(this.mapa);

        return this.mapa;
    },

    // Convierte una direccion escrita en coordenadas
    buscarDireccion: function (texto) {
        var consulta = 'https://nominatim.openstreetmap.org/search'
            + '?format=jsonv2&limit=1&countrycodes=co&accept-language=es'
            + '&q=' + encodeURIComponent(texto);

        return fetch(consulta)
            .then(function (respuesta) { return respuesta.json(); })
            .then(function (resultados) {
                if (!resultados.length) {
                    return null;
                }

                return {
                    lat: parseFloat(resultados[0].lat),
                    lon: parseFloat(resultados[0].lon),
                    nombre: resultados[0].display_name
                };
            });
    },

    // Traza la ruta por carretera entre las dos direcciones y la dibuja en el mapa
    trazarRuta: function (textoOrigen, textoDestino, alTerminar) {
        var self = this;

        Promise.all([this.buscarDireccion(textoOrigen), this.buscarDireccion(textoDestino)])
            .then(function (puntos) {
                var origen = puntos[0];
                var destino = puntos[1];

                if (!origen || !destino) {
                    return null;
                }

                var consulta = 'https://router.project-osrm.org/route/v1/driving/'
                    + origen.lon + ',' + origen.lat + ';'
                    + destino.lon + ',' + destino.lat
                    + '?overview=full&geometries=geojson';

                return fetch(consulta)
                    .then(function (respuesta) { return respuesta.json(); })
                    .then(function (datos) {
                        var ruta = datos.routes[0];

                        self.limpiarRuta();

                        // OSRM entrega las coordenadas como [lon, lat] y Leaflet usa [lat, lon]
                        var linea = ruta.geometry.coordinates.map(function (c) {
                            return [c[1], c[0]];
                        });

                        self.capaRuta = L.polyline(linea, { color: '#0d6efd', weight: 5 }).addTo(self.mapa);

                        self.marcadores.push(
                            L.marker([origen.lat, origen.lon]).addTo(self.mapa).bindPopup('Origen: ' + origen.nombre)
                        );
                        self.marcadores.push(
                            L.marker([destino.lat, destino.lon]).addTo(self.mapa).bindPopup('Destino: ' + destino.nombre)
                        );

                        self.mapa.fitBounds(self.capaRuta.getBounds(), { padding: [30, 30] });

                        return {
                            distanciaKm: ruta.distance / 1000,
                            horas: ruta.duration / 3600,
                            nombreOrigen: origen.nombre,
                            nombreDestino: destino.nombre
                        };
                    });
            })
            .then(function (resultado) {
                if (alTerminar) {
                    alTerminar(resultado || null);
                }
            })
            .catch(function () {
                if (alTerminar) {
                    alTerminar(null);
                }
            });
    },

    // Borra la ruta y los marcadores dibujados
    limpiarRuta: function () {
        if (this.capaRuta) {
            this.mapa.removeLayer(this.capaRuta);
            this.capaRuta = null;
        }

        for (var i = 0; i < this.marcadores.length; i++) {
            this.mapa.removeLayer(this.marcadores[i]);
        }
        this.marcadores = [];
    }
};
