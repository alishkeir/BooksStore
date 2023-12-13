import { useEffect, useCallback, useRef } from 'react';
import Head from 'next/head';
import { CheckoutBoxMapComponent, Map, MapWrapper, MarkerListWrapper, SearchWrapper } from '@components/checkoutBoxMap/checkoutBoxMap.styled';
import { useState } from 'react';
import InputQuicksearch from '@components/inputQuicksearch/inputQuicksearch';
import MapMarkerList from '@components/mapMarkerList/mapMarkerList';
import _debounce from 'lodash/debounce';
import { useMutation } from 'react-query';
import useRequest from '@hooks/useRequest/useRequest';
import { handleApiRequest, getResponseById } from '@libs/api';

import imageMarkerDPDInactive from '@assets/images/markers/marker-dpd-inactive.png';
import imageMarkerDPDActive from '@assets/images/markers/marker-dpd-active.png';
import imageMarkerPostaInactive from '@assets/images/markers/marker-posta-inactive.png';
import imageMarkerPostaActive from '@assets/images/markers/marker-posta-active.png';
import imageMarkerPickpackInactive from '@assets/images/markers/marker-pickpack-inactive.png';
import imageMarkerPickpackActive from '@assets/images/markers/marker-pickpack-active.png';
import imageMarkerGLSInactive from '@assets/images/markers/marker-gls-inactive.png';
import imageMarkerGLSActive from '@assets/images/markers/marker-gls-active.png';
import imageMarkerAlomgyarInactive from '@assets/images/markers/marker-alomgyar-inactive.png';
import imageMarkerAlomgyarActive from '@assets/images/markers/marker-alomgyar-active.png';
import imageMarkerFoxpostInactive from '@assets/images/markers/marker-foxpost-inactive.png';
import imageMarkerFoxpostActive from '@assets/images/markers/marker-foxpost-active.png';
import imageMarkerPacketaActive from '@assets/images/markers/marker-packeta-active.png';
import imageMarkerPacketaInactive from '@assets/images/markers/marker-packeta-inactive.png';
let providerImages = {
  dpd: {
    inactive: imageMarkerDPDInactive.src,
    active: imageMarkerDPDActive.src,
  },
  posta: {
    inactive: imageMarkerPostaInactive.src,
    active: imageMarkerPostaActive.src,
  },
  pick_pack_point: {
    inactive: imageMarkerPickpackInactive.src,
    active: imageMarkerPickpackActive.src,
  },
  gls: {
    inactive: imageMarkerGLSInactive.src,
    active: imageMarkerGLSActive.src,
  },
  alomgyar: {
    inactive: imageMarkerAlomgyarInactive.src,
    active: imageMarkerAlomgyarActive.src,
  },
  fox_post: {
    inactive: imageMarkerFoxpostInactive.src,
    active: imageMarkerFoxpostActive.src,

  },
  packeta: {
    inactive: imageMarkerPacketaInactive.src,
    active: imageMarkerPacketaActive.src,
  },
};

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'checkout-get-pickup-points': {
      method: 'GET',
      path: '/helpers',
      ref: 'pick_up_points',
      request_id: 'checkout-get-pickup-points',
      body: {},
    },
  },
};

