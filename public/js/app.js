(function() {
  var readTime, root;

  readTime = function(text) {
    var words, wordsPerMinute;
    words = text.trim().split(/\s+/g).length;
    wordsPerMinute = 200.0;
    return Math.max(8000, Math.ceil(60000 * words / wordsPerMinute));
  };

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  if (!root.quoterr) {
    root.quoterr = {};
  }

  root.quoterr.readTime = readTime;

}).call(this);

(function() {
  var $author, $link, $quote, circle, quoteSlideShow, quoterr, root, runner, timeout, timer;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  if (!root.quoterr) {
    root.quoterr = {};
  }

  quoterr = root.quoterr;

  $quote = $('.quote');

  $author = $('.author a');

  $link = $('#unique-link');

  timer = -1;

  timeout = 0;

  circle = null;

  runner = function(data) {
    timeout = 0;
    $quote.html(data['quote']);
    $author.html(data['author']);
    $author.attr({
      href: data['author.link']
    });
    $author.attr({
      title: 'Read quotes by ' + data['author']
    });
    $link.attr({
      href: data['quote.link']
    });
    if (timeout === 0) {
      timeout = quoterr.readTime($quote.text());
    }
    circle = new ProgressBar.Circle('#slideshow', {
      color: '#000',
      strokeWidth: 12,
      fill: 'transparent',
      duration: timeout
    });
    circle.animate(1, (function(_this) {
      return function() {
        circle.destroy();
        return circle = null;
      };
    })(this));
    $.ajax({
      data: {
        _format: 'json'
      },
      url: '/',
      success: (function(_this) {
        return function(d) {
          return timer = window.setTimeout(function() {
            runner(d);
          }, timeout);
        };
      })(this)
    });
  };

  quoteSlideShow = function(_do, _time) {
    if (_do == null) {
      _do = true;
    }
    if (_time == null) {
      _time = 0;
    }
    timeout = _time;
    if (_do) {
      runner({
        quote: $quote.text(),
        author: $author.text(),
        'quote.link': $link.attr('href'),
        'author.link': $author.attr('href')
      });
    } else {
      window.clearTimeout(timer);
      if (circle) {
        circle.destroy();
      }
    }
  };

  quoterr.quoteSlideShow = quoteSlideShow;

}).call(this);

//# sourceMappingURL=app.js.map