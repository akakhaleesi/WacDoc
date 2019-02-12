$(function(){

  $('#save_doc').click(function(event){
    event.preventDefault();
    var doc_content = $('#editor').text();
    $('input[name="doc_content"]').val(doc_content);
    $('#save').submit();
  });

  $('#bold').click(function(){
    wrap('span','bold');
  });

  $('#italic').click(function(){
    wrap('span','italic');
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

  $('#list1').click(function(){
    wrap('li', 'puce');
  });

  $('#list2').click(function(){
    wrap('li', 'decimal');
  });

  function wrap(balise, tag){
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

            var newNode = document.createElement(balise);
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
