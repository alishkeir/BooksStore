import { Scrollbars } from 'react-custom-scrollbars-2';
import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import { IconWrapper, ImageWrapper, ListItem, Location, MapMarkerListComponent, Meta, Name } from '@components/mapMarkerList/mapMarkerList.styled';
import Image from "next/image";

export default function MapMarkerList({ markers, selectedBox, images, onBoxSelect }) {
  return (
    <MapMarkerListComponent>
      <Scrollbars autoHeight autoHeightMax={320} className="scrollbar">
        {markers.map((marker) => (
          <ListItem key={marker.box.provider_id} onClick={() => onBoxSelect(marker)} selected={marker === selectedBox}>
            <ImageWrapper>
              <Image loading="lazy" src={images[marker.box.provider].inactive} width="35" height="41" alt=""></Image>
            </ImageWrapper>
            <Meta>
              <Name>
                {marker.box.provider_name} - {marker.box.name}
              </Name>
              <Location>
                {marker.box.zip} {marker.box.city}, {marker.box.address}
              </Location>
            </Meta>
            {marker === selectedBox && (
              <IconWrapper>
                <Icon type="check" iconWidth="19px" iconHeight="14px" iconColor={colors.malachite}></Icon>
              </IconWrapper>
            )}
          </ListItem>
        ))}
      </Scrollbars>
    </MapMarkerListComponent>
  );
}
