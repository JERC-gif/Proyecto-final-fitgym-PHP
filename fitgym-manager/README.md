# FitGym Manager

## Descripción

Sistema de gestión para gimnasios desarrollado con Laravel 12, Jetstream y Livewire. Permite la administración de usuarios, membresías y el control de acceso basado en roles (admin, staff, cliente).

### Características principales

- **Autenticación**: Sistema de autenticación con Jetstream (Livewire)
- **Control de acceso por roles**: Admin, Staff y Cliente con permisos diferenciados
- **Gestión de usuarios**: CRUD completo de usuarios (solo admin)
- **Gestión de membresías**: CRUD de membresías con tipos predefinidos (Visita, Semana, Mes)
- **Membresías de usuarios**: Visualización y gestión de membresías asignadas a usuarios
- **Panel administrativo**: Dashboard con estadísticas y gestión centralizada
- **Confirmaciones interactivas**: SweetAlert2 para acciones críticas

## Requisitos

- PHP >= 8.2
- Composer
- Node.js y NPM
- Base de datos (MySQL, PostgreSQL o SQLite)

## Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd fitgym-manager
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar el archivo .env

```bash
cp .env.example .env
php artisan key:generate
```

Editar el archivo `.env` y configurar:

```env
APP_NAME="FitGym Manager"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fitgym_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Este comando creará:
- Las tablas de la base de datos
- Usuarios de prueba (admin, staff, clientes)
- Membresías predefinidas (Visita, Semana, Mes)
- Asignaciones de membresías a usuarios de prueba

### 5. Compilar assets (opcional)

```bash
npm install
npm run build
```

### 6. Iniciar el servidor

```bash
php artisan serve
```

El sistema estará disponible en: `http://localhost:8000`

## Usuarios de prueba

### Administrador
- **Email**: `admin@gym.test`
- **Contraseña**: `password`
- **Permisos**: Acceso completo al sistema

### Staff
- **Email**: `staff@gym.test`
- **Contraseña**: `password`
- **Permisos**: Ver membresías de usuarios, editar membresías

### Cliente
- **Email**: `client@gym.test`
- **Contraseña**: `password`
- **Permisos**: Ver y gestionar su propia membresía

### Clientes adicionales
- `cliente1@gym.test` / `password`
- `cliente2@gym.test` / `password`
- `cliente3@gym.test` / `password`

## Estructura del proyecto

```
fitgym-manager/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Admin/          # Controladores del panel admin
│   └── Models/                 # Modelos Eloquent
├── database/
│   ├── migrations/             # Migraciones de base de datos
│   └── seeders/                # Seeders para datos de prueba
├── resources/
│   └── views/
│       ├── admin/               # Vistas del panel administrativo
│       └── layouts/            # Layouts principales
└── routes/
    └── web.php                 # Rutas de la aplicación
```

## Roles y permisos

### Admin
- Gestión completa de usuarios (crear, editar, eliminar)
- Gestión completa de membresías (crear, editar, eliminar)
- Ver membresías de todos los usuarios
- Acceso al panel administrativo

### Staff
- Ver membresías de usuarios
- Ver catálogo de membresías
- Editar membresías (con confirmación)

### Cliente
- Ver su propia membresía
- Adquirir nuevas membresías
- Cancelar su membresía activa

## Tecnologías utilizadas

- **Laravel 12**: Framework PHP
- **Jetstream**: Autenticación y scaffolding
- **Livewire**: Componentes interactivos
- **Tailwind CSS**: Framework CSS
- **Flowbite**: Componentes UI para Tailwind
- **SweetAlert2**: Alertas y confirmaciones

## Comandos útiles

```bash
# Limpiar caché
php artisan optimize:clear

# Ejecutar solo seeders
php artisan db:seed

# Crear un nuevo usuario admin
php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role' => 'admin', 'is_active' => true]);
```
