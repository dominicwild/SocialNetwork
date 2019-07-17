function removeTags(index, content) {
    var text = $(content).html();
    text = text.replace(/(<([^>]+)>)/ig,"");
    $(content).html(text);
}

function emojiImageToUnicode (index,content) {
    var emojiCDN = /https:\/\/cdn.jsdelivr.net\/emojione\/assets\//;
    $(content).find("img").each(function(index,image){
        if(emojiCDN.test($(image).attr("src"))){
            var emoji = $(image).attr("alt");
            $(image).replaceWith(emoji);
        }
    });
}

function innerHTMLTextAreaEmoji(element, textArea){
    var content = element.html();
    content = processContent($(element));
    element.html(textArea.attr("data-content",content.html()));
    var emojiArea = $(textArea).emojioneArea({
        pickerPosition: "right",
        autocomplete: "on"
    });
    emojiArea[0].emojioneArea.setText(textArea.data("content"));
}

function processContent(content){
    $(content).each(emojiImageToUnicode);
    $(content).each(removeTags);
    return content;
}

function debug(data){
    $("body").children().first().before(data);
}

