#!/bin/bash
# Script de ejemplos de uso de la API v1 con curl
# Reemplaza BASE_URL con tu URL local o remota

BASE_URL="http://localhost:8000/api/v1"

# Colores para la terminal
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}=== API v1 - Ejemplos con curl ===${NC}\n"

# 1. GET - Obtener todos los spots
echo -e "${GREEN}1. Obtener todos los spots${NC}"
echo "curl -X GET $BASE_URL/spots -H 'Accept: application/json'"
curl -X GET "$BASE_URL/spots" -H "Accept: application/json"
echo -e "\n"

# 2. GET - Obtener un spot específico
echo -e "${GREEN}2. Obtener un spot específico (ID=1)${NC}"
echo "curl -X GET $BASE_URL/spots/1 -H 'Accept: application/json'"
curl -X GET "$BASE_URL/spots/1" -H "Accept: application/json"
echo -e "\n"

# 3. POST - Crear un nuevo spot
echo -e "${GREEN}3. Crear un nuevo spot${NC}"
echo "curl -X POST $BASE_URL/spots \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -H 'Accept: application/json' \\"
echo "  -d '{ \"nombre\": \"Montsant\", \"lat\": 41.3500, \"lon\": 1.2500, \"descripcion\": \"Zona rocosa perfecta\", \"nivel\": \"advanced\", \"imagen\": \"https://example.com/montsant.jpg\" }'"
curl -X POST "$BASE_URL/spots" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"nombre":"Montsant","lat":41.3500,"lon":1.2500,"descripcion":"Zona rocosa perfecta","nivel":"advanced","imagen":"https://example.com/montsant.jpg"}'
echo -e "\n"

# 4. PUT - Actualizar un spot (todas las propiedades)
echo -e "${GREEN}4. Actualizar un spot (PUT - todos los campos)${NC}"
echo "curl -X PUT $BASE_URL/spots/1 \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -H 'Accept: application/json' \\"
echo "  -d '{ \"nombre\": \"Siurana Actualizado\", \"lat\": 41.3801, \"lon\": 1.1749, \"descripcion\": \"Actualizado\", \"nivel\": \"expert\" }'"
curl -X PUT "$BASE_URL/spots/1" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"nombre":"Siurana Actualizado","lat":41.3801,"lon":1.1749,"descripcion":"Actualizado","nivel":"expert"}'
echo -e "\n"

# 5. PATCH - Actualizar parcialmente un spot
echo -e "${GREEN}5. Actualizar un spot parcialmente (PATCH - un campo)${NC}"
echo "curl -X PATCH $BASE_URL/spots/2 \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -H 'Accept: application/json' \\"
echo "  -d '{ \"nivel\": \"beginner\" }'"
curl -X PATCH "$BASE_URL/spots/2" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"nivel":"beginner"}'
echo -e "\n"

# 6. DELETE - Eliminar un spot
echo -e "${GREEN}6. Eliminar un spot${NC}"
echo "curl -X DELETE $BASE_URL/spots/3 -H 'Accept: application/json' -v"
curl -X DELETE "$BASE_URL/spots/3" -H "Accept: application/json" -v
echo -e "\n"

# 7. Ejemplo de error de validación (campo faltante)
echo -e "${GREEN}7. Ejemplo de error de validación (nivel inválido)${NC}"
echo "curl -X POST $BASE_URL/spots \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -H 'Accept: application/json' \\"
echo "  -d '{ \"nombre\": \"Test\", \"lat\": 41.0, \"lon\": 1.0, \"descripcion\": \"Test\", \"nivel\": \"invalid_level\" }'"
curl -X POST "$BASE_URL/spots" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"nombre":"Test","lat":41.0,"lon":1.0,"descripcion":"Test","nivel":"invalid_level"}'
echo -e "\n"

echo -e "${BLUE}=== Fin de ejemplos ===${NC}"
