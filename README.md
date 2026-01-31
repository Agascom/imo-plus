# Immobilier Gabon API

API REST complète pour une plateforme immobilière au Gabon, développée avec Laravel. Ce projet permet la gestion des utilisateurs, la publication d'annonces immobilières (vente/location), la gestion des favoris et l'upload de photos.

## 🚀 Fonctionnalités

- **Authentification & Utilisateurs**
  - Inscription et Connexion (Tokens sécurisés via Laravel Sanctum).
  - Gestion de profil (Mise à jour infos, photo de profil).
  - Champs spécifiques : Téléphone (+241), Ville, Pays.

- **Gestion des Biens (Annonces)**
  - CRUD complet : Créer, Lire, Mettre à jour, Supprimer.
  - Types de biens : Chambre, Studio, Appartement, Villa, Terrain.
  - Types de transaction : Louer, Vendre.
  - Détails complets : Prix, Fréquence (Mois/Nuit/Total), Quartier, Ville, Commodités (Chambres, SDB, Surface).
  - Compteur de vues (incrémenté à chaque consultation).
  - Filtrage : Par type de transaction, type de bien, recherche textuelle (titre/ville/quartier), statut "en vedette".

- **Multimédia**
  - Upload de photos pour chaque bien.
  - Gestion de l'image principale.

- **Favoris**
  - Ajouter/Retirer un bien des favoris.
  - Lister ses favoris.

## 🛠 Prérequis

- PHP 8.2 ou supérieur
- Composer
- SQLite (activé par défaut)

## 📦 Installation

1. **Cloner le projet**
   ```bash
   git clone <url-du-repo>
   cd immobilier-gabon-api
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configuration de l'environnement**
   Copiez le fichier d'exemple et générez la clé d'application :
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Assurez-vous que la base de données SQLite est configurée dans `.env` :
   ```
   DB_CONNECTION=sqlite
   # Supprimez ou commentez les autres lignes DB_...
   ```

   Créez le fichier de base de données :
   ```bash
   touch database/database.sqlite
   ```

4. **Migrations**
   Créez les tables dans la base de données :
   ```bash
   php artisan migrate
   ```

5. **Lancer le serveur**
   ```bash
   php artisan serve
   ```
   L'API sera accessible sur `http://localhost:8000`.

## 📚 Documentation API

### Authentification

| Méthode | Endpoint | Description | Auth Requise |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Créer un compte | Non |
| `POST` | `/api/login` | Se connecter (Retourne un Token) | Non |
| `POST` | `/api/logout` | Se déconnecter | Oui |
| `GET` | `/api/me` | Récupérer le profil utilisateur | Oui |
| `PUT` | `/api/me` | Mettre à jour le profil | Oui |

**Exemple Body (Register):**
```json
{
    "name": "Jean Mvé",
    "email": "jean@example.com",
    "password": "password123",
    "phone": "+24107000000",
    "city": "Libreville",
    "country": "Gabon"
}
```

### Biens Immobiliers (Properties)

| Méthode | Endpoint | Description | Auth Requise |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/properties` | Liste des annonces (Filtres dispos) | Non |
| `GET` | `/api/properties/{id}` | Détails d'une annonce (+ incrément vues) | Non |
| `POST` | `/api/properties` | Créer une annonce | Oui |
| `PUT` | `/api/properties/{id}` | Modifier une annonce (Propriétaire) | Oui |
| `DELETE` | `/api/properties/{id}` | Supprimer une annonce (Propriétaire) | Oui |
| `POST` | `/api/properties/{id}/images` | Uploader une image | Oui |
| `GET` | `/api/me/properties` | Voir mes annonces | Oui |

**Filtres (Query Params pour GET /api/properties):**
- `transaction_type`: `rent` ou `sale`
- `property_type`: `studio`, `villa`, `apartment`, etc.
- `search`: Recherche texte (Titre, Ville, Quartier)
- `is_featured`: `true` ou `false`

**Exemple Body (Création):**
```json
{
    "title": "Villa Luxueuse Akanda",
    "description": "Belle villa avec piscine...",
    "price": 450000,
    "frequency": "month",
    "transaction_type": "rent",
    "property_type": "villa",
    "city": "Libreville",
    "neighborhood": "Akanda",
    "bedrooms": 4,
    "bathrooms": 3,
    "area": 250
}
```

### Favoris

| Méthode | Endpoint | Description | Auth Requise |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/favorites` | Liste des biens favoris de l'utilisateur | Oui |
| `POST` | `/api/favorites/{id}` | Ajouter/Retirer des favoris (Toggle) | Oui |

## ✅ Tests

Le projet inclut des tests fonctionnels (Feature Tests).

Pour lancer les tests :
```bash
php artisan test
```

## Structure de la Base de Données

- **users**: id, name, email, password, phone, city, country, profile_photo_path...
- **properties**: id, user_id, title, description, price, frequency, transaction_type, property_type, city, neighborhood, bedrooms, bathrooms, area, status, views_count, is_featured...
- **property_images**: id, property_id, image_path, is_primary...
- **favorites**: id, user_id, property_id...

---
Développé avec ❤️ pour l'immobilier au Gabon.
