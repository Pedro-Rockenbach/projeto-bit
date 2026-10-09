from urllib.request import Request, urlopen
from bs4 import BeautifulSoup
import questionary
import maria


url = "https://www.inf.unioeste.br/sitecomp/?page_id=55"

req = Request(
    url, headers={"User-Agent": "Mozilla/5.0 (compatible; MyWikipediaParser/1.0)"}
)

html = urlopen(req).read()
bs = BeautifulSoup(html, "html.parser")

table = bs.find("table")

names = []

for row in table.find_all("tr"):
    td = row.find("td")
    if td is None:
        continue
    materia = td.get_text(strip=True)
    if materia:
        names.append(materia)

print("================= Web scrapper v1 =================")
print()

conn = maria.conn()

opcao = questionary.checkbox(
    "Selecione as materias que deseja inserir:",
    choices=names
).ask()

maria.insert(conn, opcao)