export default function CheckoutBoxMap(props) {
  let { selectedBox, onBoxSelect } = props;

  let minSearchInputLength = useRef(2);
  let mapRef = useRef();
  let markersRef = useRef([]);
  let selectedMarkerRef = useRef();
  let searchPositionMarkerRef = useRef();

  let [head, setHead] = useState(null);
  let [mapLoaded, setMapLoaded] = useState(false);
  let [markersLoaded, setMarkersLoaded] = useState(false);
  let [visibleMarkers, setVisibleMarkers] = useState([]);
  let [inputSearch, setInputSearch] = useState('');
  let [locationSearchResults, setLocationSearchResults] = useState([]);

  let authCheckTokenQuery = useMutation((requestUpdateBuild) => handleApiRequest(requestUpdateBuild));
  let authCheckTokenRequest = useRequest(requestTemplates, authCheckTokenQuery);

  let getVisibleMarkers = _debounce(
    () => {
      let bounds = mapRef.current.getBounds();
      let visibleMarkers = [];

      let activeMarkers = markersRef.current.filter((marker) => marker.visible);

      for (let marker of activeMarkers) {
        if (bounds.contains(marker.getPosition())) {
          visibleMarkers.push(marker);

          // Max 10 visible markers
          if (visibleMarkers.length >= 10) break;
        }
      }

      if (visibleMarkers.length > 0) setVisibleMarkers(visibleMarkers);
    },
    300,
    { leading: false, trailing: true },
  );

  let boxMapCallbackRef = useCallback((node) => {
    window.checkoutBoxMapInit = () => {
      mapRef.current = new window.google.maps.Map(node, {
        center: { lat: 47.49691, lng: 19.06912 },
        zoom: 13,
      });

      mapRef.current.addListener('bounds_changed', () => {
        getVisibleMarkers();
      });

      setMapLoaded(true);
    };

    setHead(
      <Head>
        <script
          async
          defer
          src={`https://maps.googleapis.com/maps/api/js?key=${process.env.NEXT_PUBLIC_GOOGLE_MAP_API_KEY}&callback=checkoutBoxMapInit&libraries=places&language=hu`}
        ></script>
      </Head>,
    );
  }, []);

  // Selecting a location from autocomplete
  let handleLocationSelect = useCallback((location) => {
    let placesService = new window.google.maps.places.PlacesService(mapRef.current);
    let placeDetailsRequest = { placeId: location.place_id, fields: ['geometry.location'] };

    placesService.getDetails(placeDetailsRequest, (result, status) => {
      if (status === window.google.maps.places.PlacesServiceStatus.OK && result) {
        // Jumping to location on map
        mapRef.current.setCenter(result.geometry.location);

        // If marker exist, we remove it
        if (searchPositionMarkerRef.current) searchPositionMarkerRef.current.setMap(null);

        // Creating a default marker
        let marker = new window.google.maps.Marker({
          position: result.geometry.location,
        });

        // Placing a marker
        marker.setMap(mapRef.current);

        // Saving pointer marker
        searchPositionMarkerRef.current = marker;

        setInputSearch(location.structured_formatting.main_text);
        setLocationSearchResults([]);
      }
    });
  }, []);

  // Map autocomplete input
  let handleLocationInput = useCallback(
    (input) => {
      if (!mapLoaded) return;

      setInputSearch(input);

      if (!input || input.length < minSearchInputLength.current) return;

      let parliamentLatLng = new window.google.maps.LatLng({ lat: 47.507101, lng: 19.045645 });
      let autocompleteService = new window.google.maps.places.AutocompleteService();

      let displaySuggestions = function (predictions, status) {
        if (status === window.google.maps.places.PlacesServiceStatus.OK && predictions) {
          setLocationSearchResults(predictions);
        }
      };

      autocompleteService.getPlacePredictions({ input: input, location: parliamentLatLng, radius: 300000 }, displaySuggestions);
    },
    [mapLoaded],
  );

  // Clearing map object
  useEffect(() => {
    return () => {
      delete window.google.maps;
    };
  }, []);

  // Highlighting selected marker
  useEffect(() => {
    if (!selectedBox) return;

    for (let marker of markersRef.current) {
      if (marker === selectedMarkerRef.current) {
        marker.setIcon(providerImages[marker.box.provider].inactive);
      }
    }

    for (let marker of markersRef.current) {
      if (marker === selectedBox) {
        marker.setIcon(providerImages[marker.box.provider].active);
        selectedMarkerRef.current = marker;
      }
    }
  }, [selectedBox]);

  // Handling visible markers
  useEffect(() => {
    if (!markersLoaded) return;
    getVisibleMarkers();
  }, [markersLoaded]);

  // Creating markers
  useEffect(() => {
    if (!mapLoaded) return;

    authCheckTokenRequest.addRequest('checkout-get-pickup-points');

    authCheckTokenRequest.commit({
      onSettled: (data) => {
        let authGetCartResponse = getResponseById(data, 'checkout-get-pickup-points');

        if (authGetCartResponse?.success) {
          authGetCartResponse.body.options.forEach((item) => {
            createMarker(item);
          });
          setMarkersLoaded(true);
        } else {
          // Login error
          setMarkersLoaded(true);
        }
      },
    });
  }, [mapLoaded]);

  return (
    <CheckoutBoxMapComponent>
      {head}
      <SearchWrapper>
        <InputQuicksearch
          minLenght={minSearchInputLength.current}
          placeholder="pl. város vagy utcanév"
          results={locationSearchResults}
          input={inputSearch}
          setInput={handleLocationInput}
          onLocationSelect={handleLocationSelect}
        ></InputQuicksearch>
      </SearchWrapper>
      <MapWrapper>
        <Map ref={boxMapCallbackRef}></Map>
      </MapWrapper>
      <MarkerListWrapper>
        <MapMarkerList markers={visibleMarkers} images={providerImages} {...props}></MapMarkerList>
      </MarkerListWrapper>
    </CheckoutBoxMapComponent>
  );

  function createMarker(item) {
    var marker = new window.google.maps.Marker({
      position: { lat: item.lat, lng: item.lng },
      icon: providerImages[item.provider].inactive,
    });

    // Adding extra info to marker
    marker.box = item;

    marker.addListener('click', () => {
      onBoxSelect(marker);
    });

    // Saving markers for later usage
    markersRef.current.push(marker);

    // Pushing markers to map
    marker.setMap(mapRef.current);
  }
}
