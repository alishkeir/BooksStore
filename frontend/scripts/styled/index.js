let chokidar = require('chokidar');
let path = require('path');
let fs = require('fs');
let prettier = require('prettier');

let basePath = path.resolve(__dirname, '../../');

let watcher = chokidar.watch([path.resolve(basePath, 'pages'), path.resolve(basePath, 'components')], {
  ignored: /.*\.styled\..*/,
  persistent: true,
});

watcher.on('change', (sourceFilePath) => {
  let sourceFileName = path.basename(sourceFilePath);
  let sourceFileDirName = sourceFilePath.replace(sourceFileName, '');
  let sourceFileContent = fs.readFileSync(sourceFilePath, { encoding: 'utf-8' });

  // Finding styled import statement in source file
  let styledFileImportPath = getStyledImportPath(getSourceStyledImport(sourceFileContent));
  let styledFilePath = getFullImportPath(styledFileImportPath, basePath, sourceFileDirName);

  // Is this a styled or a component file
  if (fs.existsSync(styledFilePath)) {
    // We get all the tags in source file
    let sourceFileTags = getTagsFromFile(sourceFileContent, /<([A-Z]+[a-zA-Z]*).*>/g);

    // We get all the imported stuff in source
    // so we dont put those in style
    let sourceFileImportedTags = getTagsFromFile(sourceFileContent, /import (([A-Z][a-zA-Z]+,*\s*)+).* from/g);

    // We filter the tags which dont have any import
    let sourceFileOrphanTags = removeTags(sourceFileImportedTags, sourceFileTags);

    // If there is styled file for component
    let styledFileContent = fs.readFileSync(styledFilePath, { encoding: 'utf8' });
    let styledFileTags = getTagsFromFile(styledFileContent, /export let ([a-zA-Z1-9]+)/g);
    let styledFileMissingTags = getMissingTags(sourceFileOrphanTags, styledFileTags);

    // Creating content to be appended to styled
    if (styledFileMissingTags.length > 0) {
      let styledContentToAppend = '';

      // Adding all the tag exports
      for (let missingStyledTag of styledFileMissingTags) {
        styledContentToAppend += `\nexport let ${missingStyledTag} = styled.div\`\`;\n`;
      }

      // Appending the new content to the styled file
      fs.appendFileSync(styledFilePath, styledContentToAppend);

      // We import relevant stuff into source file
      let sourceNewImportString = createImportStatement(
        getCommonTags(sourceFileTags, styledFileTags).concat(styledFileMissingTags).sort(),
        styledFileImportPath,
      );

      // Replacing old import with new

      // Prettier
      prettier.resolveConfig(sourceFilePath).then((options) => {
        let sourceContentToReplace = sourceFileContent.replace(getSourceStyledImport(sourceFileContent), sourceNewImportString);

        // Writing source file
        setTimeout(() => {
          fs.writeFileSync(sourceFilePath, prettier.format(sourceContentToReplace, options));
        }, 1000);
      });
    }
  }
});

// Gets the styled import statement from source file
function getSourceStyledImport(sourceFileContent) {
  if (!sourceFileContent) return;

  let sourceStyledImportStringRegex = new RegExp(`import {?\\s*([A-Z][a-zA-Z\\s,]+)\\s*}? from '(.*\\.styled(.js)*)\\';`, 'g');
  let sourceFileMatch = sourceFileContent.match(sourceStyledImportStringRegex);

  return sourceFileMatch?.length > 0 ? sourceFileMatch[0] : null;
}

// Gets the styled file path based on source import
function getStyledImportPath(importStatement) {
  if (!importStatement) return;

  let sourceStyledImportStringRegex = new RegExp(`'(.*\\.styled(.js)*)\\'`, 'g');
  let sourceStyledImportString = importStatement.match(sourceStyledImportStringRegex);

  return sourceStyledImportString?.length > 0 ? sourceStyledImportString[0].replaceAll("'", '') : null;
}

// Makes a resolved FS path from different import file paths
function getFullImportPath(importPath, basePath, sourceFileDirName) {
  if (!importPath) return;

  let resolvedPath;

  resolvedPath = importPath.replace('.js', '');

  if (importPath.includes('@')) {
    resolvedPath = importPath.replace('@', '');
    resolvedPath = path.resolve(basePath, resolvedPath) + '.js';
  } else {
    resolvedPath = path.resolve(sourceFileDirName, resolvedPath) + '.js';
  }

  return resolvedPath;
}

function createImportStatement(array, sourceFileBaseName) {
  let importString = 'import { ';

  for (let i = 0; i < array.length; i++) {
    importString += `${array[i]}`;
    importString += i + 1 < array.length ? ', ' : ' ';
  }

  importString += `} from '${sourceFileBaseName}';`;

  return importString;
}

function removeArrayDuplicates(array) {
  return array.filter((item, index) => array.indexOf(item) === index);
}

function getTagsFromFile(file, regex) {
  let tags = file.matchAll(regex);
  let sourceFileTags = [];

  // Gathering all the styled tags
  for (const iterator of tags) {
    // We need to separate and clean the multiple imports
    let trimmedArray = iterator[1]
      .trim()
      .split(',')
      .map((tag) => tag.trim())
      .filter((tag) => (tag ? tag : false));
    sourceFileTags = sourceFileTags.concat(trimmedArray);
  }

  // Removing dupes
  return removeArrayDuplicates(sourceFileTags);
}

function getMissingTags(tags, haystack) {
  return tags.filter((tag) => {
    return !haystack.includes(tag);
  });
}

function getCommonTags(tags, haystack) {
  return tags.filter((tag) => {
    return haystack.includes(tag);
  });
}

function removeTags(tags, haystack) {
  return haystack.filter((haystackTag) => {
    return !tags.includes(haystackTag);
  });
}
