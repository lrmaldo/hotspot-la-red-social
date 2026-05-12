import re

with open("resources/views/livewire/portal/carrusel-campanas.blade.php", "r", encoding="utf-8") as f:
    text = f.read()

# 1. Update wrapper
text = text.replace(
    "<div class=\"portal-wrapper\">\n        <div class=\"portal-container\" x-data=\"{ showAd: false }\">",
    "<div class=\"portal-wrapper\" x-data=\"{ showAd: false }\">\n        <div class=\"portal-container\">"
)
text = text.replace(
    "<div class=\"portal-wrapper\">\r\n        <div class=\"portal-container\" x-data=\"{ showAd: false }\">",
    "<div class=\"portal-wrapper\" x-data=\"{ showAd: false }\">\r\n        <div class=\"portal-container\">"
)

# 2. Extract modal
start_marker = "<!-- ESTADO ACTIVO: REPRODUCTOR DE VIDEO PUBLICITARIO"
idx_start = text.find(start_marker)
idx_end = text.find("Determinar los segundos de cuenta regresiva para el anuncio")

if idx_start != -1 and idx_end != -1:
    block_pattern = r"(<!-- ESTADO ACTIVO: REPRODUCTOR DE VIDEO PUBLICITARIO .*?@endif)"
    match = re.search(block_pattern, text[idx_start:idx_end+100], re.DOTALL)
    if match:
        modal_block = match.group(1)
        text = text[:idx_start] + text[idx_start + len(modal_block):]
        
        idx_ins = text.find("<!-- Script MD5")
        if idx_ins != -1:
            text = text[:idx_ins] + "\n        " + modal_block + "\n\n" + text[idx_ins:]
        
        with open("resources/views/livewire/portal/carrusel-campanas.blade.php", "w", encoding="utf-8") as f:
            f.write(text)
        print("Done!")
    else:
        print("Block not found match")
else:
    print("Markers not found")
