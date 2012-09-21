import webapp2

class MainPage(webapp2.RequestHandler):
  def get(self):
      html_source=open('/home/jotegui/code/BIDDSAT/index.html','r').read()
      self.response.headers['Content-Type'] = 'text/HTML'
      self.response.write(html_source)

app = webapp2.WSGIApplication([('/', MainPage)],
                              debug=True)
