#!/usr/bin/env python

from mincss.processor import Processor


urls = [
	'http://localhost:8000/?debug=1',
	'http://localhost:8000/static/templates/login.nj.html',
	'http://localhost:8000/static/templates/edit.nj.html',
	'http://localhost:8000/static/templates/entryList.nj.html',
	'http://localhost:8000/static/templates/userCreate.nj.html',
]

def run():
	p = Processor()
	for url in urls:
		p.process(url)

	for each in p.links:
		print each.after


if __name__ == '__main__':
	run()
