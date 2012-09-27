import webapp2

class MainPage(webapp2.RequestHandler):
	def get(self):
		html_source=open('/home/jotegui/code/BIDDSAT/index.html','r').read()
		self.response.headers['Content-Type'] = 'text/HTML'
		self.response.write(html_source)

class Biddsat(webapp2.RequestHandler):
	def get(self):
		self.response.headers['Content-type'] = 'text/HTML'
		self.response.write('Hey there, it\'s me, BIDDSAT')

app = webapp2.WSGIApplication([('/', MainPage), ('/app/',Biddsat)], debug=True)
