import colors from '@vars/colors';
import { themes } from '@vars/theme';
import {
  Color,
  Section,
  SectionBlock,
  SectionContent,
  SectionTitle,
  SitePageWrapper,
  SubSection,
  SwatchList,
  SwatchWrapper,
  Text,
} from '@components/pages/sitePage.styled';

export default function Site() {
  return (
    <SitePageWrapper>
      <Section>
        <SectionTitle>Colors:</SectionTitle>
        <SectionContent>
          <SwatchList>
            {Object.keys(colors).map((key) => (
              <Swatch
                color={colors[key]}
                title={
                  <>
                    {key} <br /> {colors[key]}
                  </>
                }
                key={key}
              ></Swatch>
            ))}
          </SwatchList>
        </SectionContent>
      </Section>
      <Section>
        <SectionTitle>Theme:</SectionTitle>
        <SectionContent>
          {Object.keys(themes).map((theme) => (
            <SubSection key={theme}>
              <h3>{theme}</h3>
              {Object.keys(themes[theme]).map((themeCategory) => (
                <SectionBlock key={themeCategory}>
                  <h4>{themeCategory}</h4>
                  <SwatchList>
                    {Object.keys(themes[theme][themeCategory]).map((themeElem) => (
                      <Swatch
                        color={themes[theme][themeCategory][themeElem]}
                        title={
                          <>
                            {themeElem} <br /> {themes[theme][themeCategory][themeElem]}
                          </>
                        }
                        key={themeElem}
                      ></Swatch>
                    ))}
                  </SwatchList>
                </SectionBlock>
              ))}
            </SubSection>
          ))}
        </SectionContent>
      </Section>
    </SitePageWrapper>
  );
}

function Swatch({ color, title }) {
  return (
    <SwatchWrapper>
      <Color color={color}></Color>
      <Text>{title}</Text>
    </SwatchWrapper>
  );
}
