$(function(){

  $('#bold').click(function(){
    wrap('bold');
  });

  $('#italic').click(function(){
    wrap('italic');
  });

  $('#color').click(function(){
    var c = $('#c').val();
    document.execCommand('foreColor',false,c);
  });

  $('#backcolor').click(function(){
    var c = $('#c').val();
    document.execCommand('BackColor',false,c);
  });

  $('#size').click(function(){
    var size = $('#n').val();
    if(size != ''){
      document.execCommand('fontSize', false, size);
    }
  });

  function wrap(tag){
    var sel, range;
    var selectedText;
    if(window.getSelection){
      sel = window.getSelection();

      if(sel.rangeCount){
        range = sel.getRangeAt(0);
        parentEl = range.commonAncestorContainer;

        if (parentEl.nodeType != 1) {
          parentEl = parentEl.parentNode;
          console.log(parentEl)
          var tagclass = parentEl.getAttribute('class');
          if(tagclass == tag){
            parentEl.className = null;
          }
          else {
            selectedText = range.toString();
            range.deleteContents();

            var newNode = document.createElement("span");
            var txt = document.createTextNode(selectedText);
            newNode.className = tag;
            newNode.append(txt);
            range.insertNode(newNode);
          }
        }
      }
    }
  }

});
