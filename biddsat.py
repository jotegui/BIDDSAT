import webapp2

class MainPage(webapp2.RequestHandler):
	def get(self):
		html_source=open('/home/jotegui/code/BIDDSAT/index.html','r').read()
		self.response.headers['Content-Type'] = 'text/HTML'
		self.response.write(html_source)

class Biddsat(webapp2.RequestHandler):
	def get(self):
		self.response.headers['Content-Type'] = 'text/HTML'
		self.response.write('Yet to be developed')

app = webapp2.WSGIApplication([('/', MainPage), ('/app/',Biddsat)], debug=True)
