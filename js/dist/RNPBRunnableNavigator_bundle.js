rndefine("#RNPBRunnableNavigator",["#RNPBRunnablePage/RunnableBlockBase.Options","#RNPBRunnablePage/RunnableBlockBase","#RNPBCore/EventManager"],(function(e,n,t){"use strict";class a extends e.RunnableBlockBaseOptions{LoadDefaultValues(){super.LoadDefaultValues(),this.NextIndex=0,this.PreviousIndex=0}}class s extends n.RunnableBlockBase{constructor(e,n){super(e,n),this.Container.querySelector(".rnprevious").addEventListener("click",(e=>{e.preventDefault(),-1!=this.Options.PreviousIndex&&this.Page.GoToPage(this.Options.PreviousIndex)})),this.Container.querySelector(".rnnext").addEventListener("click",(e=>{e.preventDefault(),-1!=this.Options.NextIndex&&this.Page.GoToPage(this.Options.NextIndex)}))}}t.EventManager.Subscribe("GetRunnableOptions",(e=>"Navigator"==e.Type?(new a).Merge(e):null)),t.EventManager.Subscribe("GetRunnable",(e=>"Navigator"==e.Options.Type?new s(e.Options,e.Column):null)),exports.RunnableNavigatorOptions=a,exports.RunnableNavigator=s}));
