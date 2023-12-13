import { useCallback, useRef, useEffect } from 'react';
import Head from 'next/head';
import { CheckoutShopMapComponent, Map } from '@components/checkoutShopMap/checkoutShopMap.styled';
import { useState } from 'react';

import imageMarkerAlomgyarInactive from '@assets/images/markers/marker-alomgyar-inactive.png';
import imageMarkerAlomgyarActive from '@assets/images/markers/marker-alomgyar-active.png';

export default function CheckoutShopMap(props) {
  let { shoplist, selectedStore, onMarkerSelect = () => {} } = props;

  let mapRef = useRef();
  let markersRef = useRef([]);
  let [head, setHead] = useState(null);
  let [mapLoaded, setMapLoaded] = useState(false);
  let [markersAdded, setMarkersAdded] = useState(false);

  let shopMapCallbackRef = useCallback((node) => {
    window.checkoutShopMapInit = () => {
      mapRef.current = new window.google.maps.Map(node, {
        center: { lat: 47.49691, lng: 19.06912 },
        zoom: 14,
      });

      setMapLoaded(true);
    };

    setHead(
      <Head>
        <script
          async
          defer
          src={`https://maps.googleapis.com/maps/api/js?key=${process.env.NEXT_PUBLIC_GOOGLE_MAP_API_KEY}&callback=checkoutShopMapInit&libraries=places`}
        ></script>
      </Head>,
    );
  }, []);

  // Clearing map object
  useEffect(() => {
    return () => {
      delete window.google.maps;
    };
  }, []);

  useEffect(() => {
    if (!shoplist || !mapLoaded || markersAdded) return;

    if (shoplist?.body?.book_shops) {
      // Placing markers on map
      {
        shoplist.body.book_shops.filter((e) => e?.show_shipping).forEach((shop) => {
          let marker = new window.google.maps.Marker({
            position: { lat: parseFloat(shop.latitude), lng: parseFloat(shop.longitude) },
            icon: imageMarkerAlomgyarInactive.src,
          });

          marker.addListener('click', () => {
            markersRef.current.forEach((marker) => marker.setIcon(imageMarkerAlomgyarInactive.src));
            marker.setIcon(imageMarkerAlomgyarActive.src);
            onMarkerSelect(shop);
          });

          // Adding extra info to marker
          marker.shop = shop;

          // Saving markers for later usage
          markersRef.current.push(marker);

          // Pushing markers to map
          marker.setMap(mapRef.current);
        });
      }

      // Fitting bounds to markers
      {
        let bounds = new window.google.maps.LatLngBounds();

        markersRef.current.forEach((marker) => {
          bounds.extend(marker.getPosition());
        });

        mapRef.current.fitBounds(bounds);
      }

      setMarkersAdded(true);
    }
  }, [shoplist, mapLoaded]);

  // Display outside selected store
  useEffect(() => {
    if (!selectedStore || !markersAdded) return;

    markersRef.current.forEach((marker) => {
      if (marker.shop.id === selectedStore) {
        marker.setIcon(imageMarkerAlomgyarActive.src);
      } else {
        marker.setIcon(imageMarkerAlomgyarInactive.src);
      }
    });
  }, [selectedStore, markersAdded]);

  useEffect(() => {
    return () => {
      window.google.maps = null;
    };
  }, []);

  return (
    <CheckoutShopMapComponent>
      {head}
      <Map ref={shopMapCallbackRef}></Map>
    </CheckoutShopMapComponent>
  );
}
