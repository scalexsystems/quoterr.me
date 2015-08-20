root = exports ? this
if not root.quoterr
    root.quoterr = {}

quoterr = root.quoterr

$quote = $('.quote')
$author = $('.author a')
$link = $('#unique-link')
timer = -1
timeout = 0
circle = null

runner = (data) ->
    timeout = 0
    $quote.html data['quote']
    $author.html data['author']
    $author.attr href: data['author.link']
    $author.attr title: 'Read quotes by ' + data['author']
    $link.attr href: data['quote.link']

    if timeout is 0
        timeout = quoterr.readTime $quote.text()

    circle = new ProgressBar.Circle '#slideshow', {color: '#000', strokeWidth: 12, fill: 'transparent', duration: timeout}

    circle.animate 1, =>
        circle.destroy()
        circle = null

    $.ajax data: {_format: 'json'}, url: '/', success: (d) =>
        timer = window.setTimeout(() =>
            runner d
            return
        , timeout)
    return

quoteSlideShow = (_do = true, _time = 0) ->
    timeout = _time
    if _do
        runner quote: $quote.text(), author: $author.text(), 'quote.link': $link.attr('href'), 'author.link': $author.attr('href')
    else
        window.clearTimeout timer
        if circle
            circle.destroy()
    return

quoterr.quoteSlideShow = quoteSlideShow