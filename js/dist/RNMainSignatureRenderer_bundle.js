rndefine("#RNMainSignatureRenderer",["#RNMainRNPage/RendererBase.Model","#RNMainCore/Sanitizer","#RNMainRNPage/RendererBase","#RNMainCore/EventManager"],(function(e,r,t,n){"use strict";class a extends t.RendererBase{constructor(e){super(e),this.state={}}SubRender(){if(this.props.Model.Block.Page.IsDesign)return React.createElement("a",{target:"_blank",href:"#"},"Signature Image");let e=this.props.Model.GetURL();return""==e?null:React.createElement("div",null,React.createElement("img",{alt:e,src:e,style:{width:"100%"}}))}}a.defaultProps={},n.EventManager.Subscribe("GetFieldRendererModel",(e=>{if("Signature"==e.Field.RendererType)return new i(e.Block,e.Field,e.Data)}));class i extends e.RendererBaseModel{Render(){return React.createElement(a,{Model:this})}GetOptions(){return this.Field.Items}GetURL(){return r.Sanitizer.GetStringValueFromPath(this.Data,["Value"])}}exports.SignatureRendererModel=i,exports.SignatureRenderer=a}));