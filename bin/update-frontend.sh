#!/bin/bash

set -e  # Stop bij fouten

# Standaard frontend map
FRONTEND_DIR="portal"

# Controleer of de frontend-map bestaat
if [ ! -d "$FRONTEND_DIR" ]; then
    echo "❌ Fout: Map '$FRONTEND_DIR' bestaat niet. Voer eerst './bin/init-frontend.sh' uit."
    exit 1
fi

# Haal updates op van de Vue starter
echo "🔄 Bijwerken van '$FRONTEND_DIR' vanuit kingscode-vue-starter..."
git subtree pull --prefix="$FRONTEND_DIR" git@github.com:kingscode/kingscode-vue-starter.git main --squash

echo "✅ Frontend is bijgewerkt!"
