export class RollupLoader{
    Prefix='';
    Dictionary=[];
    constructor(prefix)
    {
        this.Prefix=prefix;
        this.Dictionary=[];
    }

    CreateBundleForEachSubFolder(path,namespace,sublevels=0){
        let subFolderPath=__dirname+'/src/'+path+'/';

        let folders=this.GetSubFolders(subFolderPath,0,sublevels);

        for(let currentFolder of folders)
        {
            let conflictingPath=this.Dictionary.find(x=>x.Path.indexOf(folders)===0);

            if(conflictingPath!=null)
            {
                throw Error('the path '+folders+' and '+conflictingPath.Path+' are conflicting');
            }

            var path=require('path');
            var fs=require('fs');

            let baseName=path.basename(currentFolder);
            let fileName=baseName;
            if(fs.existsSync(path.join(currentFolder,'Index.tsx')))
                baseName='Index';



            this.Dictionary.push({
                "Path":currentFolder.replace(/\\/g,'/'),
                "Namespace":namespace,
                "Name":baseName,
                "FileName":fileName
            });
        }





    }

    GetEntryPoints(){
        let entries=[];
        for(let currentDictionary of this.Dictionary)
        {






        }
    }

    GetSubFolders(subFolderPath, currentSubLevel, desiredSubLevel)
    {
        if(desiredSubLevel==-1)
            return [subFolderPath];
        let fs=require('fs');
        let result=fs.readdirSync(subFolderPath);

        let subFoldersToReturn=[];
        for(let entryFolder of result)
        {

            let currentFolder=subFolderPath+entryFolder+'/';
            if(currentSubLevel===desiredSubLevel)
            {
                subFoldersToReturn.push(currentFolder);
            }
            else
            {
                subFoldersToReturn = subFoldersToReturn.concat(this.GetSubFolders(currentFolder, currentSubLevel + 1, desiredSubLevel));
            }

        }

        return subFoldersToReturn;

    }
}