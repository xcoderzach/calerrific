$(function() { setTimeout(function() { 

function split( val ) {
  return val.split( /,\s*/ );
}

function extractLast( term ) {
  return split( term ).pop();
}

$.get("/tags/all", function(data) {
  var availableTags = JSON.parse(data)
  $("#event-tags")
    .bind( "keydown", function( event ) {
      if (event.keyCode === $.ui.keyCode.TAB && $(this).data("autocomplete").menu.active) {
        event.preventDefault()
      }
    })
    .autocomplete({
      minLength: 0,
      source: function( request, response ) {
        response($.ui.autocomplete.filter(
          availableTags, extractLast(request.term)));
      },
      focus: function() {
        return false;
      },
      select: function( event, ui ) {
        var terms = split( this.value );
        terms.pop();
        terms.push( ui.item.value );
        terms.push( "" );
        this.value = terms.join( ", " );
        return false;
      }
    });
  })
}, 9000) })
