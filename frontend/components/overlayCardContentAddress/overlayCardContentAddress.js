import { useState, useEffect, useMemo } from 'react';
import InputText from '@components/inputText/inputText';
import InputTextarea from '@components/inputTextarea/inputTextarea';
import InputRadioBlock from '@components/inputRadioBlock/inputRadioBlock';
import Button from '@components/button/button';
import AlertBox from '@components/alertBox/alertBox';
import Dropdown from '@components/dropdown/dropdown';
import { nullInObjectToEmpty } from '@libs/helpers';
import currency from '@libs/currency';
import {
  Bottom,
  ButtonWrapper,
  Content,
  Form,
  InputCol,
  InputWrapper,
  OverlayCardContentAddressComponent,
  Question,
  TabWrapper,
  Tabs,
  Title,
  Top,
} from '@components/overlayCardContentAddress/overlayCardContentAddress.styled';

export function FormContent(props) {
  let { type, address, addressType, onAddressTypeClick, countries = [], useInput } = props;
  let { inputs, setInput, setInputs, errors } = useInput;

  let dropdownOptions = useMemo(
    () =>
      countries.map((country) => {
        return {
          value: country.id,
          label: `${country.name}${(type === 'shipping' && country.name !== 'Magyarország') ? ` - ${currency.format(country.fee)}` : ''}`,
          selected: country.selected ? true : address?.country.id === country.id ? true : false,
        };
      }),
    [address, countries],
  );

  useEffect(() => {
    if (!address) return;

    let nonullAddress = nullInObjectToEmpty(address);
    nonullAddress.country_id = nonullAddress.country.id;
    delete nonullAddress.country;

    setInputs(nonullAddress);
  }, []);

  return (
    <>
      {type !== 'shipping' && (
        <Tabs>
          <TabWrapper>
            <InputRadioBlock
              label="Magánszemély"
              name="person-type"
              checked={addressType === 'private'}
              onClick={() => onAddressTypeClick('private')}
            ></InputRadioBlock>
          </TabWrapper>
          <TabWrapper>
            <InputRadioBlock
              label={
                <>
                  Szervezet <span style={{ fontWeight: 'normal' }}>(cég, könyvtár, egyesület)</span>
                </>
              }
              name="person-type"
              checked={addressType === 'business'}
              onClick={() => onAddressTypeClick('business')}
            ></InputRadioBlock>
          </TabWrapper>
        </Tabs>
      )}
      {addressType === 'private' && (
        <Form>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputText
                label="Vezetéknév"
                name="input-card-content-address-last-name"
                value={inputs.last_name}
                error={errors.last_name}
                onChange={(e) => setInput('last_name', e.target.value)}
              ></InputText>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputText
                label="Keresztnév"
                name="input-card-content-address-first-name"
                value={inputs.first_name}
                error={errors.first_name}
                onChange={(e) => setInput('first_name', e.target.value)}
              ></InputText>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputText
                label="Település"
                name="input-card-content-address-city"
                value={inputs.city}
                error={errors.city}
                onChange={(e) => setInput('city', e.target.value)}
              ></InputText>
            </InputCol>
            <InputCol className="col">
              <InputText
                label="Irányítószám"
                name="input-card-content-address-zip"
                value={inputs.zip_code}
                error={errors.zip_code}
                onChange={(e) => setInput('zip_code', e.target.value)}
              ></InputText>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputText
                label="Cím"
                name="input-card-content-address-address"
                value={inputs.address}
                error={errors.address}
                onChange={(e) => setInput('address', e.target.value)}
              ></InputText>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <Dropdown
                width="100%"
                label="Ország"
                error={errors.country_id}
                placeholder="Válassz"
                options={dropdownOptions}
                height="50px"
                onSelect={(e) => setInput('country_id', e.value)}
              ></Dropdown>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputTextarea
                label="Megjegyzés (emelet, ajtó, épület, lépcsőház, csengő)"
                name="input-card-content-address-comment"
                value={inputs.comment}
                error={errors.comment}
                onChange={(e) => setInput('comment', e.target.value)}
              ></InputTextarea>
            </InputCol>
          </InputWrapper>
        </Form>
      )}
      {addressType === 'business' && (
        <Form>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputText
                label="Vezetéknév"
                name="input-card-content-address-last-name"
                value={inputs.last_name}
                error={errors.last_name}
                onChange={(e) => setInput('last_name', e.target.value)}
              ></InputText>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputText
                label="Keresztnév"
                name="input-card-content-address-first-name"
                value={inputs.first_name}
                error={errors.first_name}
                onChange={(e) => setInput('first_name', e.target.value)}
              ></InputText>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputText
                label="Cégnév"
                name="input-card-content-address-business"
                value={inputs.business_name}
                error={errors.business_name}
                onChange={(e) => setInput('business_name', e.target.value)}
              ></InputText>
            </InputCol>
          </InputWrapper>
          {type !== 'shipping' && (
            <InputWrapper className="row">
              <InputCol className="col">
                <InputText
                  label="Adószám"
                  name="input-card-content-address-vat"
                  value={inputs.vat_number}
                  error={errors.vat_number}
                  onChange={(e) => setInput('vat_number', e.target.value)}
                ></InputText>
              </InputCol>
            </InputWrapper>
          )}
          <InputWrapper className="row">
            <InputCol className="col">
              <InputText
                label="Település"
                name="input-card-content-address-city"
                value={inputs.city}
                error={errors.city}
                onChange={(e) => setInput('city', e.target.value)}
              ></InputText>
            </InputCol>
            <InputCol className="col">
              <InputText
                label="Irányítószám"
                name="input-card-content-address-zip"
                value={inputs.zip_code}
                error={errors.zip_code}
                onChange={(e) => setInput('zip_code', e.target.value)}
              ></InputText>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputText
                label="Cím"
                name="input-card-content-address-address"
                value={inputs.address}
                error={errors.address}
                onChange={(e) => setInput('address', e.target.value)}
              ></InputText>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <Dropdown
                width="100%"
                label="Ország"
                error={errors.country_id}
                placeholder="Válassz"
                options={dropdownOptions}
                height="50px"
                onSelect={(e) => setInput('country_id', e.value)}
              ></Dropdown>
            </InputCol>
          </InputWrapper>
          <InputWrapper className="row">
            <InputCol className="col">
              <InputTextarea
                label="Megjegyzés (emelet, ajtó, épület, lépcsőház, csengő)"
                name="input-card-content-address-comment"
                value={inputs.comment}
                error={errors.comment}
                onChange={(e) => setInput('comment', e.target.value)}
              ></InputTextarea>
            </InputCol>
          </InputWrapper>
        </Form>
      )}
    </>
  );
}

export default function OverlayCardContentAddress(props) {
  let { type, display, address, title, question, responseErrors, onSubmit = () => {}, onCancel = () => {} } = props;
  let [addressType, setAddressType] = useState(type === 'shipping' ? 'business' : address ? address.entity_type : 'private');

  return (
    <OverlayCardContentAddressComponent display={display}>
      <Top>
        <Title>{title}</Title>
      </Top>
      <Content>
        <Question>{question}</Question>
        <FormContent {...props} addressType={addressType} onAddressTypeClick={setAddressType} />
      </Content>
      <Bottom>
        {responseErrors && <AlertBox responseErrors={responseErrors}></AlertBox>}
        <ButtonWrapper>
          <Button type="primary" buttonWidth="100%" buttonHeight="50px" onClick={() => onSubmit(addressType, address ? address.id : null)}>
            Mentés
          </Button>
        </ButtonWrapper>
        <ButtonWrapper>
          <Button type="secondary" buttonWidth="100%" buttonHeight="50px" onClick={onCancel}>
            Mégse
          </Button>
        </ButtonWrapper>
      </Bottom>
    </OverlayCardContentAddressComponent>
  );
}
