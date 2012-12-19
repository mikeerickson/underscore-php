# Setup Affix ------------------------------------------------------ /

$('.main').affix
  'offset': 50

# Load docs files -------------------------------------------------- /

parse = (file) ->
  file = marked.lexer file
  file = marked.parser(file)

  return file

for page in ['Arrays', 'Number', 'Object', 'Parse', 'String']
  $.ajax
    type: 'GET'
    async: false,
    url: "./docs/#{page}.md"
    success: (file) ->
      file = parse file
      title = "<h1>#{page}</h1>"
      $('#'+page).html title+file
      $('pre code', '#'+page).addClass('lang-php')
      $('article > ul').addClass('list-unstyled')
      $('article ul ul').addClass('breadcrumb')

$.get './README.md', (file) ->
  file = parse file
  $('#readme').html file

  $('body').scrollspy
    'offset': 100

# Create dynamic navigation ---------------------------------------- /

$('.main > li').on 'activate', (li) ->
  li = $(li.target)
  #if $('ul', li).length
  $('.main ul').hide()
  $('ul', li).slideDown()

for title in $('a[name]')
  text = $(title).attr('name')
  # $(title).attr('id', text)
  category = $(title).parent().parent().attr('id')
  $('.'+category).append("<li><a href='##{text}'>#{text}</a></li>")