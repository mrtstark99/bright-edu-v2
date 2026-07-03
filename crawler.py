import requests
from bs4 import BeautifulSoup
import json
import re

url = 'https://hh-japaneeds.com/schools/'
response = requests.get(url)
soup = BeautifulSoup(response.text, 'html.parser')

regions = set()
schools = []

for table in soup.find_all('table'):
    region_name = "Unknown"
    # First try to get it from table headers
    ths = table.find_all('th')
    if len(ths) >= 2:
        th2 = ths[1].text.strip()
        m = re.search(r'in\s+(.*?)(?:\s+Prefecture)?$', th2, re.IGNORECASE)
        if m:
            region_name = m.group(1).strip()
            
    if region_name == "Unknown" or "Japanese" in region_name:
        # Fallback to closest h2/h3
        prev = table.find_previous_sibling(['h2', 'h3'])
        if not prev and table.parent:
            prev = table.parent.find_previous_sibling(['h2', 'h3'])
        if prev:
            region_name = prev.text.strip().replace(' region', '').replace(' Prefecture', '').strip()

    region_name = region_name.upper()
    regions.add(region_name)
    
    for tr in table.find_all('tr'):
        tds = tr.find_all('td')
        if len(tds) >= 2:
            area = tds[0].text.strip()
            name = tds[1].text.strip()
            
            a = tds[1].find('a')
            website = a['href'] if a and 'href' in a.attrs else ""
            
            schools.append({
                "region": region_name,
                "area": area,
                "name_en": name,
                "name_jp": "",
                "tuition_info": "",
                "website": website
            })

data = {
    "regions": sorted(list(regions)),
    "schools": schools
}

with open('schools_data.json', 'w', encoding='utf-8') as f:
    json.dump(data, f, ensure_ascii=False, indent=4)

print(f"Crawled {len(schools)} schools in {len(regions)} regions.")
