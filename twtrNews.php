<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 10,
  interval: 30000,
  width: 650,
  height: 500,
  theme: {
    shell: {
      background: '#d46559',
      color: '#fdfdfc'
    },
    tweets: {
      background: '#422724',
      color: '#fdfdfc',
      links: '#de7266'
    }
  },
  features: {
    scrollbar: false,
    loop: true,
    live: false,
    behavior: 'default'
  }
}).render().setUser('ZnoteAAC').start();
</script>