let inquirer = require('inquirer');
let fs = require('fs');
let path = require('path');
let prettier = require('prettier');

inquirer
  .prompt([{ type: 'input', name: 'name', message: 'Name your Component' }])
  .then((answers) => {
    if (answers.name && answers.name.length > 0) {
      let componentDir = path.resolve(process.cwd(), 'components', answers.name);
      let componentFilePath = `${componentDir}/${answers.name}.js`;
      let componentStyledFilePath = `${componentDir}/${answers.name}.styled.js`;

      // If directory we create
      if (!fs.existsSync(componentDir)) {
        fs.mkdirSync(componentDir);
      }

      // Prettier
      prettier.resolveConfig(componentFilePath).then((options) => {
        fs.writeFileSync(componentFilePath, prettier.format(createComponentContent(answers.name), options));
        fs.writeFileSync(componentStyledFilePath, prettier.format(createComponentStyledContent(answers.name), options));
      });

      console.info('Your component is ready!');
    }
  })
  .catch((error) => {
    if (error.isTtyError) {
      console.log("Prompt couldn't be rendered in the current environment");
    } else {
      console.log('Something else went wrong', error);
    }
  });

function createComponentContent(componentName) {
  let UCComponentName = UCFirst(componentName);

  return `
        import { ${UCComponentName}Component } from '@components/${componentName}/${componentName}.styled';

        export default function ${UCComponentName}() {
            return <${UCComponentName}Component></${UCComponentName}Component>
        }
    `;
}

function createComponentStyledContent(componentName) {
  let UCComponentName = UCFirst(componentName);

  return `
        import styled from '@emotion/styled';

        export let ${UCComponentName}Component = styled.div\`\`;
    `;
}

function UCFirst(string) {
  return string[0].toUpperCase() + string.slice(1);
}
