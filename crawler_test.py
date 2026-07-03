import requests
from bs4 import BeautifulSoup
import json
import re

url_ja = 'https://hh-japaneeds.com/ja/schools/'
response = requests.get(url_ja)
soup = BeautifulSoup(response.text, 'html.parser')

with open('debug_ja.txt', 'w', encoding='utf-8') as f:
    for table in soup.find_all('table')[:3]:
        for tr in table.find_all('tr'):
            tds = tr.find_all(['th', 'td'])
            f.write(" | ".join([td.text.strip() for td in tds]) + "\n")
        f.write("-" * 40 + "\n")
