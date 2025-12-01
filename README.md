# NeuroBlock 🧠

**Extension WordPress IA pour générer des blocs et pages personnalisés**

Version: 1.0.0  
Auteur: Papyrus - Starlight Pro Agency  
License: GPL v2 or later

---

## 📋 Description

NeuroBlock est une extension WordPress moderne et puissante qui vous permet de générer automatiquement des pages et blocs personnalisés pour Gutenberg et Elementor à l'aide de modèles d'intelligence artificielle.

### ✨ Caractéristiques principales

- 🎨 **Génération automatique de contenu** : Créez des blocs Gutenberg et widgets Elementor en quelques secondes
- 🔒 **100% Sécurisé** : Vos clés API sont chiffrées avec les salts WordPress
- 💰 **Totalement gratuit** : Utilisez votre propre API IA (pas de frais d'abonnement)
- 🌐 **Multi-plateformes** : Compatible avec OpenAI, DeepSeek, Google Gemini, et Ollama (local)
- 🎯 **Interface moderne** : Dashboard élégant et facile à utiliser
- 🚀 **Optimisé** : Code léger et performant

---

## 🚀 Installation

### Installation manuelle

1. Téléchargez le fichier ZIP du plugin
2. Allez dans **Extensions → Ajouter**
3. Cliquez sur **Téléverser une extension**
4. Sélectionnez le fichier ZIP et cliquez sur **Installer maintenant**
5. Activez l'extension

### Installation via FTP

1. Décompressez le fichier ZIP
2. Uploadez le dossier `neuroblock` dans `/wp-content/plugins/`
3. Activez l'extension depuis le menu Extensions de WordPress

---

## ⚙️ Configuration

### 1. Obtenir une clé API

#### OpenAI
1. Créez un compte sur [platform.openai.com](https://platform.openai.com)
2. Allez dans **API Keys**
3. Créez une nouvelle clé secrète
4. Copiez la clé (format: `sk-...`)

#### DeepSeek
1. Créez un compte sur [platform.deepseek.com](https://platform.deepseek.com)
2. Générez une clé API
3. Copiez la clé

#### Google Gemini
1. Créez un compte sur [makersuite.google.com](https://makersuite.google.com)
2. Obtenez une clé API
3. Copiez la clé

#### Ollama (Local - GRATUIT)
1. Installez [Ollama](https://ollama.ai) sur votre machine
2. Téléchargez un modèle : `ollama pull llama2`
3. Aucune clé API nécessaire !

### 2. Configurer NeuroBlock

1. Allez dans **NeuroBlock** dans le menu WordPress
2. Sélectionnez votre fournisseur IA
3. Entrez votre clé API (sauf pour Ollama)
4. Choisissez le modèle
5. Cliquez sur **Enregistrer les paramètres**

---

## 🎯 Utilisation

### Générer un bloc Gutenberg

1. Allez dans l'onglet **Générateur**
2. Décrivez ce que vous voulez créer
3. Sélectionnez **Bloc Gutenberg** comme type
4. Choisissez un style (Moderne, Minimaliste, etc.)
5. Cliquez sur **Générer avec IA**
6. Le code HTML/CSS est généré automatiquement
7. Copiez-collez dans un bloc HTML personnalisé

### Générer une page complète

1. Allez dans l'onglet **Générateur**
2. Décrivez votre page (ex: "Landing page pour une application mobile")
3. Sélectionnez **Page complète**
4. Générez et utilisez le code

### Exemples de prompts

**Bloc Hero Section:**
```
Créer une section hero moderne avec un titre accrocheur "Révolutionnez votre business", 
un sous-titre, et un bouton CTA violet. Design minimaliste avec dégradé de fond.
```

**Pricing Table:**
```
Créer un tableau de prix avec 3 colonnes (Starter, Pro, Enterprise), 
incluant les prix, listes de fonctionnalités, et boutons d'action. Style professionnel.
```

**Contact Form:**
```
Créer un formulaire de contact élégant avec champs nom, email, sujet et message. 
Inclure validation visuelle et bouton d'envoi avec effet hover.
```

---

## 📁 Structure des fichiers

```
neuroblock/
│
├── neuroblock.php                      # Fichier principal
├── README.md                           # Documentation
│
├── assets/
│   ├── css/
│   │   ├── neuroblock-admin.css       # Styles admin
│   │   ├── neuroblock-blocks.css      # Styles blocs frontend
│   │   └── neuroblock-blocks-editor.css # Styles éditeur Gutenberg
│   │
│   └── js/
│       ├── neuroblock-admin.js        # Scripts admin
│       └── neuroblock-blocks.js       # Scripts blocs Gutenberg
│
└── includes/
    ├── class-neuroblock-admin.php     # Interface admin
    ├── class-neuroblock-api.php       # Gestion API IA
    ├── class-neuroblock-blocks.php    # Blocs Gutenberg
    └── class-neuroblock-security.php  # Sécurité et chiffrement
```

---

## 🔒 Sécurité

NeuroBlock prend la sécurité au sérieux :

- ✅ Chiffrement AES-256 des clés API
- ✅ Utilisation des salts WordPress
- ✅ Vérification des nonces pour toutes les requêtes AJAX
- ✅ Sanitisation de toutes les entrées utilisateur
- ✅ Aucune donnée stockée sur des serveurs tiers
- ✅ Code conforme aux standards WordPress

---

## 🛠️ Développement

### Prérequis

- PHP 7.4+
- WordPress 5.8+
- Extension `openssl` PHP activée

### Hooks disponibles

```php
// Filtrer le prompt avant l'appel API
add_filter('neuroblock_prompt', function($prompt, $type, $style) {
    return $prompt . "\nUtiliser des couleurs vives.";
}, 10, 3);

// Action après génération réussie
add_action('neuroblock_content_generated', function($content, $type) {
    // Votre code ici
}, 10, 2);
```

---

## ❤️ Support et Donations

NeuroBlock est **gratuit et open source**. Si vous trouvez ce plugin utile, vous pouvez soutenir le développement :

### Cryptomonnaies acceptées

**Bitcoin (BTC)**
```
bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh
```

**Monero (XMR)**
```
4AdUndXHHZ6cfufTMvppY6JwXNouMBzSkbLYfpAV5Usx3skxNgYeYTRj5UzqtReoS44qo9mtmXCqY45DJ852K5Jv2684Rge
```

**Tether (USDT)**
```
0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb5
```

---

## 📝 Changelog

### Version 1.0.0 (2025-01-15)
- 🎉 Lancement initial
- ✨ Support OpenAI, DeepSeek, Gemini, Ollama
- 🎨 Interface admin moderne
- 🔒 Chiffrement des clés API
- 📦 Blocs Gutenberg
- 🚀 Générateur de contenu IA

---

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Forkez le projet
2. Créez une branche (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add AmazingFeature'`)
4. Pushez (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

---

## 📄 License

Ce projet est sous licence GPL v2 or later.

---

## 🔗 Liens utiles

- **Site web**: https://starlightproagency.com
- **Support**: contact@starlightproagency.com
- **Documentation**: https://starlightproagency.com/neuroblock/docs

---

Développé avec ❤️ par **Papyrus** - Starlight Pro Agency