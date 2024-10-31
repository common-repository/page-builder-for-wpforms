function RNTranslate(key) {
    var RNTranslatorDictionary={};
    if(typeof RNTranslatorDictionary[key]=='undefined')
        return key;
    return RNTranslatorDictionary[key];
}

