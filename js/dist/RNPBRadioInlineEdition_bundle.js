rndefine("#RNPBRadioInlineEdition",["exports","#RNPBMultipleItemInlineEdition/MultipleItemInlineEdition","#RNPBCore/EventManager"],(function(e,t,i){"use strict";class n extends t.MultipleItemInlineEdition{RenderEditFields(){let e=this.FieldContainer.getAttribute("data-fieldid"),t=document.createElement("div");this.FieldContainer.appendChild(t);for(let i of this.Items){let n=document.createElement("div");n.classList.add("optionitem"),t.appendChild(n);let l=document.createElement("input");n.appendChild(l),l.type="radio",l.name=e,l.value=i;let d=document.createElement("label");n.appendChild(d),d.innerText=i,d.addEventListener("click",(()=>{l.checked=!l.checked})),this.SelectedValues.indexOf(i)>=0&&(l.checked=!0)}}SerializeValue(){let e=super.SerializeValue();return 0==e.length?"":e[0]}}i.EventManager.Subscribe("GetInlineEdition",(e=>{if("radio"==e.SubType)return new n(e.RunnablePage,e.Container)})),e.RadioInlineEdition=n,Object.defineProperty(e,"__esModule",{value:!0})}));
