<template>
    <gmap-map
            :center="center"
            :zoom="18"
            style="width: 100%; height: 300px;border-radius: 15px 50px 30px 5px;"
            :options="opciones">
        <gmap-marker
                :key="index"
                v-for="(m, index) in markers"
                :position="m.position"
                :clickable="true"
                :draggable="true"
                @click="center=m.position"
        />
    </gmap-map>
</template>
<script>
    import * as VueGoogleMaps from 'vue2-google-maps';
    Vue.use(VueGoogleMaps, {
        load: {
            key: 'AIzaSyAXphG-N8S7KAOCbfRGPeogIq07yYZCazo'
        }
    });
    export default {
        name: "direccion-mapa",
        data:()=>({
            center: {lat:-1.4871999480391953, lng:-77.99808485804078},
            markers: [{
                position: {lat:-1.4871999480391953, lng:-77.99808485804078,title: "ASECONT - PUYO"}
            }],
            opciones: {
                scrollwheel: true,
                draggable: false,
                styles:[
                    {
                        "featureType": "administrative",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#444444"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [
                            {
                                "color": "#f2f2f2"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [
                            {
                                "saturation": -100
                            },
                            {
                                "lightness": 45
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "labels.icon",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [
                            {
                                "color": "#aad2e3"
                            },
                            {
                                "visibility": "on"
                            }
                        ]
                    }
                ]
            }
        }),
    }
</script>
<style scoped>

</style>