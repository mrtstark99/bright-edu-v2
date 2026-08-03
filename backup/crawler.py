import requests
from bs4 import BeautifulSoup
import json

def get_soup(url):
    response = requests.get(url)
    return BeautifulSoup(response.text, 'html.parser')

soup_en = get_soup('https://hh-japaneeds.com/schools/')
soup_ja = get_soup('https://hh-japaneeds.com/ja/schools/')

schools = []

# To match EN and JA tables, we'll extract them in order
elements_en = soup_en.find_all(['h2', 'h3', 'table'])
elements_ja = soup_ja.find_all(['h2', 'h3', 'table'])

tables_en_with_context = []
current_macro = "Unknown"
current_pref = "Unknown"

for elem in elements_en:
    if elem.name == 'h2':
        text = elem.text.strip()
        if "region" in text or text in ["Hokkaido", "Kyushu and Okinawa"]:
            current_macro = text.replace(' region', '').strip()
            current_pref = current_macro
    elif elem.name == 'h3':
        current_pref = elem.text.strip().replace(' Prefecture', '').strip()
    elif elem.name == 'table':
        tables_en_with_context.append({
            'macro': current_macro.upper(),
            'pref': current_pref.upper(),
            'table': elem
        })

tables_ja_list = soup_ja.find_all('table')

for i in range(min(len(tables_en_with_context), len(tables_ja_list))):
    context = tables_en_with_context[i]
    table_en = context['table']
    table_ja = tables_ja_list[i]
    
    rows_en = table_en.find_all('tr')
    rows_ja = table_ja.find_all('tr')
    
    for j in range(min(len(rows_en), len(rows_ja))):
        tds_en = rows_en[j].find_all(['td', 'th'])
        tds_ja = rows_ja[j].find_all(['td', 'th'])
        
        if len(tds_en) >= 2 and tds_en[0].name == 'td':
            area_en = tds_en[0].text.strip()
            name_en = tds_en[1].text.strip()
            
            a_en = tds_en[1].find('a')
            website = a_en['href'] if a_en and 'href' in a_en.attrs else ""
            
            area_ja = tds_ja[0].text.strip() if len(tds_ja) >= 2 else area_en
            name_jp = tds_ja[1].text.strip() if len(tds_ja) >= 2 else name_en
            
            schools.append({
                "macro_region": context['macro'],
                "prefecture": context['pref'],
                "area": area_en,
                "area_ja": area_ja,
                "name_en": name_en,
                "name_jp": name_jp,
                "website": website
            })

data = {
    "schools": schools
}

with open('schools_data.json', 'w', encoding='utf-8') as f:
    json.dump(data, f, ensure_ascii=False, indent=4)

print(f"Crawled {len(schools)} schools.")
