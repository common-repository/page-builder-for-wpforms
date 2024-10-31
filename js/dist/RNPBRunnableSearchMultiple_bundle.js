rndefine('#RNPBRunnableSearchMultiple', ['exports', '#RNPBRunnableSearchBar/RunnableSearchFieldBase', '#RNPBCore/EventManager', '#RNPBRunnableSearchBar/RunnableSearchFieldBase.Options', '#RNPBCore/Sanitizer'], (function (exports, RunnableSearchFieldBase, EventManager, RunnableSearchFieldBase_Options, Sanitizer) { 'use strict';

    class RunnableSearchMultipleOptions extends RunnableSearchFieldBase_Options.RunnableSearchFieldBaseOptions {}

    class RunnableSearchMultiple extends RunnableSearchFieldBase.RunnableSearchFieldBase {
      constructor(SearchBar, options) {
        super(SearchBar, options);
        this.Container.setAttribute('multiple', 'multiple');
        let selectedOptions = this.Container.querySelectorAll('option[selected]');
        let values = [];
        selectedOptions.forEach(x => values.push(x.innerText));
        this.TomSelect = new TomSelect(this.Container, {
          plugins: ['remove_button']
        });
        this.TomSelect.setValue(values);
      }

      GetWasFilled() {
        return Sanitizer.Sanitizer.SanitizeStringArray(this.TomSelect.getValue()).length > 0;
      }

      InternalGetSearchInfo() {
        return Sanitizer.Sanitizer.SanitizeStringArray(this.TomSelect.getValue());
      }

    }
    EventManager.EventManager.Subscribe('GetSearchField', option => {
      if (option.Options.SearchType == 'Multiple') return new RunnableSearchMultiple(option.SearchBar, new RunnableSearchMultipleOptions().Merge(option.Options));
    });

    exports.RunnableSearchMultiple = RunnableSearchMultiple;

    Object.defineProperty(exports, '__esModule', { value: true });

}));
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiUk5QQlJ1bm5hYmxlU2VhcmNoTXVsdGlwbGVfYnVuZGxlLmpzIiwic291cmNlcyI6WyIuLi9zcmMvRHluYW1pY3MvUnVubmFibGUvU2VhcmNoQmFyRmllbGRUZW1wbGF0ZXMvUnVubmFibGVTZWFyY2hNdWx0aXBsZS9SdW5uYWJsZVNlYXJjaE11bHRpcGxlLk9wdGlvbnMudHN4IiwiLi4vc3JjL0R5bmFtaWNzL1J1bm5hYmxlL1NlYXJjaEJhckZpZWxkVGVtcGxhdGVzL1J1bm5hYmxlU2VhcmNoTXVsdGlwbGUvUnVubmFibGVTZWFyY2hNdWx0aXBsZS50c3giXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtSdW5uYWJsZVNlYXJjaEZpZWxkQmFzZU9wdGlvbnN9IGZyb20gXCIjRHluYW1pY3MvUnVubmFibGUvQmxvY2tzL1J1bm5hYmxlU2VhcmNoQmFyL1J1bm5hYmxlU2VhcmNoRmllbGRCYXNlLk9wdGlvbnNcIjtcclxuXHJcbmV4cG9ydCBjbGFzcyBSdW5uYWJsZVNlYXJjaE11bHRpcGxlT3B0aW9ucyBleHRlbmRzIFJ1bm5hYmxlU2VhcmNoRmllbGRCYXNlT3B0aW9uc3tcclxuXHJcbn0iLCJpbXBvcnQge1J1bm5hYmxlU2VhcmNoRmllbGRCYXNlfSBmcm9tIFwiI0R5bmFtaWNzL1J1bm5hYmxlL0Jsb2Nrcy9SdW5uYWJsZVNlYXJjaEJhci9SdW5uYWJsZVNlYXJjaEZpZWxkQmFzZVwiO1xyXG5pbXBvcnQge0V2ZW50TWFuYWdlcn0gZnJvbSBcIiNEeW5hbWljcy9TaGFyZWQvQ29yZS9FdmVudHMvRXZlbnRNYW5hZ2VyXCI7XHJcbmltcG9ydCB7UnVubmFibGVTZWFyY2hNdWx0aXBsZU9wdGlvbnN9IGZyb20gXCIjRHluYW1pY3MvUnVubmFibGUvU2VhcmNoQmFyRmllbGRUZW1wbGF0ZXMvUnVubmFibGVTZWFyY2hNdWx0aXBsZS9SdW5uYWJsZVNlYXJjaE11bHRpcGxlLk9wdGlvbnNcIjtcclxuaW1wb3J0IHtSdW5uYWJsZVNlYXJjaEJhcn0gZnJvbSBcIiNEeW5hbWljcy9SdW5uYWJsZS9CbG9ja3MvUnVubmFibGVTZWFyY2hCYXIvUnVubmFibGVTZWFyY2hCYXJcIjtcclxuaW1wb3J0IHtSdW5uYWJsZVNlYXJjaEZpZWxkQmFzZU9wdGlvbnN9IGZyb20gXCIjRHluYW1pY3MvUnVubmFibGUvQmxvY2tzL1J1bm5hYmxlU2VhcmNoQmFyL1J1bm5hYmxlU2VhcmNoRmllbGRCYXNlLk9wdGlvbnNcIjtcclxuaW1wb3J0IHtTYW5pdGl6ZXJ9IGZyb20gXCIjRHluYW1pY3MvU2hhcmVkL0NvcmUvVXRpbGl0aWVzL1Nhbml0aXplclwiO1xyXG5cclxuZGVjbGFyZSB2YXIgVG9tU2VsZWN0OmFueTtcclxuZXhwb3J0IGNsYXNzIFJ1bm5hYmxlU2VhcmNoTXVsdGlwbGUgZXh0ZW5kcyBSdW5uYWJsZVNlYXJjaEZpZWxkQmFzZXtcclxuICAgIHB1YmxpYyBPcHRpb25zOlJ1bm5hYmxlU2VhcmNoTXVsdGlwbGVPcHRpb25zO1xyXG4gICAgcHVibGljIENvbnRhaW5lcjpIVE1MSW5wdXRFbGVtZW50O1xyXG4gICAgcHVibGljIFRvbVNlbGVjdDphbnk7XHJcblxyXG4gICAgY29uc3RydWN0b3IoU2VhcmNoQmFyOiBSdW5uYWJsZVNlYXJjaEJhciwgb3B0aW9uczogUnVubmFibGVTZWFyY2hGaWVsZEJhc2VPcHRpb25zKSB7XHJcbiAgICAgICAgc3VwZXIoU2VhcmNoQmFyLCBvcHRpb25zKTtcclxuICAgICAgICB0aGlzLkNvbnRhaW5lci5zZXRBdHRyaWJ1dGUoJ211bHRpcGxlJywnbXVsdGlwbGUnKTtcclxuICAgICAgICBsZXQgc2VsZWN0ZWRPcHRpb25zPXRoaXMuQ29udGFpbmVyLnF1ZXJ5U2VsZWN0b3JBbGwoJ29wdGlvbltzZWxlY3RlZF0nKTtcclxuICAgICAgICBsZXQgdmFsdWVzPVtdO1xyXG4gICAgICAgIHNlbGVjdGVkT3B0aW9ucy5mb3JFYWNoKCh4OmFueSk9PnZhbHVlcy5wdXNoKHguaW5uZXJUZXh0KSlcclxuXHJcbiAgICAgICAgdGhpcy5Ub21TZWxlY3Q9bmV3IFRvbVNlbGVjdCh0aGlzLkNvbnRhaW5lcix7XHJcbiAgICAgICAgICAgIHBsdWdpbnM6IFsncmVtb3ZlX2J1dHRvbiddXHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIHRoaXMuVG9tU2VsZWN0LnNldFZhbHVlKHZhbHVlcyk7XHJcbiAgICB9XHJcblxyXG4gICAgR2V0V2FzRmlsbGVkKCk6Ym9vbGVhbiB7XHJcbiAgICAgICAgcmV0dXJuIFNhbml0aXplci5TYW5pdGl6ZVN0cmluZ0FycmF5KHRoaXMuVG9tU2VsZWN0LmdldFZhbHVlKCkpLmxlbmd0aD4wO1xyXG4gICAgfVxyXG5cclxuICAgIEludGVybmFsR2V0U2VhcmNoSW5mbygpIHtcclxuICAgICAgICByZXR1cm4gU2FuaXRpemVyLlNhbml0aXplU3RyaW5nQXJyYXkodGhpcy5Ub21TZWxlY3QuZ2V0VmFsdWUoKSlcclxuICAgIH1cclxuXHJcbn1cclxuXHJcblxyXG5FdmVudE1hbmFnZXIuU3Vic2NyaWJlKCdHZXRTZWFyY2hGaWVsZCcsKG9wdGlvbjphbnkpPT57XHJcbiAgICBpZihvcHRpb24uT3B0aW9ucy5TZWFyY2hUeXBlPT0nTXVsdGlwbGUnKVxyXG4gICAgICAgIHJldHVybiBuZXcgUnVubmFibGVTZWFyY2hNdWx0aXBsZShvcHRpb24uU2VhcmNoQmFyLG5ldyBSdW5uYWJsZVNlYXJjaE11bHRpcGxlT3B0aW9ucygpLk1lcmdlKG9wdGlvbi5PcHRpb25zKSk7XHJcbn0pOyJdLCJuYW1lcyI6WyJSdW5uYWJsZVNlYXJjaE11bHRpcGxlT3B0aW9ucyIsIlJ1bm5hYmxlU2VhcmNoRmllbGRCYXNlT3B0aW9ucyIsIlJ1bm5hYmxlU2VhcmNoTXVsdGlwbGUiLCJSdW5uYWJsZVNlYXJjaEZpZWxkQmFzZSIsImNvbnN0cnVjdG9yIiwiU2VhcmNoQmFyIiwib3B0aW9ucyIsIkNvbnRhaW5lciIsInNldEF0dHJpYnV0ZSIsInNlbGVjdGVkT3B0aW9ucyIsInF1ZXJ5U2VsZWN0b3JBbGwiLCJ2YWx1ZXMiLCJmb3JFYWNoIiwieCIsInB1c2giLCJpbm5lclRleHQiLCJUb21TZWxlY3QiLCJwbHVnaW5zIiwic2V0VmFsdWUiLCJHZXRXYXNGaWxsZWQiLCJTYW5pdGl6ZXIiLCJTYW5pdGl6ZVN0cmluZ0FycmF5IiwiZ2V0VmFsdWUiLCJsZW5ndGgiLCJJbnRlcm5hbEdldFNlYXJjaEluZm8iLCJFdmVudE1hbmFnZXIiLCJTdWJzY3JpYmUiLCJvcHRpb24iLCJPcHRpb25zIiwiU2VhcmNoVHlwZSIsIk1lcmdlIl0sIm1hcHBpbmdzIjoiOztJQUVPLE1BQU1BLDZCQUFOLFNBQTRDQyw4REFBNUMsQ0FBMEU7O0lDTTFFLE1BQU1DLHNCQUFOLFNBQXFDQywrQ0FBckMsQ0FBNEQ7SUFLL0RDLEVBQUFBLFdBQVcsQ0FBQ0MsU0FBRCxFQUErQkMsT0FBL0IsRUFBd0U7UUFDL0UsS0FBTUQsQ0FBQUEsU0FBTixFQUFpQkMsT0FBakIsQ0FBQSxDQUFBO0lBQ0EsSUFBQSxJQUFBLENBQUtDLFNBQUwsQ0FBZUMsWUFBZixDQUE0QixVQUE1QixFQUF1QyxVQUF2QyxDQUFBLENBQUE7UUFDQSxJQUFJQyxlQUFlLEdBQUMsSUFBS0YsQ0FBQUEsU0FBTCxDQUFlRyxnQkFBZixDQUFnQyxrQkFBaEMsQ0FBcEIsQ0FBQTtRQUNBLElBQUlDLE1BQU0sR0FBQyxFQUFYLENBQUE7SUFDQUYsSUFBQUEsZUFBZSxDQUFDRyxPQUFoQixDQUF5QkMsQ0FBRCxJQUFTRixNQUFNLENBQUNHLElBQVAsQ0FBWUQsQ0FBQyxDQUFDRSxTQUFkLENBQWpDLENBQUEsQ0FBQTtJQUVBLElBQUEsSUFBQSxDQUFLQyxTQUFMLEdBQWUsSUFBSUEsU0FBSixDQUFjLElBQUEsQ0FBS1QsU0FBbkIsRUFBNkI7VUFDeENVLE9BQU8sRUFBRSxDQUFDLGVBQUQsQ0FBQTtJQUQrQixLQUE3QixDQUFmLENBQUE7SUFJQSxJQUFBLElBQUEsQ0FBS0QsU0FBTCxDQUFlRSxRQUFmLENBQXdCUCxNQUF4QixDQUFBLENBQUE7SUFDSCxHQUFBOztJQUVEUSxFQUFBQSxZQUFZLEdBQVc7SUFDbkIsSUFBQSxPQUFPQyxtQkFBUyxDQUFDQyxtQkFBVixDQUE4QixJQUFLTCxDQUFBQSxTQUFMLENBQWVNLFFBQWYsRUFBOUIsQ0FBQSxDQUF5REMsTUFBekQsR0FBZ0UsQ0FBdkUsQ0FBQTtJQUNILEdBQUE7O0lBRURDLEVBQUFBLHFCQUFxQixHQUFHO1FBQ3BCLE9BQU9KLG1CQUFTLENBQUNDLG1CQUFWLENBQThCLEtBQUtMLFNBQUwsQ0FBZU0sUUFBZixFQUE5QixDQUFQLENBQUE7SUFDSCxHQUFBOztJQXpCOEQsQ0FBQTtBQThCbkVHLDZCQUFZLENBQUNDLFNBQWIsQ0FBdUIsZ0JBQXZCLEVBQXlDQyxNQUFELElBQWM7TUFDbEQsSUFBR0EsTUFBTSxDQUFDQyxPQUFQLENBQWVDLFVBQWYsSUFBMkIsVUFBOUIsRUFDSSxPQUFPLElBQUkzQixzQkFBSixDQUEyQnlCLE1BQU0sQ0FBQ3RCLFNBQWxDLEVBQTRDLElBQUlMLDZCQUFKLEVBQUEsQ0FBb0M4QixLQUFwQyxDQUEwQ0gsTUFBTSxDQUFDQyxPQUFqRCxDQUE1QyxDQUFQLENBQUE7SUFDUCxDQUhELENBQUE7Ozs7Ozs7Ozs7In0=