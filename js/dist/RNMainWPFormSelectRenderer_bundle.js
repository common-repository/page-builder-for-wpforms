rndefine("#RNMainWPFormSelectRenderer",["exports","#RNMainRNPage/RendererBase","#RNMainRNPage/RendererBase.Model"],(function(e,t,r){"use strict";class s extends t.RendererBase{constructor(e){super(e),this.state={}}SubRender(){return React.createElement("div",{className:"wpforms-container-full"},React.createElement("div",{className:"wpforms-form"},React.createElement("select",{className:"wpforms-form wpforms-field-"+this.Model.GetRawSettingString(["size"]),value:this.props.Model.GetStringValue()},this.props.Model.GetOptions().map((e=>React.createElement("option",{value:e.Value},e.Label))))))}}s.defaultProps={},e.WPFormSelectRendererModel=a;class a extends r.RendererBaseModel{Render(){return React.createElement(s,{Model:this})}GetOptions(){return this.Field.Items}}class l extends t.RendererBase{constructor(e){super(e),this.state={}}SubRender(){return React.createElement("div",{className:"wpforms-container-full"},React.createElement("div",{className:"wpforms-form"},React.createElement("select",{className:"wpforms-form wpforms-field-"+this.Model.GetRawSettingString(["size"]),value:this.props.Model.GetStringValue()},this.props.Model.GetOptions().map((e=>React.createElement("option",{value:e.Value},e.Label))))))}}l.defaultProps={},e.WPFormSelectRendererModel=a,e.WPFormSelectRenderer=l,Object.defineProperty(e,"__esModule",{value:!0})}));
