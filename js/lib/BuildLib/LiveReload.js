'use strict';

var livereload$1 = require('livereload');
var path = require('path');
require('child_process');
var net = require('net');

var liveReloadServer=null;
export function livereload(options = { watch: '' }) {

    if(liveReloadServer==null)
    {
        var newPath=path.join(process.cwd(),'dist');

        console.log('INITIALIZING LIVE RELOAD');

        liveReloadServer=livereload$1.createServer();
        console.log('start watching ',newPath);

        liveReloadServer.watch(newPath);

    }

}
