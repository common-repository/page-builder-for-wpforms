rndefine("#RNPBCarouselDomProcessor",["exports","#RNPBCore/EventManager","#RNPBRunnablePage/RunnablePage"],(function(e,t,a){"use strict";class r{constructor(e){this.RunnablePage=e;let t=this.RunnablePage.Container.querySelector(".swiper"),a=JSON.parse(t.getAttribute("data-options")),r={grabCursor:!0,centeredSlides:!0,slidesPerView:"auto",coverflowEffect:{rotate:50,stretch:0,depth:100,modifier:1,slideShadows:!0},loop:!0,navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}};a.EnablePagination&&(r.pagination={el:".swiper-pagination",disableOnInteraction:!1,clickable:!0}),a.AutoPlay&&(r.autoplay={delay:a.AutoPlayDelay}),new Swiper(".swiper",r)}}t.EventManager.Subscribe("InitializeDomProcessors",(e=>{new r(e)})),e.CarouselDomProcessor=r,Object.defineProperty(e,"__esModule",{value:!0})}));