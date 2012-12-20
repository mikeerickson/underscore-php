
# ////////////////////////////////////////////////////////////////////
# ///////////////////////////// HELPERS //////////////////////////////
# ////////////////////////////////////////////////////////////////////

# Parses a file with Marked ---------------------------------------- /

parse = (file) ->
  file = marked.lexer file
  file = marked.parser file

  return file

# ////////////////////////////////////////////////////////////////////
# ////////////////////////////// SETUP ///////////////////////////////
# ////////////////////////////////////////////////////////////////////

# Setup Affix ------------------------------------------------------ /

$('.main').affix
  'offset': 50

# Load README ------------------------------------------------------ /

$.get './underscore/README.md', (file) ->
  $('#readme').html parse file

  # Turn on ScrollSpy once content is loaded
  $('body').scrollspy
    'offset': 100

# Load docs files -------------------------------------------------- /

for page in ['Arrays', 'Number', 'Object', 'Parse', 'String']
  $.ajax
    type: 'GET'
    async: false,
    url: "./docs/#{page}.md"
    success: (file) ->

      # Parse and append file
      file = parse file
      article = $('#' + page)
        .html("<h1>#{page}</h1>#{file}")

      # Format code blocks
      $('pre code', article)
        .addClass('lang-php')

      # Format internal navigations
      $('ul', article)
        .addClass('list-unstyled')
        .find('ul')
          .addClass('breadcrumb')

# Create dynamic navigation ---------------------------------------- /

for title in $('a[name]')

  # Get function and class
  method = $(title).attr('name')
  typeClass = $(title).parents('article').attr('id')
  namespacedMethod = typeClass + '-' +method

  # Namespace function and add navigation element
  $(title).attr('name', namespacedMethod).attr('id', namespacedMethod)
  $('.'+typeClass).append("<li><a href='##{namespacedMethod}'>#{method}</a></li>")

$('.main > li').on 'activate', (li) ->
  li = $(li.target)
  if li.has('ul').length
    $('.main ul').hide()
    $('ul', li).slideDown()