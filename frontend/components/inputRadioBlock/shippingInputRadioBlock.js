import InputRadio from '@components/inputRadio/inputRadio';
import { InputRadioBlockComponent, Label, LabelsWrapper, RadioWrapper, Sublabel } from '@components/inputRadioBlock/inputRadioBlock.styled';
import { useEffect, useState } from 'react';
import { TabDeliveryCost, TabSubDeliveryCost } from '@components/pages/szallitasiAdatokPage.styled';
import currency from '@libs/currency';

export default function ShippingInputRadioBlock(props) {
  let { method, setChecked, selectedShippingOption, selectDefaultMethod, showOnePrice, fee, ...rest } = props;
  const [selected, setSelected] = useState(null);

  const handleCheck = (key) => {
    setChecked(key);
    setSelected(key);
  };

  useEffect(() => {
    if (selectedShippingOption === method.key) {
      if (method.methods) {
        handleCheck(method.key);
      } else {
        setChecked(selectedShippingOption);
      }
    }
  }, [selectedShippingOption]);

  useEffect(() => {
    if (selectDefaultMethod) {
      handleCheck(method.key);
    }
  }, [selectDefaultMethod]);

  return (
    <InputRadioBlockComponent {...rest}>
      {props.checked && method ? (
        <RadioWrapper>
          <InputRadio {...rest}></InputRadio>
        </RadioWrapper>
      ) : null}
      <div className="d-block" style={{ width: '100%' }}>
        <LabelsWrapper>
          {method.label && <Label>{method.label}</Label>}
          {method.sublabel && <Sublabel>{method.sublabel}</Sublabel>}
        </LabelsWrapper>
        {!selectDefaultMethod && (
          <>
            {!props.checked && method.label === 'Házhozszállítással' ? null : (
              <>
                {method.methods
                  ? method.methods.map((subMethod) => (
                      <div
                        key={subMethod.key}
                        className="d-flex justify-content-between align-items-center my-2 w-100"
                        onClick={() => handleCheck(subMethod.key)}
                      >
                        <LabelsWrapper>
                          <div className="d-flex gap-5 align-items-center">
                            <RadioWrapper>
                              <InputRadio checked={selected === subMethod.key}></InputRadio>
                            </RadioWrapper>
                            <LabelsWrapper>{subMethod.label}</LabelsWrapper>
                          </div>
                        </LabelsWrapper>
                        <TabSubDeliveryCost className="relative">{currency.format(subMethod.fee)}</TabSubDeliveryCost>
                      </div>
                    ))
                  : null}
              </>
            )}
          </>
        )}
        {showOnePrice && <TabDeliveryCost>{currency.format(fee)}</TabDeliveryCost>}
      </div>
    </InputRadioBlockComponent>
  );
}
