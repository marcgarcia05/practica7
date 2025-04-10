# Pràctica 7 - Sistema de Gestió d'Articles

Un sistema de gestió de contingut desenvolupat amb Laravel que permet administrar articles i usuaris.

## 📋 Descripció

Aquest projecte és una aplicació web basada en Laravel que proporciona una plataforma per a la gestió d'articles i usuaris. Inclou un sistema complet d'autenticació, rols d'usuari i administració de contingut.

## ✨ Característiques

- 🔐 Sistema d'autenticació complet (registre, inici de sessió, recuperació de contrasenya)
- 👥 Gestió d'usuaris amb diferents rols
- 📝 CRUD complet per a articles
- 🖥️ Panell d'administració
- 🎨 Interfície responsive i amigable

## 🛠️ Tecnologies Utilitzades

- [Laravel](https://laravel.com/) - Framework PHP
- [MySQL](https://www.mysql.com/) (o SQLite per a desenvolupament)
- HTML, CSS, JavaScript
- [Bootstrap](https://getbootstrap.com/) (si s'utilitza)

## 🚀 Instal·lació

Segueix aquests passos per configurar el projecte localment:

1. **Clonar el repositori**
   ```bash
   git clone https://github.com/marcgarcia05/practica7.git
   cd practica7
   ```

2. **Instal·lar dependències**
   ```bash
   composer install
   npm install
   ```

3. **Configurar l'entorn**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar la base de dades**
   
   Edita l'arxiu `.env` amb les teves credencials de base de dades:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=practica7
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Migrar i sembrar la base de dades**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compilar assets (si és necessari)**
   ```bash
   npm run dev
   ```

7. **Iniciar servidor de desenvolupament**
   ```bash
   php artisan serve
   ```

   L'aplicació estarà disponible a: [http://localhost:8000](http://localhost:8000)

## 📊 Estructura de la Base de Dades

El sistema utilitza principalment dos models principals:

- **Usuaris**: Gestiona la informació d'usuaris i autenticació
- **Articles**: Administra el contingut dels articles

## 👥 Rols d'Usuari

- **Administrador**: Gestió completa d'usuaris i articles
- **Usuari**: Gestió dels seus propis articles

## 🔒 Seguretat

L'aplicació implementa pràctiques de seguretat estàndard de Laravel:
- Protecció CSRF
- Autenticació segura
- Validació de dades

## 🤝 Contribució

Si vols contribuir al projecte:

1. Fes un fork del repositori
2. Crea una branca per a la teva funció (`git checkout -b feature/nova-funcio`)
3. Fes commit dels teus canvis (`git commit -m 'Afegir nova funcio'`)
4. Fes push a la branca (`git push origin feature/nova-funcio`)
5. Obre un Pull Request

## 📝 Llicència

Aquest projecte està sota la Llicència MIT - veure l'arxiu [LICENSE](LICENSE) per a més detalls.

## 📧 Contacte

Marc García - [Perfil de GitHub](https://github.com/marcgarcia05)

Enllaç del projecte: [https://github.com/marcgarcia05/practica7](https://github.com/marcgarcia05/practica7)
