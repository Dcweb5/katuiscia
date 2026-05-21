import glob
import re

html_files = glob.glob('*.html')

for f in html_files:
    if f in ['connexion.html', 'inscription.html', 'compte.html', 'compte-commandes.html', 'compte-retours.html', 'compte-recompenses.html', 'compte-avis.html', 'admin.html', 'admin-produits.html', 'admin-commandes.html', 'admin-utilisateurs.html', 'admin-funnels.html']:
        continue
        
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
    
    # Replace button with a tag for Mon compte
    pattern = re.compile(r'<button class="header__action-btn" aria-label="Mon compte">(.*?)</button>', re.DOTALL)
    
    if pattern.search(content):
        content = pattern.sub(r'<a href="connexion.html" class="header__action-btn" aria-label="Mon compte">\1</a>', content)
        
        with open(f, 'w', encoding='utf-8') as file:
            file.write(content)
        print(f"Updated {f}")

print("Done updating auth links.")
