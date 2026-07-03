# Sistema de Asistencia Médica — Venezuela

Sistema web para el registro de consultas médicas, pacientes, tratamientos y
medicamentos suministrados. Pensado para atención en terreno / emergencias, con
interfaz responsiva (móvil, tablet, escritorio).

## 🚀 Características

- ✅ Registro de consultas médicas
- ✅ Gestión de pacientes y médicos (reutiliza registros por DNI / matrícula)
- ✅ Control de medicamentos suministrados (varios por consulta)
- ✅ Historial de atenciones por fecha
- ✅ Diseño responsivo
- ✅ Protección CSRF y validación del lado del servidor
- ✅ Base de datos PostgreSQL

## 📂 Estructura del repo

- **`laravel-app/`** → aplicación **Laravel 12 + Blade + PostgreSQL** (versión
  recomendada y ya funcional).
- **Raíz** (`index.php`, `procesar.php`, `historial.php`, …) → versión original
  en PHP plano. Se conserva solo como referencia histórica.

## ▶️ Cómo arrancar la app (Laravel)

El entorno ya está instalado en esta máquina (PHP 8.5, Composer 2.10, PostgreSQL 18).

```powershell
cd laravel-app
php artisan serve
```

Luego abrir **http://127.0.0.1:8000**

### Rutas

- `/`                 → landing pública (visitante, sin login)
- `/login`            → inicio de sesión
- `/dashboard`        → panel con métricas (requiere login)
- `/consultas/nueva`  → formulario de registro por pasos (requiere login)
- `/consultas/{id}`   → detalle de la consulta (requiere login)
- `/consultas/{id}/editar` → editar consulta (admin/médico)
- `/consultas/{id}/pdf` → exportar consulta a PDF (requiere login)
- `/pacientes`        → listado con búsqueda y paginación (requiere login)
- `/pacientes/{id}`   → ficha del paciente con historial clínico (requiere login)
- `/pacientes/{id}/editar` → editar ficha del paciente (requiere login)
- `/historial`        → historial con filtros (rango de fechas + médico) y paginación
- `/usuarios`         → gestión de personal (solo admin)
- `/medicos`          → gestión de médicos (solo admin)

### Cuentas de prueba (contraseña: `password`)

| Rol         | Correo                |
|-------------|-----------------------|
| Administrador | admin@centro.med    |
| Médico      | medico@centro.med     |
| Recepción   | recepcion@centro.med  |

> Cargar usuarios de prueba: `php artisan db:seed --class=UserSeeder`

### Comandos útiles

```powershell
php artisan migrate          # aplicar migraciones
php artisan migrate:fresh    # recrear todas las tablas (borra datos)
php artisan db:seed --class=DemoSeeder   # cargar datos de ejemplo
php artisan route:list       # ver rutas
```

## 🗄️ Base de datos

Configurada en `laravel-app/.env`:

| Parámetro | Valor            |
|-----------|------------------|
| Motor     | PostgreSQL 18    |
| Host      | 127.0.0.1:5432   |
| Base      | asistencia_medica|
| Usuario   | postgres         |
| Password  | postgres         |

> ⚠️ La contraseña `postgres` es solo para desarrollo local. Cámbiala antes de
> desplegar en un servidor real.

## 🔧 Requisitos (para reinstalar en otra máquina)

- PHP 8.2+ con extensiones `pdo_pgsql`, `pgsql`, `openssl`, `mbstring`, `fileinfo`, `curl`
- Composer 2+
- PostgreSQL 12+

## 📄 Licencia

Uso interno / educativo.
