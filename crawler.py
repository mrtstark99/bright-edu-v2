import requests
from bs4 import BeautifulSoup
import json
import re

def get_tables(url):
    response = requests.get(url)
    soup = BeautifulSoup(response.text, 'html.parser')
    return soup.find_all('table')

tables_en = get_tables('https://hh-japaneeds.com/schools/')
tables_ja = get_tables('https://hh-japaneeds.com/ja/schools/')

regions = set()
schools = []

print(f"EN tables: {len(tables_en)}, JA tables: {len(tables_ja)}")

for i in range(min(len(tables_en), len(tables_ja))):
    table_en = tables_en[i]
    table_ja = tables_ja[i]
    
    region_name = "Unknown"
    # First try to get it from table headers
    ths = table_en.find_all('th')
    if len(ths) >= 2:
        th2 = ths[1].text.strip()
        m = re.search(r'in\s+(.*?)(?:\s+Prefecture)?$', th2, re.IGNORECASE)
        if m:
            region_name = m.group(1).strip()
            
    if region_name == "Unknown" or "Japanese" in region_name:
        # Fallback to closest h2/h3
        prev = table_en.find_previous_sibling(['h2', 'h3'])
        if not prev and table_en.parent:
            prev = table_en.parent.find_previous_sibling(['h2', 'h3'])
        if prev:
            region_name = prev.text.strip().replace(' region', '').replace(' Prefecture', '').strip()

    region_name = region_name.upper()
    regions.add(region_name)
    
    rows_en = table_en.find_all('tr')
    rows_ja = table_ja.find_all('tr')
    
    for j in range(min(len(rows_en), len(rows_ja))):
        tds_en = rows_en[j].find_all(['td', 'th'])
        tds_ja = rows_ja[j].find_all(['td', 'th'])
        
        # Skip header rows where we see "Area" / "地域名"
        if len(tds_en) >= 2 and tds_en[0].name == 'td':
            area_en = tds_en[0].text.strip()
            name_en = tds_en[1].text.strip()
            
            a_en = tds_en[1].find('a')
            website = a_en['href'] if a_en and 'href' in a_en.attrs else ""
            
            area_ja = tds_ja[0].text.strip() if len(tds_ja) >= 2 else area_en
            name_jp = tds_ja[1].text.strip() if len(tds_ja) >= 2 else name_en
            
            schools.append({
                "region": region_name,
                "area": area_en,
                "area_ja": area_ja,
                "name_en": name_en,
                "name_jp": name_jp,
                "website": website
            })

data = {
    "regions": sorted(list(regions)),
    "schools": schools
}

with open('schools_data.json', 'w', encoding='utf-8') as f:
    json.dump(data, f, ensure_ascii=False, indent=4)

print(f"Crawled {len(schools)} schools in {len(regions)} regions.")
