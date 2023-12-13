import styled from '@emotion/styled';
import breakpoints from '@vars/breakpoints';

export let ProfileAddressItemComponent = styled.div`
  display: flex;
`;

export let Address = styled.div`
  font-size: 14px;
  line-height: 26px;
  flex: 1;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 12px;
    line-height: 20px;
  }
`;

export let Title = styled.div`
  font-weight: 700;
  font-size: 20px;
  line-height: 28px;
  margin-bottom: 8px;

  @media (max-width: ${breakpoints.max.lg}) {
    font-size: 18px;
  }
`;

export let BusinessName = styled.div``;

export let Name = styled.div``;

export let Street = styled.div``;

export let CityZip = styled.div``;

export let Country = styled.div``;

export let Note = styled.div``;

export let TaxNumber = styled.div``;

export let Actions = styled.div``;

export let Icons = styled.div`
  display: flex;
`;

export let IconWrapper = styled.div`
  padding: 5px;
  margin-left: 30px;
  cursor: pointer;

  @media (max-width: ${breakpoints.max.lg}) {
    margin-left: 15px;
  }
`;
