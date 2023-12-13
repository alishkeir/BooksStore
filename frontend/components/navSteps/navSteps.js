import React, { useState, useEffect } from 'react';
import Icon from '@components/icon/icon';
import _cloneDeep from 'lodash/cloneDeep';
import {
  CircleActive,
  CircleFailed,
  CircleFinished,
  CircleInactive,
  CircleWrapper,
  Label,
  Line,
  NavStepsComponent,
  Spot,
  Spots,
} from '@components/navSteps/navSteps.styled';

let defaultState = [
  {
    label: 'Számlázás',
    state: null, // finished, active
  },
  {
    label: 'Szállítás',
    state: null,
  },
  {
    label: 'Összesítés',
    state: null,
  },
  {
    label: 'Fizetés',
    state: null,
  },
];

export default function NavSteps({ activeSpot, failedSpot }) {
  let [spots, setSpots] = useState(defaultState);

  useEffect(() => {
    let spotsClone = _cloneDeep(spots);

    spotsClone.forEach((spot, spotIndex) => {
      if (spotIndex === failedSpot) {
        spot.state = 'failed';
      } else if (spotIndex < activeSpot) {
        spot.state = 'finished';
      } else if (spotIndex === activeSpot) {
        spot.state = 'active';
      } else {
        spot.state = null;
      }
    });

    setSpots(spotsClone);
  }, [activeSpot, failedSpot]);

  return (
    <NavStepsComponent>
      <Spots>
        {spots.map((spot, spotIndex) => {
          let backLineActive = spot.state ? true : false;
          let forwardLineActive = spot.state === 'finished' ? true : false;
          let isFirst = spotIndex === 0 ? true : false;
          let isLast = spotIndex === spots.length - 1 ? true : false;

          if (spot.state === 'finished') {
            return (
              <React.Fragment key={spotIndex}>
                <Spot>
                  <CircleWrapper backLineActive={backLineActive} forwardLineActive={forwardLineActive} isFirst={isFirst} isLast={isLast}>
                    <CircleFinished>
                      <Icon type="check" iconColor="white" iconWidth="19px" iconHeight="14px"></Icon>
                    </CircleFinished>
                  </CircleWrapper>
                  <Label finished>{spot.label}</Label>
                </Spot>
                {!isLast && <Line active></Line>}
              </React.Fragment>
            );
          } else if (spot.state === 'active') {
            return (
              <React.Fragment key={spotIndex}>
                <Spot key={spotIndex}>
                  <CircleWrapper backLineActive={backLineActive} forwardLineActive={forwardLineActive} isFirst={isFirst} isLast={isLast}>
                    <CircleActive>{spotIndex + 1}</CircleActive>
                  </CircleWrapper>
                  <Label active>{spot.label}</Label>
                </Spot>
                {!isLast && <Line></Line>}
              </React.Fragment>
            );
          } else if (spot.state === 'failed') {
            return (
              <React.Fragment key={spotIndex}>
                <Spot>
                  <CircleWrapper backLineActive={backLineActive} forwardLineActive={forwardLineActive} isFirst={isFirst} isLast={isLast}>
                    <CircleFailed>
                      <Icon type="ex-thin" iconColor="white" iconWidth="19px" iconHeight="19px"></Icon>
                    </CircleFailed>
                  </CircleWrapper>
                  <Label failed>{spot.label}</Label>
                </Spot>
                {!isLast && <Line></Line>}
              </React.Fragment>
            );
          } else {
            return (
              <React.Fragment key={spotIndex}>
                <Spot key={spotIndex}>
                  <CircleWrapper backLineActive={backLineActive} forwardLineActive={forwardLineActive} isFirst={isFirst} isLast={isLast}>
                    <CircleInactive>{spotIndex + 1}</CircleInactive>
                  </CircleWrapper>
                  <Label>{spot.label}</Label>
                </Spot>
                {!isLast && <Line></Line>}
              </React.Fragment>
            );
          }
        })}
      </Spots>
    </NavStepsComponent>
  );
}
