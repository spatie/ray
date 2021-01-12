---
title: Windows 10 using homestead
weight: 3
---

Windows 10 does not support the PhpStorm protocol and therefore you will not be able to open PhpStorm given the link from Ray. 
As a work-around, @aik099 has a package to edits your registry to correlate the PhpStorm link protocol to your PhpStorm instance. 

Just goto https://github.com/aik099/PhpStormProtocol and follow the directions. There is a chance you may need to reference https://github.com/aik099/PhpStormProtocol/issues/32. 
If it doesn't work out of the box. However, be sure to update your run_editor.js to your current version of PhpStorm and everything should work. 

here is an example of an updated run_editor.js file:



```js
var settings = {
    // flag to active Jetbrain Toolbox configuration
    toolBoxActive: false,

    // Set to 'true' (without quotes) if run on Windows 64bit. Set to 'false' (without quotes) otherwise.
    x64: true,

    // Set to folder name, where PhpStorm was installed to (e.g. 'PhpStorm')
    folder_name: 'PhpStorm 2019.2.2', // BE SURE TO UPDATE THESE TO THE CORRECT VERSION

    // Set to window title (only text after dash sign), that you see, when switching to running PhpStorm instance
    window_title: 'PhpStorm 2019.2.2',  // BE SURE TO UPDATE THESE TO THE CORRECT VERSION

    // In case your file is mapped via a network share and paths do not match.
    // eg. /var/www will can replaced with Y:/
    projects_basepath: '',
    projects_path_alias: ''
};


// don't change anything below this line, unless you know what you're doing
var url = WScript.Arguments(0),
    match = /^phpstorm:\/\/open\/?\?(url=file:\/\/|file=)(.+)&line=(\d+)$/.exec(url),
    project = '',
    editor = '"C:\\' + ( settings.x64 ? 'Program Files' : 'Program Files (x86)' ) + '\\JetBrains\\' + settings.folder_name + ( settings.x64 ? '\\bin\\phpstorm64.exe' : '\\bin\\phpstorm.exe' ) + '"';

if (settings.toolBoxActive) {
    configureToolboxSettings(settings);
}

if (match) {

    var shell = new ActiveXObject('WScript.Shell'),
        file_system = new ActiveXObject('Scripting.FileSystemObject'),
        file = decodeURIComponent(match[ 2 ]).replace(/\+/g, ' '),
        search_path = file.replace(/\//g, '\\');

    if (settings.projects_basepath !== '' && settings.projects_path_alias !== '') {
        file = file.replace(new RegExp('^' + settings.projects_basepath), settings.projects_path_alias);
    }

    while (search_path.lastIndexOf('\\') !== -1) {
        search_path = search_path.substring(0, search_path.lastIndexOf('\\'));

        if (file_system.FileExists(search_path + '\\.idea\\.name')) {
            project = search_path;
            break;
        }
    }

    if (project !== '') {
        editor += ' "%project%"';
    }

    editor += ' --line %line% "%file%"';

    var command = editor.replace(/%line%/g, match[ 3 ])
        .replace(/%file%/g, file)
        .replace(/%project%/g, project)
        .replace(/\//g, '\\');



    shell.Exec(command);
    shell.AppActivate(settings.window_title);
}

function configureToolboxSettings(settings) {
    var shell = new ActiveXObject('WScript.Shell'),
        appDataLocal = shell.ExpandEnvironmentStrings("%localappdata%"),
        toolboxDirectory = appDataLocal + '\\JetBrains\\Toolbox\\apps\\PhpStorm\\ch-0\\';

    // Reference the FileSystemObject
    var fso = new ActiveXObject('Scripting.FileSystemObject');

    // Reference the Text directory
    var folder = fso.GetFolder(toolboxDirectory);

    // Reference the File collection of the Text directory
    var fileCollection = folder.SubFolders;


    var maxMajor = 0,
        maxMinor = 0,
        maxPatch = 0,
        maxVersionFolder = "";
    // Traverse through the fileCollection using the FOR loop
    // read the maximum version from toolbox filesystem
    for (var objEnum = new Enumerator(fileCollection); !objEnum.atEnd(); objEnum.moveNext()) {
        var folderObject = ( objEnum.item() );
        if (folderObject.Name.lastIndexOf('plugins') === -1) {
            var versionMatch = /(\d+)\.(\d+)\.(\d+)/.exec(folderObject.Name),
                major = parseInt(versionMatch[ 1 ]),
                minor = parseInt(versionMatch[ 2 ]),
                patch = parseInt(versionMatch[ 3 ]);
            if (maxMajor === 0 || maxMajor <= major) {
                maxMajor = major;
                if (maxMinor === 0 || maxMinor <= minor) {
                    maxMinor = minor;
                    if (maxPatch === 0 || maxPatch <= patch) {
                        maxPatch = patch;
                        maxVersionFolder = folderObject.Name;
                    }
                }
            }
        }
    }

    settings.folder_name = maxVersionFolder;

    // read version name and product name from product-info.json
    var versionFile = fso.OpenTextFile(toolboxDirectory + settings.folder_name + "\\product-info.json", 1, true);
    var content = versionFile.ReadAll();

    eval('var productVersion = ' + content + ';');
    settings.window_title = 'PhpStorm ' + productVersion.version;
    editor = '"' + toolboxDirectory + settings.folder_name + '\\' + productVersion.launch[ 0 ].launcherPath.replace(/\//g, '\\') + '"';
}
```
