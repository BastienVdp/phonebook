## Informations
- Title:  `Phonebook`
- Authors:  `neitsab`

## Installation
- Clone the projet using git
- Run `composer install`
- Run `npm install`
- Configure the app/config.php 
- Run migrations by executing `php database/index.php (--seed| --refresh)`
- Run `npm run dev` for compiling assets (js, scss) only for development 

## Pattern 
- All controllers in the app/Controllers folder
- All actions in the app/Actions folder
- All models in the app/Models folder
- All migrations in the database/migrations folder
- All factories in the database/factories folder (+ add your factories in the database/seeders/DatabaseSeeder)
- All routes in the routes folder
- All assets in the resources folder
- All views in the resources/views folder


## Directory Hierarchy
```
|—— app
|    |—— Actions
|        |—— Auth
|            |—— LoginAction.php
|            |—— RegisterAction.php
|        |—— Contact
|            |—— AddContactAction.php
|            |—— UpdateContactAction.php
|        |—— User
|            |—— UpdateInformationsAction.php
|            |—— UpdatePasswordAction.php
|    |—— config.php
|    |—— Controllers
|        |—— Api
|            |—— AuthController.php
|            |—— ContactsController.php
|            |—— ProfileController.php
|        |—— SiteController.php
|    |—— Core
|        |—— Application.php
|        |—— Controller.php
|        |—— Database.php
|        |—— Factory.php
|        |—— Middleware.php
|        |—— Migration.php
|        |—— Model.php
|        |—— Request.php
|        |—— Response.php
|        |—— Router.php
|        |—— Runner.php
|        |—— Session.php
|        |—— Validation.php
|        |—— View.php
|    |—— Middlewares
|        |—— AuthMiddleware.php
|        |—— VerifyTokenMiddleware.php
|    |—— Models
|        |—— Contact.php
|        |—— User.php
|    |—— Services
|        |—— FileUploader.php
|    |—— Trait
|        |—— RecoveryPassword.php
|        |—— Relationship.php
|—— database
|    |—— factories
|        |—— ContactFactory.php
|        |—— UserFactory.php
|    |—— index.php
|    |—— migrations
|        |—— m001_create_users_table.php
|        |—— m002_create_contacts_table.php
|    |—— seeders
|        |—— DatabaseSeeder.php
|—— public
|    |—— .htaccess
|    |—— assets
|        |—— css
|            |—— index.css
|        |—— fonts
|            |—— Goldplay-Bold.woff2
|            |—— Goldplay-Light.woff2
|            |—— Goldplay-Medium.woff2
|            |—— Goldplay-Regular.woff2
|            |—— Goldplay-SemiBold.woff2
|            |—— Goldplay-Thin.woff2
|        |—— js
|            |—— index.js
|    |—— images
|        |—— contacts
|            |—— 6556066ceddc8_image.jpg
|            |—— 655606c403175_image.jpg
|            |—— 6556070940f47_image.jpg
|            |—— 655607208cbb4_image.jpg
|            |—— 655609f89cd5e.png
|            |—— 655658c8638bf.png
|            |—— 6558859e8a56d.png
|            |—— 655885a94a314.png
|            |—— default.png
|    |—— index.php
|—— resources
|    |—— js
|        |—— app
|            |—— application.js
|            |—— components
|                |—— Categories.js
|                |—— Contacts.js
|                |—— Header.js
|                |—— Search.js
|                |—— Toast.js
|                |—— Transition.js
|            |—— core
|                |—— Component.js
|                |—— Page.js
|            |—— pages
|                |—— addContact.js
|                |—— home.js
|                |—— login.js
|                |—— profile.js
|                |—— register.js
|                |—— showContact.js
|            |—— utils
|                |—— axios.js
|        |—— index.js
|    |—— sass
|        |—— base
|            |—— index.scss
|            |—— _fonts.scss
|            |—— _global.scss
|            |—— _reset.scss
|        |—— components
|            |—— index.scss
|            |—— _categories.scss
|            |—— _contacts.scss
|            |—— _form.scss
|            |—— _header.scss
|            |—— _searchbar.scss
|            |—— _toast.scss
|        |—— index.scss
|        |—— pages
|            |—— index.scss
|            |—— _add.scss
|            |—— _login.scss
|            |—— _profile.scss
|            |—— _register.scss
|            |—— _show.scss
|        |—— utils
|            |—— mixins.scss
|            |—— variables.scss
|    |—— views
|        |—— components
|            |—— Form.php
|        |—— layouts
|            |—— default.php
|        |—— HomeView.php
|        |—— AddContactView.php
|        |—— LoginView.php
|        |—— ProfileView.php
|        |—— RegisterView.php
|        |—— ShowContactView.php
|—— routes
|    |—— api.php
|    |—— web.php
|—— composer.json
|—— composer.lock
|—— webpack.mix.js
|—— mix-manifest.json
|—— package-lock.json
|—— package.json
```
