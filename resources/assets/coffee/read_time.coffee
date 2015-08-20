readTime = (text) ->
    words = text.trim().split(/\s+/g).length
    wordsPerMinute = 200.0
    Math.max(8000, Math.ceil(60000 * words / wordsPerMinute))

root = exports ? this
if not root.quoterr
    root.quoterr = {}
root.quoterr.readTime = readTime