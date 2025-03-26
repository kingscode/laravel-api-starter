#!/bin/bash

set -e  # Stop bij fouten

# Standaardmap
DEFAULT_FRONTEND_DIR="portal"

# Vraag waar de frontend moet komen
read -p "Waar wil je de frontend plaatsen? (default: portal): " FRONTEND_DIR
FRONTEND_DIR=${FRONTEND_DIR:-$DEFAULT_FRONTEND_DIR}

# Controleer of de map al bestaat
if [ -d "$FRONTEND_DIR" ]; then
    echo "❌ Fout: Map '$FRONTEND_DIR' bestaat al. Kies een andere naam of verwijder de map."
    exit 1
fi

# Voeg de Vue starter toe als subtree
echo "🚀 Nieuwe frontend aanmaken in '$FRONTEND_DIR' vanuit kingscode-vue-starter..."
git subtree add --prefix="$FRONTEND_DIR" git@github.com:kingscode/kingscode-vue-starter.git main --squash

# Vraag naar entities
read -p "Wil je standaard entities aanmaken? (bijv. product, order, customer) [leave empty to skip]: " ENTITIES

if [ -n "$ENTITIES" ]; then
    ENTITY_DIR="$FRONTEND_DIR/entities"
    mkdir -p "$ENTITY_DIR"

    IFS=',' read -ra ENTITY_LIST <<< "$ENTITIES"  # Splits de input op komma's

    for ENTITY in "${ENTITY_LIST[@]}"; do
        ENTITY_TRIMMED=$(echo "$ENTITY" | xargs)  # Verwijder spaties
        ENTITY_PATH="$ENTITY_DIR/$ENTITY_TRIMMED"

        mkdir -p "$ENTITY_PATH"

        CONFIG_FILE="$ENTITY_PATH/config.ts"

        # Maak een standaard config.ts bestand aan
        cat <<EOL > "$CONFIG_FILE"
export default {
  icon: "mdi-cube-outline",
  fields: {
    name: {
      type: "string",
      required: true,
    },
  },
  actions: {
    create: true,
    import: false,
  },
  crudActions: {
    delete: true,
    edit: true,
  },
};
EOL

        echo "✅ Entity '$ENTITY_TRIMMED' aangemaakt in '$CONFIG_FILE'"
    done
fi

# Bevestiging
echo "✅ Frontend succesvol toegevoegd in '$FRONTEND_DIR'."
echo "ℹ️  Gebruik './bin/update-frontend.sh' om later updates binnen te halen."
