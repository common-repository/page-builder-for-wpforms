rndefine("#RNPBMultipleItemInlineEdition",["exports","#RNPBInlineEditionDomProcessor/InlineEditionBase"],(function(e,t){"use strict";class i extends t.InlineEditionBase{Initialize(){this.Items=JSON.parse(this.FieldContainer.getAttribute("data-items")),this.ParseSelectedValues(this.FieldContainer.getAttribute("data-value"))}SerializeValue(){let e=[];return document.querySelectorAll("input:checked").forEach(((t,i)=>{e.push(t.value)})),e}ParseSelectedValues(e){let t=JSON.parse(e);Array.isArray(t.value)?this.SelectedValues=t.value:this.SelectedValues=t.value.split("\n")}AfterSave(e){this.ParseSelectedValues(JSON.stringify(e.Raw))}}e.MultipleItemInlineEdition=i,Object.defineProperty(e,"__esModule",{value:!0})}));
