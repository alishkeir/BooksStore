import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import dynamic from 'next/dynamic';
import {
  Indicator,
  Input,
  InputQuicksearchComponent,
  InputWrapper,
  ResultContent,
  ResultIcon,
  ResultLine,
  ResultLines,
  ResultSubtext,
  ResultText,
  ResultWrapper,
} from '@components/inputQuicksearch/inputQuicksearch.styled';

let LoaderIcon = dynamic(() => import('react-spinners/BeatLoader'));

export default function InputQuicksearch(props) {
  let { results, loading, input, minLength = 3, placeholder, setInput, onLocationSelect } = props;

  function getOpenState(input, results) {
    return input.length < minLength || results.length < 1 ? false : true;
  }

  let open = getOpenState(input, results);

  return (
    <InputQuicksearchComponent>
      <InputWrapper>
        {loading && (
          <Indicator>
            <LoaderIcon size="7" color={colors.mischka} />
          </Indicator>
        )}
        <Input type="text" placeholder={placeholder} value={input} onInput={(e) => setInput(e.target.value)} open={open}></Input>
      </InputWrapper>
      {open && (
        <ResultWrapper>
          <ResultLines>
            {results.map((result) => (
              <ResultLine key={result.place_id} onClick={() => onLocationSelect(result)}>
                <ResultIcon>
                  <Icon type="pin" inconWidth="16px" iconHeight="18px" iconColor={colors.monza}></Icon>
                </ResultIcon>
                <ResultContent>
                  <ResultText>{result.structured_formatting.main_text}</ResultText>
                  <ResultSubtext>{result.structured_formatting.secondary_text}</ResultSubtext>
                </ResultContent>
              </ResultLine>
            ))}
          </ResultLines>
        </ResultWrapper>
      )}
    </InputQuicksearchComponent>
  );
}
