# Jenkins + Webhook GitHub (Local)

## 1) Prerequis Jenkins
- Jenkins local installe.
- Plugins Jenkins:
  - `Pipeline`
  - `Git`
  - `GitHub`
- Outils disponibles sur le noeud Jenkins:
  - `php` (8.2)
  - `composer`
  - `docker`

## 2) Creer le job Jenkins
- `New Item` -> `Pipeline` -> nom: `isi-burger-pipeline`.
- Dans `Pipeline`, choisir `Pipeline script from SCM`.
- `SCM`: `Git`.
- Repository URL: votre repo GitHub.
- Branch Specifier: `*/nom_prenom_burger`.
- Script Path: `Jenkinsfile`.
- Sauvegarder.

## 3) Activer le trigger webhook cote Jenkins
- Ouvrir le job -> `Configure`.
- Cocher `GitHub hook trigger for GITScm polling`.
- Sauvegarder.

## 4) Rendre Jenkins local accessible depuis GitHub
GitHub doit atteindre Jenkins via internet.

Exemple avec `ngrok`:
```bash
ngrok http 8080
```

Recuperer l URL publique generee, par exemple:
`https://abcd-1234.ngrok-free.app`

## 5) Configurer le webhook cote GitHub
- Repo GitHub -> `Settings` -> `Webhooks` -> `Add webhook`
- Payload URL:
  - `https://abcd-1234.ngrok-free.app/github-webhook/`
- Content type: `application/json`
- Events:
  - `Just the push event`
- Active: coche

Puis `Add webhook`.

## 6) Test de bout en bout
- Faire un push sur la branche `Marieme_Ndiaye_burger`.
- Verifier dans Jenkins que le build se declenche automatiquement.
- Verifier les stages:
  - Checkout depuis GitHub
  - Install dependances Laravel
  - Prepare app Laravel
  - Migrations
  - Build image Docker

## 7) Remarques
- Si le repo est prive, configurez les credentials Git dans Jenkins.
- Si Docker n est pas accessible dans Jenkins, ajoutez l utilisateur Jenkins au groupe `docker`.
