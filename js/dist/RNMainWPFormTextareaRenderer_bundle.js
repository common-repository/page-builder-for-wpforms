rndefine("#RNMainWPFormTextareaRenderer",["exports","#RNMainRNPage/RendererBase","#RNMainRNPage/RendererBase.Model"],(function(e,r,t){"use strict";class a extends r.RendererBase{constructor(e){super(e),this.state={}}SubRender(){return React.createElement("div",{className:"wpforms-container-full"},React.createElement("div",{className:"wpforms-form"},React.createElement("textarea",{className:"wpforms-form wpforms-field-"+this.Model.GetRawSettingString(["size"]),value:this.props.Model.GetStringValue()})))}}a.defaultProps={},e.WPFormTextareaRendererModel=s;class s extends t.RendererBaseModel{Render(){return React.createElement(a,{Model:this})}}class n extends r.RendererBase{constructor(e){super(e),this.state={}}SubRender(){return React.createElement("div",{className:"wpforms-container-full"},React.createElement("div",{className:"wpforms-form"},React.createElement("textarea",{className:"wpforms-form wpforms-field-"+this.Model.GetRawSettingString(["size"]),value:this.props.Model.GetStringValue()})))}}n.defaultProps={},e.WPFormTextareaRendererModel=s,e.WPFormTextareaRenderer=n,Object.defineProperty(e,"__esModule",{value:!0})}));
