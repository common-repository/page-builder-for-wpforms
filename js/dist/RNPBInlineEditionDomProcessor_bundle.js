rndefine("#RNPBInlineEditionDomProcessor",["#RNPBRunnablePage/RunnablePage","lit","#RNPBCore/WpAjaxPost","#RNPBCore/EventManager"],(function(i,e,t,n){"use strict";var a={},s={};!function(i){Object.defineProperty(i,"__esModule",{value:!0});var e="floppy-disk",t=[128190,128426,"save"],n="f0c7",a="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V173.3c0-17-6.7-33.3-18.7-45.3L352 50.7C340 38.7 323.7 32 306.7 32H64zm0 96c0-17.7 14.3-32 32-32H288c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V128zM224 416c-35.3 0-64-28.7-64-64s28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64z";i.definition={prefix:"fas",iconName:e,icon:[448,512,t,n,a]},i.faFloppyDisk=i.definition,i.prefix="fas",i.iconName=e,i.width=448,i.height=512,i.ligatures=t,i.unicode=n,i.svgPathData=a,i.aliases=t}(s),function(i){Object.defineProperty(i,"__esModule",{value:!0});var e=s;i.definition={prefix:e.prefix,iconName:e.iconName,icon:[e.width,e.height,e.aliases,e.unicode,e.svgPathData]},i.faSave=i.definition,i.prefix=e.prefix,i.iconName=e.iconName,i.width=e.width,i.height=e.height,i.ligatures=e.aliases,i.unicode=e.unicode,i.svgPathData=e.svgPathData,i.aliases=e.aliases}(a);var o={},d={};!function(i){Object.defineProperty(i,"__esModule",{value:!0});var e="xmark",t=[128473,10005,10006,10060,215,"close","multiply","remove","times"],n="f00d",a="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z";i.definition={prefix:"fas",iconName:e,icon:[320,512,t,n,a]},i.faXmark=i.definition,i.prefix="fas",i.iconName=e,i.width=320,i.height=512,i.ligatures=t,i.unicode=n,i.svgPathData=a,i.aliases=t}(d),function(i){Object.defineProperty(i,"__esModule",{value:!0});var e=d;i.definition={prefix:e.prefix,iconName:e.iconName,icon:[e.width,e.height,e.aliases,e.unicode,e.svgPathData]},i.faTimes=i.definition,i.prefix=e.prefix,i.iconName=e.iconName,i.width=e.width,i.height=e.height,i.ligatures=e.aliases,i.unicode=e.unicode,i.svgPathData=e.svgPathData,i.aliases=e.aliases}(o);class r{constructor(i){this.RunnablePage=i,this.InlineFields=[],this.RunnablePage.Container.querySelectorAll(".inline-edit").forEach(((i,e)=>{let t=i.getAttribute("data-type"),a=i.getAttribute("data-subtype"),s=n.EventManager.Publish("GetInlineEdition",{Type:t,SubType:a,RunnablePage:this.RunnablePage,Container:i});null!=s&&this.InlineFields.push(s)}))}}n.EventManager.Subscribe("InitializeDomProcessors",(i=>{new r(i)})),exports.InlineEditionBase=class{constructor(i,e){this.RunnablePage=i,this.FieldContainer=e,this.Mode="Normal",this.EndEdition=this.EndEdition.bind(this),this.OriginalContent=this.FieldContainer.innerHTML,this.FieldContainer.addEventListener("click",(i=>{i.preventDefault(),i.stopImmediatePropagation(),"Normal"==this.Mode&&this.StartEdition()})),this.Value=JSON.parse(this.FieldContainer.getAttribute("data-value")),this.Initialize()}Initialize(){}EndEdition(){this.Mode="Normal",this.FieldContainer.innerHTML=this.OriginalContent,this.FieldContainer.classList.remove("editing"),document.body.removeEventListener("click",this.EndEdition)}StartEdition(){this.FieldContainer.classList.add("editing"),this.Mode="Edit",this.FieldContainer.innerHTML="",this.RenderEditFields(),document.body.addEventListener("click",this.EndEdition),this.FieldContainer.appendChild(e.renderInline(e.html` <div style="margin-top: 3px"> <button class="inline-edit-button" @click="${i=>{i.stopImmediatePropagation(),i.preventDefault(),this.Save()}}"> <rn-fontawesome .icon="${a.faSave}"></rn-fontawesome> </button> <button class="inline-edit-remove" @click="${i=>{i.stopImmediatePropagation(),i.preventDefault(),this.EndEdition()}}"> <rn-fontawesome .icon="${o.faTimes}"></rn-fontawesome> </button> </div> `))}async Save(){let i=await t.WpAjaxPost.Post("SaveInline",{EntryId:this.FieldContainer.getAttribute("data-entryid"),FieldId:this.FieldContainer.getAttribute("data-fieldid"),Nonce:this.FieldContainer.getAttribute("data-nonce"),PathId:this.FieldContainer.getAttribute("data-pathid"),Value:this.SerializeValue()});null!=i&&(this.AfterSave(i),this.OriginalContent=i.HTML,this.FieldContainer.setAttribute("data-value",JSON.stringify(i.Raw)),this.Value=i.Raw,this.EndEdition())}AfterSave(i){}},exports.InlineEditionDomProcessor=r}));