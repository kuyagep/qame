@extends('layouts.main')

@section('title', 'School Map Location')
@section('content-header', 'School Map Location')

@push('styles')
    <style>
        #school-map {
            height: 75vh;
            width: 100%;
            border-radius: 4px;
            position: relative;
        }

        /* Search Box Control */
        #pac-input {
            background-color: #fff;
            font-family: Roboto, sans-serif;
            font-size: 14px;
            font-weight: 300;
            margin-top: 10px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 280px;
            height: 38px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            outline: none;
        }

        /* Screenshot Mask Overlay */
        #screenshotOverlay {
            position: absolute;
            border: 2px dashed #001f3f;
            background-color: rgba(0, 31, 63, 0.2);
            z-index: 1000;
            display: none;
            pointer-events: none;
        }

        .map-custom-btn {
            background: #fff;
            border: 2px solid #fff;
            border-radius: 3px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .3);
            color: rgb(25, 25, 25);
            cursor: pointer;
            font-family: Roboto, Arial, sans-serif;
            font-size: 14px;
            line-height: 38px;
            margin-top: 10px;
            margin-right: 10px;
            padding: 0 10px;
            text-align: center;
        }

        .map-custom-btn:hover {
            background-color: #f1f1f1;
        }
    </style>
@endpush

@section('content')
    <div class="card card-outline card-navy">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title">Campus & Office Locations</h3>
        </div>
        <div class="card-body p-2 position-relative">
            <!-- Search / Find Address Input -->
            <input id="pac-input" class="controls" type="text" placeholder="Search address or location..." />

            <!-- Map Container -->
            <div id="school-map"></div>

            <!-- Screenshot Selection Overlay -->
            <div id="screenshotOverlay"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- html2canvas Library for Area Screenshot Capture -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- Google Maps JS API with Places Library -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRHwDQ0n4r95ZFYwvKSCfwG7qz-Kl327E&libraries=places&callback=initMap"
        async defer></script>

    <script>
        let map;
        let bounds;

        function initMap() {
            const offices = @json($offices);

            // 1. Initial Center (Davao del Sur default)
            const initialCenter = {
                lat: 6.7450,
                lng: 125.3169
            };

            // 2. Map Configuration (Satellite/Hybrid/Street Type Selector, Fullscreen, Zoom)
            map = new google.maps.Map(document.getElementById("school-map"), {
                zoom: 12,
                center: initialCenter,
                mapTypeId: google.maps.MapTypeId.HYBRID, // Options: SATELLITE, HYBRID, ROADMAP, TERRAIN
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.TOP_RIGHT,
                },
                fullscreenControl: true,
                zoomControl: true,
                streetViewControl: false,
            });

            bounds = new google.maps.LatLngBounds();
            const infoWindow = new google.maps.InfoWindow();

            // 3. Location Point Pin Icon Styling
            const pinSymbol = {
                url: "https://maps.google.com/mapfiles/ms/icons/red-pushpin.png", // Red Location Pin
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(10, 32) // Anchors tip of pin
            };

            // 4. Plot Database Offices with Markers & Popups
            offices.forEach(office => {
                if (office.latitude && office.longitude) {
                    const lat = parseFloat(office.latitude);
                    const lng = parseFloat(office.longitude);
                    const pos = {
                        lat: lat,
                        lng: lng
                    };

                    const deptName = office.department ? office.department.name : 'N/A';
                    const codeLabel = office.code ? `(${office.code})` : '';

                    const marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        title: `${office.name} ${codeLabel}`,
                        icon: pinSymbol
                    });

                    // Marker Info Window Popup
                    marker.addListener("click", () => {
                        infoWindow.setContent(`
                            <div style="font-family: Arial; padding: 4px;">
                                <h6 style="margin: 0 0 4px 0; font-weight: bold; color: #001f3f;">
                                    ${office.name} ${codeLabel}
                                </h6>
                                <span style="font-size: 13px; color: #555;">
                                    <b>Department:</b> ${deptName}
                                </span>
                            </div>
                        `);
                        infoWindow.open(map, marker);
                    });

                    bounds.extend(pos);
                }
            });

            // Auto-fit camera view to encompass all markers
            if (offices.length > 0) {
                map.fitBounds(bounds);
            }

            // ==========================================
            // SEARCH / FIND ADDRESS (Google Places)
            // ==========================================
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();
                if (places.length == 0) return;

                const searchBounds = new google.maps.LatLngBounds();
                places.forEach(place => {
                    if (!place.geometry || !place.geometry.location) return;
                    if (place.geometry.viewport) {
                        searchBounds.union(place.geometry.viewport);
                    } else {
                        searchBounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(searchBounds);
            });

            // ==========================================
            // NAVIGATION BUTTONS (Home & Locate Me)
            // ==========================================

            // Home Button
            const homeBtn = document.createElement("button");
            homeBtn.className = "map-custom-btn";
            homeBtn.innerHTML = "🏠 Home";
            homeBtn.title = "Reset View to Offices";
            homeBtn.onclick = () => {
                if (offices.length > 0) map.fitBounds(bounds);
                else map.setCenter(initialCenter);
            };
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeBtn);

            // Locate Me Button
            const locateBtn = document.createElement("button");
            locateBtn.className = "map-custom-btn";
            locateBtn.innerHTML = "🎯 Locate Me";
            locateBtn.title = "Center on GPS location";
            locateBtn.onclick = () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        const userPos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        map.setCenter(userPos);
                        map.setZoom(16);
                        new google.maps.Marker({
                            position: userPos,
                            map: map,
                            title: "Your Location"
                        });
                    });
                }
            };
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(locateBtn);

            // ==========================================
            // AREA SCREENSHOT CAPTURE TOOL
            // ==========================================
            const screenshotBtn = document.createElement("button");
            screenshotBtn.className = "map-custom-btn";
            screenshotBtn.innerHTML = "📷 Capture Area";
            screenshotBtn.title = "Drag to select map area to download";

            let isSelecting = false;
            let startX, startY;
            const overlay = document.getElementById("screenshotOverlay");

            screenshotBtn.onclick = () => {
                alert("Click and drag across the map to select an area for screenshot.");
                const mapDiv = document.getElementById("school-map");

                const onMouseDown = (e) => {
                    isSelecting = true;
                    const rect = mapDiv.getBoundingClientRect();
                    startX = e.clientX - rect.left;
                    startY = e.clientY - rect.top;

                    overlay.style.left = startX + "px";
                    overlay.style.top = startY + "px";
                    overlay.style.width = "0px";
                    overlay.style.height = "0px";
                    overlay.style.display = "block";
                };

                const onMouseMove = (e) => {
                    if (!isSelecting) return;
                    const rect = mapDiv.getBoundingClientRect();
                    const currentX = e.clientX - rect.left;
                    const currentY = e.clientY - rect.top;

                    const width = Math.abs(currentX - startX);
                    const height = Math.abs(currentY - startY);

                    overlay.style.width = width + "px";
                    overlay.style.height = height + "px";
                    overlay.style.left = Math.min(startX, currentX) + "px";
                    overlay.style.top = Math.min(startY, currentY) + "px";
                };

                const onMouseUp = () => {
                    if (!isSelecting) return;
                    isSelecting = false;

                    // Capture selected screen region using html2canvas
                    html2canvas(mapDiv, {
                        useCORS: true
                    }).then(canvas => {
                        const cropCanvas = document.createElement("canvas");
                        const ctx = cropCanvas.getContext("2d");

                        const cropX = parseInt(overlay.style.left);
                        const cropY = parseInt(overlay.style.top);
                        const cropW = parseInt(overlay.style.width);
                        const cropH = parseInt(overlay.style.height);

                        if (cropW > 10 && cropH > 10) {
                            cropCanvas.width = cropW;
                            cropCanvas.height = cropH;
                            ctx.drawImage(canvas, cropX, cropY, cropW, cropH, 0, 0, cropW, cropH);

                            const link = document.createElement("a");
                            link.download = "map-screenshot.png";
                            link.href = cropCanvas.toDataURL("image/png");
                            link.click();
                        }

                        overlay.style.display = "none";
                        mapDiv.removeEventListener("mousedown", onMouseDown);
                        mapDiv.removeEventListener("mousemove", onMouseMove);
                        mapDiv.removeEventListener("mouseup", onMouseUp);
                    });
                };

                mapDiv.addEventListener("mousedown", onMouseDown);
                mapDiv.addEventListener("mousemove", onMouseMove);
                mapDiv.addEventListener("mouseup", onMouseUp);
            };

            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(screenshotBtn);
        }
    </script>
@endpush
