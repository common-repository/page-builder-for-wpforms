rndefine("#RNPBFileUploadDomProcessor",["#RNPBCore/EventManager","#RNPBRunnablePage/RunnablePage"],(function(e,n){"use strict";class o{constructor(e){this.RunnablePage=e,this.RunnablePage.Container.querySelectorAll(".file-upload-item").forEach(((e,n)=>{e.querySelector(".removeIcon").addEventListener("click",(()=>{e.closest(".file-upload-item").remove()}))}))}}e.EventManager.Subscribe("InitializeDomProcessors",(e=>{new o(e)}))}));