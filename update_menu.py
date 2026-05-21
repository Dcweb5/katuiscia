import glob
import re

html_files = glob.glob('*.html')

for f in html_files:
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
    
    # 1. Remove the NOS VALEURS from left nav and restore CONSEILS
    # The block we inserted was:
    #                     <div class="header__dropdown">
    #             <a href="#" class="header__nav-link">NOS VALEURS</a>
    #             <div class="header__dropdown-content header__dropdown-content--mega">
    #               <span class="dropdown-heading">Nos Engagements</span>
    #               <a href="#">À propos de la beauté responsable</a>
    #               <a href="#">À propos de l'emballage durable</a>
    #               <a href="#">À propos de la personne &amp; la biodiversité</a>
    #               <div class="dropdown-divider"></div>
    #               <span class="dropdown-heading">Notre Vision</span>
    #               <a href="#">Un avenir plus beau</a>
    #             </div>
    #           </div>
    # We will just replace this block with <a href="#" class="header__nav-link">CONSEILS</a>
    # Since indentation might vary, we use regex.
    left_pattern = re.compile(r'<div class="header__dropdown">\s*<a href="#" class="header__nav-link">NOS VALEURS</a>\s*<div class="header__dropdown-content header__dropdown-content--mega">.*?</div>\s*</div>', re.DOTALL)
    
    if left_pattern.search(content):
        content = left_pattern.sub('<a href="#" class="header__nav-link">CONSEILS</a>', content)
    
    # 2. Add NOS VALEURS to the right nav
    # Find:
    #         <nav class="header__nav-group header__nav-group--right" aria-label="Navigation droite">
    #           <div class="header__dropdown">
    #             <a href="#" class="header__nav-link">SERVICES &amp; BOUTIQUES</a>
    #             <div class="header__dropdown-content">
    #               <a href="formation.html">Formation</a>
    #               <a href="grossiste.html">Devenir Grossiste</a>
    #             </div>
    #           </div>
    #           </nav> (or </nav>)
    # We append the NOS VALEURS block before </nav>
    
    nos_valeurs_html = '''          <div class="header__dropdown">
            <a href="#" class="header__nav-link">NOS VALEURS</a>
            <div class="header__dropdown-content header__dropdown-content--mega">
              <span class="dropdown-heading">Nos Engagements</span>
              <a href="#">À propos de la beauté responsable</a>
              <a href="#">À propos de l'emballage durable</a>
              <a href="#">À propos de la personne &amp; la biodiversité</a>
              <span class="dropdown-heading" style="margin-top: 15px;">Notre Vision</span>
              <a href="#">Un avenir plus beau</a>
            </div>
          </div>
'''
    # Wait, some files have `</nav>` on a new line, some might not.
    # Let's insert it after the SERVICES & BOUTIQUES dropdown.
    # Find the closing </div> of the SERVICES & BOUTIQUES dropdown, inside the right nav.
    right_pattern = re.compile(r'(<div class="header__dropdown">\s*<a href="#" class="header__nav-link">SERVICES &amp; BOUTIQUES</a>\s*<div class="header__dropdown-content">\s*<a href="formation.html">Formation</a>\s*<a href="grossiste.html">Devenir Grossiste</a>\s*</div>\s*</div>)')
    
    if right_pattern.search(content):
        content = right_pattern.sub(r'\1\n' + nos_valeurs_html, content)


    # 3. Mobile menu update
    # Remove Nos Valeurs from between Maison and Contact
    mobile_valeurs_pattern = re.compile(r'<div class="mobile-menu__dropdown-wrapper">\s*<span class="mobile-menu__link">Nos Valeurs</span>\s*<div class="mobile-menu__dropdown-content">.*?</div>\s*</div>', re.DOTALL)
    
    if mobile_valeurs_pattern.search(content):
        content = mobile_valeurs_pattern.sub('<a href="#" class="mobile-menu__link">Conseils</a>', content)
        
    # Append Nos Valeurs after Services & Boutiques
    mobile_services_pattern = re.compile(r'(<a href="#" class="mobile-menu__link">Services &amp; Boutiques</a>)')
    
    mobile_valeurs_html = '''    <div class="mobile-menu__dropdown-wrapper">
      <span class="mobile-menu__link">Nos Valeurs</span>
      <div class="mobile-menu__dropdown-content">
        <span class="mobile-dropdown-heading">Nos Engagements</span>
        <a href="#" class="mobile-dropdown-link">À propos de la beauté responsable</a>
        <a href="#" class="mobile-dropdown-link">À propos de l'emballage durable</a>
        <a href="#" class="mobile-dropdown-link">À propos de la personne &amp; la biodiversité</a>
        <span class="mobile-dropdown-heading">Notre Vision</span>
        <a href="#" class="mobile-dropdown-link">Un avenir plus beau</a>
      </div>
    </div>'''
    
    if mobile_services_pattern.search(content):
        content = mobile_services_pattern.sub(r'\1\n' + mobile_valeurs_html, content)

    with open(f, 'w', encoding='utf-8') as file:
        file.write(content)

print("HTML files updated.")
